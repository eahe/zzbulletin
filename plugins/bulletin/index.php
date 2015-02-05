<?php
require "../../includes/main/sessions.php";

$fileName = "configuration/bulletin.php";
if(!file_exists($fileName)){
	header("location: install/install1.php");
	exit;
}

require "configuration/bulletin.php";

// "permission denied for included file."
if(isset($_GET['status']) && $_GET['status'] == 403){
	$_SESSION['status'] = 1;
	$_SESSION['noticesBad'] = "403 - Direct access denied for included file.";
}
if(isset($_GET['f']))
$f2 = $_GET['f'];
else $f2=0; 

$_SESSION['noSessionsNoConfig'] = 1;
require "index2.php";


?>