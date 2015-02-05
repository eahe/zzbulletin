<?php
if(!isset($_SESSION['onlyOnceImportantTopics']))
$_SESSION['onlyOnceImportantTopics'] = 0;
if(!isset($_SESSION['onlyOnceForumTopics']))
$_SESSION['onlyOnceForumTopics'] = 0;

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){	
	include "includes/pinFolderLockHot.php";
	echo "</td>";

	if($threadDisplay == 1 || $topics == 1){
		// "get $id from threads."
		try {
			$stmt11 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=1");
			$stmt11->bindParam(':c', $c);
			$stmt11->bindParam(':t', $t);
			$stmt11->execute();
			$row11 = $stmt11->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		$idEdit = $row11['id'];
		$topicTitlePostCreatedAt = $row11['topicTitle'];
		
		if($username != "guest"){
			if(isset($_COOKIE['timezone']))
				$timestampPostCreatedAt = $row11['timestamp'] + $_COOKIE['timezone'];
			else $timestampPostCreatedAt = $row11['timestamp'];
		} else $timestampPostCreatedAt = $row11['timestamp'];
		
		$timestampPostCreatedAt = timestampDate($timestampPostCreatedAt);

		echo "<td width='42%'><a href='{$pluginUrl}threadRead/$idEdit/$p'><p>" . $topicTitlePostCreatedAt . "</p></a>";
		echo "<p>Created " . $timestampPostCreatedAt . " by " . $username2 . ".</p></td>";
		echo "<td id='center' width='11%'>" . $replies . "</td>";
		echo "<td id='center' width='11%'>" . $views . "</td>";

		/* "the smaller the class='font1' set in comments.css then the more"
		"username characters that can be used and without breaking the display."*/
		echo "<td class='font1' id='left' width='28%'>";

		if($permission >= $threadDelete && $username == $username2 || $permission >= $threadDeleteAll){
			echo "<div id='buttonsTextAlign'><a class='btn btn-danger confirm' href='{$pluginUrl}threadDelete/$idEdit' onmouseover='title=\"\"' title='Are you sure you want to delete this thread and a poll that might be associated with it?'><i class='fa removeWhite fa-lg'></i></a>";
		}
		
		// "post edit button."
		if($permission >= $postEdit && $username == $username2 || $permission >= $postEditAll){
			echo "<a class='btn btn-primary' href='{$pluginUrl}postEdit/$idEdit/1'><i class='fa file-text-o fa-lg'></i></a></div>";
		}

		try {
			$stmt3 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t ORDER BY r DESC LIMIT 1");
			$stmt3->bindParam(':c', $c);
			$stmt3->bindParam(':t', $t);
			$stmt3->execute();
			$row3 = $stmt3->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		if($username != "guest"){
			if(isset($_COOKIE['timezone']))
				$timestamp2 = cleanData($row3['timestamp']) + $_COOKIE['timezone'];
			else $timestamp2 = cleanData($row3['timestamp']);
		} else $timestamp2 = cleanData($row3['timestamp']);
		
		$username2 = cleanData($row3['username']);
		$timestamp = timestampDate($timestamp2);

		try {
			$stmt4 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE r>0 AND t=:t ORDER BY r");
			$stmt4->bindParam(':t', $t);
			$stmt4->execute();
			$row4 = $stmt4->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$r = $row4['r'];
						
		// "determine if username has mark read as forum in database."		
		try {
			$stmt8 = $dbh->prepare("SELECT * FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND r=:r AND r!=0");
			$stmt8->bindParam(':username', $username);
			$stmt8->bindParam(':c', $c);
			$stmt8->bindParam(':t', $t);
			$stmt8->bindParam(':r', $r);
			$stmt8->execute();
			$row8 = $stmt8->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$markAsRead = $row8['username'];
		$mark = $row8['mark'];

		if($markAsRead == NULL){
			try {
				$stmt5 = $dbh->prepare("INSERT INTO {$bulletin}mark_as_read (mark, username, f, c, t, r ) VALUES(0, :username, :f, :c, :t, :r)");
				$stmt5->bindParam(':username', $username);
				$stmt5->bindParam(':f', $f);
				$stmt5->bindParam(':c', $c);
				$stmt5->bindParam(':t', $t);
				$stmt5->bindParam(':r', $r);
				$stmt5->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}
		
		try {
			$stmt10 = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username2");
			$stmt10->bindParam(':username2', $username2);
			$stmt10->execute();
			$row10 = $stmt10->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$avatar2 = cleanData($row10['avatar']);
		$yourGender = cleanData($row10['yourGender']);
		echo "<table class='table9'><tr><th>";						
						
		// "display the members small avatar in the table."
		$imageWidth=35; $imageHeight = 35;
		if(isset($avatar2) && strlen($avatar2) > 10){
			echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsUploadDirectory . $avatar2 . '" ></center>';
		} else{
				if($yourGender == 'm')
					echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsLocalDirectory . 'male/' . $avatar2 . '" ></center>';
				else echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsLocalDirectory . 'female/' . $avatar2 . '" ></center>';
		}

		echo "</th><td>";
		echo $username2 . "<br>" . $timestamp . ".";
		echo "</td></tr></table></td></tr>";
	} else{
		$iByPass = 0;
		echo "<td class='font1' colspan='4'>";
		
		try {
			$stmt1 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t ORDER BY r");
			$stmt1->bindParam(':c', $c);
			$stmt1->bindParam(':t', $t);
			$stmt1->execute();
			$row1 = $stmt1->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		$items = array();
		while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){	
			$items[] = $row1;
		}
		// "create new list grouped by parent id."
		$itemsByParent = array();
		foreach($items as $item){
			if(!isset($itemsByParent[$item['parentId']])){
				$itemsByParent[$item['parentId']] = array();
			}

			$itemsByParent[$item['parentId']][] = $item;
		}

		$_SESSION['countUl'] = 0;
		
		// "display the threaded tree."
		printList($itemsByParent, 1, $iByPass);
		for($ii=0; $ii<$_SESSION['countUl']; $ii++)
		echo "</ul>";

		echo "</td></tr>";
	}
}
?>