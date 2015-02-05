<?php

if(!isset($c) && !isset($t) && !isset($r)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "delete thread from forum."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}threads WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);  
	$stmt->bindParam(':t', $t);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t");
	$stmt->bindParam(':c', $c);  
	$stmt->bindParam(':t', $t);  
	$stmt->bindParam(':username', $username);   
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete poll questions that are associated with a thread."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_questions WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);  
	$stmt->bindParam(':t', $t);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete poll answers that are associated with a thread."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_answers WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);  
	$stmt->bindParam(':t', $t);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete poll votes that are associated with a thread."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}poll_votes WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);  
	$stmt->bindParam(':t', $t);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "delete poll cookie if any."
try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}cookies WHERE c=:c AND t=:t");
	$stmt->bindParam(':c', $c);  
	$stmt->bindParam(':t', $t);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "First get total number of rows in threads table."
try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE c=:c AND r=1");
	$stmt->bindParam(':c', $c);
	$stmt->execute();
	$total_pages = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$lastpage = ceil($total_pages/$limit);

if($p > $lastpage)
$p = $lastpage;

unset($_SESSION['noticesBad']);

?>