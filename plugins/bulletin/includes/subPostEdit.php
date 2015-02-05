<?php
require '../../includes/main/header.php';
require '../../includes/main/tinymce.php';
if(!isset($c) && !isset($t) && !isset($r)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/buttonsBulletin.php';

if(isset($_SESSION['noticesBad'])){
	noticesBad();
	unset($_SESSION['noticesBad']);
}

// "get thread to edit"
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE id=:idRead");
	$stmt->bindParam(':idRead', $idRead);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$attachFile = $row1['attachFile'];
$topicTitle = $row1['topicTitle'];
$topicBody = $row1['topicBody'];

// "empty table cell cannot be edited in opera browser, so the following stops"
// "that from happening."
$topicBody = str_replace('</td>','<strong>&nbsp;</strong></td>',$topicBody);
$topicBody = str_replace('&nbsp;<strong>&nbsp;</strong></td>','<strong>&nbsp;</strong></td>',$topicBody);
$topicBody = str_replace('&nbsp;<strong>&nbsp;</strong><strong>&nbsp;</strong></td>','<strong>&nbsp;</strong></td>',$topicBody);
$topicBody = str_replace('<strong>&nbsp;</strong><strong>&nbsp;</strong></td>','<strong>&nbsp;</strong></td>',$topicBody);

echo "<form id='' enctype='multipart/form-data' method='POST' action='" . $pluginUrl . "postEdit2.php'>"; ?>
	<table class='table2' id='left'>
		<tr>
			<th id='center'>Edit a post.</th>
		</tr>
		<tr>
			<td><br>Topic title: <font color='red'>*</font><br> <!-- echo topicTitle -->
				<input name='topicTitle' size='76' maxlength='53' value= '<?php if(isset($topicTitle)) echo $topicTitle; ?>' /> <input type='hidden' name='id'
				value='<?php if(isset($idRead)) echo $idRead; ?>'> <input
				type='hidden' name='r' value='<?php if(isset($r)) echo $r; ?>'> <input
				type='hidden' name='p' value='<?php if(isset($p)) echo $p; ?>'>
				<input
				type='hidden' name='attachFile' value='<?php if(isset($attachFile)) echo $attachFile; ?>'>				
			</td>
		</tr>		
		<tr>
			<!-- "display under the title field, the body of the message" -->
			<!-- "to be posted." -->
			<td><br>Topic body. <font color='red'>*</font>
				<textarea id='2' cols='1' rows='1' name='topicBody'>
					<?php if(isset($topicBody)) echo $topicBody; ?>
				</textarea>
			</td>
		</tr>
		<?php 
		if($attachFile != ""){
			echo "<tr>";
			// "attach file"
			echo "<td id='left'>Delete attached file?";
			echo "<input type='radio' name='attachFileCheck' value='yes' />Yes";
			echo "<input type='radio' name='attachFileCheck' value='no' checked />No";
		} else{
			echo "<tr><td><br>Attach a file to your post. Select ONLY an archive .zip or .tar file to upload. Optional.</td></tr><tr><td><input type='file' name='file' id='file'/>";
			echo " <button class='btn btn-default' name='name' type='reset' value='clear'>Clear</button>";
		}
			echo "</td></tr>";
		?>
		<tr>
			<td id='center'>
				<?php include "maintenanceModeCheck.php"; ?>
			</td>
		</tr>

	</table>
</form>

<?php 
require '../../includes/main/footer.php';
exit;
?>