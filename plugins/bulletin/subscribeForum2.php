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

if(isset($_GET['username']) && isset($_GET['unsubscribe'])){
	$username2 = cleanData($_GET['username']);
	$unsubscribeForum2 = cleanData($_GET['unsubscribe']);

	// "get data for unsubscribe forum by email."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_forum WHERE username=:username2 AND unsubscribe=:unsubscribeForum2");
		$stmt->bindParam(':username2', $username2);
		$stmt->bindParam(':unsubscribeForum2', $unsubscribeForum2);
		$stmt->execute();
		$row1 = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$username = cleanData($row1['username']);
	$unsubscribeForum = cleanData($row1['unsubscribe']);
	$c = cleanData($row1['c']);
	
	if($username != $username2 || $unsubscribeForum != $unsubscribeForum2){
		$_SESSION['noticesBad'] = "Possible modification of the email unsubscribe link or not subscribed to the forum.";
		header("location: {$pluginUrl}index");
		exit;
	} else{
		try {	
			$stmt = $dbh->prepare("DELETE FROM {$bulletin}subscribe_forum WHERE username=:username AND unsubscribe=:unsubscribeForum");
			$stmt->bindParam(':username', $username);  
			$stmt->bindParam(':unsubscribeForum', $unsubscribeForum);  
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$_SESSION['noticesGood'] = 'Unsubscribe from forum is successful.';
		
		if(isset($_SESSION['id'])){
			$id = $_SESSION['id'];
			header("location: {$pluginUrl}threadViewAll/$id/1");
			exit;
		} else {
			header("location: {$pluginUrl}index");
			exit;
		}
	}
}

if(isset($_GET['c']))
$c = cleanData($_GET['c']);

if(!isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "this is needed to determine if a database insert or update is needed."
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

$username2 = $row1['username'];

if($username2 == $username){
	try {	
		$stmt = $dbh->prepare("DELETE FROM {$bulletin}subscribe_forum WHERE username=:username AND c=:c");
		$stmt->bindParam(':username', $username); 
		$stmt->bindParam(':c', $c);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
} else{
	$_SESSION['noticesFair'] = 'You are already unsubscribed from this forum.';
	$id = $_SESSION['id'];
	header("location: {$pluginUrl}threadViewAll/$id/1");
	exit;
}

$_SESSION['noticesGood'] = 'Unsubscribe from forum is successful.';
$id = $_SESSION['id'];
header("location: {$pluginUrl}threadViewAll/$id/1");
exit;

?>