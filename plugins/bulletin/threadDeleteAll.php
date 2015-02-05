<?php
require '../../includes/main/header.php';
require 'includes/buttonsBulletin.php';
	
if($permission < $threadDeleteAll){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['c']))
$c = cleanData($_GET['c']);

if(!isset($f) && !isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "get id for threadViewAll."
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

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c");
	$stmt->bindParam(':c', $c);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$c2 = cleanData($row['c']);

if($c2 == 0){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "delete all threads from a category."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}threads WHERE c=:c");  
	$stmt->bindParam(':c', $c);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c");  
	$stmt->bindParam(':c', $c);  
	$stmt->bindParam(':username', $username);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete all poll questions from a category."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_questions WHERE c=:c");  
	$stmt->bindParam(':c', $c);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete all poll answers from a category."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_answers WHERE c=:c");  
	$stmt->bindParam(':c', $c);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete all poll votes from a category."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_votes WHERE c=:c");  
	$stmt->bindParam(':c', $c);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete poll cookie if any."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}cookies WHERE c=:c");  
	$stmt->bindParam(':c', $c);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

unset($_SESSION['noticesBad']);

$_SESSION['noticesGood'] = 'All threads deleted successfully.';
header("location: {$pluginUrl}threadViewAll/$id/1");
exit;

?>