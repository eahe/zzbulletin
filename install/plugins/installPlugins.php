<?php
require '../../includes/main/header.php';
require '../../includes/buttonsBulletin.php';

if($permission < 4 || $username != 'admin'){
	header("location: ../../index.php");
	exit;
}

$plugins = array();

if(isset($_POST['plugins']))
$plugins = $_POST['plugins'];

if(isset($_POST['homepage']))
$homepage = $_POST['homepage'];

foreach($plugins as $key=>$result) {
	echo "Installing the " . $result . " plugin.<br>";
	if(is_file("../../plugins/" . $result . "/sql/install/" . $result . ".php"))
    		require "../../plugins/" . $result . "/sql/install/" . $result . ".php";

	else echo $result . ".php database does not exist."; 
	echo "<br><br><br>";
	
	try {	
		$stmt = $dbh->prepare("DELETE FROM {$root}plugin_homepage");
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "insert plugin into database. if plugin is in the database then plugin will"
	// "be displayed at the homepage."
	if(isset($homepage) && $homepage == $result){
		try {
			$stmt = $dbh->prepare("INSERT INTO {$root}plugin_homepage (plugin) VALUES (:result)");
			$stmt->bindParam(':result', $result);
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
	}
	
	// "insert plugin into database so that other pages can determine if"
	// "plugin is installed."
	try {
		$stmt = $dbh->prepare("INSERT INTO {$root}plugin_install (plugin) VALUES (:result)");
		$stmt->bindParam(':result', $result);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
}

echo "Refresh page to add any plugins from the side panle.";
?>