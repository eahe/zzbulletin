<?php
require "../../includes/main/header.php";

if($permission < $forumEdit){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

// "clean and set the variables."
if(isset($_POST['forumName']))
$forumName = cleanData($_POST['forumName']);
if(isset($_POST['f']))
$f = cleanData($_POST['f']);
if(isset($_POST['c']))
$c = cleanData($_POST['c']);

if(!isset($f) && !isset($c) && !isset($forumName)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY f ASC");
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

if($forumName == Null){
	$_SESSION['boardError1'] = 1; // "Forum name cannot be empty.";
	header("location: {$pluginUrl}forumEdit/$f/$c");
	exit;
} elseif($row2['forumName'] == $forumName){
	$_SESSION['boardError2'] = 1; // "You have typed in a forum name that already exists.";
	header("location: {$pluginUrl}forumEdit/$f/$c");
	exit;
}

// "changes the forumName (the forum name)
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}forums SET forumName=:forumName WHERE f=:f AND c=0");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':forumName', $forumName);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

if($_SESSION['forumEdit'] == 1){
	header("location: {$pluginUrl}index");
	exit;
} elseif($_SESSION['forumEdit'] == 2){

try {
	$stmt4 = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
	$stmt4->bindParam(':c', $c);
	$stmt4->execute();
	$row4 = $stmt4->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$id = $row4['id'];

header("location: {$pluginUrl}threadViewAll/$id/1");
exit;
}
?>