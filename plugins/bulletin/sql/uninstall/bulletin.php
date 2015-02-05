<?php
if($permission < 4 || $username != 'admin'){
	header("location: ../../index.php");
	exit;
}

$time = time();
require '../../includes/main/pdoTablePrefix.php';

$sql="DROP TABLE IF EXISTS {$bulletin}forums, {$bulletin}threads, 
{$bulletin}permissions, {$bulletin}configuration, {$bulletin}preferences,
{$bulletin}mark_as_read, {$bulletin}poll_answers, {$bulletin}poll_questions,
{$bulletin}poll_votes, {$bulletin}cookies, {$bulletin}subscribe_forum,
{$bulletin}subscribe_thread;";

try { 
	$dbh->exec($sql);

	echo "Dropped all {$bulletin} tables successfully.<br>";
	} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

?>
