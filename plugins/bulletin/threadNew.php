<?php 
if(!isset($_SESSION['newPoll'])){
	require '../../includes/main/header.php';
	
	unset($_SESSION['newPoll']);

	if($permission < $threadNew){
		$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
		$_SESSION['noticesBad'] = 0;
		header("location: {$pluginUrl}index");
		exit;
	}
	
	if(isset($_GET['f'])){
		$f = cleanData($_GET['f']);
	}
	if(isset($_GET['c'])){
		$c = cleanData($_GET['c']);
	}
	if(isset($_GET['t'])){
		$t = cleanData($_GET['t']);
	}

	if(!isset($f) && !isset($c) && !isset($t)){
		$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
		header("location: {$pluginUrl}index");
		exit;
	}

	try {
		$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND r=1 ORDER BY t DESC LIMIT 1");
		$stmt->bindParam(':c', $c);
		$stmt->execute();
		$row7 = $stmt->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	$f2 = cleanData($row7['f']) + 1;
	$c2 = cleanData($row7['c']) + 1;
	$t2 = cleanData($row7['t']) + 1;

	if($f2 == 0 || $c2 == 0 || $t2 != $t){
		$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
		header("location: {$pluginUrl}index");
		exit;
	}
	
	$id = $_SESSION['id'];
	require 'includes/buttonsBulletin.php';

	// notices are the messages at the top of the screen with a red,
	// yellow and green background."
	if(isset($_SESSION['noticesBad']))
	noticesBad();
	unset($_SESSION['noticesBad']);
}

require '../../includes/main/tinymce.php';

if(isset($_SESSION['pollForm'])){
	unset($_SESSION['pollForm']);
} else echo "<form enctype='multipart/form-data' method='POST' action='" . $pluginUrl . "threadNew2.php'>";

?>

<table class='table2' id='left'>
	<tr>
		<th id='center'>New thread.</th>
	</tr>
	<tr><td>
			<?php if(!isset($_SESSION['newPoll'])){ 
				echo ""; 
				// "echo $_SESSION['titleCharacterLimit'] in textarea, after"
				// "a submit, but only if this session is not empty."
				echo "<br>Topic title: <font color='red'>*.</font><br><input size='76' name='topicTitle'	maxlength='53' value='";
				if(isset($_SESSION['topicTitle']))
				echo $_SESSION['topicTitle']; 
				echo "' />";
			}
			?>
		</td>
	</tr>
	<tr>
		<td><br>Topic body: <font color='red'>*</font> 
		<!-- "echo $_SESSION['topicBody'] in textarea, after
			a submit, but only if this session is not empty." --> 
			<textarea id='2' name='topicBody'>
				<?php if(isset($_SESSION['topicBody'])) echo $_SESSION['topicBody']; ?>
			</textarea> <input type='hidden' name='t'
			value='<?php if(isset($t)) echo $t; ?>'> <input type='hidden'
			name='c' value='<?php if(isset($c)) echo $c; ?>'> <input
			type='hidden' name='f' value='<?php if(isset($f)) echo $f; ?>'>
		</td>
	</tr>
	<tr>
		<td>Attach a file to your post. Select ONLY an archive .zip or .tar file to upload. Optional.
		</td>
	</tr>
	<tr>
		<td> 
			<input type="file" name="file" id="file"/>
			<button class="btn btn-default" name="name" type="reset" value="clear">Clear</button>

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