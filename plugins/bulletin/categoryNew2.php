<?php
require '../../includes/main/header.php';

if($permission < $categoryNew){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_POST['categoryTitle'])){
	$categoryTitle = cleanData($_POST['categoryTitle']);
	$_SESSION['categoryTitle'] = $categoryTitle;
}
if(isset($_POST['categoryBody'])){
	$categoryBody = cleanData($_POST['categoryBody']);
	$_SESSION['categoryBody'] = $categoryBody;
}
if(isset($_POST['categorySelect'])){
	$f = cleanData($_POST['categorySelect']);
	$_SESSION['categorySelect'] = $f;
}
if(isset($_POST['c']))
$c = cleanData($_POST['c']);


if(!isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "if $categorySelect and $categoryTitle and $categoryBody is empty"
// "then output the error at categoryNew.php. display the error at"
// "top of the page."
if($_SESSION['categorySelect'] == "" && $_SESSION['categoryTitle'] == "" && $_SESSION['categoryBody'] == ""){
	unset($_SESSION['categorySelect']);
	$_SESSION['noticesBad'] = "Select a forum cannot be empty. Category title and body cannot be empty.";
	header("location: {$pluginUrl}categoryNew/$c");
	exit;
}

if($_SESSION['categorySelect'] == "" && $_SESSION['categoryBody'] == ""){
	unset($_SESSION['categorySelect']);
	$_SESSION['noticesBad'] = "Select a forum cannot be empty. Category body cannot be empty.";
	header("location: {$pluginUrl}categoryNew/$c");
	exit;
}

if($_SESSION['categorySelect'] == "" && $_SESSION['categoryTitle'] == ""){
	unset($_SESSION['categorySelect']);
	$_SESSION['noticesBad'] = "Select a forum cannot be empty. Category title cannot be empty.";
	header("location: {$pluginUrl}categoryNew/$c");
	exit;
}

if($_SESSION['categorySelect'] == ""){
	unset($_SESSION['categorySelect']);
	$_SESSION['noticesBad'] = "Select a forum cannot be empty.";
	header("location: {$pluginUrl}categoryNew/$c");
	exit;
}

// "if $categoryTitle and $categoryBody is empty then output the error at" 
// "categoryNew.php. display the error at top of the page."
if($_SESSION['categoryTitle'] == "" && $_SESSION['categoryBody'] == ""){
	$_SESSION['noticesBad'] = "Category title and category body cannot be empty.";
	header("location: {$pluginUrl}categoryNew/$c");
	exit;
}

if($_SESSION['categoryTitle'] == ""){
	$_SESSION['noticesBad'] = "Category title cannot be empty.";
	header("location: {$pluginUrl}categoryNew/$c");
	exit;
}

if($_SESSION['categoryBody'] == ""){
	$_SESSION['noticesBad'] = "Category Body cannot be empty.";
	header("location: {$pluginUrl}categoryNew/$c");
	exit;
}

// "prepare for new category."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY c DESC LIMIT 1");
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){		
	$c = $row['c'] + 1;
	$l = $row['l'] + 1;
}

// "if $row['c'] is not set then c equal  1."
if(!isset($c))
$c = 1;

// "create the category"
try {
	$stmt = $dbh->prepare("INSERT INTO {$bulletin}forums (f, c, l, categoryTitle, categoryBody) VALUES (:f, :c, :l, :categoryTitle, :categoryBody)");
	$stmt->bindParam(':f', $f);
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':l', $l);
	$stmt->bindParam(':categoryTitle', $categoryTitle);
	$stmt->bindParam(':categoryBody', $categoryBody);
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

unset($_SESSION['categoryTitle']);
unset($_SESSION['categoryBody']);
unset($_SESSION['categorySelect']);
unset($_SESSION['noticesBad']);
	
$_SESSION['noticesGood'] = "Category created.";
header("location: {$pluginUrl}index");
exit;
?>