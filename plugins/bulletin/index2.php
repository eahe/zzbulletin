<?php
$fileName = "configuration/bulletin.php";
if(!file_exists($fileName)){
	header("location: {$pluginUrl}install/install1.php");
	exit;
}

if(!isset($pluginUrl)){
	session_start();
	$_SESSION['noticesBad'] = "Direct access denied for \"" . basename($_SERVER['PHP_SELF']) . "\".";

	header("location: {$pluginUrl}index");
	exit;
}

require "../../includes/main/header.php";

$_SESSION['fullUrl'] = fullUrl();

// "this value determines if forumEdit.php page should be redirected"
// "to index.php or to threadViewAll.php."
$_SESSION['forumEdit'] = 1;

$f2 = cleanData($f2);

if(isset($f2) && $f2 != 0){
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE f=:f2");
		$stmt->bindParam(':f2', $f2);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	$skipF = 1;
} else {
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY f ASC");
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
}

// "put important veriables in to an array."
// "$f=forum array. $c=category array. "$l=move/arrow position"
$id = array();
$c = array();
$f = array();
$forumName = array();
$categoryTitle = array();
$categoryBody = array();
while($row1 = $stmt->fetch(PDO::FETCH_ASSOC)){
	$id[] = $row1['id'];
	$c[] = $row1['c'];
	$f[] = $row1['f'];
	$l = $row1['l'];
	$forumName[] = $row1['forumName'];
	$categoryTitle[] = $row1['categoryTitle'];
	$categoryBody[] = $row1['categoryBody'];
}

require 'includes/buttonsBulletin.php';

if(isset($_SESSION['noticesGood'])){
	noticesGood();
	unset($_SESSION['noticesGood']);
}

// "if cookie exists then give message that user needs to be logged out"
// "to view file."
if(isset($_SESSION['cookieCheck1'])){
	$_SESSION['noticesFair'] = "You need to be logged out to view \"" . $_SESSION['cookieCheck1'] . "\".";
	noticesFair();
	unset($_SESSION['cookieCheck1']);
	unset($_SESSION['noticesFair']);
}

// "display message that guest needs to be log in to view file."
if(isset($_SESSION['cookieCheck2'])){
	$_SESSION['noticesFair'] = "Log in to view \"" . $_SESSION['cookieCheck2'] . "\".";
	noticesFair();
	unset($_SESSION['cookieCheck2']);
	unset($_SESSION['noticesFair']);
}

if(isset($_SESSION['noticesFair'])){
	noticesFair();
	unset($_SESSION['noticesFair']);
}

// "permission denied for root file."
if(isset($_SESSION['noticesBad'])){
	if(isset($_SESSION['basename'])){
		$_SESSION['noticesBad'] = "You do not have permission to access \"" .  $_SESSION['basename'] . "\".";
	}
	noticesBad();
}


if(!isset($forumName)){
	$_SESSION['noticesBad'] = "No forums to display.";
	noticesBad();
}

unset($_SESSION['noticesBad']);
unset($_SESSION['basename']);

// "set the variables used in this page."
$i = 0; $ii = 0; $iii = -1;
$location1 = 0; $location2 = 0;
$byPass = 0; $totalBulletinThreads = 0;
$totalBulletinPosts = 0;

// "$bBoardMax gets the maximum number of forums from table forums."
if(isset($f))
	$bBoardMax = count($f) -1;

// "this is a loop to display the forums in desc order."
// "if there are no forums in the database then do not"
// "enter into the loop."
if(isset($bBoardMax) && $bBoardMax >= 0 && isset($f)){

	for($i = 0; $i <= $bBoardMax; $i++){

		// "determine if the forums can be displayed."
		if($ii != $f[$i]){
			{
				$location1 = $location1 +1;
				$ii = $f[$i];

				if($forumName[$i] == NULL)
					$forumName[$i]  = $f[$i];

				if($i > 1)
					echo "</table>";
					
				// "display the forums."
				echo "<table class='table3' id='left'><col width='8%'> <col width='42%'><col width='11%'><col width='11%'><col width='28%'><tr>

						<th width='8%'></th>";
				echo "<th id='left' colspan='3'>" . $forumName[$i] . "</th>";
				echo "<th id='center'>";

				// "delete a forum."
				if($permission >= $forumDelete){
					echo "<a class='btn btn-danger confirm' href='{$pluginUrl}forumDelete/$f[$i]' onmouseover='title=\"\"' title='Are you sure you want to delete this forum including the categories, threads and polls within the forum?'><i class='fa removeWhite fa-lg'></i></a>";
				}

				// "edit forum text link"
				if($permission >= $forumEdit){
					echo "<a class='btn btn-primary' href='{$pluginUrl}forumEdit/$f[$i]'><i class='fa file-text-o fa-lg'></i></a>";
				}

				echo "</th></tr><tr>";
				// "displays the header of the forums."
				echo "<td id='center' ></td>";
				echo "<td id='center' >Forums</td>";
				echo "<td id='center' >Threads</td>";
				echo "<td id='center' >Posts</td>";

				if($permission >= $categoryDelete || $permission >= $categoryEdit || $permission >= $categoryReorder){
					echo "<td id='center' >Options / Last post by</td></tr>";
				} else
					echo "<td id='center' >Last Post by</td></tr>";
				
				// "at user preferences, option to remove html <br> tag"
				// "for forums."
				if(isset($brTag2) && $brTag2 == 'y'){
					if($i > 0)
						echo "<br>";
				}
					
			}
		}
		// "this is the category loop."
		// "check $c for categories exist or exit loop."
		if($c[$i] > 0 ){
			$forumName[$location2]  =  $forumName[$i];
			$location2 = $location2 + 1;

			// "display a black folder at far left of category table."
			echo "<tr><td id='center'>";
			
			$CTemp = $c[$i]; $FTemp = $f[$i];
			try {
				$stmt = $dbh->prepare("SELECT * FROM {$bulletin}mark_as_read  WHERE username=:username AND c=:CTemp AND mark='0' LIMIT 1");
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':CTemp', $CTemp);
				$stmt->execute();
				$row7 = $stmt->fetch();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
			$mark = $row7['mark'];
			
			try {
				$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}mark_as_read WHERE username=:username AND c=:CTemp");
				$stmt->bindParam(':username', $username); 
				$stmt->bindParam(':CTemp', $CTemp); 
				$stmt->execute();
				$rCount = $stmt->fetchColumn();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}			
			
			if(isset($mark) && $mark == 0 || $rCount == 0)
			echo "<a class='iconColor'><i
						class='fa fa-folder-open fa-2x'></i></a></td>";
			else echo "<a class='iconColor'><i 
			class='fa fa-folder fa-2x'></i></a></td>";

			echo "<td><a href='{$pluginUrl}threadViewAll/$id[$i]/1'>";

			// "display categoryTitle and categoryBody in table."
			echo "" . $categoryTitle[$i] . "</a>" . $categoryBody[$i];
			echo "</td><td id='center'>";

			// "$count refers to how many threads to display."
			try {
				$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads Where c=:CTemp AND r='1'");
				$stmt->bindParam(':CTemp', $CTemp); 
				$stmt->execute();
				$count = $stmt->fetchColumn();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
			
			echo $count;
			echo "</td><td id='center'>";

			// "this $count refers to how many posts to display."
			try {
				$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads Where c=:CTemp AND r>0");
				$stmt->bindParam(':CTemp', $CTemp); 
				$stmt->execute();
				$count = $stmt->fetchColumn();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}

			echo $count;
			echo "</td>";
			echo "<td class='font1' id='left'><div id='buttonsTextAlign'>";

			try {
				$stmt = $dbh->prepare("UPDATE {$bulletin}forums SET l=:location2 WHERE c=:CTemp");
				$stmt->bindParam(':location2', $location2);
				$stmt->bindParam(':CTemp', $CTemp);
				$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
			
			// "delete a category with all threads under its category."
			if($permission >= $categoryDelete){
				echo "<a class='btn btn-danger confirm' href='{$pluginUrl}categoryDelete/$c[$i]' onmouseover='title=\"\"' title='Are you sure you want to delete this category, including all threads and polls within this category?'><i class='fa removeWhite fa-lg'></i></a>";
			}

			// "used to edit the body and title of the category."
			if($permission >= $categoryEdit){
				echo "<a class='btn btn-primary' href='{$pluginUrl}categoryEdit/$c[$i]'><i class='fa file-text-o fa-lg'></i></a>";
			}

			// "display the image of arrows to move"
			// "up / down the categories."
			if($location2 > 0){
				$iii = $location2 - 1;

				$size = count(array_filter($c));
				if($permission >= $categoryReorder){
					if($size >= 2){
						echo "<a class='btn btn-primary' href='{$pluginUrl}categoryReorder/$location2/up'><i class='fa arrow-up fa-lg'></i></a>";

						echo "<a class='btn btn-primary' href='{$pluginUrl}categoryReorder/$location2/down'><i class='fa arrow-down fa-lg'></i></a>";
					}
				}
				echo "</div>";
				
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:CTemp AND r>'0' ORDER BY timestamp DESC LIMIT 1");
					$stmt->bindParam(':CTemp', $CTemp);
					$stmt->execute();
					$row9 = $stmt->fetch();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}

				if($row9 != NULL){
					$timestamp = cleanData($row9['timestamp']);
					$username2 = cleanData($row9['username']);

					try {
						$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
						$stmt->bindParam(':username', $username2);
						$stmt->execute();
						$row10 = $stmt->fetch();
					} catch (PDOException $e) {
						echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
						exit;
					}
					
					$avatar2 = cleanData($row10['avatar']);
					$yourGender = cleanData($row10['yourGender']);
					
					if($username != "guest"){
						if(isset($_COOKIE['timezone']))
							$timestamp = $timestamp + $_COOKIE['timezone'];
					}
						
					echo "<table class='table9'><tr><th>";

					// "display the members small avatar in the table."
					$imageWidth=35; $imageHeight = 35;
					if(isset($avatar2) && strlen($avatar2) > 10){
						echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl .$avatarsUploadDirectory . $avatar2 . '" ></center>';
					} else{
						if($yourGender == 'm')
							echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsLocalDirectory . 'male/' . $avatar2 . '" ></center>';
						else echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsLocalDirectory . 'female/' . $avatar2 . '" ></center>';
					}

					echo "</th><td>";

					$timestamp = timestampDate($timestamp);
					echo $username2 . "<br>" . $timestamp . ".";
					echo "</tr></table>";
				} else echo "<div>Nobody.</div>";

				if($bBoardMax == 1)
					echo "</td></tr></table></td></tr>";
			}
			if($bBoardMax == 1)
				echo "</td></tr></table></td></tr>";
			
			$forumNameTemp = $forumName[$iii];
			// "save important information to the database."
			try {
					$stmt = $dbh->prepare("UPDATE {$bulletin}forums SET f=:FTemp, forumName=:forumNameTemp, l=:location2 WHERE c=:CTemp");
					$stmt->bindParam(':location2', $location2);
					$stmt->bindParam(':forumNameTemp', $forumNameTemp);
					$stmt->bindParam(':FTemp', $FTemp);
					$stmt->bindParam(':CTemp', $CTemp);
					$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}

			try {
					$stmt = $dbh->prepare("UPDATE {$bulletin}threads SET f=:FTemp WHERE c=:CTemp");
					$stmt->bindParam(':FTemp', $FTemp);
					$stmt->bindParam(':CTemp', $CTemp);
					$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}
	}
}

echo "</table>";

if(isset($i))
	unset($i);

require '../../includes/main/footer.php';

?>