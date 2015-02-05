<?php
require '../../includes/main/header.php';

if(isset($_GET['id'])){
$id = cleanData($_GET['id']);

	// "determine if id value exists in the database."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE id=:id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$row = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	$c = cleanData($row['c']);
	$t = cleanData($row['t']);
	$r = cleanData($row['r']);
	$parentId = cleanData($row['parentId']);
	$topicTitle = cleanData($row['topicTitle']);
} 

if(isset($_GET['p']))
$p = cleanData($_GET['p']);

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':r', $r);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$c2 = cleanData($row['c']);
$t2 = cleanData($row['t']);
$r2 = cleanData($row['r']);

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

if($permission >= $postDeleteAll && $r > 1){
	require "includes/subPostDelete.php";
}

if($permission >= $postDelete && $username == $username2 && $r > 1){
	require "includes/subPostDelete.php";
}

$id = $_SESSION['id'];
$_SESSION['noticesBad'] = "You do not have permission to delete this post.";
header("location: {$pluginUrl}threadRead/$id/$p");
exit;

?>