<?php
require "includes/main/header.php";

if(!isset($_COOKIE['username'])){
	$_SESSION['cookieCheck2'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['currentEmailAddress']))
$currentEmailAddress = cleanData($_POST['currentEmailAddress']);
if(isset($_POST['newEmailAddress']))
$newEmailAddress = cleanData($_POST['newEmailAddress']);

// "get the users current email address."
try {
	$stmt2 = $dbh->prepare("SELECT emailAddress FROM {$root}users WHERE username=:username");
	$stmt2->bindParam(':username', $username);
	$stmt2->execute();
	$row2 = $stmt2->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$currentEmailAddressGet = cleanData($row2['emailAddress']);

// "if the current email address entered is not correct then display an error."
if($currentEmailAddress != $currentEmailAddressGet){
	$_SESSION['noticesBad'] = "That email address is not found in the database.";
	header("location: {$pluginUrl}changeEmailAddress.php");
	exit;
}

// "if current email address is the same as your wanted new email address"
// "then display the notices bad message."
if($currentEmailAddress == $newEmailAddress){
	$_SESSION['noticesBad'] = "Your current email address and your new email address are the same.";
	header("location: {$pluginUrl}changeEmailAddress.php");
	exit;
}

// "save the new email address to the database."
try {
	$stmt = $dbh->prepare("UPDATE {$root}users SET emailAddress=:newEmailAddress WHERE username=:username");
	$stmt->bindParam(':newEmailAddress', $newEmailAddress);
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$_SESSION['noticesGood'] = "Your email address is now " . $newEmailAddress . ".";
header("location: {$pluginUrl}index");
exit;

?>