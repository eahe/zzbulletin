<?php
if(isset($_COOKIE['username']))
$username = $_COOKIE['username'];
else $username = "guest";

try {
	$stmt = $dbh->prepare("SELECT * FROM {$skeleton}example");
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "Example preferences."
$example =                       $row1['example'];

?>