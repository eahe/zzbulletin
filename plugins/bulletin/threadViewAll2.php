<?php
if(!isset($dbh)){
	session_start();
	$_SESSION['noticesBad'] = "Direct access denied for \"" . basename($_SERVER['PHP_SELF']) . "\"";
	header("location: {$pluginUrl}index");
	exit;
}

$_SESSION['forumEdit'] = 2;

require 'includes/buttonsBulletin.php';
require 'includes/notices.php';

// "get the number of threads from the category."
try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE c=:c");
	$stmt->bindParam(':c', $c);
	$stmt->execute();
	$threadsCount2 = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
if($threadsCount2 == 0){
	$_SESSION['noticesBad'] = "No threads to display.";
	noticesBad();
	unset($_SESSION['noticesBad']);
}

// "display the forum. $forumName is from buttonsBulletin.php near breadcrumbs."
echo "<table class='table1' id='left'><col width='8%'> <col width='42%'><col width='11%'><col width='11%'><col width='28%'>";
echo "<tr><th></th><th id='left' colspan='3'>" . $forumName . "</th>";
echo "<th id='center' width='26%'>";

// "button to delete all threads within the category."
if($permission >= $threadDeleteAll){
	if($threadsCount2 > 0){
		echo "<a class='btn btn-danger confirm' href='{$pluginUrl}threadDeleteAll/$c' onmouseover='title=\"\"' title='Are you sure you want to delete all threads and polls within this category?'><i class='fa removeWhite fa-lg'></i></a>";
	}
}

// "button to edit forum text."
if($permission >= $forumEdit){
	echo "<a class='btn btn-primary' href='{$pluginUrl}forumEdit/$f/$c'><i class='fa file-text-o fa-lg'></i></a>";
}

echo "</th></tr><tr>";

require 'includes/threadViewAllPagination.php';
require '../../includes/main/footer.php';

?>