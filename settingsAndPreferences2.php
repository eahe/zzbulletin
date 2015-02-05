<?php
require 'includes/main/header.php';
require 'includes/imageManipulator.php';

if(!isset($_COOKIE['username'])){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['adjacents']))
	$adjacents = cleanData($_POST['adjacents']);
if(isset($_POST['birthdayDay']))
	$birthdayDay = cleanData($_POST['birthdayDay']);
if(isset($_POST['birthdayMonthWord']))
	$birthdayMonthWord = cleanData($_POST['birthdayMonthWord']);
if(isset($_POST['birthdayYear']))
	$birthdayYear = cleanData($_POST['birthdayYear']);
if(isset($_POST['website']))
	$website = cleanData($_POST['website']);
if(isset($_POST['country']))
	$country = cleanData($_POST['country']);
if(isset($_POST['postSignature']))
	$postSignature = cleanData($_POST['postSignature']);
if(isset($_POST['bootstrapButtonsDisplay']))
	$bootstrapButtonsDisplay = cleanData($_POST['bootstrapButtonsDisplay']);
if(isset($_POST['limit3']))
	$limit3 = cleanData($_POST['limit3']);
if(isset($_POST['limit4']))
	$limit4 = cleanData($_POST['limit4']);

$saveBirthday = NULL;

// "make birthdayMonth2 in array and for the birthday."
$birthdayMonth2 = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");


if($birthdayDay == 0 && $birthdayMonthWord == '' && $birthdayYear == 0){
	// "prepare to save the users birthday if all three fields are empty."
	$saveBirthday = 1;
} elseif($birthdayDay == 0 || $birthdayMonthWord == '' || $birthdayYear == 0){
	$_SESSION['noticesFair'] = "The birthday values of day, month and year need to be either all empty or all none empty to save.";
} else{
	// "get the month in numbers."
	for($ii = 0; $ii <= 11; $ii++){
		if($birthdayMonth2[$ii] == $birthdayMonthWord){

			$birthdayMonth = $ii + 1;
		}
	}
	$saveBirthday = 1;
}

// "save the users birthday."
if($saveBirthday == 1){
	if(isset($birthdayMonth))
		$birthdayTimestamp = mktime(0, 0, 0, $birthdayMonth, $birthdayDay);
	else $birthdayMonth = NULL;

	try {
		$stmt3 = $dbh->prepare("UPDATE {$root}users SET birthdayDay=:birthdayDay, birthdayMonth=:birthdayMonth, birthdayTimestamp=:birthdayTimestamp, birthdayMonthWord=:birthdayMonthWord, birthdayYear=:birthdayYear WHERE username=:username");
		$stmt3->bindParam(':birthdayDay', $birthdayDay);
		$stmt3->bindParam(':birthdayMonth', $birthdayMonth);
		$stmt3->bindParam(':birthdayTimestamp', $birthdayTimestamp);
		$stmt3->bindParam(':birthdayMonthWord', $birthdayMonthWord);
		$stmt3->bindParam(':birthdayYear', $birthdayYear);
		$stmt3->bindParam(':username', $username);
		$stmt3->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
}

try {
	$stmt = $dbh->prepare("UPDATE {$root}users SET website=:website, country=:country, postSignature=:postSignature, bootstrapButtonsDisplay=:bootstrapButtonsDisplay, paginationAvatarsOnPage=:paginationAvatarsOnPage, adjacents=:adjacents, paginationSearchResultsOnPage=:paginationSearchResultsOnPage WHERE username=:username");
	$stmt->bindParam(':website', $website);
	$stmt->bindParam(':country', $country);
	$stmt->bindParam(':postSignature', $postSignature);
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':bootstrapButtonsDisplay', $bootstrapButtonsDisplay);
	$stmt->bindParam(':adjacents', $adjacents);
	$stmt->bindParam(':paginationAvatarsOnPage', $limit3);
	$stmt->bindParam(':paginationSearchResultsOnPage', $limit4);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "limit the size of the image. if image is greater then the size permitted"
// "then display the error message."
if(isset($_FILES["file"]["size"]) && $_FILES["file"]["size"] > $maximumImageUploadSize * 1024 ){
	$_SESSION['noticesBad'] = "Image is too large in Kilobytes or has exceeded php.ini maximum size.";
	header("location: {$pluginUrl}settingsAndPreferences.php");
	exit;
}

// "the time in numbers are displayed in front of the image name."
$newNamePrefix = time() . '_';

// "allowed image extensions."
$allowedExts = array("gif", "jpeg", "jpg", "png");

if(isset($_FILES["file"]["name"])){
	$temp = explode(".", $_FILES["file"]["name"]);

	$extension = end($temp);
	if((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/jpg")
			|| ($_FILES["file"]["type"] == "image/pjpeg")
			|| ($_FILES["file"]["type"] == "image/x-png")
			|| ($_FILES["file"]["type"] == "image/png"))
			&& in_array($extension, $allowedExts)){

		// "if error of some sort then display the error message."
		if($_FILES["file"]["error"] > 0){
			$_SESSION['noticesBad'] = "Return Code: " . $_FILES["file"]["error"] . ".";
			header("location: {$pluginUrl}settingsAndPreferences.php");
			exit;
		} else{
			// "checks for duplicate image names."
			if(file_exists($avatarsUploadDirectory . $newNamePrefix . $_FILES["file"]["name"])){
				$_SESSION['noticesBad'] = $newNamePrefix . $_FILES["file"]["name"] . " already exists.";
				header("location: {$pluginUrl}settingsAndPreferences.php");
				exit;
			} else{
				// "move avatar to uploads directory."
				move_uploaded_file($_FILES["file"]["tmp_name"],
				$avatarsUploadDirectory . $newNamePrefix . $_FILES["file"]["name"]);
				$avatar = $newNamePrefix . $_FILES["file"]["name"];
			}
		}
	}	elseif($_FILES["file"]["name"] != ""){
		$_SESSION['noticesBad'] = "Invalid file. " . $_FILES["file"]["type"] . ".";
		header("location: {$pluginUrl}settingsAndPreferences.php");
		exit;
	}
}

// "save the avatar."
if(isset($avatar) && strlen($avatar) > 10){
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET avatar=:avatar, avatarLocal='n' WHERE username=:username");
		$stmt->bindParam(':avatar', $avatar);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
}

$_SESSION['noticesGood'] = "Profile saved.";
header("location: {$pluginUrl}settingsAndPreferences.php");
exit;
?>
