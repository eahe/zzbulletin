<?php
require '../../includes/main/sessions.php';
require "configuration/bulletin.php";
require '../../includes/main/database.php';
require '../../includes/main/getTablePrefix.php';
require 'includes/main/functions.php';
require 'includes/main/variables.php';
require '../../includes/main/variables.php';

if(isset($_GET['id'])){
$idRead = cleanData($_GET['id']);

	// "determine if id value exists in the database."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE id=:idRead");
		$stmt->bindParam(':idRead', $idRead);
		$stmt->execute();
		$row = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$f = cleanData($row['f']);
	$c = cleanData($row['c']);
	$t = cleanData($row['t']);
	$r = cleanData($row['r']);
}

// "pagination var."
if(isset($_GET['p'])){
	$p = cleanData($_GET['p']);
} else $p = 1;

$_SESSION['fullUrl'] = fullUrl();

// "get poll data from thread variables."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}poll_questions WHERE c=:c AND t=:t ORDER BY c DESC LIMIT 1");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row4 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$c2 = $row4['c'];
$t2 = $row4['t'];

// "determine if poll is linked to thread."
if($c2 == $c && $t2 == $t){
	$_SESSION['pollDisplay'] = 1;
	$_SESSION['f'] = $f;
	$_SESSION['c'] = $c;
	$_SESSION['t'] = $t;
}

// "First get total number of rows in threads table."
try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE c=:c AND t=:t AND r>0");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$total_pages = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$lastpage = ceil($total_pages/$limit);	// "lastpage is = total pages / items per p, rounded up."

if(!isset($c) || !isset($t)){
	$_SESSION['noticesBad'] = "1Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

/*
$c2 = cleanData($row['c']);
$t2 = cleanData($row['t']);

if($c2 == '' || $t2 == ''){
	$_SESSION['noticesBad'] = "3Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}*/

$_SESSION['noSessionsNoConfig'] = 1;
$_SESSION['noFunctionsNoDatabase'] = 1;

require 'threadRead2.php';

?>