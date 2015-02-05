<?php
require '../../includes/main/header.php';

if($permission < $lockThread){
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
	
	$c = cleanData($row['c']);
	$t = cleanData($row['t']);
} else{
	if(isset($_GET['c'])){
		$c = cleanData($_GET['c']);
	}
	if(isset($_GET['t'])){
		$t = cleanData($_GET['t']);
	}
}

if(!isset($c) && !isset($t)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$c2 = cleanData($row1['c']);
$t2 = cleanData($row1['t']);

if($c2 == 0 || $t2 != $t){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}threads SET l=0 WHERE c=:c AND t=:t AND r=0");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$id = $_SESSION['id'];
$_SESSION['noticesGood'] = 'Thread unlocked successfully.';
header("location: {$pluginUrl}threadViewAll/$id/1");
exit;

?>