<?php
// get username from password and email address"

require 'includes/main/header.php';
require 'includes/passwordHash.php';

// "cannot view this file if logged in."
if(isset($_COOKIE['username'])){
	$_SESSION['cookieCheck1'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['password']))
$password = cleanData($_POST['password']);
if(isset($_POST['emailAddress']))
$emailAddress = cleanData($_POST['emailAddress']);

$hasher = new PasswordHash($hashCostLog2, $hashPortable);

// "get hashed password from users email address."
$hash = '*'; // "In case the username is not found."

try {
	$stmt = $dbh->prepare("SELECT password FROM {$root}users where emailAddress=:emailAddress ORDER BY password desc LIMIT 1");
	$stmt->bindParam(':emailAddress', $emailAddress);
	$stmt->execute();
	$row = $stmt->fetch();
	$hash = $row['password'];
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "if password and hash matched then get username."
if($hasher->CheckPassword($password, $hash)){
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE password=:password AND emailAddress=:emailAddress ORDER BY id DESC LIMIT 1");
		$stmt->bindParam(':password', $hash);
		$stmt->bindParam(':emailAddress', $emailAddress);
		$stmt->execute();
		$row1 = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$username = cleanData($row1['username']);
	$emailAddress = cleanData($row1['emailAddress']);
} else{
	// "if username cannot be found, display the message."
	$_SESSION['noticesFair'] = "No username found.";
	header("location: {$pluginUrl}index");
	exit;
}

if($username == "" || $username == 'guest')
$username = "nobody";

//  "print the username."	
$_SESSION['noticesGood'] = "The most recent user that is using this email address is " . $username . ".";
header("location: {$pluginUrl}index");
exit;
?>