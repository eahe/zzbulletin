<?php
require "../../includes/main/header.php";
require '../../includes/main/tinymce.php';

if(!isset($dbh)){
	session_start();
	$_SESSION['noticesBad'] = "Direct access denied for \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}

if($permission < $postReply){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

$r++;

if($permission < $postReply){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/buttonsBulletin.php';

if(isset($_SESSION['noticesGood'])){
	noticesGood();
	unset($_SESSION['noticesGood']);
}

if(isset($_SESSION['noticesBad']) && $_SESSION['noticesBad'] != "404 - File not found."){
	noticesBad();
}
unset($_SESSION['noticesBad']);
	
if(!isset($r))
$r = 1;
$r--;

// "this is the thread data that the user clicked the reply button from."
try {
	$stmt9 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r");
	$stmt9->bindParam(':c', $c);
	$stmt9->bindParam(':t', $t);
	$stmt9->bindParam(':r', $r);
	$stmt9->execute();
	$row9 = $stmt9->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$topicTitle = $row9['topicTitle'];
$_SESSION['topicTitleLastPost'] = $topicTitle;
$topicBody = $row9['topicBody'];
$attachFile = cleanData($row9['attachFile']);

if($username != "guest"){
	if(isset($_COOKIE['timezone']))
		$timestamp2 = cleanData($row9['timestamp']) + $_COOKIE['timezone'];
	else $timestamp2 = cleanData($row9['timestamp']);
} else $timestamp2 = cleanData($row9['timestamp']);

$quote = 1;

// "get the users data from the database and for the reply."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$avatar = cleanData($row1['avatar']);
$yourGender = cleanData($row1['yourGender']);
$dateJoined2 = cleanData($row1['dateJoined']);
$totalPosts = cleanData($row1['totalPosts']);

$dateJoined = date("M d Y", $dateJoined2);
$timestamp = timestampDate($timestamp2);

$topicTitle2 = $topicTitle;
$topicBody2 = $topicBody;

// "preview."
if(isset($_SESSION['postPreview'])){
	if(isset($_SESSION['topicTitle']))
	$topicTitle = $_SESSION['topicTitle'];
	if(isset($_SESSION['topicBody']))
	$topicBody = $_SESSION['topicBody'];
	$username2 = $username;
	
	$_SESSION['replyOtherProfile'] = 0;
	require "includes/buttonsThread.php";
	require "includes/postProfile.php";
	echo "<br>";
	unset($_SESSION['postPreview']);
}

$topicTitle = $topicTitle2;
$topicBody = $topicBody2;

echo "<form enctype='multipart/form-data' id='postReply' method='POST' action='" . $pluginUrl . "postReply3.php'>"; ?>
	<table class='table2' id='left'>
		<tr>
			<th id='center'>Topic reply.</th>
		</tr>
		<tr>
			<!-- "this is the title of the thread that the users
			is replying from." -->
			<td id='left'><br>Title can be changed. <font color='red'>*</font><br><input size='76' name='topicTitle' maxlength='53'	value='<?php if(isset($_SESSION['topicTitle']))
				echo $_SESSION['topicTitle']; 
				elseif(isset($topicTitle))
				echo $topicTitle;
				?>' />
			</td>
		</tr>
		<tr>
			<!-- "your message / the topicBody is here." -->
			<td id='left'><br>Topic body. <font color='red'>*</font> <!-- "display under the title field, the body of the message." -->
				<textarea id='2' cols='1' rows='1' name='topicBody'>
					<?php 
					if(isset($q) && $q == 1){
						$topicBody2 = $topicBody;
						$topicBody2 = str_replace('&nbsp;','',$topicBody2);
 
						// "removes old quotes, such as quotes that are two posts deep."
						$topicBody2 =  preg_replace("/<blockquote>(.*)<\/blockquote>/is", "", $topicBody2);
						// "remove any table."
						$topicBody2 =  preg_replace("/<table(.*)<\/table>/is", "", $topicBody2);

						// "here is when a user clicks (quote) from a post."
						// "put all justify aligned text, into a blockquote tag and"
						// "followed by the text (At message # username wrote).
						$topicBody2 = str_replace('<p style="text-align: center;">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						$topicBody2 = str_replace('<p style="text-align: right;">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						$topicBody2 = str_replace('<p style="text-align: justify;">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);

						$topicBody2 = str_replace('<p style="text-align: left;">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						// "replace text-align that has extra space."
						$topicBody2 = str_replace('<p style="text-align: center; ">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						$topicBody2 = str_replace('<p style="text-align: right; ">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						$topicBody2 = str_replace('<p style="text-align: justify; ">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						// "replace text-align that has no space."
						$topicBody2 = str_replace('<p style="text-align:center">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						$topicBody2 = str_replace('<p style="text-align:right">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
						$topicBody2 = str_replace('<p style="text-align:justify">', "<blockquote><p style='text-align: left;'>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);
	
	
						$topicBody2 = str_replace('<li style="text-align: center;">', "<li style='text-align: left;'>" , $topicBody2);
						$topicBody2 = str_replace('<li style="text-align: right;">', "<li style='text-align: left;'>" , $topicBody2);
						$topicBody2 = str_replace('<li style="text-align: justify;">', "<li style='text-align: left;'>" , $topicBody2);
						$topicBody2 = str_replace('<li style="text-align: center; ">', "<li style='text-align: left;'>" , $topicBody2);
						$topicBody2 = str_replace('<li style="text-align: right; ">', "<li style='text-align: left;'>" , $topicBody2);
						$topicBody2 = str_replace('<li style="text-align: justify; ">', "<blockquote><li style='text-align: left;'>" , $topicBody2);
						// "replace text-align that has no space."
						$topicBody2 = str_replace('<li style="text-align:center">', "<li style='text-align: left;'>" , $topicBody2);
						$topicBody2 = str_replace('<li style="text-align:right">', "<li style='text-align: left;'>" , $topicBody2);
						$topicBody2 = str_replace('<li style="text-align:justify">', "<li style='text-align: left;'>" , $topicBody2);
	
						// "all text inclosed within a <ol>, <ul> or <p> tag, are replaced with blockquote"
						// followed by a <ol>, <ul> or <p> tag then followed by the user that is quoted,"
						// "At message # username wrote."
						$topicBody2 = str_replace('<ol>', "<blockquote>At message #" . $r . ', ' . $username . ' wrote... <br><ol>' , $topicBody2);
						$topicBody2 = str_replace('<ul>', "<blockquote>At message #" . $r . ', ' . $username . ' wrote... <br><ul>' , $topicBody2);
						$topicBody2 = str_replace('<p>', "<blockquote><p>At message #" . $r . ', ' . $username . ' wrote... <br>' , $topicBody2);

						// "end the </ol>, </ul> or </p> tag within a blockquote"
						$topicBody2 = str_replace('</ol>', '</ol></blockquote>', $topicBody2);
						$topicBody2 = str_replace('</ul>', '</ul></blockquote>', $topicBody2);
						$topicBody2 = str_replace('</p>', '</p></blockquote>', $topicBody2);
	
						// "removes an empty quote"
						$topicBody2 = str_replace("<blockquote><p style='text-align: left;'>At message #" . $r . ", " . $username. " wrote... <br></p></blockquote>","",$topicBody2);
	
						$topicBody2 = str_replace("<blockquote><p>At message #" . $r . ", " . $username. " wrote... <br></p></blockquote>","",$topicBody2);

						$topicBody2 = rtrim($topicBody2);
						echo $topicBody2 . "<br>";

					} else if(isset($_SESSION['topicBody']))
					echo $_SESSION['topicBody'];

	
					?>

				</textarea> 
	
				<!-- "these variables are hidden. they are needed variables
				that are sent to postReply3.php." -->  <input type='hidden' name='f'
				value='<?php if(isset($f)) echo $f; ?>'> <input type='hidden'
				name='r' value='<?php if(isset($r)) echo $r; ?>'> <input
				type='hidden' name='t' value='<?php if(isset($t)) echo $t; ?>'> <input
				type='hidden' name='c' value='<?php if(isset($c)) echo $c; ?>'>
				<input
				type='hidden' name='p' value='<?php if(isset($p)) echo $p; ?>'>
				<input
				type='hidden' name='id' value='<?php if(isset($idRead)) echo $idRead; ?>'>
			</td>
		</tr>
		<tr>
			<?php if($permission >= $attachFileToPost){
			echo '<td><br>Attach a file to your post. Select ONLY an archive .zip or .tar file to upload. Optional.
			</td>
		</tr>
		<tr>
		<td>
				<input type="file" name="file" id="file"/>
				<button class="btn btn-default" name="name" type="reset" value="clear">Clear</button>
			</td>';
			}
			?>
		</tr>
		<tr>
			<td id="center">
				<?php include "includes/maintenanceModeCheck.php";	
				echo "  <button class='btn btn-default' name='preview' value='preview'  type='submit' >preview</button>"; ?>
			</td>
		</tr>
	</table>

</form>

<?php 

echo "<br><br>";

// "get uers information for posted message such as time the message"
// "was posted and username that posted the message."
$username2 = cleanData($row9['username']);
$timestamp = cleanData($row9['timestamp']);

// "get current timestamp for users avatar and users information"
// "at right side of table."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$root}users WHERE username=:username2");
	$stmt->bindParam(':username2', $username2);
	$stmt->execute();
	$row5 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$avatar = cleanData($row5['avatar']);
$yourGender = cleanData($row5['yourGender']);
$dateJoined2 = cleanData($row5['dateJoined']);
$totalPosts = cleanData($row5['totalPosts']);

// "make current timestamp."
$dateJoined = date("M d Y", $dateJoined2);

if($username != "guest"){
	if(isset($_COOKIE['timezone']))
	$timestamp = $timestamp + $_COOKIE['timezone'];
}

$timestamp = timestampDate($timestamp);

$_SESSION['replyOtherProfile'] = 0;
require "includes/buttonsThread.php";
require "includes/postProfile.php";

$r++;

require 'includes/postReplyPagination.php';
require '../../includes/main/footer.php';

?>