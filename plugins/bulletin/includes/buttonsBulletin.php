<?php
// "this file is similar to bread crumbs. the buttons here are used for"
// "quick navigation of the bulletin."

// "if any page other than this page has the variable of $i,"
// "then set $i to 0 so that it will not conflict."
if(!isset($i)){
	$i = 0;
}

if(!isset($c))
$c = 1;

// "get how many forums are in the database table and then $forumNew5 will"
// "plus 1 that count."
try {
	$stmt = $dbh->prepare("SELECT f FROM {$bulletin}forums ORDER BY f DESC LIMIT 1");
	$stmt->execute();
	$row = $stmt->fetch();
	$forumNew5 = $row['f'] + 1;
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "get how many categories are in the database of forums and then $categoryNew5"
// "will plus 1 that count."
try {
	$stmt = $dbh->prepare("SELECT c FROM {$bulletin}forums ORDER BY c DESC LIMIT 1");
	$stmt->execute();
	$row = $stmt->fetch();
	$categoryNew5 = $row['c'] + 1;
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

if(basename($_SERVER['PHP_SELF']) != "index.php"){
	if(isset($c)){
		// "get how many threads are in the database and then $topicNew will"
		// "plus 1 that count."
		try {
			$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c ORDER BY t DESC LIMIT 1");
			$stmt->bindParam(':c', $c);
			$stmt->execute();
			$row6 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$topicNew = $row6['t'] + 1;
		$topicNew2 = $topicNew - 1;
	}
}

if($bootstrapButtonsDisplay == "y")
echo "<div id='buttonsTextAlign' style='line-height:2.4em;'>";
else echo "<div id='buttonsTextAlign' style='line-height:1.5em;'>";

echo "<table  class='table7' border='0'><tr>";

if(basename($_SERVER['PHP_SELF']) != "index.php" ){
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
		$stmt->bindParam(':c', $c);
		$stmt->execute();
		$row = $stmt->fetch();
		$id = $row['id'];
		$_SESSION['id'] = $id;
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
}

if(isset($f) && isset($c)){
	
	// "breadcrumbs start here."
	if(basename($_SERVER['PHP_SELF']) == "threadViewAll.php"){
		// "prepare to display forumName inside html table."
		try {
			$stmt = $dbh->prepare("SELECT forumName FROM {$bulletin}forums WHERE f=:f");
			$stmt->bindParam(':f', $f);
			$stmt->execute();
			$row2 = $stmt->fetch();
			$forumName = $row2['forumName'];
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$forumName2 = $forumName;

		$forumName2 = str_replace('<p>', '' , $forumName2);
		$forumName2 = str_replace('</p>', '' , $forumName2);

		// "get the first topic title of the topic."
		try {
			$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
			$stmt->bindParam(':c', $c);
			$stmt->execute();
			$row3 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$categoryTitle2 = cleanData($row3['categoryTitle']);
		
		$categoryTitle2 = str_replace('<p>', '' , $categoryTitle2);
		$categoryTitle2 = str_replace('</p>', '' , $categoryTitle2);

		// "breadcrumbs for home and board."
		echo "<td id='left'><a href=\"" . $pluginUrl . "\">Home</a> >> <a href=\"" . $pluginUrl . "index/$f" . "\">" . $forumName2 . "</a> >> " . $categoryTitle2 . "</td>";
	}
	
	// "bread crumbs for home, board and category start here."
	if((basename($_SERVER['PHP_SELF']) == "threadRead.php"  || (basename($_SERVER['PHP_SELF']) == "postEdit.php" || basename($_SERVER['PHP_SELF']) == "postReply.php"))){
		// "prepare to display forumName inside html table."
		try {
			$stmt = $dbh->prepare("SELECT forumName FROM {$bulletin}forums WHERE f=:f");
			$stmt->bindParam(':f', $f);
			$stmt->execute();
			$row2 = $stmt->fetch();
			$forumName = $row2['forumName'];
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$forumName2 = $forumName;
	
		$forumName2 = str_replace('<p>', '' , $forumName2);
		$forumName2 = str_replace('</p>', '' , $forumName2);
	
		// "get the first topic title of the topic."
		try {
			$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
			$stmt->bindParam(':c', $c);
			$stmt->execute();
			$row3 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		$categoryTitle2 = cleanData($row3['categoryTitle']);
	
		$categoryTitle2 = str_replace('<p>', '' , $categoryTitle2);
		$categoryTitle2 = str_replace('</p>', '' , $categoryTitle2);

		// "display breadcrumbs."
		echo "<td id='left'><a href=\"" . $pluginUrl . "\">Home</a> >> <a href=\"" . $pluginUrl . "index.php/$f" . "\">" . $forumName2 . "</a> >> <a href=\"" . $pluginUrl . "threadRead/$id/$t" . "\"></a><a href=\"" . $pluginUrl . "threadViewAll/$id/1" . "\">" . $categoryTitle2 . "</a></td>";
	}
}

echo "</tr><tr><td>";

##### bulletin buttons start here #####
// "display the home button."
if($bootstrapButtonsDisplay == "y"){
	echo "<a class='btn btn-primary' href='{$rootUrl}'><i class='fa home fa-lg'></i><span>Homepage</span></a>&nbsp;";
} else {
	echo "|&nbsp;<a href='{$rootUrl}'><span style='white-space: nowrap;'>Homepage</span></a>&nbsp;|";
}

echo "&nbsp;";

// "display the bulletin home button."
if($bootstrapButtonsDisplay == "y"){
	echo "<a class='btn btn-primary' href='{$pluginUrl}'><i class='fa home fa-lg'></i><span>Board home</span></a>&nbsp;";
} else {
	echo "|&nbsp;<a href='{$pluginUrl}'><span style='white-space: nowrap;'>Board home</span></a>&nbsp;|";
}

echo "&nbsp;";

// "this is the forum new button link."
if($permission >= $forumNew){
	if($bootstrapButtonsDisplay == "y"){
		echo "<a class='btn btn-primary' href='{$pluginUrl}forumNew/$forumNew5'><i class='fa clipboard fa-lg'></i><span>Forum new</span></a>&nbsp;";
	} 
	else {
		echo "<a href='{$pluginUrl}forumNew/$forumNew5'><span style='white-space: nowrap;'>Forum new</span></a>&nbsp;|";
	}	
	
	echo "&nbsp;";
}

// "this category button link will be displayed when a forum exist."
if($permission >= $categoryNew){
	if($forumNew5 > 1){
		if(isset($categoryNew5) &&  $categoryNew5 > 0 && isset($forumNew5) &&  $forumNew5 > 0){
			if($bootstrapButtonsDisplay == "y"){
				echo "<a class='btn btn-primary' href='{$pluginUrl}categoryNew/$categoryNew5'><i class='fa files-o fa-lg'></i><span>Category new</span></a>&nbsp;";
			}
			else {
				echo "<a href='{$pluginUrl}categoryNew/$categoryNew5'><span style='white-space: nowrap;'>Category new</span></a>&nbsp;|";
			}	
			echo "&nbsp;";
		}
	}
}

// "these button links will be displayed when clicking the category link."
if(basename($_SERVER['PHP_SELF']) != "categoryEdit.php" && isset($c[$i]) &&  $c[$i] > 0 && isset($id)){
	if(basename($_SERVER['PHP_SELF']) != "categoryNew.php"){
		if(basename($_SERVER['PHP_SELF']) != "index.php"){
			if(isset($c)){
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
					$stmt->bindParam(':c', $c);
					$stmt->execute();
					$row3 = $stmt->fetch();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}
				$forumNew5 = $row3['f'];
			} elseif(isset($_SESSION['getF'])) $forumNew5 = $_SESSION['getF'];
			// "session getF, getC and getP are variables"
			// "to be used for menu links. Without these sessions"
			// "variable some buttons will not display its link"
			// "correctly."
			$_SESSION['getF'] = $forumNew5;
			$_SESSION['getC'] = $c[$i];
			$_SESSION['getP'] = 1;
				
			// "thread view button link"
			if($bootstrapButtonsDisplay == "y" && isset($id)){
				echo "<a class='btn btn-primary' href='{$pluginUrl}threadViewAll/$id/1'><i class='fa pencil-square-o fa-lg'></i><span>Threads view</span></a>&nbsp;";
			}
			elseif(isset($id)) {
				echo "<a href='{$pluginUrl}threadViewAll/$id/1'><span style='white-space: nowrap;'>Threads view</span></a>&nbsp;|";
			}
			
			echo "&nbsp;";

			if($permission >= $threadNew){
				// "thread new button"
				if(isset($topicNew)){
					// "session getT is a variable to be used for a menu""
					// "link. Without this session variable the threadNew"
					// "button will not display its link correctly."
					$_SESSION['getT'] = $topicNew;

					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}threadNew/$forumNew5/$c[$i]/$topicNew'><i class='fa folder-open2 fa-lg'></i><span>Thread new</span></a>&nbsp;";
					}
					else {
						echo "<a href='{$pluginUrl}threadNew/$forumNew5/$c[$i]/$topicNew'><span style='white-space: nowrap;'>Thread new</span></a>&nbsp;|";
					}
					echo "&nbsp;";
				}
			}

			if($permission >= $pollNew){
				if(isset($topicNew)){

					// "poll new button."
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}pollNew/$forumNew5/$c[$i]/$topicNew'><i class='fa bar-chart-o fa-lg'></i><span>Poll new</span></a>&nbsp;";
					} 
					else{
						echo "<a href='{$pluginUrl}pollNew/$forumNew5/$c[$i]/$topicNew'><span style='white-space: nowrap;'>Poll new</span></a>&nbsp;|";
					}
					
					echo "&nbsp;";
				}
			}
		}

		if(basename($_SERVER['PHP_SELF']) != "threadRead.php" && basename($_SERVER['PHP_SELF']) != "postReply.php" && basename($_SERVER['PHP_SELF']) != "postEdit.php" && basename($_SERVER['PHP_SELF']) != "index.php"){

			$temp = $c[$i];
			if($permission >= $subscribeForum && $outgoingEmails == "y"){
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_forum WHERE username=:username AND c=:temp");
					$stmt->bindParam(':username', $username);
					$stmt->bindParam(':temp', $temp);
					$stmt->execute();
					$row9 = $stmt->fetch();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}
				
				$username2 = $row9['username'];

				if($username2 == "" && isset($topicNew2)){
					// "subscribe thread button."
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}subscribeForum/$c[$i]'><i class='fa check-sign fa-lg'></i><span>Subscribe forum</span></a>&nbsp;";
						}
						else {
							echo "<a href='{$pluginUrl}subscribeForum/$c[$i]'><span style='white-space: nowrap;'>Subscribe forum</span></a>&nbsp;|";
						}	
					echo "&nbsp;";
				} else{
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}subscribeForum2/$c[$i]'><i class='fa check-sign fa-lg'></i><span>Unsubscribe forum</span></a>&nbsp;";
					}
					else{
						echo "<a href='{$pluginUrl}subscribeForum2/$c[$i]'><span style='white-space: nowrap;'>Unsubscribe forum</span></a>&nbsp;|";
					}
					echo "&nbsp;";
				}
			}		
				
			if($topicNew > 1 && $username != "guest"){
				if($bootstrapButtonsDisplay == "y"){
					echo "<a class='btn btn-primary' href='{$pluginUrl}markAsRead/$c[$i]/$topicNew2'><i class='fa tasks fa-lg'></i><span>Mark forum as read</span></a>&nbsp;";
				}
				else{
				echo "<a href='{$pluginUrl}markAsRead/$c[$i]/$topicNew2'><span style='white-space: nowrap;'>Mark forum as read</span></a>&nbsp;|";
				}
					
				echo "&nbsp;";
			}
		}
			
		if(basename($_SERVER['PHP_SELF']) == "threadRead.php" || basename($_SERVER['PHP_SELF']) == "postEdit.php" || basename($_SERVER['PHP_SELF']) == "postReply.php"){
			if($permission >= $pinThread){
				// "thread pin button."
				$CTemp = $c[$i];
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:CTemp AND t=:t AND r=0");
					$stmt->bindParam(':CTemp', $CTemp);
					$stmt->bindParam(':t', $t);
					$stmt->execute();
					$row7 = $stmt->fetch();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}

				// "if s = 1 then display button as unpin"
				$s = $row7['s'];

				if($s==0){
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}threadPin/$idRead'><i class='fa pushpin fa-lg'></i><span>Thread pin</span></a>&nbsp;";
					}
					else{
						echo "<a href='{$pluginUrl}threadPin/$idRead'> <span style='white-space: nowrap;'>Thread pin</span></a>&nbsp;|";
					}
					echo "&nbsp;";
				} else{
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}threadPin2/$idRead'><i class='fa pushpin fa-lg'></i><span>Thread un-pin</span></a>&nbsp;";
					}
					else {
						echo "<a href='{$pluginUrl}threadPin2/$idRead'><span style='white-space: nowrap;'>Thread un-pin</span></a>&nbsp;|";
					}
					echo "&nbsp;";
				}
			}

			if($permission >= $lockThread){
				// "thread lock button."
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:CTemp AND t=:t AND r=0");
					$stmt->bindParam(':CTemp', $CTemp);
					$stmt->bindParam(':t', $t);
					$stmt->execute();
					$row8 = $stmt->fetch();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}

				// "if s = 1 then display button as unpin."
				$lock = $row8['l'];

				if($lock == 0){
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}threadLock/$idRead'><i class='fa lock fa-lg'></i><span>Thread lock</span></a>&nbsp;";
					}
					else {
						echo "<a href='{$pluginUrl}threadLock/$idRead'><span style='white-space: nowrap;'>Thread lock</span></a>&nbsp;|";
					}
					echo "&nbsp;";
				} else{
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}threadLock2/$idRead'><i class='fa unlock fa-lg'></i><span>Thread unlock</span></a>&nbsp;";
					}
					else {
						echo "<a href='{$pluginUrl}threadLock2/$idRead'><span style='white-space: nowrap;'>Thread unlock</span></a>&nbsp;|";
					}
					echo "&nbsp;";
				}
			}

			if($permission >= $subscribeThread && $outgoingEmails == "y"){
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_thread WHERE username=:username AND c=:CTemp AND t=:t");
					$stmt->bindParam(':username', $username);
					$stmt->bindParam(':CTemp', $CTemp);
					$stmt->bindParam(':t', $t);
					$stmt->execute();
					$row9 = $stmt->fetch();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}
				$username2 = $row9['username'];

				if($username2 == ""){
					// "subscribe thread button."
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}subscribeThread/$c[$i]/$t'><i class='fa check-sign fa-lg'></i><span>Subscribe thread</span></a>&nbsp;";					
					}
					else {
						echo "<a href='{$pluginUrl}subscribeThread/$c[$i]/$t'><span style='white-space: nowrap;'>Subscribe thread</span></a>&nbsp;|";	
					}
					
					echo "&nbsp;";
				} else{
					if($bootstrapButtonsDisplay == "y"){
						echo "<a class='btn btn-primary' href='{$pluginUrl}subscribeThread2/$c[$i]/$t'><i class='fa check-sign fa-lg'></i><span>Unsubscribe thread</span></a>&nbsp;";
					}
					else{
						echo "<a href='{$pluginUrl}subscribeThread2/$c[$i]$t'><span style='white-space: nowrap;'>Unsubscribe thread</span></a>&nbsp;|";
					}
					
					echo "&nbsp;";
				}
			}
			
			if($topicNew > 1 && $username != "guest"){
				if($bootstrapButtonsDisplay == "y"){
					echo "<a class='btn btn-primary' href='{$pluginUrl}markAsRead/$c[$i]/$t/1'><i class='fa tasks fa-lg'></i><span>Mark thread as read</span></a>";
				}
				else{
				echo "<a href='{$pluginUrl}markAsRead/$c[$i]/$t/1'><span style='white-space: nowrap;'>Mark thread as read</span></a>";
				}
					
				//echo "&nbsp;";
			}
		}
	}
}
echo "</td>";
echo "</tr></table></div>";

?>