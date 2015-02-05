<?php 
require "includes/main/header.php";

if(!isset($_COOKIE['username'])){
	$_SESSION['cookieCheck2'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

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
require 'includes/notices.php';
?>

<script
	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	$().ready(function() {

			// "this is an input validator which sets the rules for the form. 
			// "rules such as required email address and required lenght."
	
			// "the name #changePassword must match that of the form id."
			$("#changePassword").validate({
					rules: {
						password: {
							required: true,
							minlength: 5
						},
						newPassword: {
							required: true,
							minlength: 5
						},
						confirmNewPassword: {
							required: true,
							minlength: 5,
							equalTo: "#newPassword"
						}
					},
					messages: {
						// "display an error if rules are broken"
						password: {
							required: "<?php echo "<br>Provide a password."; ?>",
							minlength: "<?php echo "<br>Password is too small."; ?>"
						},
						newPassword: {
							required: "<?php echo "<br>Provide a password."; ?>",
							minlength: "<?php echo "<br>Password is too small."; ?>"
						},
						confirmNewPassword: {
							required: "<?php echo "<br>Provide a password."; ?>",
							minlength: "<?php echo "<br>Password is too small."; ?>",
							equalTo: "<?php echo "<br>No match for New password."; ?>"
						}
					}
				});

		});
</script>

<!-- "id='changePassword' must match that of the validator." -->
<form id='changePassword' action="userMainSetup.php" method="POST">
	<input type="hidden" name="op" value="change">
	<table class='table4' id='right'>
		<tr>
			<th id='left'>Note</th>
			<th id='left' colspan='2'>Change password.</th>
		</tr>
		<tr style="height: 100%; width:50%;">
			<td style="height: 100%" width='50%' rowspan='4'><?php echo "<textarea readonly>";
				require 'includes/changePasswordText.php';
				echo "</textarea>"; ?>
			</td>
		</tr>
		<tr>
			<!-- "provide current password." -->
			<td style='width: 25%;'><label for='password'><?php echo "Current login password.";?>
				</label>
			</td>
			<td style='text-align: center;'><input style='width: 95%;'
				id='password' name='password' type='password'>
			</td>
		</tr>
		<tr>
			<!-- "provide new password." -->
			<td><label for='newPassword'><?php echo "New password.";?></label>
			</td>
			<td style='text-align: center;'><input style='width: 95%;'
				id='newPassword' name='newPassword' type='password'>
			</td>
		</tr>
		<tr>
			<!-- "provide new password again." -->
			<td><label for='confirmNewPassword'><?php echo "Confirm new password.";?>
				</label>
			</td>
			<td style='text-align: center;'><input style='width: 95%;'
				id='confirmNewPassword' name='confirmNewPassword' type='password'>
			</td>
		</tr>

		<!-- "set up the qapTcha when release is ready." 
		<div class="QapTcha"></div>
		-->
		<tr>
			<td id='center' colspan='3'>
				<?php include "includes/maintenanceModeCheck.php"; ?>
			</td>
		</tr>
	</table>
</form>
<?php 
require "includes/main/footer.php";
?>