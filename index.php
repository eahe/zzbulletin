<?php
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';

$path = 'plugins/';
$results = scandir($path);

foreach ($results as $result) {
    if ($result === '.' or $result === '..') continue;

    if (is_dir($path . '/' . $result)) {
      // "determine if a plugin should be at homepage."
		if(isset($result)){
			try {
				$stmt = $dbh->prepare("SELECT * FROM {$root}plugin_homepage WHERE plugin=:result");
				$stmt->bindParam(':result', $result);
				$stmt->execute();
				$row = $stmt->fetch();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}	
		
			if(isset($row['plugin'])){
				header("location: plugins/" . $result . "/index.php");
				exit;
			}
    }
}

if(isset($_SESSION['noticesFair']))
	require "includes/notices.php";

$_SESSION['noticesFair'] = "No plugins are setup to display at the homepage.";
require "includes/notices.php";

require "includes/main/footer.php";


?>