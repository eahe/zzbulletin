<?php
//require 'includes/buttonsBulletin.php';

if(basename($_SERVER['PHP_SELF']) == "index.php" && file_exists($pluginPath . "includes/pinFolderLockHot.php")){

	$time = time();
	$time_check = $time-600; // "10 Minutes"

	// "Get currentDate. Used to determine if someones birthday is today."
	if(isset($_COOKIE['timezone']))	
	$currentDate = gmdate("M d Y", time() + $_COOKIE['timezone']);
	else $currentDate = gmdate("M d Y", time());
	$currentDate2 = strtotime($currentDate);

	// "total bulletin threads"
	try {
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads Where r=1");
		$stmt->execute();
		$totalBulletinThreads = $stmt->fetchColumn();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "total bulletin posts."
	try {
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE r>0");
		$stmt->execute();
		$totalBulletinPosts = $stmt->fetchColumn();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}	

	// "get users with the same timestamp as currentDate."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE birthdayTimestamp=:currentDate2");
		$stmt->bindParam(':currentDate2', $currentDate2);
		$stmt->execute();
		$row7 = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	$birthdayOnline = '';
	$birthdayOnlineCount = 0;

	// "prepare the usernames to display at the birthday table."
	while($row7 = $stmt->fetch(PDO::FETCH_ASSOC)){
		// "determine if active member is hidden."
		if($birthdayOnlineCount == 0){
			$birthdayOnline .= $row7['username'];
		} else{
			$birthdayOnline .= ", ";
			$birthdayOnline .= $row7['username'];
		}
		$birthdayOnlineCount ++ ;
	}
	if($birthdayOnline == "")
	$birthdayOnline = "No members has a birthday today.";

	echo "<br>";
	echo "<table class='table4'><tr><th id='center'>Today's Birthday.</th></tr><tr><td id='left'>" . $birthdayOnline . "</td></tr></table>";

	// "cookies are needed so that if guest closed navigator then came back"
	// "then guest will be using the same cookie. cookies are needed so that"
	// "the guest count is correct."
	$sessionId = session_id();
	if(isset($_COOKIE['guest'])){
		$sessionId = $_COOKIE['guest'];
	} else{
		setcookie("guest", $sessionId, time()+31536000);
		if(isset($_COOKIE['guest'])){
			$sessionId = $_COOKIE['guest'];
		}
	}

	try {
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$root}users WHERE username<>'guest'");
		$stmt->execute();
		$membersCount = $stmt->fetchColumn();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	try {
		$stmt = $dbh->prepare("SELECT username FROM {$root}users WHERE username!='guest' OR username!='Guest' ORDER BY id DESC LIMIT 1");
		$stmt->execute();
		$row = $stmt->fetch();
		$newestMember = $row['username'];
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "if over 10 minute, delete session."
	try {
		$stmt = $dbh->prepare("DELETE FROM {$root}users_active_online WHERE activeOnlineTime<:time_check");
		$stmt->bindParam(':time_check', $time_check);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "if 'time' was not set, then there would be an incorrect"
	// "of hidden members."
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET activeOnlineTime=:time WHERE username=:username AND username<>'guest'");
		$stmt->bindParam(':time', $time);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "get the count of hidden members."
	try {
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$root}users WHERE username <> 'guest' AND userHidden='1' AND activeOnlineTime>:time_check");
		$stmt->bindParam(':time_check', $time_check);
		$stmt->execute();
		$userHidden = $stmt->fetchColumn();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "this count is needed to determine if a database insert or update is needed."
	try {
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$root}users_active_online WHERE session=:sessionId");
		$stmt->bindParam(':sessionId', $sessionId);
		$stmt->execute();
		$count = $stmt->fetchColumn();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "save active users to database."
	if($count=="0"){
		try {
			$stmt = $dbh->prepare("INSERT INTO {$root}users_active_online (username, session, activeOnlineTime, userHidden ) VALUES(:username, :sessionId, :time, :userHidden)");
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':sessionId', $sessionId);
			$stmt->bindParam(':time', $time);
			$stmt->bindParam(':userHidden', $userHidden);
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
	}

	else{
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users_active_online SET username=:username, activeOnlineTime=:time, userHidden=:userHidden WHERE session = :sessionId");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':time', $time);
		$stmt->bindParam(':userHidden', $userHidden);
		$stmt->bindParam(':sessionId', $sessionId);
		$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
	}

	// "if username is guest then update the table to userhidden is zero."
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users_active_online SET userHidden=0 WHERE username='guest'");
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "determine how many active users are online."
	try {
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$root}users_active_online");
		$stmt->execute();
		$countUsers = $stmt->fetchColumn();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "get count for most users online ever."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users_most_online");
		$stmt->execute();
		$row8 = $stmt->fetch();
		$usersTotal = $row8['usersTotal'];
		$usersTimestamp2 = $row8['usersTimestamp'];
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "determine if current active users online is greater or equal"
	// "to users most online."
	if($countUsers >= $usersTotal){
		$usersTimestamp = dateTimestamp($time);

		if($usersTotal == 0){
			try {
				$stmt = $dbh->prepare("INSERT INTO {$root}users_most_online (usersTotal, usersTimestamp) VALUES (:countUsers, :usersTimestamp)");
				$stmt->bindParam(':countUsers', $countUsers);
				$stmt->bindParam(':usersTimestamp', $usersTimestamp);
				$stmt->execute();
				$row = $stmt->fetch();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}

		} else{
			try {
				$stmt = $dbh->prepare("UPDATE {$root}users_most_online SET usersTotal=:countUsers, usersTimestamp=:usersTimestamp");
				$stmt->bindParam(':countUsers', $countUsers);
				$stmt->bindParam(':usersTimestamp', $usersTimestamp);
				$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}
		$usersTotal = $countUsers;
	}

	// "get count of active guests."
	try {
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$root}users_active_online WHERE username='guest'");
		$stmt->execute();
		$guests = $stmt->fetchColumn();
		$members = $countUsers - $guests;
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	// "get registered users that are not hidden."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users_active_online");
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$usernameOnline = '';
	$usernameOnlineCount = 0;

	// "prepare the usernames to display of the active members online."
	while($row6 = $stmt->fetch(PDO::FETCH_ASSOC)) {
		// "determine if active member is hidden."
		if($row6['userHidden'] == 0){
			if($row6['username'] != 'guest'){
				if($usernameOnlineCount == 0){
					$usernameOnline .= $row6['username'];
				} else{
					$usernameOnline .= ", ";
					$usernameOnline .= $row6['username'];
				}
				$usernameOnlineCount ++ ;
			}
		}
	}

	// "get count for most users, guests, members and hidden members ever online."
	try {
		$stmt = $dbh->prepare("SELECT * FROM {$root}users_most_online");
		$stmt->execute();
		$row9 = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	if(isset($_COOKIE['timezone']))
	$usersTimestamp2 = $row9['usersTimestamp'] + $_COOKIE['timezone'];
	else $usersTimestamp2 = $row9['usersTimestamp']; 
	$usersTimestamp = timestampDate($usersTimestamp2);

	// "get the data for guests, members and hidden members."
	$guestsTotal = $row9['guestsTotal'];
	
	if(isset($_COOKIE['timezone']))
	$guestsTimestamp = $row9['guestsTimestamp'] + $_COOKIE['timezone'];
	else $guestsTimestamp = $row9['guestsTimestamp']; 
	$guestsTimestamp = timestampDate($guestsTimestamp);

	$membersTotal = $row9['membersTotal'];
	$membersTimestamp = $row9['membersTimestamp'];
	$membersTimestamp = timestampDate($membersTimestamp);
	$hiddenTotal = $row9['hiddenTotal'];
	$hiddenTimestamp = $row9['hiddenTimestamp'];
	$hiddenTimestamp = timestampDate($hiddenTimestamp);
	
	// "update database table users_most_online table if guests, members and"
	// "hidden members are more than what is in the database table."
	if($guests >= $guestsTotal){
		$guestsTotal = $guests;
		if(isset($_COOKIE['timezone']))
		$guestsTimestamp = dateTimestamp($time) + $_COOKIE['timezone'];
		else $guestsTimestamp = dateTimestamp($time);
		
		try {
			$stmt = $dbh->prepare("UPDATE {$root}users_most_online SET guestsTotal=:guests, guestsTimestamp=:guestsTimestamp");
			$stmt->bindParam(':guests', $guests);
			$stmt->bindParam(':guestsTimestamp', $guestsTimestamp);
			$stmt->execute();
			$row9 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		$guestsTimestamp = timestampDate($guestsTimestamp);
	}

	if($members >= $membersTotal){
		$membersTotal = $members;
		if(isset($_COOKIE['timezone'])){
		$membersTimestamp = dateTimestamp($time) + $_COOKIE['timezone'];
		$membersTimestamp2 = dateTimestamp($time);
		} else {
			$membersTimestamp = dateTimestamp($time);
			$membersTimestamp2 = dateTimestamp($time);
		}
		
		try {
			$stmt = $dbh->prepare("UPDATE {$root}users_most_online SET membersTotal=:members, membersTimestamp=:membersTimestamp2");
			$stmt->bindParam(':members', $members);
			$stmt->bindParam(':membersTimestamp2', $membersTimestamp2);
			$stmt->execute();
			$row9 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		$membersTimestamp = timestampDate($membersTimestamp);
	}
		
	
	if($userHidden >= $hiddenTotal && isset($_COOKIE['timezone'])){
		$hiddenTotal = $userHidden;
		
		if(isset($_COOKIE['timezone'])){
			$hiddenTimestamp = dateTimestamp($time) + $_COOKIE['timezone'];
			$hiddenTimestamp2 = dateTimestamp($time);
		} else {
			$hiddenTimestamp = dateTimestamp($time);
			$hiddenTimestamp2 = dateTimestamp($time);
		}
		
		try {
			$stmt = $dbh->prepare("UPDATE {$root}users_most_online SET hiddenTotal=:userHidden, hiddenTimestamp=:hiddenTimestamp");
			$stmt->bindParam(':userHidden', $userHidden);
			$stmt->bindParam(':hiddenTimestamp', $hiddenTimestamp2);
			$stmt->execute();
			$row9 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}		
		$hiddenTimestamp = timestampDate($hiddenTimestamp);				
	}

	
	echo "<br>";
	echo "<table class='table6'><tr><th id='center'>Statistics / Who is online.</th></tr><tr><td id='center'>";

	// "display the Who is online and active."
	echo "Active users within 10 minutes. Total users online: $countUsers - Guests: $guests - Members: $members - hidden: $userHidden <br>";
	// "display the bulletin statistics."
	echo "Total bulletin threads: $totalBulletinThreads - Total bulletin posts: $totalBulletinPosts - Total members: $membersCount";
	echo "</td></tr>";
	// "display the newest member and also display the usernames of all
	// "that are active logged in members."

	echo "<tr><td id='left'>";
	echo "Most active users online was $usersTotal at $usersTimestamp. <br>";
	echo "Most active guests online was $guestsTotal at $guestsTimestamp. <br>";
	echo "Most active members online was $membersTotal at $membersTimestamp. <br>";
	echo "Most active hidden members online was $hiddenTotal at $hiddenTimestamp. ";
	echo "<br><br>";
	echo "Newest member: $newestMember - ";
	if($members - $userHidden == 0){
		echo "members online: No members.";
	} else echo "members online: $usernameOnline.";
	echo "</td></tr></table>";

}

echo "<br><br>";
echo "<div style='text-align: center;'><font size='2'>Powered by <a href='https://github.com/eahe/zzbulletin'>zzBulletin</a>.</font></div>";

?>
<script>
	var head = document.getElementsByTagName('head')[0],
	style = document.createElement('style');
	style.type = 'text/css';
	style.styleSheet.cssText = ':before,:after{content:none !important';
	head.appendChild(style);
	setTimeout(function(){
			head.removeChild(style);
		}, 0);
</script>

<!--[if lt IE 9]>
<script src="jquery/selectivizr.js"></script>
<![endif]-->