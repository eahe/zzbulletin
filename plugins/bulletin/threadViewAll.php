<?php
require '../../includes/main/header.php';

if(isset($_GET['id'])){
	$id = cleanData($_GET['id']);

	// "determine if id value exists."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE id=:id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$row = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	$f = cleanData($row['f']);
	$c = cleanData($row['c']);
}

// "pagination var."
if(isset($_GET['p'])){
	$p = cleanData($_GET['p']);
} else $p = 1;
$p2 = $p + 1;

$_SESSION['fullUrl'] = fullUrl();

unset($_SESSION['onlyOnceImportantTopics']);
unset($_SESSION['onlyOnceForumTopics']);

if($id == NULL){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "First get total number of rows in threads table."
try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE f=:f AND c=:c AND r=0 AND s=0");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':c', $c);
	$stmt->execute();
	$total_pages = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$lastpage = ceil($total_pages/$limit2); // "lastpage is = total pages / items per p, rounded up."
	
if($total_pages == 0)
$c2 = 2;
else $c2 = $total_pages + 1;

if($c2 == 0 || $c == 0 || $p == 0 || $c2 < $p2){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

if(($lastpage > 0 && $lastpage < $p) || $p == 0){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

require 'threadViewAll2.php';
exit;

?>