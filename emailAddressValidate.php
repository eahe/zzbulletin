<?php
require 'includes/main/header.php';

if(isset($_POST['username']))
$username = cleanData($_POST['username']);
elseif(isset($_GET['username']))
$username = cleanData($_GET['username']);

if(isset($_POST['emailAddress']))
$emailAddress = cleanData($_POST['emailAddress']);
elseif(isset($_GET['emailAddress']))
$emailAddress = cleanData($_GET['emailAddress']);

if($username == 'guest'){
	$_SESSION['noticesFair'] = 'A guest cannot validate an email address.';
	header("location: {$pluginUrl}index");
	exit;
}

// "get email address validate data"
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username AND emailAddress=:emailAddress");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':emailAddress', $emailAddress);
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$username2 =  cleanData($row2['username']);
$emailAddress2 = cleanData($row2['emailAddress']);
$emailAddressValidate = cleanData($row2['emailAddressValidate']);

if($username != $username2 || $emailAddress != $emailAddress2){
	$_SESSION['noticesBad'] = "Wrong username or email address.";
	header("location: {$pluginUrl}index");
	exit;
}

// "check if email address is validated. if '0' then email address is"
// "validated."
if($emailAddressValidate == '0' && $username != 'guest'){
	$_SESSION['noticesGood'] = "Your email address is already validated.";
	header("location: {$pluginUrl}index");
	exit;
}

// "if outgoing emails are not enabled then no email validation is required."
if($outgoingEmails == "n"){
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET emailAddressValidate='0' WHERE username=:username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$emailAddressValidate = 0;	

	$_SESSION['noticesGood'] = "Member created successfully. You can now login.";
	header("location: {$pluginUrl}index");
	exit;
} else{
	// "email address is not validated, so make email address validate a random"
	// "password."
	$emailAddressValidate = randomString();

	// "update database with the random variable of $emailAddressValidate."
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET emailAddressValidate=:emailAddressValidate WHERE username=:username");
		$stmt->bindParam(':emailAddressValidate', $emailAddressValidate);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "send the email address validate link out to the registered user."
	$to = "$emailAddress";
	$subject = $siteName . ": Validate your email address.";
	$message = "Here is the email address validate link for " . $siteName . ". Click the link below so that you can login. <a href=\"" . $pluginUrl . "emailAddressValidated.php?username=" . $username . "&emailAddressValidate=" . $emailAddressValidate . "\">Validate your email address here.</a>";
	$from = "$WebsiteEmailAddress";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From:" . $from;
	if(mail($to,$subject,$message,$headers)){
		// "after the email is sent to the users email address,"
		// "echo some important email address validate information."
		$_SESSION['noticesFair'] = "You can login only after you click the email address validate link from within your email software.";
		$_SESSION['noticesGood'] = "Email address validate link successfully sent to your email address!";

	} else{
		echo("Message delivery failed.");
	}
}

header("location: {$pluginUrl}index");
exit;

?>