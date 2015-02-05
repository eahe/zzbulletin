<?php
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_thread WHERE c=:c AND t=:t AND username!=:username");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt2 = $dbh->prepare("SELECT * FROM {$bulletin}subscribe_forum WHERE c=:c AND username!=:username");
	$stmt2->bindParam(':username', $username);
	$stmt2->bindParam(':c', $c);
	$stmt2->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$noMoreEmail = 0;

// "get important variables from table threads."
while($row8 = $stmt->fetch(PDO::FETCH_ASSOC)){	
	// "send the subscribeThread post to the user."
	$noMoreEmail = 1;
	$emailAddress = $row8['emailAddress'];
	$username = $row8['username'];
	$unsubscribe = $row8['unsubscribe'];

	$to = "$emailAddress";
	$subject = $siteName . ": Subscribed message.";
	$message = "<b>" . $topicTitle . "</b>" . $topicBody . "<hr>Login to " . $siteName . " then click the link below if you would like to unsubscribe from this thread. <a href=\"" . $pluginUrl . "subscribeThread2.php" . "?username=" . $username . "&unsubscribe=" . $unsubscribe . "\">Unsubscribe here</a>.";
	$from = "$WebsiteEmailAddress";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From:" . $from;
	mail($to,$subject,$message,$headers);
}

while($row9 = $stmt2->fetch(PDO::FETCH_ASSOC)){	
	// "send the subscribeThread post to the user."
	if($noMoreEmail == 0){
		$emailAddress = $row9['emailAddress'];
		$username = $row9['username'];
		$unsubscribe = $row9['unsubscribe'];

		$to = "$emailAddress";
		$subject = $siteName . ": Subscribed message.";
		$message = "<b>" . $topicTitle . "</b>" . $topicBody . "<hr>Login to " . $siteName . " then click the link below if you would like to unsubscribe from this forum. <a href=\"" . $pluginUrl . "subscribeForum2.php" . "?username=" . $username . "&unsubscribe=" . $unsubscribe . "\">Unsubscribe here</a>.";
		$from = "$WebsiteEmailAddress";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From:" . $from;
		mail($to,$subject,$message,$headers);
	}
}
?>