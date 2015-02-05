<?php
require '../../includes/main/header.php';
require 'includes/imageManipulator.php';

if($permission < $threadNew){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['f']))
$f = cleanData($_POST['f']);
if(isset($_POST['c']))
$c = cleanData($_POST['c']);
if(isset($_POST['t']))
$t = cleanData($_POST['t']);

if(!isset($f) && !isset($c) && !isset($t)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

if(!isset($_SESSION['newPoll'])){
	if(isset($_POST['topicTitle'])){
		$topicTitle = $_POST['topicTitle'];
		$_SESSION['topicTitle'] = $topicTitle;
	}
}

if(isset($_POST['topicBody'])){
	$topicBody =  $_POST['topicBody'];
	$_SESSION['topicBody'] = $topicBody;
}

// "get the question and answer for the new poll (pollNew.php)."
if(isset($_POST['question'])){
	$question = $_POST['question'];
	$_SESSION['question'] = $question;
}

if(isset($_POST['answer1']))
$_SESSION['answer1'] = $_POST['answer1'];

if(isset($_POST['answer2']))
$_SESSION['answer2'] = $_POST['answer2'];

if(isset($_POST['answer3']))
$_SESSION['answer3'] = $_POST['answer3'];

if(isset($_POST['answer4']))
$_SESSION['answer4'] = $_POST['answer4'];

if(isset($_POST['answer5']))
$_SESSION['answer5'] = $_POST['answer5'];

if(isset($_POST['answer6']))
$_SESSION['answer6'] = $_POST['answer6'];

if(isset($_POST['answer7']))
$_SESSION['answer7'] = $_POST['answer7'];

if(isset($_POST['answer8']))
$_SESSION['answer8'] = $_POST['answer8'];

// "determine how many poll answers there are."
if(isset($_SESSION['newPoll'])){
	$pollAnswer = 0;
	for($i = 1; $i <= 8; $i++){
		if($_POST['answer'. $i] != ''){
			$pollAnswer++;
		}
	}
}

if(!isset($_SESSION['newPoll']) && $topicTitle == "" && $topicBody == ""){
	$_SESSION['noticesBad'] = "Topic title and topic body cannot be empty.";
	header("location: {$pluginUrl}threadNew/$f/$c/$t");
	exit;
}

if(!isset($_SESSION['newPoll']) && isset($topicTitle) && $topicTitle == ""){
	$_SESSION['noticesBad'] = "Topic title cannot be empty.";
	header("location: {$pluginUrl}threadNew/$f/$c/$t");
	exit;
}

if(isset($_SESSION['newPoll']) && $pollAnswer < 2 && $topicBody == "" && $question == ''){
	$_SESSION['noticesBad'] = "Poll question cannot be empty. Must have at least 2 answers for a poll. Topic body cannot be empty.";
	header("location: {$pluginUrl}pollNew/$f/$c/$t");
	exit;
}

if(isset($_SESSION['newPoll']) && $pollAnswer < 2 && $topicBody == ""){
	$_SESSION['noticesBad'] = "Must have at least 2 answers for a poll. Topic body cannot be empty.";
	header("location: {$pluginUrl}pollNew/$f/$c/$t");
	exit;
}

if(isset($_SESSION['newPoll']) && $pollAnswer < 2 && $question == ''){
	$_SESSION['noticesBad'] = "Poll question cannot be empty. Must have at least 2 answers for a poll.";
	header("location: {$pluginUrl}pollNew/$f/$c/$t");
	exit;
}

if(isset($_SESSION['newPoll']) && $question == '' && $topicBody == ''){
	$_SESSION['noticesBad'] = "Poll question and topic body cannot be empty.";
	header("location: {$pluginUrl}pollNew/$f/$c/$t");
	exit;
}

if(isset($_SESSION['newPoll']) && $pollAnswer < 2 ){
	$_SESSION['noticesBad'] = "Must have at least 2 answers for a poll.";
	header("location: {$pluginUrl}pollNew/$f/$c/$t");
	exit;
}

if($topicBody == ""){
	$_SESSION['noticesBad'] = "Topic body cannot be empty.";
	if(isset($_SESSION['newPoll'])){
		header("location: {$pluginUrl}pollNew/$f/$c/$t");
		exit;
	} else{
		header("location: {$pluginUrl}threadNew/$f/$c/$t");
		exit;
	}
}

if(isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] !=  ""){

	// "limit the size of the file. if file is greater then the size permitted"
	// "then display the error message."
	if($_FILES["file"]["size"] > $maximumAttachmentUploadSize * 1024 * 1024 || $_FILES["file"]["size"] == 0){
		$_SESSION['noticesBad'] = "File is too large in bytes, is zero bytes, or has exceeded php.ini maximum size.";
		header("location: {$pluginUrl}threadNew/$f/$c/$t");
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
			header("location: {$pluginUrl}threadNew/$f/$c/$t");
			exit;
		} else{
			// "checks for duplicate file attachment names."
			if(file_exists($archiveAttachmentDirectory . $newNamePrefix . $_FILES["file"]["name"])){
				$_SESSION['noticesBad'] = $newNamePrefix . $_FILES["file"]["name"] . " already exists.";
				header("location: {$pluginUrl}threadNew/$f/$c/$t");
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

		header("location: {$pluginUrl}threadNew/$f/$c/$t");
		exit;
	}
}

$timestamp = 0; $r = 1;
$timestamp = dateTimestamp($timestamp);

if(isset($_SESSION['newPoll'])){
	if(isset($question))
	$topicTitle = cleanData($question);

	for($i = 1; $i <= 8; $i++){
		if($_POST['answer'. $i] != ''){
			// "create the poll."
			$answer = cleanData($_POST['answer'. $i]);
			
			try {
				$stmt = $dbh->prepare("INSERT INTO {$bulletin}poll_answers (f, c, t, value) VALUES (:f, :c, :t, :answer)");
				$stmt->bindParam(':f', $f);
				$stmt->bindParam(':c', $c);
				$stmt->bindParam(':t', $t);
				$stmt->bindParam(':answer', $answer);
				$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}
	}

	$question = cleanData($question);

	try {
		$stmt = $dbh->prepare("INSERT INTO {$bulletin}poll_questions (f, c, t, question) VALUES (:f, :c, :t, :question)");
		$stmt->bindParam(':f', $f);
		$stmt->bindParam(':c', $c);
		$stmt->bindParam(':t', $t);
		$stmt->bindParam(':question', $question);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
} else $topicTitle = cleanData($topicTitle);

$topicBody = cleanData($topicBody);

// "empty table cell cannot be edited in opera browser, so the following stops"
// "that from happening."
$topicBody = str_replace('</td>','<strong>&nbsp;</strong></td>',$topicBody);
$topicBody = str_replace('&nbsp;<strong>&nbsp;</strong></td>','<strong>&nbsp;</strong></td>',$topicBody);
$topicBody = str_replace('&nbsp;<strong>&nbsp;</strong><strong>&nbsp;</strong></td>','<strong>&nbsp;</strong></td>',$topicBody);
$topicBody = str_replace('<strong>&nbsp;</strong><strong>&nbsp;</strong></td>','<strong>&nbsp;</strong></td>',$topicBody);

// "used for the timestamp of the last post."
try {
	$stmt = $dbh->prepare("INSERT INTO {$bulletin}threads (f, c, t, r, timestamp) VALUES (:f, :c, :t, 0, :timestamp)");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':timestamp', $timestamp);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("INSERT INTO {$bulletin}threads (parentId, f, c, t, topicTitle, topicBody, r, username, timestamp, attachFile) VALUES ('1', :f, :c, :t, :topicTitle, :topicBody, :r, :username, :timestamp, :attachFile)");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':topicTitle', $topicTitle);
	$stmt->bindParam(':topicBody', $topicBody);
	$stmt->bindParam(':r', $r);
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':timestamp', $timestamp);
	$stmt->bindParam(':attachFile', $attachFile);
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

// "get variable display to determine bulletin flat display or comment display."
// "$totalPosts equals total post count the user has plus one for this post."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$totalPosts = cleanData($row2['totalPosts']) + 1;

// "update post count that the user has."
try {
	$stmt = $dbh->prepare("UPDATE {$root}users SET totalPosts=:totalPosts WHERE username=:username");
	$stmt->bindParam(':totalPosts', $totalPosts);
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "get id of new post."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':r', $r);
	$stmt->execute();
	$row8 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$idNew = cleanData($row8['id']);

require "includes/sendSubscribedForumMail.php";

$_SESSION['views'] = 1;
	
unset($_SESSION['topicTitle']);
unset($_SESSION['topicBody']);

unset($_SESSION['question']);
unset($_SESSION['answer1']);
unset($_SESSION['answer2']);
unset($_SESSION['answer3']);
unset($_SESSION['answer4']);
unset($_SESSION['answer5']);
unset($_SESSION['answer6']);
unset($_SESSION['answer7']);
unset($_SESSION['answer8']);
	
$_SESSION['threadedMode'] = 1;
include "markAsRead2.php";
unset($_SESSION['threadedMode']);
$id = $_SESSION['id'];
	
header("location: {$pluginUrl}threadRead/$idNew");
exit;

?>