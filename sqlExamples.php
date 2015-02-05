
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}preferences WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':value', $value);
	$stmt->execute();
	$row4 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("INSERT INTO {$root}users_most_online (usersTotal, usersTimestamp) VALUES (:countUsers, :usersTimestamp)");
	$stmt->bindParam(':name', $name);
	$stmt->bindParam(':value', $value);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {	
	$stmt = $dbh->prepare("DELETE FROM {$bulletin}movies WHERE filmID =  :filmID");
	$stmt->bindParam(':filmID', $user);  
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("UPDATE {$root}users SET password=:password WHERE username=:username");
	$stmt->bindParam(':password', $hash);
	$stmt->bindParam(':username', $username);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$root}users WHERE username <> 'guest' AND userHidden='1' AND activeOnlineTime>:time_check");
	$stmt->bindParam(':time', $time);
	$stmt->execute();
	$userHidden = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "remove folder"
rmdir('examples');

// "remove file"
unlink('../configuration/root.php');

$result=mysqli_query
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){		
fetchColumn();
{$bulletin}