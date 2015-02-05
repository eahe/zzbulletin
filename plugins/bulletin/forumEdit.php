<?php
require '../../includes/main/header.php';
require '../../includes/main/tinymce.php';

if($permission < $forumEdit){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['f']))
$f = cleanData($_GET['f']);
if(isset($_GET['c']))
$c = cleanData($_GET['c']);

if(!isset($f)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE f=:f");
	$stmt->bindParam(':f', $f);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$f2 = cleanData($row['f']);

if($f2 == ''){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "prepare to display forumName inside the textarea."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE f=:f");
	$stmt->bindParam(':f', $f);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}


$forumName = $row1['forumName'];

require 'includes/buttonsBulletin.php';

// "display the notices messages if any from the forumEdit2.php file."
if(isset($_SESSION['boardError1'])){
	$_SESSION['noticesBad'] = "Forum name cannot be empty.";
	noticesBad();
} elseif(isset($_SESSION['boardError2'])){
	$_SESSION['noticesBad'] = "You have typed in a forum name that already exists.";
	noticesBad();
}

unset($_SESSION['noticesBad']);
unset($_SESSION['boardError1']);
unset($_SESSION['boardError2']);

?>
<body>
<?php echo "<form id='' method='POST' action='" . $pluginUrl . "forumEdit2.php'>"; ?>
	<table class='table2' id='left'>
		<tr>
			<th id='center'>Edit the forum.</th>
		</tr>
		<tr>
			<!-- display the forum name (forumName) in the textarea -->
			<td width='25%'><br>Forum name: <font color='red'>*</font> <br> <textarea
						id='1' name='forumName'>
					<?php if(isset($forumName)) echo $forumName;?>
				</textarea> 
				<!-- "after submit button is clicked," 
				"hidden variables can be retrieved with"
				"$_POST at forumEdit2.php". -->
				<input type='hidden' name='f'
				value='<?php if(isset($f)) echo $f ?>'>
				<input type='hidden' name='c'
				value='<?php if(isset($c)) echo $c ?>'>
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