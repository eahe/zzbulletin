<?php
require 'includes/main/header.php';

if(!isset($_COOKIE['username'])){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($useAvatars) && $useAvatars == 3){
	$_SESSION['noticesFair'] = "Local avatars is not permitted.";
	header("location: {$pluginUrl}settingsAndPreferences.php");
	exit;
}

if(isset($_GET['i']))
$avatar = cleanData($_GET['i']);
else $avatar = NULL;

if(!isset($avatar)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "update users preferences"
try {
	$stmt = $dbh->prepare("UPDATE {$root}users SET avatar=:avatar, avatarLocal='y' WHERE username=:username");
	$stmt->bindParam(':avatar', $avatar);
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$_SESSION['noticesGood'] = "Avatar saved to profile.";
header("location: {$rootUrl}settingsAndPreferences.php");
exit;

?>

