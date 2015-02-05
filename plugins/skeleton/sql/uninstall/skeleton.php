<?php
if($permission < 4 || $username != 'admin'){
	header("location: ../../index.php");
	exit;
}

$time = time();
require '../../includes/main/pdoTablePrefix.php';

$sql="DROP TABLE IF EXISTS {$skeleton}example;";

try { 
	$dbh->exec($sql);

	echo "Dropped all {$skeleton} tables successfully.<br>";
	} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

?>