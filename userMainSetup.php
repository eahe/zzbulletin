<?php
require 'includes/main/header.php';
require 'includes/passwordHash.php';

// "did not agree to the terms of use."
if(isset($_POST['agree']) && cleanData($_POST['agree'] == 0)){
	$_SESSION['noticesBad'] = "You did not agree to the terms of use. Username not created.";
	header("location: {$rootUrl}register.php");
	exit;
}

// "did not agree to the privacy policy."
if(isset($_POST['agree2']) && cleanData($_POST['agree2'] == 0)){
	$_SESSION['noticesBad'] = "You did not agree to the privacy policy.";
	header("location: {$rootUrl}login.php");
	exit;
}

// "$back refers to the page that called this page."
if(isset($_POST['back']))
	$back = cleanData($_POST['back']);
elseif(isset($_GET['back']))
$back = cleanData($_GET['back']);

if(isset($_POST['emailAddress'])){
	$emailAddress =  cleanData($_POST['emailAddress']);
	$_SESSION['emailAddress'] = $emailAddress;
} elseif(isset($_GET['emailAddress'])){
	$emailAddress = cleanData($_GET['emailAddress']);
	$_SESSION['emailAddress'] = $emailAddress;
}

// "forgotPassword page uses passwordValidate to send a new random"
// "password to email address."
if(isset($_GET['passwordValidate']))
	$passwordValidate = cleanData($_GET['passwordValidate']);

// "$op is a form submitted variable. the variable of op can be 'new',"
// "'login', 'forgot'or 'change. see below:'."
if(isset($_POST['op']))
	$op = cleanData($_POST['op']);
elseif(isset($_GET['op']))
$op = cleanData($_GET['op']);

if(!isset($op)){
	$_SESSION['noticesBad'] = "Direct access denied for \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$rootUrl}index");
	exit;
}

// "if $op is not any of the variable"
// "names, then go back to the page that called this page."
if($op !== 'new' && $op !== 'login' && $op !== 'forgot' && $op !== 'change'){
	$_SESSION['noticesBad'] = "Unknown request.";
	header("location: {$rootUrl}" . $back);
	exit;
}

// "get username by link or by form submit."
if(isset($_POST['username'])){
	$username = cleanData($_POST['username']);
	$_SESSION['registerUsername'] = $username;
} elseif(isset($_GET['username'])){
	$username = cleanData($_GET['username']);
	$_SESSION['registerUsername'] = $username;
}

// "your gender."
if(isset($_POST['yourGender'])){
	$yourGender = cleanData($_POST['yourGender']);
	$_SESSION['yourGender'] = $yourGender;
}

if(isset($_POST['securityQuestion'])){
	$securityQuestion = cleanData($_POST['securityQuestion']);
	$_SESSION['securityQuestion'] = $securityQuestion;
}

// "if username is guest then leave this page."
if($username == "guest"){
	$_SESSION['noticesFair'] = 'You are a guest. login not required.';
	header("location: {$rootUrl}index");
	exit;
}

// "Sanity-check the username, don't rely on our use of prepared statements"
// "alone to prevent attacks on the SQL server via malicious usernames."
if(preg_match("/^[^a-z]{1}|[^a-z0-9_.-]+/i", $username)){
	$_SESSION['noticesBad'] = 'Invalid username.';
	header("location: {$rootUrl}" . $back);
	exit;
}

if(isset($_POST['password'])){
	$password = cleanData($_POST['password']);
	$_SESSION['password'] = $password;
}

// "Don't let them spend more of our CPU time than we were willing to."
// Besides, bcrypt happens to use the first 72 characters only anyway.
if(isset($password)){
	if(strlen($password) > 72){
		$_SESSION['noticesBad'] = "The password is too long. Maximum 72 characters permitted.";
		header("location: {$rootUrl}" . $back);
		exit;
	}
}

// "do not go beyond the maximum username characters."
if(isset($username)){
	if(strlen($username) > $usernameCharacters){
		$_SESSION['noticesBad'] = "The username is too long. Maximum 14 characters permitted.";
		header("location: {$rootUrl}" . $back);
		exit;
	}
}

$hasher = new PasswordHash($hashCostLog2, $hashPortable);

if($op === 'new'){
	// "determine if username exists."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username = :username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$rowCount = $stmt->rowCount();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	if($rowCount){
		$_SESSION['noticesBad'] = "Username exists.";
		unset($_SESSION['registerUsername']);
		header("location: {$rootUrl}" . $back);
		exit;
	}

	// "determine if the email address at register.php already exists."
	try {
		$stmt = $dbh->prepare("SELECT emailAddress FROM {$root}users WHERE emailAddress=:emailAddress");
		$stmt->bindParam(':emailAddress', $emailAddress);
		$stmt->execute();
		$row = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	$emailAddress2 = $row['emailAddress'];

	if($emailAddress2 == $emailAddress){
		$_SESSION['noticesBad'] = "Email address exists.";
		unset($_SESSION['emailAddress']);
		header("location: {$rootUrl}" . $back);
		exit;
	}

	// "try to hash the password."
	$hash = $hasher->HashPassword($password);
	if(strlen($hash) < 20){
		unset($hasher);
		$_SESSION['noticesBad'] = "Failed to hash new password.";
		header("location: {$rootUrl}" . $back);
		exit;
	}

	// "try to create the new user."
	$permission = 2;
	$dateJoined = 0;
	$dateJoined = dateTimestamp($dateJoined);

	if($yourGender == 'm')
		$avatar = "user-male.png";
	else $avatar = "user-female.png";

	try {
		$stmt = $dbh->prepare("INSERT INTO {$root}users (yourGender, username, password, securityQuestion, emailAddress, dateJoined, permission, avatar) values (:yourGender, :username, :password, :securityQuestion, :emailAddress, :dateJoined, :permission, :avatar)");
		$stmt->bindParam(':yourGender', $yourGender);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $hash);
		$stmt->bindParam(':securityQuestion', $securityQuestion);
		$stmt->bindParam(':emailAddress', $emailAddress);
		$stmt->bindParam(':dateJoined', $dateJoined);
		$stmt->bindParam(':permission', $permission);
		$stmt->bindParam(':avatar', $avatar);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "member is created. display validate message."
	header("location: {$rootUrl}emailAddressValidate.php?username=$username&emailAddress=$emailAddress");
	exit;
}

// "get users password."
if($op === 'login'){
	$hash = '*'; // "In case the username is not found."

	try {
		$stmt = $dbh->prepare("select password from {$root}users where username=:username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$row4 = $stmt->fetch();
		$hash = $row4['password'];
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	unset($_SESSION['password']);
	unset($_SESSION['registerUsername']);
	unset($_SESSION['securityQuestion']);
	unset($_SESSION['emailAddress']);
	unset($_SESSION['yourGender']);

	// "if $password and $hash are the same then create $_sessions for"
	// "loginCheck.php."
	if($hasher->CheckPassword($password, $hash)){
		$_SESSION['noticesGood'] = 'Authentication succeeded.';
		$_SESSION['username'] = $username;
		$_SESSION['emailAddressValidate'] = 0;

		// "cookie remember me variable."
		if(isset($_POST['rememberMe']) && cleanData($_POST['rememberMe'] == 1)){
			$rememberMe = 1;
		} else $rememberMe = 0;

		$_SESSION['rememberMe'] = $rememberMe;

		if(isset($_POST['userHidden']) && cleanData($_POST['userHidden'] == 1)){
			$userHidden = 1;
		} else $userHidden = 0;

		$_SESSION['userHidden'] = $userHidden;

		header("location: {$rootUrl}loginCheck.php");
		exit;
	} else{
		$_SESSION['noticesBad'] = 'Authentication failed.';
		$_SESSION['username'] = $username;
		header("location: {$rootUrl}loginCheck.php");
		exit;
	}
	$stmt->close();
}
if($op === 'forgot'){
	unset($_SESSION['password']);
	unset($_SESSION['registerUsername']);
	unset($_SESSION['securityQuestion']);
	unset($_SESSION['emailAddress']);
	unset($_SESSION['yourGender']);

	// "if new password is longer than 72 characters then display the message."
	$newPassword = randomString();
	if(strlen($newPassword) > 72){

		$_SESSION['noticesBad'] = "The new password is too long.";
		header("location: {$rootUrl}" . $back);
		exit;
	}
	// "create hash from password."
	$hash = $hasher->HashPassword($newPassword);

	// "display a message if $hash is less than 20 characters."
	if(strlen($hash) < 20){
		unset($hasher);
		$_SESSION['noticesBad'] = "Failed to hash new password.";
		header("location: {$rootUrl}" . $back);
		exit;
	}

	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$row1 = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	$passwordValidateGet = cleanData($row1['passwordValidate']);
	$usernameGet = cleanData($row1['username']);

	// "determine if the new password can be saved."
	if($username == $usernameGet && $passwordValidate == $passwordValidateGet){
		// "save password as $hash"
		try {
			$stmt = $dbh->prepare("UPDATE {$root}users SET password=:password WHERE username=:username");
			$stmt->bindParam(':password', $hash);
			$stmt->bindParam(':username', $username);
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		$_SESSION['newPassword'] = $newPassword;

		if(isset($_SESSION['newPassword'])){
			$newPassword = $_SESSION['newPassword'];
			unset($_SESSION['newPassword']);

			// "get the password validate code."
			try {
				$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
				$stmt->bindParam(':username', $username);
				$stmt->execute();
				$row1 = $stmt->fetch();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}

			$passwordValidateGet = $row1['passwordValidate'];
			$emailAddress = $row1['emailAddress'];

			// "update the users password validate code in the database."
			// "if password validate code in database matches the code from the "
			// "email address then the user can login with that password."

			if($passwordValidate == $passwordValidateGet){
				try {
					$stmt = $dbh->prepare("UPDATE {$root}users SET passwordValidate='' WHERE username=:username");
					$stmt->bindParam(':username', $username);
					$stmt->execute();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}

				// "send the password validate code out to the registered user."
				$to = "$emailAddress";
				$subject = $siteName . ": New password.";
				$message = "Here is your new password...<br><br>" . $newPassword;
				$from = $WebsiteEmailAddress;
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "From:" . $from;

				if(mail($to,$subject,$message,$headers)){
					// "tell the user that the new password is sent to the"
					// "users email address."
					$_SESSION['noticesGood'] = "A new password successfully sent to your email address!";
					header("location: {$rootUrl}index");
					exit;
				} else{
					echo("Message delivery failed");
				}
			}
		}
	} else{
		// "tell the user that something is wrong with the password link"
		$_SESSION['noticesBad'] = "Possible modification of the forgot password link or old forgot password link.";
		header("location: {$rootUrl}index");
		exit;
	}
}

if($op === 'change'){
	// "get $hash from password field."
	$hash = '*'; // "In case the username is not found."

	try {
		$stmt = $dbh->prepare("SELECT password FROM {$root}users WHERE username=:username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$row4 = $stmt->fetch();
		$hash = $row4['password'];
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	unset($_SESSION['password']);
	unset($_SESSION['registerUsername']);
	unset($_SESSION['securityQuestion']);
	unset($_SESSION['emailAddress']);
	unset($_SESSION['yourGender']);

	// "determine if current $password and $hash match."
	if($hasher->CheckPassword($password, $hash)){
		// "get new password."
		$newPassword = cleanData($_POST['newPassword']);
		if(strlen($newPassword) > 72)
			fail('The new password is too long');
		// "make $hash from new password."
		$hash = $hasher->HashPassword($newPassword);
		if(strlen($hash) < 20)
			fail('Failed to hash new password');
		unset($hasher);

		// "save new $hash from $newPassword."
		try {
			$stmt = $dbh->prepare("UPDATE {$root}users SET password=:password WHERE username=:username");
			$stmt->bindParam(':password', $hash);
			$stmt->bindParam(':username', $username);
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		$_SESSION['noticesGood'] = "Password changed.";
		header("location: {$rootUrl}index");
		exit;
	} else{
		$_SESSION['noticesBad'] = "Your current login password is not correct.";
		header("location: {$rootUrl}index");
		exit;
	}
}

// TODO "might not need anything below this line. verify."
unset($hasher);

require 'includes/main/footer.php';

$stmt->close();
$db->close();

$_SESSION['noticesBad'] = $what;
header("location: {$rootUrl}" . $back);
exit;
?>