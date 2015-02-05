<?php
require "includes/main/header.php";
require 'includes/passwordHash.php';

$hasher = new PasswordHash($hashCostLog2, $hashPortable);

// "cannot view this file if logged in."
if(isset($_COOKIE['username'])){
	$_SESSION['cookieCheck1'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['op']))
$op = cleanData($_POST['op']);
if(isset($_POST['username']))
$username = cleanData($_POST['username']);
if(isset($_POST['emailAddress']))
$emailAddress2 = cleanData($_POST['emailAddress']);
if(isset($_POST['securityQuestion']))
$securityQuestion2 = cleanData($_POST['securityQuestion']);

$newPassword = randomString();

if(isset($username) && $username != 'guest'){
	try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
	$emailAddress = $row['emailAddress'];
	$securityQuestion = $row['securityQuestion'];
	
	// "check if username exists"
	if(!isset($row['username'])){
		$_SESSION['noticesBad'] = "Username \"" . $username . "\" does not exist.";
		header("location: {$pluginUrl}forgotPassword.php");
		exit;
	}
	
	if(isset($row['username']) && $emailAddress != $emailAddress2){
		$_SESSION['noticesBad'] = "Email address \"" . $emailAddress2 . "\" does not exist for username \"" . $username . "\".";
		header("location: {$pluginUrl}forgotPassword.php");
		exit;
	}	
	
	if(isset($row['username']) && $securityQuestion != $securityQuestion2){
		$_SESSION['noticesBad'] = "Security question \"" . $securityQuestion2 . "\" does not exist for username \"" . $username . "\".";
		header("location: {$pluginUrl}forgotPassword.php");
		exit;
	}
	
	// "if bulletin emails are not enabled."
	if($outgoingEmails == "n" ){
		$_SESSION['noticesBad'] = "Forgot password is disabled because sending emails are disabled at this bulletin.";
		header("location: {$pluginUrl}index");
		exit;
	}
	
	// "create the $passwordValidate with random data."
	$passwordValidate = randomString();

	// "update $passwordValidate in the database."
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET passwordValidate=:passwordValidate WHERE username=:username");
		$stmt->bindParam(':passwordValidate', $passwordValidate);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "send the email out with instructions to click the $passwordValidate"
	// "link so that the new password will be saved."
	$to = "$emailAddress";
	$subject = $siteName . ": New password.";
	$message = "Click the below password link so that you can get your new password emailed to you, then login and change your password to something more rememberable.<br><br> If you did not click the forgot password link from " . $siteName . ", then you can delete this email and login as usual.<br><br> <a href=\"" . $pluginUrl . "userMainSetup.php?username=" . $username . "&op=" . $op . "&passwordValidate=" . $passwordValidate . "\">Change password here.</a>";
	$from = $WebsiteEmailAddress;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From:" . $from;
	if(mail($to,$subject,$message,$headers)){

		// "message to tell the user that the forgot password was"
		// "sent to the users email address."
		$_SESSION['noticesGood'] = "Forgot password link successfully sent to your email address!";
		header("location: {$pluginUrl}index");
	} else{
		echo("Message delivery failed");
	}
} else{
	$_SESSION['noticesFair'] = "Guest cannot retrieve a password.";
	header("location: {$pluginUrl}forgotPassword.php");
	exit;
}

?>