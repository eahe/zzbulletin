<?php
require 'includes/main/header.php';

try {
	$stmt = $dbh->prepare("UPDATE {$root}users_active_online SET userHidden=0 WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("UPDATE {$root}users SET userHidden=0 WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$_SESSION['noticesGood'] = "You are now logged out.";
noticesGood();

setcookie("username", '', time()-3600);
setcookie("timezone", '', time()-3600);
session_unset(); // "clear the $_SESSION variable."
session_destroy(); // "destroy the session."

?>

<script>
setTimeout(function(){
  window.location = "<?php echo $rootUrl . "index"; ?>";
}, 2000);
</script>