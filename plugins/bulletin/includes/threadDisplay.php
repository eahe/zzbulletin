<?php 
try {
	$stmt4 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r");
	$stmt4->bindParam(':c', $c);
	$stmt4->bindParam(':t', $t);
	$stmt4->bindParam(':r', $r);
	$stmt4->execute();
	$row4 = $stmt4->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$timestamp1 = cleanData($row4['timestamp']);
$idRead = cleanData($row4['id']);

// "display the thread in threaded view."
// "display the thread. flat."
echo "<table class='table6' id='left'><tr>";
echo "<th id='left' class='font1' >";

echo $topicTitle;
echo "</th>";

$_SESSION['threadedMode'] = 1;

// "determine if username has mark read as forum in database."
try {
	$stmt8 = $dbh->prepare("SELECT * FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND r=:r AND r!=0");
	$stmt8->bindParam(':username', $username);
	$stmt8->bindParam(':c', $c);
	$stmt8->bindParam(':t', $t);
	$stmt8->bindParam(':r', $r);
	$stmt8->execute();
	$row8 = $stmt8->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$markAsRead = $row8['username'];
$mark = $row8['mark'];

if($markAsRead == NULL){
	try {
		$stmt3 = $dbh->prepare("INSERT INTO {$bulletin}mark_as_read (mark, username, f, c, t, r ) VALUES(1, :username, :f, :c, :t, :r)");
		$stmt3->bindParam(':username', $username);
		$stmt3->bindParam(':f', $f);
		$stmt3->bindParam(':c', $c);
		$stmt3->bindParam(':t', $t);
		$stmt3->bindParam(':r', $r);
		$stmt3->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
} else include "./markAsRead2.php";
unset($_SESSION['threadedMode']);

// "output the number of replies for every post in the thread."
echo "<th id='center' width='200px' class='font1' >";

if(!isset($_SESSION['postPreview'])){
echo "Message #" . $r . ". <br>";
echo $timestamp .".";
}

echo "</th></tr><tr><td valign='top'>";
echo $topicBody;

echo "<hr style='width:80%'><postSignature>" . $postSignature . "</postSignature>";

// "attach file button."
if($permission >= $attachFileDownload && isset($attachFile) && $attachFile != ""){
	echo "<a href=\"" . $pluginUrl. $archiveAttachmentDirectory . $attachFile . "\"><i class='fa paperclip'></i>" . trim(strstr($attachFile, '_'), ' ') . "</a><br>";
}

if($bootstrapButtonsDisplay == "y")
echo "<div id='buttonsTextAlign' style='line-height:2.4em;'>";
else echo "<div id='buttonsTextAlign' style='line-height:1.5em;'>";

if(isset($_SESSION['search'])){
	echo "<a class='btn btn-default' href='{$pluginUrl}threadRead/$idRead'><i class='fa pencil-square2-o fa-lg'></i><span>Thread view</span></a>";
}

$topicBodySelect = $topicBody;
?>