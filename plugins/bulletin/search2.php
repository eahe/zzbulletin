<?php 
require '../../includes/main/header.php';

// "from buttonsBulletin.php variable $forumNew5."
if(isset($_SESSION['getF']))
$forumNew5 = $_SESSION['getF'];
// "from buttonsBulletin.php variable $c[$i]."
if(isset($_SESSION['getC']))
$c = $_SESSION['getC'];
// "from buttonsBulletin.php variable $p."
if(isset($_SESSION['getP']))
$p = $_SESSION['getP'];

require 'includes/buttonsBulletin.php';

// "exit if there are no categories to search."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=0 AND forumName!=''");
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$forumName = cleanData($row1['forumName']);

if($forumName == NULL){
	$_SESSION['noticesFair'] = "No categories to search.";
	header("location: {$pluginUrl}search.php");
	exit;
}

$where = ""; $searchCategoryTitle = ""; $iTemp = 0; $r = 0;

if(isset($_POST['keywords']) && $_POST['keywords'] != "")
$keywords = cleanData($_POST['keywords']);
elseif(isset($_GET['keywords']) && $_GET['keywords'] != "")
$keywords = cleanData($_GET['keywords']);
else{
	header("location: {$pluginUrl}search.php");
	exit;
}
if(isset($_POST['author']))
$author = cleanData($_POST['author']);
elseif(isset($_GET['author']))
$author = cleanData($_GET['author']);
	
if(isset($_POST['searchWithin']))
$searchWithin = cleanData($_POST['searchWithin']);
elseif(isset($_GET['searchWithin']))
$searchWithin = cleanData($_GET['searchWithin']);

if(isset($_POST['searchCategoryTitle']))
$searchCategoryTitle[] = $_POST['searchCategoryTitle'];
elseif(isset($_GET['searchCategoryTitle']))
$searchCategoryTitle[] = $_GET['searchCategoryTitle'];

$iTemp = 1;

if(isset($_POST['searchOrder']))
$searchOrder = cleanData($_POST['searchOrder']);
elseif(isset($_GET['searchOrder']))
$searchOrder = cleanData($_GET['searchOrder']);

if($searchWithin != "r=1"){
	if($searchWithin != "r!=0"){
		if($searchWithin != "r>0"){
			if($searchWithin != "r=2"){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
			}
		}
	}
}

// "remove hard return that select tag makes so that the categories are displayed"
// "on a single line."
$searchCategoryTitle[0] = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $searchCategoryTitle[0]);

$searchCategoryTitle2 = http_build_query(array('searchCategoryTitle' => $searchCategoryTitle[0]));

// "author: if author is not empty."
if($author != ""){
	// "topicTitle only with author search."
	if($searchWithin == "r=1"){
		$categories = "c=" . implode(" AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE) OR MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]) - 1;
		$categories .= " AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE)";
	}
	// "topicBody only with author search."
	if($searchWithin == "r!=0" ){
		$categories = "c=" . implode(" AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE) OR MATCH (topicBody) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]) - 1;
		$categories .= " AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE)";
	}
	// "topicTitle and topicBody search and second post of threads search."
	if($searchWithin == "r=2" || $searchWithin == "r>0"){
		$categories = "c=" . implode(" AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE) OR MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]) - 1;
		$categories .= " AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE) OR MATCH (topicBody) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=" . implode(" AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE) OR MATCH (topicBody) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]);
		$categories .= " AND MATCH (username) AGAINST ('$author' IN BOOLEAN MODE)";
	}
} else{ // "if author is empty."
	// "topicTitle only query without author search."
	if($searchWithin == "r=1"){
		$categories = "c=" . implode(" OR MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]) - 1;	
	}	
	// "topicBody only with author search."
	if($searchWithin == "r!=0" ){
		$categories = "c=" . implode(" OR MATCH (topicBody) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]) - 1;	
	}
	// "topicTitle and topicBody search and second post of threads search."
	if($searchWithin == "r=2" || $searchWithin == "r>0"){
		$categories = "c=" . implode(" OR MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]) - 1;	
		$categories .= " OR MATCH (topicBody) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=" . implode(" OR MATCH (topicBody) AGAINST (? IN BOOLEAN MODE) AND " . $searchWithin . " AND c=",$searchCategoryTitle[0]);
		$iTemp = $iTemp + count($searchCategoryTitle[0]);
	}

}

// "Boolean search or exact match."
// "Setup the query. "
if($keywords != "" && $searchWithin == "r=1"){// "Topictitle only."
	$query1 = "SELECT * FROM {$bulletin}threads WHERE MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE)";
	$query2 = "SELECT count(*) FROM {$bulletin}threads WHERE MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE)";
	$iTemp = $iTemp + 1;
	// "topicBody only."
} elseif($keywords != "" && $searchWithin == "r!=0"){
	$query1 = "SELECT * FROM {$bulletin}threads WHERE MATCH (topicBody) AGAINST (? IN BOOLEAN MODE)";
	$query2 = "SELECT count(*) FROM {$bulletin}threads WHERE MATCH (topicBody) AGAINST (? IN BOOLEAN MODE)";
	$iTemp = $iTemp + 1;
	// "Topictitle and topicBody or second posts of thread."
} elseif($keywords != "" && $searchWithin == "r=2" || $keywords != "" && $searchWithin == "r>0"){
	$query1 = "SELECT * FROM {$bulletin}threads WHERE MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE) ";
	$query2 = "SELECT count(*) FROM {$bulletin}threads WHERE MATCH (topicTitle) AGAINST (? IN BOOLEAN MODE) ";
		$iTemp = $iTemp + 1;
}

$query2 = $query2 . " AND " . $searchWithin;
$query2 = $query2 . " AND " . $categories;

// "First get total number of rows in threads table."
try {	
	$stmt = $dbh->prepare($query2);
	for($iiTemp = 1; $iiTemp <= $iTemp; $iiTemp++){
		$stmt->bindParam($iiTemp, $keywords);  	
	}	
	$stmt->execute();
	$total_pages = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$lastpage = ceil($total_pages/$limit4); // "lastpage is = total pages / items per p, rounded up."

include "includes/searchPagination.php";
require "../../includes/main/footer.php";
?>