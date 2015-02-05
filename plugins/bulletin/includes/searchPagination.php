<?php
//note: variable p refers to page

/* Setup vars for query. */
$targetpage = "search2.php";
$limited = $limit4;

if(isset($_GET['p']))
$p = cleanData($_GET['p']);
else $p = 1;

if(isset($p))
$start = ($p - 1) * $limit4; // "first item to display on this p."
else
$start = 0; // "if no p var is given, set start to 0."

/* Setup p vars for display." */
if($p == 0) $p = 1; // "if no p var is given, default to 1."
$prev = $p - 1; // "previous p is p - 1."
$next = $p + 1; // "next p is p + 1."
$lpm1 = $lastpage - 1; // "last p minus 1."

/*
Now we apply our rules and draw the pagination object.
We're actually saving the code to a variable in case we want to draw it more than once.
*/
$pagination = "";

if($lastpage > 1){
	$pagination .= "<div class=\"pagination\">";
	//previous button
	if($p > 1)
	$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$prev\">previous</a>";
	else
	$pagination.= "<span class=\"disabled\">previous</span>";

	//pages
	if($lastpage < 7 + ($adjacents * 2)) // "not enough pages to bother breaking it up."
	{
		for($counter = 1; $counter <= $lastpage; $counter++){
			if($counter == $p)
			$pagination.= "<span class=\"current\"><a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a></span>";
			else
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a>";
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2))	// "enough pages to hide some."
	{
		// "close to beginning; only hide later pages."
		if($p < 1 + ($adjacents * 2)){
			for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$lpm1\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$lastpage\">$lastpage</a>";
		}
		// "in middle; hide some front and some back."
		elseif($lastpage - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=1\">1</a>";
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=2\">2</a>";
			$pagination.= "...";
			for($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$lpm1\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$lastpage\">$lastpage</a>";
		}
		// "close to end; only hide early pages."
		else{
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=1\">1</a>";
			$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=2\">2</a>";
			$pagination.= "...";
			for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$counter\">$counter</a>";
			}
		}
	}

	// "next button."
	if($p < $counter - 1)
	$pagination.= "<a href=\"$targetpage?keywords=$keywords&author=$author&searchWithin=$searchWithin&$searchCategoryTitle2&searchOrder=$searchOrder&p=$next\">next</a>";
	else
	$pagination.= "<span class=\"disabled\">next</span>";
	$pagination.= "</div>\n";
}

// "add searchWithin, example, r=0, r>0, r=1 to query."
$query1 = $query1 . " AND " . $searchWithin;

// "search categories. display the categories on a single line in this query."
$query1 = $query1 . " AND " . $categories . " ORDER BY topicTitle $searchOrder LIMIT $start, $limit4";

try {
	$stmt2 = $dbh->prepare($query1);
	for($iiTemp = 1; $iiTemp <= $iTemp; $iiTemp++){
		$stmt2->bindParam($iiTemp, $keywords);  	
	}	
	$stmt2->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$iiiTemp = 0;
// "loop to display the query1 results if any."
while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){	
	$topicTitle = cleanData($row2['topicTitle']);
	$topicBody = cleanData($row2['topicBody']);
	$attachFile = cleanData($row2['attachFile']);
	$username2 = cleanData($row2['username']);
	$f = cleanData($row2['f']);
	$c = cleanData($row2['c']);
	$t = cleanData($row2['t']);
	$r = cleanData($row2['r']);

	if($username != "guest"){
		if(isset($_COOKIE['timezone']))
			$timestamp = cleanData($row2['timestamp']) + $_COOKIE['timezone'];
		else $timestamp = cleanData($row2['timestamp']);
	} else $timestamp = cleanData($row2['timestamp']);

	// "get current timestamp for users avatar and users information"
	// "at right side of table."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
		$stmt->bindParam(':username', $username);
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

	$_SESSION['search'] = 1;
	require "threadDisplay.php";
	unset($_SESSION['search']);

	require "postProfile.php";

	if(isset($brTag2) && $brTag2 == 'y'){
		echo "<br>";
	}
	$iiiTemp++;
}

if($iiiTemp == 0){
	$_SESSION['noticesFair'] = "No search matches found";
	header("location: {$pluginUrl}search.php");
	exit;
}

if($pagination){
	if($brTag3 == 'y'){
		echo "<br>";
	}
	echo $pagination;
}
?>