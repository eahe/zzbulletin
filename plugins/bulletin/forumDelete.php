<?php
require '../../includes/main/header.php';

if($permission < $forumDelete){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['f']))
$f = cleanData($_GET['f']);

if(!isset($f)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE f=:f");
	$stmt->bindParam(':f', $f);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$f2 = $row['f'];

if($f2 == ''){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "delete board and category from database table forum"
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}forums WHERE f=:f");
	$stmt->bindParam(':f', $f);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE f=:f");
	$stmt->bindParam(':f', $f);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}threads WHERE f=:f");
	$stmt->bindParam(':f', $f);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete all poll questions from a forum."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_questions WHERE f=:f");
	$stmt->bindParam(':f', $f);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
// "delete all poll answers from a forum."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_answers WHERE f=:f");
	$stmt->bindParam(':f', $f);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
// "delete all poll votes from a forum."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_votes WHERE f=:f");
	$stmt->bindParam(':f', $f);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
// "delete poll cookie if any."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}cookies WHERE f=:f");
	$stmt->bindParam(':f', $f);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
unset($_SESSION['noticesBad']);
$_SESSION['noticesGood'] = 'Forum deleted successfully.';
header("location: {$pluginUrl}index");
exit;

?>