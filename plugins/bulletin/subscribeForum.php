<?php
require '../../includes/main/header.php';

if($permission < $subscribeForum){
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

if(isset($_GET['c'])){
	$c = cleanData($_GET['c']);
}

if(!isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "this count is needed to determine if a database insert or update is needed."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_forum WHERE username=:username AND c=:c");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':c', $c);
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

$unsubscribeForum = randomString();

// "save active users to database."
if($username2 == ""){
	try {
		$stmt = $dbh->prepare("INSERT INTO {$bulletin}subscribe_forum (username, emailAddress, unsubscribe, c ) VALUES(:username, :emailAddress, :unsubscribeForum, :c)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':emailAddress', $emailAddress);
		$stmt->bindParam(':unsubscribeForum', $unsubscribeForum);
		$stmt->bindParam(':c', $c);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
} else{	
	$_SESSION['noticesFair'] = 'You are already subscribed to this forum.';
	$id = $_SESSION['id'];
	header("location: {$pluginUrl}threadViewAll/$id/1");
	exit;
}

$_SESSION['noticesGood'] = 'Subscribe to forum is successful.';
$id = $_SESSION['id'];
header("location: {$pluginUrl}threadViewAll/$id/1");
exit;

?>