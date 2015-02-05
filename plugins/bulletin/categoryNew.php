<?php
require '../../includes/main/header.php';

if($permission < $categoryNew){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/buttonsBulletin.php';
require '../../includes/main/tinymce.php';
	
if(isset($_GET['c']))
$c = cleanData($_GET['c']);

if(!isset($c)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY c DESC LIMIT 1");
	$stmt->execute();
	$row2 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
$c2 = $row2['c'] + 1;

if($c2 != $c){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "notices are the messages near the top of the screen with"
// a red, yellow or green background."
require 'includes/notices.php';
?>

</head>
<body>

<?php echo "<form id='' method='POST' action='" . $pluginUrl . "categoryNew2.php'>"; ?>
	<table class='table2' id='left'>

		<tr>
			<th id='center'>Create a new category.</th>
		</tr>
		<tr>
			<td>Select a forum.  <font color='red'>*</font> <?php 
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY f ASC");
					$stmt->execute();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}
				
				// "this html select tag is needed to select the forum"
				// "that the category can be displayed in." 
				echo "<select  name = 'categorySelect' >";
				echo '<option></option>';
				while($row1 = $stmt->fetch(PDO::FETCH_ASSOC)){				
					// "put every forumName inside of the html select tag options."
					// "but limit it to 70 characters"
					if($row1['c'] ==  0){
					if(isset($_SESSION['categorySelect'])){
						if($row1['f'] == $_SESSION['categorySelect'])
							echo "<option value='" . $row1['f'] . "'selected>" . truncateText($row1['forumName'], 70) . "</option>";
					} else echo "<option value='" . $row1['f'] . "'>" . truncateText($row1['forumName'], 70) . "</option>";
					}
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>

			<td><br>Category title: <font color='red'>*</font><br><textarea id='1' name='categoryTitle'><?php if(isset($_SESSION['categoryTitle'])) echo $_SESSION['categoryTitle']; ?></textarea> <input type='hidden' name='c'
				value='<?php if(isset($c)) echo $c; ?>'>
			</td>
		</tr>
		<tr>
			<!-- display under the title field, the body of the message -->
			<!-- to be posted -->
			<td><br>Category body: Please type it here. <font color='red'>*</font><br><textarea id='2' name='categoryBody'><?php if(isset($_SESSION['categoryBody'])) echo $_SESSION['categoryBody']; ?></textarea>
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