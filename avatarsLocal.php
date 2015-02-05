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
	header("location: {$pluginUrl}settingAndPreferences.php");
	exit;
}

// "from buttonsBulletin.php variable $forumNew5."
if(isset($_SESSION['getF']))
$forumNew5 = $_SESSION['getF'];
// "from buttonsBulletin.php variable $c[$i]."
if(isset($_SESSION['getC']))
$c = $_SESSION['getC'];

// "pagination var."
if(isset($_GET['p'])){
	$p = cleanData($_GET['p']);
} else $p = 1;

$p2 = $p * $limit3;
require 'includes/buttonsBulletin.php';
if(!isset($p)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

if($yourGender == 'm'){
	$lastpage = ceil($numberOfLocalMaleAvatars/$limit3);
	$genderDirectory = 'male/';
} else{
	$lastpage = ceil($numberOfLocalFemaleAvatars/$limit3);
	$genderDirectory = 'female/';
}

if(isset($p) && $p > $lastpage){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/notices.php';
?>

<table class='table7' style='width: 100%; border: 0;'>
<tr>
<?php
$ii = 0; $tdLimit = 0; $tdLimitNumber = 0;
$ii = $limit3 * ($p - 1);

while($ii != $p2 ){
	$ii++; $tdLimit++;
	if($ii == 1){
		if(isset($_SESSION['noticesGood'])){
			echo "<td colspan='4'>";	
			noticesGood();
			echo "</td></tr>";
			unset($_SESSION['noticesGood']);
		} else{
			$_SESSION['noticesGood'] = "Click on an image to save it as your avatar.";
			echo "<td colspan='4'>";	
			noticesGood();
			echo "</td></tr><tr>";
			unset($_SESSION['noticesGood']);
		}
	}
					
	if(file_exists($avatarsLocalDirectory . $genderDirectory . $ii . '.jpg')){
		if($tdLimit == 5){
			echo "</tr><tr>";
		}

		if($tdLimitNumber == 1)
		echo "<td><br><br>";
		else{
			echo "<td>";
			$tdLimitNumber = 0;
		}

		if($maintenanceMode == "n" || $permission == 4 && $username == 'admin')
		echo '<center><a href="' . $pluginUrl . 'avatarsLocal2.php?i=' . $ii . '.jpg"><img width="' . $imageWidth . '" height="' . $imageHeight . '" src="' . $avatarsLocalDirectory . $genderDirectory . $ii . '.jpg" ></a></center></td>';
		else echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" src="' . $avatarsLocalDirectory . $genderDirectory . $ii . '.jpg" ></center></td>';

		if($tdLimit == 4){
			$tdLimit = 0; $tdLimitNumber = 1;
			echo "</tr><tr>";
		}
	}
}

?>

<?php 
require 'includes/avatarsLocalPagination.php';
require 'includes/main/footer.php';

?>