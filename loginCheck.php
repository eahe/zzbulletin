<?php
require 'includes/main/header.php';

if(!isset($_GET['o'])){
	
	?>
	<noscript>
		<?php echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=javascriptDisabled.html'>"; ?>
	</noscript>
	<script>
	<!-- "users time zone" -->
		var dtime = new Date()
		var offset= -dtime.getTimezoneOffset()*60; // "hours to seconds"
		location.href = '<?php echo $rootUrl . basename($_SERVER['PHP_SELF']); ?>?o='+offset;
	</script>
	<?php
	exit;
} else{
	$_COOKIE['timezone'] = $_GET['o'];
	$timezone = $_COOKIE['timezone'];
	if(isset($_SESSION['fullUrl']))
	$baseName = $_SESSION['fullUrl'];
}

if(isset($_SESSION['username']))
$username = $_SESSION['username'];

if($username == 'guest'){
	$_SESSION['noticesBad'] = 'A guest cannot access "loginCheck.php".';
	header("location: {$rootUrl}index");
	exit;
}

// "get $emailAddressValidate to determine if cookie should be set."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$username = $row['username'];
$sessionIdGet = $row['sessionId'];
$emailAddress = $row['emailAddress'];
$emailAddressValidate = $row['emailAddressValidate'];

if($username == ""){
	header("location: $baseName");
	exit;	
}

// "if outgoing emails are not enabled then no email validation is required."
if($outgoingEmails == "n"){
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET emailAddressValidate='0' WHERE username=:username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$emailAddressValidate = "0";
}

// "set the cookie if emailAddressValidate is '0'. if not validated then"
// "output a fair notice. see the notices function at functions.php."
if($emailAddressValidate == '0' && isset($_SESSION['noticesGood'] ) && $_SESSION['noticesGood'] == 'Authentication succeeded.'){

	// "create the cookie and session."
	setcookie("username", $username, time()+31536000);
	setcookie("timezone", $timezone, time()+31536000);

	session_regenerate_id();
	$sessionId = session_id();

	// "userMainSetup.php sets the $_SESSION['rememberMe']."
	$rememberMe = $_SESSION['rememberMe'];
	$time=time();
	$userHidden = $_SESSION['userHidden'];
	
	try {
		$stmt = $dbh->prepare("UPDATE {$root}users_active_online SET userHidden=:userHidden WHERE username=:username");
		$stmt->bindParam(':userHidden', $userHidden);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	try {
		$stmt = $dbh->prepare("UPDATE {$root}users SET rememberMe=:rememberMe, userHidden=:userHidden WHERE username=:username");
		$stmt->bindParam(':rememberMe', $rememberMe);
		$stmt->bindParam(':userHidden', $userHidden);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "saves current session to the database."
	if($sessionIdGet == "" || $sessionIdGet == 0){
		try {
			$stmt = $dbh->prepare("UPDATE {$root}users SET sessionId=:sessionId WHERE username=:username");
			$stmt->bindParam(':sessionId', $sessionId);
			$stmt->bindParam(':username', $username);
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		if(isset($baseName)){
			header("location: {$baseName}");
			exit;	
		} else {
			header("location: {$rootUrl}index");
			exit;
		}

	} else{
		if(isset($baseName)){
			header("location: {$baseName}");
			exit;	
		} else {
			header("location: {$rootUrl}index");
			exit;
		}	
	}
} else{
	// "if login but the email address is not verified then display the"
	// "email address validate message"
	if($emailAddress != "" && isset($_SESSION['noticesBad']) && $_SESSION['noticesBad'] != 'Authentication failed.' || $emailAddress != "" && !isset($_SESSION['noticesBad'])){
		$_SESSION['noticesFair'] = "Your email address is not validated. You will not be able to login until you click the validate link in that email. If you waited awhile for the email and you still did not recieve it then <a href=\"" . $rootUrl . "emailAddressValidate.php?username=$username&emailAddress=$emailAddress\">try again</a>.";
		unset($_SESSION['noticesGood']);
		header("location: {$rootUrl}index");
		exit;
	} else{
		header("location: {$baseName}");
		exit;	
	}
}

header("location: {$rootUrl}index");
exit;

?>