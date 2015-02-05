<?php
// "get thread data."
$topicTitle = $row['topicTitle'];
$topicBody = $row['topicBody'];
$views = cleanData($row['views']);
$c = cleanData($row['c']);
$t = cleanData($row['t']);
$s = cleanData($row['s']);
$username2 = cleanData($row['username']);

// "at the category, get the total replies from a thread."
try {
	$stmt8 = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE c=:c AND t=:t");
	$stmt8->bindParam(':c', $c);
	$stmt8->bindParam(':t', $t);
	$stmt8->execute();
	$replies = $stmt8->fetchColumn() - 2;
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "hot topics."
try {
	$stmt4 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t ORDER BY r DESC LIMIT 1");
	$stmt4->bindParam(':c', $c);
	$stmt4->bindParam(':t', $t);
	$stmt4->execute();
	$row4 = $stmt4->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$hot = $row4['r'] - 1;

// "locked topics."
try {
	$stmt5 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t ORDER BY l DESC LIMIT 1");
	$stmt5->bindParam(':c', $c);
	$stmt5->bindParam(':t', $t);
	$stmt5->execute();
	$row5 = $stmt5->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$lock = $row5['l'];

try {
	$stmt4 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r!=0 ORDER BY timestamp DESC LIMIT 1");
	$stmt4->bindParam(':c', $c);
	$stmt4->bindParam(':t', $t);
	$stmt4->execute();
	$row4 = $stmt4->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$timestamp1 = cleanData($row4['timestamp']);

if($username != "guest"){
	if(isset($_COOKIE['timezone']))
		$timestamp2 = cleanData($row4['timestamp']) + $_COOKIE['timezone'];
	else $timestamp2 = cleanData($row4['timestamp']);
} else $timestamp2 = cleanData($row4['timestamp']);

$username2 = cleanData($row4['username']);
$timestamp = timestampDate($timestamp2);

if(isset($_SESSION['onlyOnceImportantTopics']) && $_SESSION['onlyOnceImportantTopics'] == 0 && isset($topics) && $topics == 1){

	echo "<tr><td id='topic' colspan='6'>Important topics.</td></tr><tr>";
	$_SESSION['onlyOnceImportantTopics'] = 1;
	
	echo "<td id='backgroundcolor'></td>";
	echo "<td id='backgroundcolor' >Threads</td>";
	echo "<td id='backgroundcolor' >Replies</td>";
	echo "<td id='backgroundcolor' >Views</td>";
	
	if($permission >= $threadDelete){
		echo "<td id='backgroundcolor' >Options / Last post by</td></tr>";
	}	else echo "<td id='center' >Last Post by</td></tr>";
}

if(isset($_SESSION['onlyOnceForumTopics']) && $_SESSION['onlyOnceForumTopics'] == 0 && isset($topics) && $topics == 2){
	echo "<tr><td id='topic' colspan='6'>Forum topics.</td>";

	
	if($threadDisplay == 2){
		echo "<tr><td id='backgroundcolor' colspan='5'>Threads: Username, Date and time of post.</td></tr>";
	} else {
		echo "<tr>";
		$_SESSION['onlyOnceImportantTopics'] = 1;
		
		echo "<td id='backgroundcolor'></td>";
		echo "<td id='backgroundcolor' >Threads</td>";
		echo "<td id='backgroundcolor' >Replies</td>";
		echo "<td id='backgroundcolor' >Views</td>";
		
		if($permission >= $threadDelete){
			echo "<td id='backgroundcolor' >Options / Last post by</td></tr>";
		}	else echo "<td id='center' >Last Post by</td></tr>";
	}
	
	echo "<tr>";
	$_SESSION['onlyOnceForumTopics'] = 1;
} else echo "<tr>";

echo "<td class='iconColor' width='8'>";

try {
	$stmt7 = $dbh->prepare("SELECT * FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND mark=0 LIMIT 1");
	$stmt7->bindParam(':username', $username);
	$stmt7->bindParam(':c', $c);
	$stmt7->bindParam(':t', $t);
	$stmt7->execute();
	$row7 = $stmt7->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$mark = $row7['mark'];

try {
	$stmt8 = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t");
	$stmt8->bindParam(':username', $username);
	$stmt8->bindParam(':c', $c);
	$stmt8->bindParam(':t', $t);
	$stmt8->execute();
	$rCount = $stmt8->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
// "put tdIcons1 here. hot threads, pin and thread lock goes here."
if($s == 1)
	echo "<a class='iconColor'><i
			class='fa pushpin'></i> </a>";
if(isset($mark) && $mark == 0 || $rCount == 0)
	echo "<a class='iconColor'><i
			class='fa folder-open'></i> </a>";
else echo "<a class='iconColor'><i
		class='fa folder2'></i> </a>";
echo "<br>";
if($lock == 1)
	echo "<a class='iconColor'><i
			class='fa lock'></i> </a>";
if($hot >= $hotTopic)
	echo "<a class='iconColor'><i
			class='fa fire'></i> </a>";
unset($rCount);
?>