<?php
require "../../includes/main/header.php";
require 'includes/buttonsSkeleton.php';
require "../../includes/notices.php";

$_SESSION['noticesGood'] = "Skeleton plugin index page displayed without any errors.";

// "noticesGood, noticesFair or noticesBad can be used here."
if(isset($_SESSION['noticesGood'])){
	noticesGood();
	unset($_SESSION['noticesGood']);
}

echo "This is the start page of the skeleton plugin.";
echo "<br><br>";
echo 'the skeleton_data[1] from "/plugins/skeleton/includes/language/en/php" file outputs as... ' .  $skeleton_data[1];

require "../../includes/main/footer.php";
?>