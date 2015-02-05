<?php
require "../../includes/main/header.php";

// "from buttonsBulletin.php variable $forumNew5."
if(isset($_SESSION['getF']))
$forumNew5 = $_SESSION['getF'];
// "from buttonsBulletin.php variable $c[$i]."
if(isset($_SESSION['getC']))
$c = $_SESSION['getC'];
// "from buttonsBulletin.php variable $p."
if(isset($_SESSION['getP']))
$p = $_SESSION['getP'];

require 'includes/buttonsBulletin.php';

if(!isset($_COOKIE['username'])){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/notices.php';
?>

<script
	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	$().ready(function() {
	
			// "this is an input validator which sets the rules for the form. 
			// "rules such as required email address and required lenght."
	
			// "the name #signupForm2 must match that of the form id."
			$("#settings").validate({
					rules: {
						WebsiteEmailAddress: {
							required: true,
							email: true
						}				
					},
					messages: {
						// "display an error if rules are broken"
						WebsiteEmailAddress: "<?php echo "<br>Provide an email address"; ?>"
					}
				});

		});
</script>

<form id='settings' method='POST' action='yourPreferences2.php'>
	<table class='table6'>
		<tr>
			<th colspan='2'>Your preferences.</th>
		</tr>
		<tr>
			<td>At side panel, display ten resent bulletin posts.</td>
			<td><input type='radio' class='radio' name='tenResentBulletinPosts' value='y'
				<?php if(isset($tenResentBulletinPosts)) if($tenResentBulletinPosts =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='tenResentBulletinPosts' value='n'
				<?php if(isset($tenResentBulletinPosts)) if($tenResentBulletinPosts =='n') echo 'checked';?>>No.</td>
		</tr>
		<tr><!-- "thread display." -->
			<td>When viewing a thread, how would you like its posts to be displayed.</td>
			<td><input type='radio' class='radio' name='threadDisplay' value='1'
				<?php if(isset($threadDisplay)) if($threadDisplay =='1') echo 'checked';?>>flat mode. <input
				type='radio' class='radio' name='threadDisplay' value='2'
				<?php if(isset($threadDisplay)) if($threadDisplay =='2') echo 'checked';?>>threaded mode.</td>
		</tr>	
		<tr>
			<!-- "Posts shown together." -->
			<td><label><?php echo "Number of posts shown on page at the same time."; ?>
				</label>
			</td>
			<td><?php 
				$ii = 5;
				echo "<select name='limit'>";			

				while($ii < 40){
					$ii = $ii + 5;
					if($limit == $ii)
					echo '<option value=' . $limit . " selected />". $limit;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<!-- "Posts shown together." -->
			<td><label><?php echo "Number of threads shown on page at the same time."; ?>
				</label>
			</td>
			<td><?php 
				$ii = 5;
				echo "<select name='limit2'>";			

				while($ii < 80){
					$ii = $ii + 5;
					if($limit2 == $ii)
					echo '<option value=' . $limit2 . " selected />". $limit2;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<!-- "brTag1 is "<br> html tag."  -->
			<td>A space between control panel / side menus.</td>
			<td><input type='radio' class='radio' name='brTag1' value='y'
				<?php if(isset($brTag1)) if($brTag1 =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='brTag1' value='n'
				<?php if(isset($brTag1)) if($brTag1 =='n') echo 'checked';?>>No.</td>
		</tr>
		<tr>

			<!-- "brTag2 is "<br> html tag."  -->
			<td>A space between forums, forum categories or posted messages.</td>
			<td><input type='radio' class='radio' name='brTag2' value='y'
				<?php if(isset($brTag2)) if($brTag2 =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='brTag2' value='n'
				<?php if(isset($brTag2)) if($brTag2 =='n') echo 'checked';?>>No.</td>
		</tr>
		<tr>

			<!-- "brTag3 is "<br> html tag."  -->
			<td>A space between pagination.</td>
			<td><input type='radio' class='radio' name='brTag3' value='y'
				<?php if(isset($brTag3)) if($brTag3 =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='brTag3' value='n'
				<?php if(isset($brTag3)) if($brTag3 =='n') echo 'checked';?>>No.</td>
		</tr>

	</table>
	<table class='table6'>
		<tr>
			<td id='center' colspan='2'>
				<?php include "includes/maintenanceModeCheck.php"; ?>
			</td>
		</tr>
	</table>
</form>

<?php 

require '../../includes/main/footer.php';

?>