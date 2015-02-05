<?php
require '../../includes/main/header.php';

if($permission < $forumNew){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['forumName']))
$forumName = cleanData($_POST['forumName']);

if(isset($_POST['f']))
$f = cleanData($_POST['f']);

if(!isset($f) && !isset($forumName)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY f ASC");
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

/*
"error if creating a forum topic and there
"exists a forum topic with that name. the
"($_SESSION['boardError1'] = 1) sets the error
"while forumNew.php displays the error."
*/
while($row2 = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($row2['forumName'] == $forumName && $forumName != NULL){
		$_SESSION['boardError1'] = 1;
		header("location: {$pluginUrl}forumNew/$f");
		exit;
	}
}

// "($_SESSION['boardError2'] = 1) sets an error "
// "message to be displayed at forumNew.php file."
if($forumName == Null){
	$_SESSION['boardError2'] = 1;
	header("location: {$pluginUrl}forumNew/$f");
	exit;
}

// "create the forum"
try {
	$stmt = $dbh->prepare("INSERT INTO {$bulletin}forums (f, c, forumName) VALUES (:f, '0', :forumName)");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':forumName', $forumName);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$_SESSION['noticesGood'] = "Forum created.";
header("location: {$pluginUrl}index");
exit;

?>