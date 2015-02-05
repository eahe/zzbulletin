<?php 
require "../../includes/main/header.php";

if($permission < $pollNew){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if(isset($_GET['f']))
$f = cleanData($_GET['f']);

if(isset($_GET['c']))
$c = cleanData($_GET['c']);

if(isset($_GET['t']))
$t = cleanData($_GET['t']);

if(!isset($f) && !isset($c) && !isset($t)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

// "determine if data exists in the database."
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

require 'includes/buttonsBulletin.php';
require 'includes/notices.php';

echo "<form id='newPoll' enctype='multipart/form-data' method='POST' action='" . $pluginUrl . "threadNew2.php'>"; ?>

<!-- "display the forums." -->
<table class='table4' id='right'>
	<tr>
		<th id='left'>Note.</th>
		<th id='left' colspan='2'>New poll.</th>
	</tr>
	<tr style="height: 100%; width:50%;">
		<td style="height: 100%" width='50%' rowspan='10'><?php echo "<textarea readonly>";
			require "includes/pollNewText.php";
			echo "</textarea>"; ?></td>
	</tr>
	<tr>
		<!-- "provide poll question." -->
		<td id='right'><label><?php echo "Question."; ?></label>
		</td>
		<td><input id='question' name="question" type='text' value='<?php if(isset($_SESSION['question'])) echo $_SESSION['question']; ?>'>
		</td>
	</tr>
	<tr>
		<!-- "provide poll answer." -->
		<td id='right'><label><?php echo "Answer 1.";?></label>
		</td>
		<td><input id='answer1' name='answer1' type='text' value='<?php if(isset($_SESSION['answer1'])) echo $_SESSION['answer1']; ?>'>
		</td>
	</tr>

	<tr>
		<td><label><?php echo "Answer 2.";?></label>
		</td>
		<td><input id='answer2' name='answer2' type='text' value='<?php if(isset($_SESSION['answer2'])) echo $_SESSION['answer2']; ?>'>
		</td>
	</tr>

	<tr>
		<td><label><?php echo "Answer 3.";?></label>
		</td>
		<td><input id='answer3' name='answer3' type='text' value='<?php if(isset($_SESSION['answer3'])) echo $_SESSION['answer3']; ?>'>
		</td>
	</tr>

	<tr>
		<td><label><?php echo "Answer 4.";?></label>
		</td>
		<td><input id='answer4' name='answer4' type='text' value='<?php if(isset($_SESSION['answer4'])) echo $_SESSION['answer4']; ?>'>
		</td>
	</tr>

	<tr>
		<td><label><?php echo "Answer 5.";?></label>
		</td>
		<td><input id='answer5' name='answer5' type='text' value='<?php if(isset($_SESSION['answer5'])) echo $_SESSION['answer5']; ?>'>
		</td>
	</tr>

	<tr>
		<td><label><?php echo "Answer 6.";?></label>
		</td>
		<td><input id='answer6' name='answer6' type='text' value='<?php if(isset($_SESSION['answer6'])) echo $_SESSION['answer6']; ?>'>
		</td>
	</tr>

	<tr>
		<td><label><?php echo "Answer 7.";?></label>
		</td>
		<td><input id='answer7' name='answer7' type='text' value='<?php if(isset($_SESSION['answer7'])) echo $_SESSION['answer7']; ?>'>
		</td>
	</tr>

	<tr>
		<td><label><?php echo "Answer 8.";?></label>
		</td>
		<td><input id='answer8' name='answer8' type='text' value='<?php if(isset($_SESSION['answer8'])) echo $_SESSION['answer8']; ?>'>
		</td>
	</tr>
</table>

<br>
<?php 
$_SESSION['pollForm'] = 1;
$_SESSION['newPoll'] = 1;
require "threadNew.php";
?>