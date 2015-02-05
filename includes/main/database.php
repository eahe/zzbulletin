<?php
ini_set('display_errors', 0);

// "make a database connection and with the variables"
// "from database/root.php."
    try {
        $dbh = new PDO("mysql:host=$dbHost;charset=utf8", $dbUsername, $dbPassword);

        $dbh->exec("CREATE DATABASE `$dbName`"); 
            //    CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
            //    GRANT ALL ON `$db`.* TO '$user'@'localhost';
            //    FLUSH PRIVILEGES;
                
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch(PDOException $e){
	echo "Failed to create database: " . $e->getMessage() . "<br>";
	unlink('configuration/root.php');
	exit;
}

try {
    $dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
}

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
ini_set('display_errors', 1);

?>