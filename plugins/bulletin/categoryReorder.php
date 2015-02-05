<?php
require '../../includes/main/header.php';

if($permission < $categoryReorder){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

// "categories are moved up or down with the arrows images at index.php."
if(isset($_GET['a']))
$a = cleanData($_GET['a']);

if(!isset($a)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "get the last l veriable in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY l DESC LIMIT 1");
	$stmt->execute();
	$row8= $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$desc = $row8['l'];

if($a == "down"){
	$l = cleanData($_GET['l']);

	// "if arrow down and $l valiable cannot go down any more, then $l2 will"
	// "be the value 1 and $l will be the last value of $desc. these values"
	// "will be updated near the bottom of this page."
	if($l == $desc){
		$l2 = 1;
	} else{
		// "swap the variables of $l2 and $l, which will swap the categories"
		// "positions."
		$l = $l + 1;
		$l2 = $l - 1;
	}
}

// "the value of $l and $l2 are the opposite of the values from moving down."
if($a == "up"){
	$l = cleanData($_GET['l']);

	if($l == 1){
		$l2 = $desc;
	} else{
		$l = $l - 1;
		$l2 = $l + 1;
	}
}

// get the current category row where '$l' is at."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE l=:l");
	$stmt->bindParam(':l', $l);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$f = cleanData($row1['f']);
$c = cleanData($row1['c']);
$categoryTitle = cleanData($row1['categoryTitle']);
$categoryBody = cleanData($row1['categoryBody']);

// get the category row where '$l2' is at."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE l=:l2");
	$stmt->bindParam(':l2', $l2);
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$b2 = cleanData($row2['f']);
$c2 = cleanData($row2['c']);
$categoryTitle2 = cleanData($row2['categoryTitle']);
$categoryBody2 = cleanData($row2['categoryBody']);

// "update these queries based on $l and $l2 values."
try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}forums SET f=:f, c=:c2, categoryTitle=:categoryTitle2, categoryBody=:categoryBody2 WHERE l=:l");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':c2', $c2);
	$stmt->bindParam(':categoryTitle2', $categoryTitle2);
	$stmt->bindParam(':categoryBody2', $categoryBody2);
	$stmt->bindParam(':l', $l);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("UPDATE {$bulletin}forums SET f=:b2, c=:c, categoryTitle=:categoryTitle, categoryBody=:categoryBody WHERE l=:l2");
	$stmt->bindParam(':b2', $b2);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':categoryTitle', $categoryTitle);
	$stmt->bindParam(':categoryBody', $categoryBody);
	$stmt->bindParam(':l2', $l2);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

header("location: {$pluginUrl}index");
exit;
?>