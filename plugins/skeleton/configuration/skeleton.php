<?php
// "none plugin database tables / files."
$fileName = "../../configuration/root.php";
if(!file_exists($fileName)){
	header("location: install/install1.php");
	exit;
} else require "../../configuration/root.php";

// The base url to the website. Ends with a '/' (slash) without quotes.
$pluginUrl = $rootUrl . "plugins/skeleton/";

// Specify server path to the index.php. Ends with / (slash) without quotes.
$pluginPath = $rootPath . "plugins/skeleton/";

// ####### DO NOT CHANGE ANYTHING BELOW THIS LINE. ####### 

// This is the folder where avatars and banners are kept. 
// Ends with / (slash) without quotes.
$imagesDirectory = "../../images/";
$avatarsLocalDirectory = "../../images/avatars/local/";
$avatarsUploadDirectory = "../../images/avatars/uploads/";
$archiveAttachmentDirectory = "../../archive/attachment/";

?>