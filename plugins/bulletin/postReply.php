<?php
require '../../includes/main/sessions.php';
require "configuration/bulletin.php";
require '../../includes/main/database.php';
require '../../includes/main/getTablePrefix.php';
require 'includes/main/functions.php';
require 'includes/main/variables.php';
require '../../includes/main/variables.php';

$_SESSION['fullUrl'] = fullUrl();

$_SESSION['noSessionsNoConfig'] = 1;
$_SESSION['noFunctionsNoDatabase'] = 1;
	
if($permission < $postReply){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

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

if(isset($_GET['q'])){
	$q = $_GET['q'];
} else $q = 0;
if(isset($_GET['p'])){
	$p = $_GET['p'];
} else $p = 1;

// "after login, variable basename will return user to the last page before login."
$_SESSION['fullUrl'] = fullUrl();
	
// "First get total number of rows in thread table."
try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE c=:c AND t=:t AND r!=0 ORDER BY r desc LIMIT 1");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$total_pages = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per p, rounded up.

if(!isset($f) || !isset($c) || !isset($t) || !isset($r) || $q > 1){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

if($p == 0 ){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt2 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE f=:f AND c=:c AND t=:t");
	$stmt2->bindParam(':f', $f);
	$stmt2->bindParam(':c', $c);
	$stmt2->bindParam(':t', $t);
	$stmt2->execute();
	$row2 = $stmt2->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$f2 = cleanData($row2['f']);
$c2 = cleanData($row2['c']);
$t2 = cleanData($row2['t']);
$r2 = cleanData($row2['r']);

if($f2 == '' || $c2 == '' || $t2 == '' || $r2 == ''){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

require 'postReply2.php';
?>