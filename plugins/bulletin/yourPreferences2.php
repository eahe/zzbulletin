<?php
require "../../includes/main/header.php";

if(!isset($_COOKIE['username'])){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}
if(isset($_POST['tenResentBulletinPosts']))
$tenResentBulletinPosts = cleanData($_POST['tenResentBulletinPosts']);
if(isset($_POST['threadDisplay']))
$threadDisplay = cleanData($_POST['threadDisplay']);

if ($threadDisplay == 2)
$_SESSION['threadedMode'] = 1;
else unset($_SESSION['threadedMode']);

if(isset($_POST['limit']))
$limit = cleanData($_POST['limit']);
if(isset($_POST['limit2']))
$limit2 = cleanData($_POST['limit2']);
if(isset($_POST['brTag1']))
$brTag1 = cleanData($_POST['brTag1']);
if(isset($_POST['brTag2']))
$brTag2 = cleanData($_POST['brTag2']);
if(isset($_POST['brTag3']))
$brTag3 = cleanData($_POST['brTag3']);

// "update users preferences"
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}preferences SET tenResentBulletinPosts=:tenResentBulletinPosts, threadDisplay=:threadDisplay,  paginationPostsOnPage=:paginationPostsOnPage, paginationThreadsOnPage=:paginationThreadsOnPage, brTag1=:brTag1, brTag2=:brTag2, brTag3=:brTag3 WHERE username=:username");
	$stmt->bindParam(':tenResentBulletinPosts', $tenResentBulletinPosts);
	$stmt->bindParam(':threadDisplay', $threadDisplay);
	$stmt->bindParam(':paginationPostsOnPage', $limit);
	$stmt->bindParam(':paginationThreadsOnPage', $limit2);
	$stmt->bindParam(':brTag1', $brTag1);
	$stmt->bindParam(':brTag2', $brTag2);
	$stmt->bindParam(':brTag3', $brTag3);
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE username=:username");
	$stmt->bindParam(':username', $username);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$_SESSION['noticesGood'] = "Preferences saved.";
header("location: {$pluginUrl}yourPreferences.php");
exit;

?>