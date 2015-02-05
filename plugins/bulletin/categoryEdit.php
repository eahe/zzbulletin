<?php
require '../../includes/main/header.php';
require '../../includes/main/tinymce.php';

if($permission < $categoryEdit){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['c']))
$c = cleanData($_GET['c']);

if(!isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
	$stmt->bindParam(':c', $c);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$c2 = cleanData($row['c']);

if($c2 == 0){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/buttonsBulletin.php';
require 'includes/notices.php';

// "get category topic and body variables."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c=:c");
	$stmt->bindParam(':c', $c);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$categoryTitle = $row1['categoryTitle'];
$categoryBody = $row1['categoryBody'];

echo "<form name='myform' method='POST' action='" . $pluginUrl . "categoryEdit2.php'>"; ?>
	<table class='table2' id='left'>

		<tr>
			<!-- "after submit button is clicked, these hidden variables"
			"can be retrieved with $_POST at categoryEdit2.php" -->
			<th id='center'>Edit the category. <input type='hidden' name='c'
				value='<?php if(isset($c)) echo $c; ?>' /> <input type='hidden'
				name='categoryTitle'
				value='<?php if(isset($categoryTitle)) echo $categoryTitle; ?>' /> <input
				type='hidden' name='categoryBody'
				value='<?php if(isset($categoryBody)) echo $categoryBody; ?>' />
				
			</th>
		</tr>
		<tr>
			<td width='25%'><br>Category title: <font color='red'>*</font>
				<br> <textarea id='1' cols='1' rows='1' name='categoryTitle'>
					<?php if(isset($categoryTitle)) echo $categoryTitle; ?>
				</textarea>
			</td>
		</tr>
		<tr>
			<td><br>Category body: Please type it here. <font color='red'>*</font>

				<!-- if variable exist then display it in the textarea --> <textarea
					id='2' cols='1' rows='1' name='categoryBody'>
					<?php if(isset($categoryBody)) echo $categoryBody; ?>
				</textarea></td>
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