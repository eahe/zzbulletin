<?php 
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';

// "cannot view this file if logged in."
if(isset($_COOKIE['username'])){
	$_SESSION['cookieCheck1'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/notices.php';

?>

<script
	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	$().ready(function() {

			// "set the rules for the sign up form. rules such as"
			// "limit the size of a password entered into the html input field."
	
			// "the name #forgotUsername must match that of the form id."
			$("#forgotUsername").validate({
					rules: {
						password: {
							required: true,
							minlength: 5
						},
						emailAddress: {
							required: true,
							email: true
						},
						confirmEmailAddress: {
							required: true,
							equalTo: "#emailAddress",
						}	
					},
					messages: {
						// "display an error if validator rules are broken"
						password: {
							required: "<?php echo "<br>Provide a password."; ?>",
							minlength: "<?php echo "<br>Password is too small."; ?>"
						},
						emailAddress: "<?php echo "<br>Provide an email address."; ?>",
						confirmEmailAddress: {
							required: "<?php echo "<br>Provide an email address."; ?>",
							equalTo: "<?php echo "<br>No match for new email address."; ?>"
						}
					}
				});

		});
</script>

</head>
<body>
<!-- "id='forgotUsername' must match that of the validator." -->
<form id='forgotUsername' action="forgotUsername2.php" method="POST">
	<table class='table4' id='right'>
		<tr>
			<th id='left'>Note</th>
			<th id='left' colspan='2'>Forgot username.</th>
		</tr>
		<tr style="height: 100%; width:50%;">
			<td style="height: 100%" width='50%' rowspan='4'><?php echo "<textarea readonly>";
				require 'includes/forgotUsernameText.php';
				echo "</textarea>"; ?>
			</td>
		</tr>
		<tr>
			<!-- "provide current password." -->
			<td style='width:25%'><label for='password'><?php echo "Password.";?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%' id='password' name='password'
				type='password'>
			</td>
		</tr>
		<tr>
			<!-- "provide current email address." -->
			<td><label for='emailAddress'><?php echo "Email address."; ?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%;' id='emailAddress'
				name='emailAddress' type='text'>
			</td>
		</tr>
		<tr>
			<!-- "provide current email address again."-->
			<td><label for='confirmEmailAddress'><?php echo "Confirm email address."; ?>
				</label>
			</td>
			<td style='text-align: center;'><input style='width:95%' type='text'
				id='confirmEmailAddress' name='confirmEmailAddress'>
			</td>
		</tr>

		<!-- set up the qapTcha when release is ready 
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