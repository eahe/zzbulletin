<?php
require '../../includes/main/header.php';
require '../../includes/main/tinymce.php';

if($permission < $forumNew){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['f']))
$f = cleanData($_GET['f']);

if(!isset($f)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database"
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY f DESC LIMIT 1");
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$f2 = $row['f'] + 1;

if($f2 != $f){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "notices are the messages near the top of the"
// "screen with a red, yellow and green background. see function.php."
if(isset($_SESSION['noticesBad']))
unset($_SESSION['noticesBad']);

require 'includes/buttonsBulletin.php';
?>

</head>
<body>
<?php 
// "display the notices messages if any from the forumNew2.php file"
if(isset($_SESSION['boardError2'])){
	$_SESSION['noticesBad'] = "Forum name cannot be empty.";
	noticesBad();
}

if(isset($_SESSION['boardError1'])){
	$_SESSION['noticesBad'] = "You have typed in a forum name that already exists.";
	noticesBad();
}

unset($_SESSION['noticesBad']);
unset($_SESSION['boardError1']);
unset($_SESSION['boardError2']);

echo "<form id='forumNew' method='POST' action='" . $pluginUrl . "forumNew2.php'>"; ?>
	<table class='table2' id='left'>
		<tr>
			<th id='center'>Create a new forum.</th>
		</tr>
		<tr>
			<td><br> <label>Forum name: <font color='red'>*</font>
				</label><br><textarea id='1' name='forumName'></textarea> 
				 <input
				type='hidden' name='f' value='<?php if(isset($f)) echo $f; ?>'>
			</td>
		</tr>
		<tr>
			<td id='center'>
				<?php include "includes/maintenanceModeCheck.php"; ?>				
			</td>
		</tr>
	</table>
</form>

<?php 
require '../../includes/main/footer.php';

?>