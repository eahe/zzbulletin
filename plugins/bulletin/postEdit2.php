<?php
require '../../includes/main/header.php';
require 'includes/imageManipulator.php';

if($permission < $postEdit){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['id']))
$idRead = cleanData($_POST['id']);

if(isset($_POST['r']))
$r = cleanData($_POST['r']);

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

$id2 = cleanData($row['id']);
$c = cleanData($row['c']);
$t = cleanData($row['t']);
$r = cleanData($row['r']);

if(isset($_POST['topicTitle']))
$topicTitle = cleanData($_POST['topicTitle']);
if(isset($_POST['topicBody']))
$topicBody = cleanData($_POST['topicBody']);
if(isset($_POST['p']))
$p = cleanData($_POST['p']);
if(isset($_POST['attachFile']))
$attachFile = cleanData($_POST['attachFile']);
if(isset($_POST['attachFileCheck']))
$attachFileCheck = cleanData($_POST['attachFileCheck']);

if(isset($attachFileCheck) && $attachFileCheck == "yes"){
	$fileName = $archiveAttachmentDirectory . $attachFile;
	if(file_exists($fileName)){
		unlink($fileName);
		$attachFile = "";
	}
}

if(!isset($c) && !isset($t) && !isset($r) && !isset($p)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

if($topicTitle == "" && $topicBody == ""){
	$_SESSION['noticesBad'] = "Topic title and topic body cannot be empty.";
	header("location: {$pluginUrl}postEdit/$idRead/$p");
	exit;
}

if($topicTitle == ""){
	$_SESSION['noticesBad'] = "Topic title cannot be empty.";
	header("location: {$pluginUrl}postEdit/$idRead/$p");
	exit;
}

if($topicBody == ""){
	$_SESSION['noticesBad'] = "Topic body cannot be empty.";
	header("location: {$pluginUrl}postEdit/$idRead/$p");
	exit;
}

if(isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] !=  ""){
	// "limit the size of the file. if file is greater then the size permitted"
	// "then display the error message
	if($_FILES["file"]["size"] > $maximumAttachmentUploadSize * 1024 * 1024 || $_FILES["file"]["size"] == 0){
		$_SESSION['noticesBad'] = "File is too large in bytes, is zero bytes, or has exceeded php.ini maximum size.";
		header("location: {$pluginUrl}postEdit/$idRead/$p");
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
			header("location: {$pluginUrl}postEdit/$idRead/$p");
			exit;
		} else{
			// "checks for duplicate file attachment names."
			if(file_exists($archiveAttachmentDirectory . $newNamePrefix . $_FILES["file"]["name"])){
				$_SESSION['noticesBad'] = $newNamePrefix . $_FILES["file"]["name"] . " already exists.";
				header("location: {$pluginUrl}postEdit/$idRead/$p");
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

		header("location: {$pluginUrl}postEdit/$idRead/$p");
		exit;
	}
}

// "input the current timestamp in the database for the reply post."
$timestamp = '0';
$timestamp = dateTimestamp($timestamp);

// "update the thread timestamp."
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}threads SET timestamp=:timestamp WHERE c=:c AND t=:t AND r=0");
	$stmt->bindParam(':timestamp', $timestamp);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "if no notices then update the database of threads."
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}threads SET topicTitle=:topicTitle, topicBody=:topicBody, attachFile=:attachFile, timestamp=:timestamp WHERE c=:c AND t=:t AND r=:r");
	$stmt->bindParam(':topicTitle', $topicTitle);
	$stmt->bindParam(':topicBody', $topicBody);
	$stmt->bindParam(':attachFile', $attachFile);
	$stmt->bindParam(':timestamp', $timestamp);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->bindParam(':r', $r);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

header("location: {$pluginUrl}threadRead/$idRead/$p");
exit;

?>