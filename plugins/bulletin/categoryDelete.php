<?php
require '../../includes/main/header.php';

if($permission < $categoryDelete){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['c']))
$c = cleanData($_GET['c']);

if(!isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
	$stmt->bindParam(':c', $c);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$c2 = $row['c'];

if($c2 == 0){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "delete category."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}forums WHERE c=:c");
	$stmt->bindParam(':c', $c);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE c=:c");
	$stmt->bindParam(':c', $c);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete all threads."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}threads WHERE c=:c");
	$stmt->bindParam(':c', $c);  
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
$_SESSION['noticesGood'] = 'Category deleted successfully.';
header("location: {$pluginUrl}index");
exit;

?>