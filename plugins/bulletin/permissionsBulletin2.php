<?php
require "../../includes/main/header.php";

if(!isset($_COOKIE['username'])){	
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if($permission < 4 && $username != 'admin'){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

$forumNew = $_POST['forumNew'];
$forumEdit = $_POST['forumEdit'];
$forumDelete = $_POST['forumDelete'];
$categoryNew = $_POST['categoryNew'];
$categoryReorder = $_POST['categoryReorder'];
$categoryEdit = $_POST['categoryEdit'];
$categoryDelete = $_POST['categoryDelete'];
$threadNew = $_POST['threadNew'];
$threadDelete = $_POST['threadDelete'];
$threadDeleteAll = $_POST['threadDeleteAll'];
$postReply = $_POST['postReply'];
$postEdit = $_POST['postEdit'];
$postEditAll = $_POST['postEditAll'];
$postDelete = $_POST['postDelete'];
$postDeleteAll = $_POST['postDeleteAll'];
$attachFileToPost = $_POST['attachFileToPost'];
$attachFileDownload = $_POST['attachFileDownload'];
$attachFileDelete = $_POST['attachFileDelete'];
$pollNew = $_POST['pollNew'];
$pollVote = $_POST['pollVote'];
$pinThread = $_POST['pinThread'];
$lockThread = $_POST['lockThread'];
$subscribeForum = $_POST['subscribeForum'];
$subscribeThread = $_POST['subscribeThread'];

try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}permissions SET forumNew=:forumNew, forumEdit=:forumEdit, forumDelete=:forumDelete, categoryNew=:categoryNew, categoryReorder=:categoryReorder, categoryEdit=:categoryEdit, categoryDelete=:categoryDelete, threadNew=:threadNew, threadDelete=:threadDelete, threadDeleteAll=:threadDeleteAll, postReply=:postReply, postEdit=:postEdit, postEditAll=:postEditAll, postDelete=:postDelete, postDeleteAll=:postDeleteAll, attachFileToPost=:attachFileToPost, attachFileDownload=:attachFileDownload, attachFileDelete=:attachFileDelete, pollNew=:pollNew, pollVote=:pollVote, pinThread=:pinThread, lockThread=:lockThread, subscribeForum=:subscribeForum, subscribeThread=:subscribeThread");
	$stmt->bindParam(':forumNew', $forumNew);
	$stmt->bindParam(':forumEdit', $forumEdit);
	$stmt->bindParam(':forumDelete', $forumDelete);
	$stmt->bindParam(':categoryNew', $categoryNew);
	$stmt->bindParam(':categoryReorder', $categoryReorder);
	$stmt->bindParam(':categoryEdit', $categoryEdit);
	$stmt->bindParam(':categoryDelete', $categoryDelete);
	$stmt->bindParam(':threadNew', $threadNew);
	$stmt->bindParam(':threadDelete', $threadDelete);
	$stmt->bindParam(':threadDeleteAll', $threadDeleteAll);
	$stmt->bindParam(':postReply', $postReply);
	$stmt->bindParam(':postEdit', $postEdit);
	$stmt->bindParam(':postEditAll', $postEditAll);
	$stmt->bindParam(':postDelete', $postDelete);
	$stmt->bindParam(':postDeleteAll', $postDeleteAll);
	$stmt->bindParam(':attachFileToPost', $attachFileToPost);
	$stmt->bindParam(':attachFileDownload', $attachFileDownload);
	$stmt->bindParam(':attachFileDelete', $attachFileDelete);
	$stmt->bindParam(':pollNew', $pollNew);
	$stmt->bindParam(':pollVote', $pollVote);
	$stmt->bindParam(':pinThread', $pinThread);
	$stmt->bindParam(':lockThread', $lockThread);
	$stmt->bindParam(':subscribeForum', $subscribeForum);
	$stmt->bindParam(':subscribeThread', $subscribeThread);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
$_SESSION['noticesGood'] = "Permissions saved.";
header("location: {$pluginUrl}permissionsBulletin.php");
exit;

?>