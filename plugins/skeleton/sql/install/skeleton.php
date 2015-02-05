<?php
if($permission < 4 || $username != 'admin'){
	header("location: ../../index.php");
	exit;
}

$time = time();
require '../../includes/main/pdoTablePrefix.php';

// "Example table."
$sql="CREATE TABLE {$skeleton}example(
`id`                            INT(10) NOT NULL AUTO_INCREMENT,
`example`                       VARCHAR(1) NOT NULL default 'y',
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);
	
	echo "Table example created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$example = "n";

try {
	$stmt = $dbh->prepare("INSERT INTO {$skeleton}example (example) VALUES (:example)");
	$stmt->bindParam(':example', $example);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

?>