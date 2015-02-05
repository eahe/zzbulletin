<?php 
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';
require 'includes/notices.php';

$_SESSION['maintenanceModeLogin'] = 1;

if(isset($_COOKIE['username'])){
	$_SESSION['noticesBad'] = "Cannot login because you are logged in.";
	header("location: {$pluginUrl}index");
	exit;
}

?>

<script
	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	// "sets the form to have conditions that must be met in order to submit"
	// "the data."

	// "the name #signupForm1 must match that of the form id."
	$().ready(function() {
			$("#signupForm1").validate({
					rules: {
						username: {
							required: true,
							minlength: 2
						},
						password: {
							required: true,
							minlength: 5
						}, 
					},
					messages: {
						// "display the text when rules are not ment."
						username: {
							required: "<?php echo "<br>Enter a username."; ?>",
							minlength: "<?php echo "<br>Username is too small."; ?>"
						},
						password: {
							required: "<?php echo "<br>Provide a password."; ?>",
							minlength: "<?php echo "<br>Password is too small."; ?>"
						}	
					}
				});

		});
</script>

</head>
<body>
<!-- "id='signupForm1' must match that of the validator." -->
<form id='signupForm1' method='POST' action='userMainSetup.php'>
	<!-- "display the forums" -->
	<table class='table4' id='right'>
		<tr>
			<th id='left'>Privacy policy.</th>
			<th colspan='3' id='left'>Login.</th>
		</tr>
		<tr style="height: 100%; width:50%;">
			<td style='height: 100%' width='50%' rowspan='5'><?php echo "<textarea readonly>";
				require 'includes/termsOfUse.php';
				echo "</textarea>"; ?></td>
		</tr>
		<tr>
			<!-- "provide username." -->
			<td id='right'><input type='hidden' name='back' value='login.php'> <input
				type='hidden' name='op' value='login'> <label for='username'><?php echo "Username."; ?>
				</label>
			</td>
			<td><input style='width:95%' id='username' name='username' type='text'>
			</td>
		</tr>
		<tr>
			<!-- "provide current password." -->
			<td id='right' width='185'><label for='password'><?php echo "Password.";?>
				</label>
			</td>
			<td><input style='width:95%' id='password' name='password' type='password'>
			</td>
		</tr>
		<tr>
			<!-- "remember me." -->
			<td colspan='3'><?php echo "Log me on automatically."; ?>
				<input type='radio' class='radio' name='rememberMe' value='1'
				checked>Yes <input type='radio' class='radio' name='rememberMe'
				value='0'>No</td>
		</tr>
		<tr>
			<!-- "online Status. determine if users can see you online." -->
			<td colspan='3'><label for='userHidden'> <?php echo "Hide my online status."; ?>
				</label> <input type='radio' class='radio' name='userHidden'
				value='1'>Yes <input type='radio' class='radio' name='userHidden'
				value='0' checked>No</td>
		</tr>
		<tr>
			<td colspan='3' id='center'><label for='agree2'><?php echo "Please agree to the privacy policy."; ?>
				</label> <input type='radio' class='radio' name='agree2' value='1'
				checked>Yes <input type='radio' class='radio' name='agree2'
				value='0'>No</td>
		</tr>
		<!-- "set up the qapTcha when release is ready." 
		<div class="QapTcha"></div>
		-->


		<tr>
			<td id='center' colspan='3'><button class="btn btn-danger"
						name="name" type="submit">Submit Query</button>
			</td>
		</tr>
	</table>
</form>

<?php 
require "includes/main/footer.php";
?>