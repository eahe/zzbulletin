<?php
if (!isset($_SESSION['threadedMode']))
require '../../includes/main/header.php';

if(isset($_GET['f'])){
	$f = cleanData($_GET['f']);
	if($f == NULL)
	unset($f);
}
if(isset($_GET['c'])){
	$c = cleanData($_GET['c']);
	if($c == NULL)
	unset($c);
}
if(isset($_GET['t'])){
	$t = cleanData($_GET['t']);
	if($t == NULL)
	unset($t);
}
if(isset($_GET['r'])){
	$r = cleanData($_GET['r']);
	if($r == NULL)
	unset($r);
}
if(isset($_GET['q'])){
	$q = cleanData($_GET['q']);
	if($q == NULL)
	unset($q);
}

if($username == "guest" && !isset($_SESSION['threadedMode'])){
	$_SESSION['noticesBad'] = "Guest cannot mark threads as read.";
	header("location: {$pluginUrl}index");
	exit;
}

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND r=1");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row3 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$username3 = cleanData($row3['username']);

if(!isset($r) && !isset($q)){
	// "mark all threads."
	try {
		$stmt = $dbh->prepare("UPDATE {$bulletin}mark_as_read SET mark=1 WHERE username=:username AND c=:c");
		$stmt->bindParam(':c', $c);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}	
} 	elseif(!isset($r) && isset($q)){
	// "mark one thread."
	try {
		$stmt = $dbh->prepare("UPDATE {$bulletin}mark_as_read SET mark=1 WHERE username=:username AND c=:c AND t=:t");
		$stmt->bindParam(':c', $c);
		$stmt->bindParam(':t', $t);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}	
} else{
	try {
		$stmt = $dbh->prepare("UPDATE {$bulletin}mark_as_read SET mark=1 WHERE username=:username AND c=:c AND t=:t AND r=:r");
		$stmt->bindParam(':c', $c);
		$stmt->bindParam(':t', $t);
		$stmt->bindParam(':r', $r);		
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}	
}

if(!isset($_SESSION['threadedMode'])){
	
	if($username3 == ""){
		$_SESSION['noticesFair'] = "Direct access to that 'mark as' page is not permitted now."; 
		unset($_SESSION['noticesGood']);
	} elseif(!isset($q)){
		$_SESSION['noticesGood'] = 'Mark Forum as read successfully.';
	}	else 
		$_SESSION['noticesGood'] = 'Mark thread as read successfully.';
		$id = $_SESSION['id'];
		header("location: {$pluginUrl}threadViewAll/$id/1");
		exit;
		
}

?>