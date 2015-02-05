<?php
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';

// "file not found"
if (isset($_GET['status']) && $_GET['status'] == 404){
	$_SESSION['noticesBad'] = "404 - File not found.";
	noticesBad();
	unset($_SESSION['noticesBad']);
}

echo "<table id='left'><tr><td>";
echo "The requested URL was not found on this server. If you entered the URL manually please check your spelling and try again. If you think this is a server error, please contact the ";
echo '<a href="mailto:' . $WebsiteEmailAddress . '?Subject=' . $siteName . ':%20"target="_top">webmaster</a>.';
echo "</td></tr></table>";

require 'includes/main/footer.php';



?>