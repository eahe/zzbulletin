<?php
require "includes/main/header.php";

if(!isset($_COOKIE['username'])){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if($permission < 4){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['siteName']))
$siteName = cleanData($_POST['siteName']);
if(isset($_POST['maintenanceMode']))
$maintenanceMode = cleanData($_POST['maintenanceMode']);
if(isset($_POST['maintenanceModeText']))
$maintenanceModeText = cleanData($_POST['maintenanceModeText']);
if(isset($_POST['bannerAdvertisement']))
$bannerAdvertisement = cleanData($_POST['bannerAdvertisement']);
if(isset($_POST['bannerAdvertisementCode']))
$bannerAdvertisementCode = cleanData($_POST['bannerAdvertisementCode']);
if(isset($_POST['aboutBox']))
$aboutBox = cleanData($_POST['aboutBox']);
if(isset($_POST['aboutBoxText']))
$aboutBoxText = cleanData($_POST['aboutBoxText']);
if(isset($_POST['blockAdvertisement']))
$blockAdvertisement = cleanData($_POST['blockAdvertisement']);
if(isset($_POST['blockAdvertisementCode']))
$blockAdvertisementCode = cleanData($_POST['blockAdvertisementCode']);
if(isset($_POST['outgoingEmails']))
$outgoingEmails = cleanData($_POST['outgoingEmails']);
if(isset($_POST['useAvatars']))
$useAvatars = cleanData($_POST['useAvatars']);
if(isset($_POST['enableTextAnnouncement']))
$enableTextAnnouncement = cleanData($_POST['enableTextAnnouncement']);
if(isset($_POST['textAnnouncement']))
$textAnnouncement = cleanData($_POST['textAnnouncement']);
if(isset($_POST['imageWidth']))
$imageWidth = cleanData($_POST['imageWidth']);
if(isset($_POST['imageHeight']))
$imageHeight = cleanData($_POST['imageHeight']);
if(isset($_POST['maximumImageUploadSize']))
$maximumImageUploadSize = cleanData($_POST['maximumImageUploadSize']);
if(isset($_POST['maximumAttachmentUploadSize']))
$maximumAttachmentUploadSize = cleanData($_POST['maximumAttachmentUploadSize']);
if(isset($_POST['usernameCharacters']))
$usernameCharacters = cleanData($_POST['usernameCharacters']);
if(isset($_POST['WebsiteEmailAddress']))
$WebsiteEmailAddress = cleanData($_POST['WebsiteEmailAddress']);
if(isset($_POST['titleCharacterLimit']))
$titleCharacterLimit = cleanData($_POST['titleCharacterLimit']);
if(isset($_POST['postSignatureCharacterLimit']))
$postSignatureCharacterLimit = cleanData($_POST['postSignatureCharacterLimit']);
if(isset($_POST['numberOfLocalMaleAvatars']))
$numberOfLocalMaleAvatars = cleanData($_POST['numberOfLocalMaleAvatars']);
if(isset($_POST['numberOfLocalFemaleAvatars']))
$numberOfLocalFemaleAvatars = cleanData($_POST['numberOfLocalFemaleAvatars']);

// "If using only the uploads directory then Move all local"
// "avatars to upload directory so that they will display at forum."
if($useAvatars == 3){
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users");
		$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}


while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$username2 = $row['username'];
		$avatar = $row['avatar'];
		$yourGender = $row['yourGender'];
		$avatarLocal = $row['avatarLocal'];

		$prefix =  mt_rand(1000000000, 9999999999);

		if($avatarLocal == 'y'){
			if($yourGender == 'm'){
				if(file_exists($avatarsLocalDirectory . 'male/' . $avatar)){
					$avatar2 = $prefix . "_m" . $avatar;

					if (!is_writable($avatarsLocalDirectory . 'male/' . $avatar)) {
						$_SESSION['noticesBad'] = 'Folder: ' . $avatarsLocalDirectory . 'male/ is not writable.';
						noticesBad();
						unset($_SESSION['noticesBad']);
						exit;
					}

					copy($avatarsLocalDirectory . 'male/' . $avatar, $avatarsUploadDirectory . $avatar2);

					try {
						$stmt = $dbh->prepare("UPDATE {$root}users SET avatar=:avatar2, avatarLocal='n' WHERE username=:username2");
						$stmt->bindParam(':avatar2', $avatar2);
						$stmt->bindParam(':username2', $username2);
						$stmt->execute();
					} catch (PDOException $e) {
						echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
						exit;
					}

				}
			} else{
				if(file_exists($avatarsLocalDirectory . 'female/' . $avatar)){
					$avatar2 = $prefix . "_f" . $avatar;

					if (!is_writable($avatarsLocalDirectory . 'female/' . $avatar)) {
						$_SESSION['noticesBad'] = 'Folder: ' . $avatarsLocalDirectory . 'female/ is not writable.';
						noticesBad();
						unset($_SESSION['noticesBad']);
						exit;
					}

					copy($avatarsLocalDirectory . 'female/' . $avatar, $avatarsUploadDirectory . $avatar2);
					
					try {
						$stmt = $dbh->prepare("UPDATE {$root}users SET avatar=:avatar2, avatarLocal='n' WHERE username=:username2");
						$stmt->bindParam(':avatar2', $avatar2);
						$stmt->bindParam(':username2', $username2);
						$stmt->execute();
					} catch (PDOException $e) {
						echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
						exit;
					}
				}
			}
		}
	}
}

// "update users preferences"
try {
	$stmt = $dbh->prepare("UPDATE {$root}configuration SET useAvatars=:useAvatars, siteName=:siteName, maintenanceMode=:maintenanceMode, maintenanceModeText=:maintenanceModeText, bannerAdvertisement=:bannerAdvertisement, bannerAdvertisementCode=:bannerAdvertisementCode, aboutBox=:aboutBox, aboutBoxText=:aboutBoxText,  blockAdvertisement=:blockAdvertisement, blockAdvertisementCode=:blockAdvertisementCode, enableTextAnnouncement=:enableTextAnnouncement, textAnnouncement=:textAnnouncement, outgoingEmails=:outgoingEmails, imageWidth=:imageWidth, imageHeight=:imageHeight, maximumImageUploadSize=:maximumImageUploadSize, maximumAttachmentUploadSize=:maximumAttachmentUploadSize, usernameCharacters=:usernameCharacters, numberOfLocalFemaleAvatars=:numberOfLocalFemaleAvatars, numberOfLocalMaleAvatars=:numberOfLocalMaleAvatars, postSignatureCharacterLimit=:postSignatureCharacterLimit, titleCharacterLimit=:titleCharacterLimit, WebsiteEmailAddress=:WebsiteEmailAddress");
	$stmt->bindParam(':siteName', $siteName);
	$stmt->bindParam(':maintenanceMode', $maintenanceMode);
	$stmt->bindParam(':maintenanceModeText', $maintenanceModeText);
	$stmt->bindParam(':bannerAdvertisement', $bannerAdvertisement);
	$stmt->bindParam(':bannerAdvertisementCode', $bannerAdvertisementCode);
	$stmt->bindParam(':aboutBox', $aboutBox);
	$stmt->bindParam(':aboutBoxText', $aboutBoxText);
	$stmt->bindParam(':blockAdvertisement', $blockAdvertisement);
	$stmt->bindParam(':blockAdvertisementCode', $blockAdvertisementCode);
	$stmt->bindParam(':enableTextAnnouncement', $enableTextAnnouncement);
	$stmt->bindParam(':textAnnouncement', $textAnnouncement);
	$stmt->bindParam(':outgoingEmails', $outgoingEmails);
	$stmt->bindParam(':imageWidth', $imageWidth);
	$stmt->bindParam(':imageHeight', $imageHeight);
	$stmt->bindParam(':maximumImageUploadSize', $maximumImageUploadSize);
	$stmt->bindParam(':maximumAttachmentUploadSize', $maximumAttachmentUploadSize);
	$stmt->bindParam(':usernameCharacters', $usernameCharacters);
	$stmt->bindParam(':useAvatars', $useAvatars);
	$stmt->bindParam(':numberOfLocalFemaleAvatars', $numberOfLocalFemaleAvatars);
	$stmt->bindParam(':numberOfLocalMaleAvatars', $numberOfLocalMaleAvatars);
	$stmt->bindParam(':postSignatureCharacterLimit', $postSignatureCharacterLimit);
	$stmt->bindParam(':titleCharacterLimit', $titleCharacterLimit);
	$stmt->bindParam(':WebsiteEmailAddress', $WebsiteEmailAddress);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$_SESSION['noticesGood'] = "Preferences saved.";
header("location: {$rootUrl}websiteConfiguration.php");
exit;

?>