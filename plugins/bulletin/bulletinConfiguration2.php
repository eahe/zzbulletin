<?php
require "../../includes/main/header.php";

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

if(isset($_POST['hotTopic']))
$hotTopic = cleanData($_POST['hotTopic']);
if(isset($_POST['deleteOnlyLastPost']))
$deleteOnlyLastPost = cleanData($_POST['deleteOnlyLastPost']);	

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
					copy($avatarsLocalDirectory . 'male/' . $avatar, $avatarsUploadDirectory . $avatar2);

					try {
						$stmt = $dbh->prepare("UPDATE {$root}users SET avatar=:avatar2, avatarLocal='n' WHERE username=:username2");
						$stmt->bindParam(':avatar2', $avatar2);
						$stmt->bindParam(':username', $username2);
						$stmt->execute();
					} catch (PDOException $e) {
						echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
						exit;
					}

				}
			} else{
				if(file_exists($avatarsLocalDirectory . 'female/' . $avatar)){
					$avatar2 = $prefix . "_f" . $avatar;
					copy($avatarsLocalDirectory . 'female/' . $avatar, $avatarsUploadDirectory . $avatar2);
					
					try {
						$stmt = $dbh->prepare("UPDATE {$root}users SET avatar=:avatar2, avatarLocal='n' WHERE username=:username2");
						$stmt->bindParam(':avatar2', $avatar2);
						$stmt->bindParam(':username', $username2);
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
	$stmt = $dbh->prepare("UPDATE {$bulletin}configuration SET 
hotTopic=:hotTopic, deleteOnlyLastPost=:deleteOnlyLastPost");
	$stmt->bindParam(':hotTopic', $hotTopic);
	$stmt->bindParam(':deleteOnlyLastPost', $deleteOnlyLastPost);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$_SESSION['noticesGood'] = "Preferences saved.";
header("location: {$pluginUrl}bulletinConfiguration.php");
exit;

?>