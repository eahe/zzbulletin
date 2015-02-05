<?php
require '../../includes/main/header.php';
unset($_SESSION['threadedMode']);

if(isset($_GET['f'])){
	$f = cleanData($_GET['f']);
	if($f == NULL)
	unset($f);
}
if(isset($_GET['c'])){
	$c = cleanData($_GET['c']);
	if($c == NULL)
	unset($c);
}
if(isset($_GET['t'])){
	$t = cleanData($_GET['t']);
	if($t == NULL)
	unset($t);
}
if(isset($_GET['r'])){
	$r = cleanData($_GET['r']);
	if($r == NULL)
	unset($r);
}
if(isset($_GET['q'])){
	$q = cleanData($_GET['q']);
	if($q == NULL)
	unset($q);
}

if(!isset($_SESSION['threadedMode'])){
	$_SESSION['noticesGood'] = 'Mark Forum as read successfully.';
	if(isset($r) && isset($q)){
		header("location: {$pluginUrl}markAsRead2.php?c=$c&t=$t&r=$r&q=$q");
		exit;
	} elseif(isset($r) && !isset($q)){
		header("location: {$pluginUrl}markAsRead2.php?c=$c&t=$t&r=$r");
		exit;
	} elseif(!isset($r) && isset($q)){
		header("location: {$pluginUrl}markAsRead2.php?c=$c&t=$t&q=$q");
		exit;
	} else {
		header("location: {$pluginUrl}markAsRead2.php?c=$c&t=$t");
		exit;	
	}
}
?>