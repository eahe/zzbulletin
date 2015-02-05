<?php 
require '../../includes/main/sessions.php';
require "configuration/bulletin.php";
require '../../includes/main/database.php';
require '../../includes/main/getTablePrefix.php';
require 'includes/main/functions.php';
require 'includes/main/variables.php';
require '../../includes/main/variables.php';

$_SESSION['noSessionsNoConfig'] = 1;
$_SESSION['noFunctionsNoDatabase'] = 1;

if(isset($_GET['id'])){
	$idRead = cleanData($_GET['id']);
}

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

$id2 = cleanData($row['id']);
$f = cleanData($row['f']);
$c = cleanData($row['c']);
$t = cleanData($row['t']);
$r = cleanData($row['r']);

if(isset($_GET['p']))
$p = cleanData($_GET['p']);

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':r', $r);
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$c2 = cleanData($row2['c']);
$t2 = cleanData($row2['t']);
$r2 = cleanData($row2['r']);

if($c2 == 0 || $t2 != $t || $r2 == 0){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

try {
	$stmt1 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r ORDER BY r DESC Limit 1");
	$stmt1->bindParam(':c', $c);
	$stmt1->bindParam(':t', $t);
	$stmt1->bindParam(':r', $r);
	$stmt1->execute();
	$row1 = $stmt1->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$username2 = cleanData($row1['username']);

if($permission >= $postEditAll){
	require "includes/subPostEdit.php";
}

if($permission >= $postEdit && $username == $username2){
	require "includes/subPostEdit.php";
}

if(isset($_SESSION['noticesBad'])){
	noticesBad();
	unset($_SESSION['noticesBad']);
} else{
	$_SESSION['noticesBad'] = "You do not have permission to edit the post.";
	header("location: {$pluginUrl}threadRead/$idRead/$p");
	exit;
}

?>