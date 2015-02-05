<?php
if(isset($_COOKIE['username']))
$username = $_COOKIE['username'];
else $username = "guest";

try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "Login / register preferences."
$rememberMe =                    $row1['rememberMe'];
$permission =                    $row1['permission'];
$yourGender =                    $row1['yourGender'];
$securityQuestion =              $row1['securityQuestion'];

// "users profile"
$postSignature =                 $row1['postSignature'];
$avatar =                        $row1['avatar'];
$avatarLocal =                   $row1['avatarLocal'];
$birthdayDay2 =                  $row1['birthdayDay'];
$birthdayMonth2 =                $row1['birthdayMonth'];
$birthdayMonthWord2 =            $row1['birthdayMonthWord'];
$birthdayYear2 =                 $row1['birthdayYear'];
$birthdayTimestamp =             $row1['birthdayTimestamp'];
$website =                       $row1['website'];
$country =                       $row1['country'];
$bootstrapButtonsDisplay =       $row1['bootstrapButtonsDisplay'];
$limit3 =                        $row1['paginationAvatarsOnPage'];
$limit4 =                        $row1['paginationSearchResultsOnPage'];
$adjacents =                     $row1['adjacents'];

try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}configuration"); 
	$stmt->execute(); 
	$row3 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
$siteName =                      $row3['siteName'];
$maintenanceMode =               $row3['maintenanceMode'];
$maintenanceModeText =           $row3['maintenanceModeText'];
$bannerAdvertisement =           $row3['bannerAdvertisement'];
$bannerAdvertisementCode =       $row3['bannerAdvertisementCode'];
$aboutBox =                      $row3['aboutBox'];
$aboutBoxText =                  $row3['aboutBoxText'];
$blockAdvertisement =            $row3['blockAdvertisement'];
$blockAdvertisementCode =        $row3['blockAdvertisementCode'];
$outgoingEmails =                $row3['outgoingEmails'];
$useAvatars =                    $row3['useAvatars'];
$enableTextAnnouncement =        $row3['enableTextAnnouncement'];
$textAnnouncement =              $row3['textAnnouncement'];
$imageWidth =                    $row3['imageWidth'];
$imageHeight =                   $row3['imageHeight'];
$maximumImageUploadSize =        $row3['maximumImageUploadSize'];
$maximumAttachmentUploadSize =   $row3['maximumAttachmentUploadSize'];
$usernameCharacters =            $row3['usernameCharacters'];
$WebsiteEmailAddress =           $row3['WebsiteEmailAddress'];
$titleCharacterLimit =           $row3['titleCharacterLimit'];
$postSignatureCharacterLimit =   $row3['postSignatureCharacterLimit'];
$numberOfLocalMaleAvatars =      $row3['numberOfLocalMaleAvatars'];
$numberOfLocalFemaleAvatars =    $row3['numberOfLocalFemaleAvatars'];

$_SESSION['titleCharacterLimit'] = $titleCharacterLimit;
$_SESSION['postSignatureCharacterLimit'] = $postSignatureCharacterLimit;
?>