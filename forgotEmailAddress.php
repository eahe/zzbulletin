<?php
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';

if(!isset($_COOKIE['username'])){
	$_SESSION['cookieCheck2'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

// "get users email address."
try {
	$stmt = $dbh->prepare("SELECT emailAddress FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$emailAddress = cleanData($row['emailAddress']);

// "display at website, the users email address."
$_SESSION['noticesGood'] = "Your email address is " . $emailAddress . ".";
header("location: {$pluginUrl}index");

require 'includes/main/footer.php';

?>

