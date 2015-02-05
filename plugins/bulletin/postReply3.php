<?php
require '../../includes/main/header.php';

if($permission < $postReply){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['topicTitle'])){
	$topicTitle = $_POST['topicTitle'];
	$_SESSION['topicTitle'] = $topicTitle;
}
if(isset($_POST['topicBody'])){
	$topicBody = $_POST['topicBody'];
	$_SESSION['topicBody'] = $topicBody;
}

if(isset($_POST['f']))
$f = cleanData($_POST['f']);
if(isset($_POST['c']))
$c = cleanData($_POST['c']);
if(isset($_POST['t']))
$t = cleanData($_POST['t']);
if(isset($_POST['r']))
$r = cleanData($_POST['r']);
if(isset($_POST['id']))
$idRead = cleanData($_POST['id']);
if(isset($_POST['p']))
$p = cleanData($_POST['p']);
else $p = 1;

$n = $r;

if(!isset($f) && !isset($c) && !isset($t) && !isset($r)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "get comment mode, such as thread flat or thread comment"
// "also get users number of posts. plus one for this post."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row3 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$totalPosts = cleanData($row3['totalPosts']) + 1;

// "get last post from thread."

try {
	$stmt = $dbh->prepare("SELECT MAX(r) AS r FROM {$bulletin}threads WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row4 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$r = cleanData($row4['r']);

// "get last post from thread."
if(isset($_SESSION['topicTitleLastPost']))
$topicTitleLastPost = $_SESSION['topicTitleLastPost'];

if($topicTitle == "" && $topicBody == ""){
	$_SESSION['noticesBad'] = "Topic title and topic body cannot be empty.";
	header("location: {$pluginUrl}postReply/$idRead/$p");
	exit;
}

if($topicTitle == ""){
	$_SESSION['noticesBad'] = "Topic title cannot be empty.";
	header("location: {$pluginUrl}postReply/$idRead/$p");
	exit;
}

if($topicBody == ""){
	$_SESSION['noticesBad'] = "Topic body cannot be empty.";
	header("location: {$pluginUrl}postReply/$idRead/$p");
	exit;
}

if(isset($_POST['preview'])){
	$_SESSION['postPreview'] = 1;	
	$_SESSION['noticesGood'] = "Post preview.";
	header("location: {$pluginUrl}postReply/$idRead/$p");
	exit;
}

if(isset($_FILES["file"])&& $_FILES["file"]["name"] !=  ""){
	// "limit the size of the file. if file is greater then the size permitted"
	// "then display the error message."
	if($_FILES["file"]["size"] > $maximumAttachmentUploadSize * 1024 * 1024 || $_FILES["file"]["size"] == 0){
		$_SESSION['noticesBad'] = "File is too large in bytes, is zero bytes, or has exceeded php.ini maximum size.";
		header('location: {$pluginUrl}postReply/$idRead/$p');
		exit;
	}

	// "the time in numbers are displayed in front of the file attachment name."
	$newNamePrefix = time() . '_';

	// "allowed image extensions."
	$allowedExts = array("zip", "tar");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if((($_FILES["file"]["type"] == "application/octet-stream")
			|| ($_FILES["file"]["type"] == "application/x-compressed")
			|| ($_FILES["file"]["type"] == "application/x-tar")
			|| ($_FILES["file"]["type"] == "application/zip")
			|| ($_FILES["file"]["type"] == "application/x-zip")			
		)
		&& in_array($extension, $allowedExts)){

		// "if error of some sort then display the error message."
		if($_FILES["file"]["error"] > 0){
			$_SESSION['noticesBad'] = "Return Code: " . $_FILES["file"]["error"] . ".";
			header('location: {$pluginUrl}postReply/$idRead/$p');
			exit;
		} else{
			// "checks for duplicate file attachment names."
			if(file_exists($archiveAttachmentDirectory . $newNamePrefix . $_FILES["file"]["name"])){
				$_SESSION['noticesBad'] = $newNamePrefix . $_FILES["file"]["name"] . " already exists.";
				header('location: {$pluginUrl}postReply/$idRead/$p');
				exit;
			} else{
				// "move file to attachment directory."
				if (is_writable($archiveAttachmentDirectory)){
					move_uploaded_file($_FILES["file"]["tmp_name"],
					$archiveAttachmentDirectory . $newNamePrefix . $_FILES["file"]["name"]);
					$attachFile = $newNamePrefix . $_FILES["file"]["name"];
				} else $_SESSION['noticesBad'] = "File not saved. permission denied for folder " . $archiveAttachmentDirectory;
			}
		}
	}	elseif($_FILES["file"]["name"] != ""){
		$_SESSION['noticesBad'] = "Invalid file. " . $_FILES["file"]["type"] . ".";

		header("location: {$pluginUrl}postReply/$idRead/$p");
		exit;
	}
}

$r++;

$timestamp = '0';
$timestamp = dateTimestamp($timestamp);

$topicTitle = cleanData($topicTitle);
$topicBody = cleanData($topicBody);

$topicBody2 = $topicBody;

if(!isset($attachFile))
$attachFile = "";
else $attachFile = cleanData($attachFile);

// "if title is new for any post in thread."
if($topicTitle != $_SESSION['topicTitleLastPost']){	
	$topicTitleLastPost = $_SESSION['topicTitleLastPost'];
	
	try {
		$stmt9 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE topicTitle=:topicTitle AND t=:t");
		$stmt9->bindParam(':topicTitle', $topicTitle);
		$stmt9->bindParam(':t', $t);
		$stmt9->execute();
		$row9 = $stmt9->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	$idRead = cleanData($row9['id']);
	
	if($idRead==NULL){
		// "if new title in post does not match any title in thread then"
		// "id will be the id in thread where r=1. this will give a threaded post"
		// "depth just greater then that of the first post in the thread."
		try {
			$stmt10 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE r=1 AND t=:t");
			$stmt10->bindParam(':t', $t);
			$stmt10->execute();
			$row10 = $stmt10->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$idRead =  cleanData($row10['id']);
	
	}
}

// "update the threads timestamp."
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}threads SET timestamp='$timestamp' WHERE f=:f AND c=:c AND t=:t AND r=0");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("INSERT INTO {$bulletin}threads (parentId, username, timestamp, topicTitle, topicBody, f, r, c, t, attachFile) VALUES ( :idRead, :username, :timestamp, :topicTitle, :topicBody2, :f, :r, :c, :t, :attachFile)");
	$stmt->bindParam(':idRead', $idRead);
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':timestamp', $timestamp);
	$stmt->bindParam(':topicTitle', $topicTitle);
	$stmt->bindParam(':topicBody2', $topicBody2);
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':r', $r);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':attachFile', $attachFile);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("INSERT INTO {$bulletin}mark_as_read (mark, username, f, r, c, t) VALUES (0, :username, :f, :r, :c, :t)");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':r', $r);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "get id from created topic
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':r', $r);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$idRead = cleanData($row1['id']);

// "get page count for pagination"
try {
	$stmt1 = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE c=:c AND t=:t AND r!=0");
	$stmt1->bindParam(':c', $c);
	$stmt1->bindParam(':t', $t);
	$stmt1->execute();
	$row1 = $stmt1->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$p = cleanData(ceil($row1/$limit));

// "update the users post count."
try {
	$stmt = $dbh->prepare("UPDATE {$root}users SET totalPosts=:totalPosts WHERE username=:username");
	$stmt->bindParam(':totalPosts', $totalPosts);
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "update the table forums timestamp."
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}forums SET timestamp=:timestamp WHERE f=:f AND c=:c");
	$stmt->bindParam(':timestamp', $timestamp);
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':c', $c);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

require "includes/sendSubscribedForumMail.php";

unset($_SESSION['topicTitleLastPost']);
unset($_SESSION['topicTitle']);
unset($_SESSION['topicBody']);

$_SESSION['threadedMode'] = 1;
include "markAsRead2.php";
unset($_SESSION['threadedMode']);

$id = $_SESSION['id'];

header("location: {$pluginUrl}threadRead/$idRead/$p");
exit;

?>