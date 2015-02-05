<?php
if(!isset($dbh)){
	session_start();
	$_SESSION['noticesBad'] = "Direct access denied for \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

require "../../includes/main/header.php";

if(isset($_SESSION['id']))
$id = $_SESSION['id'];
require 'includes/buttonsBulletin.php';
require 'includes/notices.php';

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}cookies WHERE username='$username' AND c=:c AND t=:t");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row5 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$f2 = $row5['f'];
$c2 = $row5['c'];
$t2 = $row5['t'];

// "if username poll cookie is not in database."
if($c2 != $c || $t2 != $t)
setcookie("voted" . $c . $t, '', time()-3600);

// "get the views total."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=0");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

if($p == 1)
	$views = cleanData($row2['views']) + 1;
else $views = cleanData($row2['views']);

$quote = 1;
require 'includes/threadReadPagination.php';

// "everytime a user views a thread, the view variable is increased by 1."
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}threads SET views=:views WHERE c=:c AND t=:t AND r=0");
	$stmt->bindParam(':views', $views);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
require '../../includes/main/footer.php';

?>