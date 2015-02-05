<?php
require '../../includes/main/header.php';

if($permission < $subscribeThread){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if($outgoingEmails == "n"){
	$_SESSION['noticesBad'] = "Outgoing emails are not enabled.";
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['c']))
$c = cleanData($_GET['c']);
if(isset($_GET['t']))
$t = cleanData($_GET['t']);

if(!isset($c) || !isset($t)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$c2 = cleanData($row['c']);
$t2 = cleanData($row['t']);

if($c2 == 0 || $t2 != $t){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "this is needed to determine if a database insert or update is needed."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_thread WHERE username=:username AND c=:c AND t=:t");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$username2 = cleanData($row1['username']);

// "get the email address of user."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$emailAddress = cleanData($row2['emailAddress']);

$unsubscribeThread = randomString();

// "save active users to database."
if($username2 == ""){
	try {
		$stmt = $dbh->prepare("INSERT INTO {$bulletin}subscribe_thread (username, emailAddress, unsubscribe, c, t ) VALUES(:username, :emailAddress, :unsubscribeThread, :c, :t)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':emailAddress', $emailAddress);
		$stmt->bindParam(':unsubscribeThread', $unsubscribeThread);
		$stmt->bindParam(':c', $c);
		$stmt->bindParam(':t', $t);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
} else{	
	$_SESSION['noticesFair'] = 'You are already subscribed to this thread.';
	$id = $_SESSION['id'];
	header("location: {$pluginUrl}threadViewAll/$id/1");
	exit;
}

$_SESSION['noticesGood'] = 'Subscribe to the thread is successful.';
$id = $_SESSION['id'];
header("location: {$pluginUrl}threadViewAll/$id/1");
exit;

?>