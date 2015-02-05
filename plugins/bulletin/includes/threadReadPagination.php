<?php
//note: variable p refers to page

/* Setup vars for query. */
$targetpage = "{$pluginUrl}threadRead";
$limited = $limit; $ii = 0;

if(isset($p))
$start = ($p - 1) * $limit; // "first item to display on this p."
else
$start = 0; // "if no p var is given, set start to 0."

/* "Setup p vars for display." */
if($p == 0) $p = 1; // "if no p var is given, default to 1."
$prev = $p - 1; // "previous p is p - 1."
$next = $p + 1;
$lpm1 = $lastpage - 1; // "last p minus 1."

/*
Now we apply our rules and draw the pagination object.
We're actually saving the code to a variable in case we want to draw it more than once.
*/
$pagination = "";

if($lastpage > 1){
	$pagination .= "<div class=\"pagination\">";
	// "previous button."
	if($p > 1)
	$pagination.= "<a href=\"$targetpage/$idRead/$prev\">previous</a>";
	else
	$pagination.= "<span class=\"disabled\">previous</span>";

	// "pages."
	if($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
	{
		for($counter = 1; $counter <= $lastpage; $counter++){
			if($counter == $p)
			$pagination.= "<span class=\"current\"><a href=\"$targetpage/$idRead/$counter\">$counter</a></span>";
			else
			$pagination.= "<a href=\"$targetpage/$idRead/$counter\">$counter</a>";
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
	{
		// "close to beginning; only hide later pages."
		if($p < 1 + ($adjacents * 2)){
			for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage/$idRead/$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage/$idRead/$counter\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage/$idRead/$lpm1\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage/$idRead/$lastpage\">$lastpage</a>";
		}
		// "in middle; hide some front and some back."
		elseif($lastpage - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
			$pagination.= "<a href=\"$targetpage/$idRead/1\">1</a>";
			$pagination.= "<a href=\"$targetpage/$idRead/2\">2</a>";
			$pagination.= "...";
			for($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage/$idRead/$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage/$idRead/$counter\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage/$idRead/$lpm1\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage/$idRead/$lastpage\">$lastpage</a>";
		}
		// "close to end; only hide early pages."
		else{
			$pagination.= "<a href=\"$targetpage/$idRead/1\">1</a>";
			$pagination.= "<a href=\"$targetpage/$idRead/2\">2</a>";
			$pagination.= "...";
			for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage/$idRead/$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage/$idRead/$counter\">$counter</a>";
			}
		}
	}

	// "next button."
	if($p < $counter - 1)
	$pagination.= "<a href=\"$targetpage/$idRead/$next\">next</a>";
	else
	$pagination.= "<span class=\"disabled\">next</span>";
	$pagination.= "</div>\n";
}

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r>0 ORDER BY id, t DESC LIMIT $start, $limit");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

if($row == NULL){
	$_SESSION['noticesFair'] = "Page does not exist.";
	require "includes/notices.php";
	exit;
}

if($threadDisplay == 1 || isset($s) && $s == 1){
	try {
		$stmt2 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r>0 ORDER BY id, t DESC LIMIT $start, $limit");
		$stmt2->bindParam(':c', $c);
		$stmt2->bindParam(':t', $t);
		$stmt2->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "get important variables from table threads."
	while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
		$topicTitle = $row2['topicTitle'];
		$topicBody = $row2['topicBody'];
	
		$f = cleanData($row2['f']);
		$c = cleanData($row2['c']);
		$t = cleanData($row2['t']);
		$r = cleanData($row2['r']) - 1;
		$id = cleanData($row2['id']);
		$attachFile = cleanData($row2['attachFile']);
		$username2 = cleanData($row2['username']);
	
		if($username != "guest"){
			if(isset($_COOKIE['timezone']))
				$timestamp = cleanData($row2['timestamp']) + $_COOKIE['timezone'];
			else $timestamp = cleanData($row2['timestamp']);
		} else $timestamp = cleanData($row2['timestamp']);
		
		// "get current timestamp for users avatar and users information"
		// "at right side of table."
		try {
			$stmt1 = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username2");
			$stmt1->bindParam(':username2', $username2);
			$stmt1->execute();
			$row1 = $stmt1->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$avatar = cleanData($row1['avatar']);
		$yourGender = cleanData($row1['yourGender']);
		$dateJoined2 = cleanData($row1['dateJoined']);
		$totalPosts = cleanData($row1['totalPosts']);
		$postSignature = cleanData($row1['postSignature']);
	
		// "make current timestamp."
		$dateJoined = date("M d Y", $dateJoined2);
		$timestamp = timestampDate($timestamp);

		$r++;

		require "includes/buttonsThread.php";
		require "includes/postProfile.php";
		
		if(isset($brTag2) && $brTag2 == 'y' && $r > 0 && $threadDisplay == 1){
			echo "<br>";
		}
	}
// "threaded mode."
} elseif($threadDisplay == 2 ){
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r AND r>0 LIMIT 1");
		$stmt->bindParam(':c', $c);
		$stmt->bindParam(':t', $t);
		$stmt->bindParam(':r', $r);
		$stmt->execute();
		$row = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$topicTitle = cleanData($row['topicTitle']);
	$topicBody = $row['topicBody'];

	$f = cleanData($row['f']);
	$c = cleanData($row['c']);
	$t = cleanData($row['t']);
	$id = cleanData($row['id']);
	$attachFile = cleanData($row['attachFile']);
	$username2 = cleanData($row['username']);
	
	if($username != "guest"){
		if(isset($_COOKIE['timezone']))
			$timestamp = cleanData($row2['timestamp']) + $_COOKIE['timezone'];
		else $timestamp = cleanData($row2['timestamp']);
	} else $timestamp = cleanData($row2['timestamp']);
	
	// "get current timestamp for users avatar and users information"
	// "at right side of table."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username2");
		$stmt->bindParam(':username2', $username2);
		$stmt->execute();
		$row1 = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$avatar = cleanData($row1['avatar']);
	$yourGender = cleanData($row1['yourGender']);
	$dateJoined2 = cleanData($row1['dateJoined']);
	$totalPosts = cleanData($row1['totalPosts']);
	$postSignature = cleanData($row1['postSignature']);
	
	// "make current timestamp."
	$dateJoined = date("M d Y", $dateJoined2);
	$timestamp = timestampDate($timestamp);

	require "includes/buttonsThread.php";
	require "includes/postProfile.php";
	
	/*$sql = "SELECT * FROM threads WHERE c='$c' AND r=1 AND s=1 ORDER BY s DESC, timestamp DESC";*/
	
	// "display the forum. $forumName is from buttonsBulletin.php near breadcrumbs."
	echo "<br><table class='table6' id='left'><col width='8%'>";
	echo "<tr><th></th>";
	echo "<th id='center' width='92%'>View thread.";
	include "includes/pinFolderLockHot.php";
	echo "<td width='100%'>";
		
	$iByPass = 0;

	try {
	$stmt4 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND r>0 AND t=:t ORDER BY r");
	$stmt4->bindParam(':c', $c);
	$stmt4->bindParam(':t', $t);
	$stmt4->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
	$items = array();
	while($row = $stmt4->fetch(PDO::FETCH_ASSOC)){		
		$items[] = $row;
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
}

if($threadDisplay == 1){
	if($pagination){
		if($brTag3 == 'y' && $threadDisplay == 1){
			echo "<br>";
		}
		echo $pagination;
	}
} else echo "</td></tr></table>";
$id = $_SESSION['id'];
?>