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

if(isset($_GET['username']) && isset($_GET['unsubscribe'])){
	$username2 = cleanData($_GET['username']);
	$unsubscribeThread2 = cleanData($_GET['unsubscribe']);

	if ($username2 == $username){
		// "get data for unsubscribe forum by email."
		try {
			$stmt = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_thread WHERE username=:username2 AND unsubscribe=:unsubscribeThread2");
			$stmt->bindParam(':username2', $username2);
			$stmt->bindParam(':unsubscribeThread2', $unsubscribeThread2);
			$stmt->execute();
			$row1 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		$username = cleanData($row1['username']);
		$unsubscribeThread = cleanData($row1['unsubscribe']);
		$c = cleanData($row1['c']);
		$t = cleanData($row1['t']);

		if($username != $username2 || $unsubscribeThread != $unsubscribeThread2){
			$_SESSION['noticesBad'] = "Possible modification of the email unsubscribe link or not subscribed to the thread.";
			header("location: {$pluginUrl}index");
			exit;
		} else{
			try {
				$stmt = $dbh->prepare("DELETE FROM {$bulletin}subscribe_thread WHERE username=:username AND unsubscribe=:unsubscribeThread");
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':unsubscribeThread', $unsubscribeThread);
				$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}

			$_SESSION['noticesGood'] = 'Unsubscribe from thread is successful.';

			if(isset($_SESSION['id'])){
				$id = $_SESSION['id'];
				header("location: {$pluginUrl}threadViewAll/$id/1");
				exit;
			} else {
				header("location: {$pluginUrl}index");
				exit;
			}
		}
	} else {
		$_SESSION['noticesFair'] = "Your username does not match that of the username in the unsubscribe link.";
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
if(isset($_GET['t']))
	$t = cleanData($_GET['t']);

if(!isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "this count is needed to determine if a database insert or update is needed."
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

$username2 = $row1['username'];

if(isset($username2)){
	try {
		$stmt = $dbh->prepare("DELETE FROM {$bulletin}subscribe_thread WHERE username=:username AND c=:c AND t=:t");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':c', $c);
		$stmt->bindParam(':t', $t);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
} else{	
	$_SESSION['noticesFair'] = 'You are already unsubscribed to this thread.';
	$id = $_SESSION['id'];
	header("location: {$pluginUrl}threadViewAll/$id/1");
	exit;
}


$_SESSION['noticesGood'] = 'Unsubscribe from thread is successful.';
$id = $_SESSION['id'];
header("location: {$pluginUrl}threadViewAll/$id/1");
exit;

?>