<?php
require '../../includes/main/header.php';

if(isset($_GET['id'])){
$idRead = cleanData($_GET['id']);
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
	$r = cleanData($row['r']);
}

if(isset($_GET['p']))
$p = cleanData($_GET['p']);
else $p = 1;

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=1");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
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
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r ORDER BY r DESC Limit 1");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':r', $r);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$username2 = cleanData($row1['username']);

if($permission >= $threadDelete && $username == $username2){
	require "includes/subThreadDelete.php";
}

if($permission >= $threadDeleteAll){
	require "includes/subThreadDelete.php";
}

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

$_SESSION['noticesGood'] = 'The thread deleted successfully.';
header("location: {$pluginUrl}threadViewAll/$id/1");
exit;

?>