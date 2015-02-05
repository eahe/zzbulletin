<?php
require 'includes/main/header.php';

// "cannot view this file if logged in"
if(isset($_COOKIE['username'])){
	$_SESSION['cookieCheck1'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['username']))
$usernameGet =  cleanData($_GET['username']);

if(isset($_GET['emailAddressValidate']))
$emailAddressValidateGet =  cleanData($_GET['emailAddressValidate']);

// "get the username and emailAddressValidate code from table users."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $usernameGet);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$username = cleanData($row['username']);
$emailAddressValidate = cleanData($row['emailAddressValidate']);

if($username == $usernameGet && $emailAddressValidate == "0"){
	$_SESSION['noticesGood'] = "Please login. Your email address is validated.";
	header("location: {$pluginUrl}index");
	exit;
} 

/*
"if the $_GET is ok then save in the database.
for example, the user checks mail and clicks a link, the $_get variables
from that link is verified from the database table of users."
*/
if(($username == $usernameGet) && ($emailAddressValidate == $emailAddressValidateGet)){

	// "if emailAddressValidate='0' then the user can login because"
	// "the email address is now validated when '0' is set."
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET emailAddressValidate='0' WHERE username=:username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	$_SESSION['noticesGood'] = "You can now login.";
	header("location: {$pluginUrl}index");
	exit;
} else{
	// "if the validate link submitted to the users email address"
	// "was changed then display the error message"
	$_SESSION['noticesBad'] = "Possible modification of the email address validate link or an old email address validate link.";
	header("location: {$pluginUrl}index");
	exit;
}
?>