<?php
require '../../includes/main/header.php';
require '../../includes/buttonsBulletin.php';

if($permission < 4 || $username != 'admin'){
	header("location: ../../index.php");
	exit;
}

$plugins = array();
$remove = array();

if(isset($_POST['plugins']))
$plugins = $_POST['plugins'];

if(isset($_POST['remove']))
$remove = $_POST['remove'];

foreach($plugins as $key=>$result) {
	echo "Uninstalling the " . $result . " plugin.<br>";

	if(is_file("../../plugins/" . $result . '/sql/uninstall/' . $result . ".php"))
    		require "../../plugins/" . $result . "/sql/uninstall/" . $result . ".php";
	else echo $result . ".php database does not exist."; 
	echo "<br><br><br>";
	
	// "remove plugin from homepage if any."
	try {	
		$stmt = $dbh->prepare("DELETE FROM {$root}plugin_homepage WHERE plugin=:result");
		$stmt->bindParam(':result', $result);  
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "remove plugin from plugin_install, so that the plugin will not"
	// "be displayed at the main menu."
	try {	
		$stmt = $dbh->prepare("DELETE FROM {$root}plugin_install WHERE plugin=:result");
		$stmt->bindParam(':result', $result);  
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
}


// "delete folder and all files within it."
function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
}

echo "Refresh page to remove any plugins from the side panle.";
?>