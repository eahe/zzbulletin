<?php
if(isset($_COOKIE['username']))
$username = $_COOKIE['username'];

// if username is not set, then set the username to guest so that"
// the rememberMe code below will not fail."
if(!isset($username))
$username = "guest";

// "get rememberMe variable from database user table when"
// "session named cookie is not set."
if(!isset($_SESSION['cookie'])){
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$row = $stmt->fetch();
		$rememberMe = $row['rememberMe'];
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "next time this page is loaded, set session cookie, so that"
	// "the code above will get the remember me variable."
	$_SESSION['cookie'] = 1;

	// "delete cookie if rememberMe = 0 (0 for not remember me)."
	// "the delete cookie happens when user closes navigator"
	// "and then comes back to site."
	if(isset($_COOKIE['username']) && $rememberMe == 0){
		header("location: {$pluginUrl}logout.php");
		exit;
	}
}
?>