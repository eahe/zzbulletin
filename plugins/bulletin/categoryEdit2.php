<?php
require '../../includes/main/header.php';

if($permission < $categoryEdit){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['c']))
$c = cleanData($_POST['c']);
if(isset($_POST['categoryTitle']))
$categoryTitle = cleanData($_POST['categoryTitle']);
if(isset($_POST['categoryBody']))
$categoryBody = cleanData($_POST['categoryBody']);

// "output these notices at categoryEdit.php."
if($categoryTitle == "" && $categoryBody == ""){
	$_SESSION['noticesBad'] = "Category title and category body cannot be empty.";
	header("location: {$pluginUrl}categoryEdit/$c");
	exit;
}

if($categoryTitle == ""){
	$_SESSION['noticesBad'] = "Category title cannot be empty.";
	header("location: {$pluginUrl}categoryEdit/$c");
	exit;
}

if($categoryBody == ""){
	$_SESSION['noticesBad'] = "Category body cannot be empty.";
	header("location: {$pluginUrl}categoryEdit/$c");
	exit;
}

// "if no more errors then update the database of forums."
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}forums SET categoryTitle=:categoryTitle, categoryBody=:categoryBody WHERE c=:c");
	$stmt->bindParam(':categoryTitle', $categoryTitle);
	$stmt->bindParam(':categoryBody', $categoryBody);
	$stmt->bindParam(':c', $c);	
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
header("location: {$pluginUrl}index");
?>
