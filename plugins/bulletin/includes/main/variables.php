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

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}preferences WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row4 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "Your Preferences."
// "$limit, $limit2, ect, are pagination variables."
if($row4['username'] != ""){
$tenResentBulletinPosts =        $row4['tenResentBulletinPosts'];
$threadDisplay =                 $row4['threadDisplay'];
$limit =                         $row4['paginationPostsOnPage'];
$limit2 =                        $row4['paginationThreadsOnPage'];
$brTag1 =                        $row4['brTag1'];
$brTag2 =                        $row4['brTag2'];
$brTag3 =                        $row4['brTag3'];
} else {
	try {
		$stmt = $dbh->prepare("INSERT INTO {$bulletin}preferences (username) VALUES (:username)");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
}
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}permissions");
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "table premissions."
$forumNew =                      $row2['forumNew'];
$forumEdit =                     $row2['forumEdit'];
$forumDelete =                   $row2['forumDelete'];
$categoryNew =                   $row2['categoryNew'];
$categoryReorder =               $row2['categoryReorder'];
$categoryEdit =                  $row2['categoryEdit'];
$categoryDelete =                $row2['categoryDelete'];
$threadNew =                     $row2['threadNew'];
$threadDelete =                  $row2['threadDelete'];
$threadDeleteAll =               $row2['threadDeleteAll'];
$postReply =                     $row2['postReply'];
$postEdit =                      $row2['postEdit'];
$postEditAll =                   $row2['postEditAll'];
$postDelete =                    $row2['postDelete'];
$postDeleteAll =                 $row2['postDeleteAll'];
$attachFileToPost =              $row2['attachFileToPost'];
$attachFileDownload =            $row2['attachFileDownload'];
$attachFileDelete =              $row2['attachFileDelete'];
$pollNew =                       $row2['pollNew'];
$pollVote	 =               $row2['pollVote'];
$pinThread =                     $row2['pinThread'];
$lockThread =                    $row2['lockThread'];
$subscribeForum 	=        $row2['subscribeForum'];
$subscribeThread 	=        $row2['subscribeThread'];

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}configuration"); 
	$stmt->execute(); 
	$row3 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
$hotTopic =                      $row3['hotTopic'];
$deleteOnlyLastPost =            $row3['deleteOnlyLastPost'];

?>