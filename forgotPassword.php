<?php 
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';

// "cannot view this file if logged in."
if(isset($_COOKIE['username'])){
	$_SESSION['cookieCheck1'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

if($outgoingEmails == "n" ){
	$_SESSION['noticesBad'] = "Forgot password is disabled because sending emails are disabled at this bulletin.";
	header("location: {$pluginUrl}index");
	exit;
}

?>

<script
	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	$().ready(function() {
	
			// "this is an input validator which sets the rules for the form. 
			// "rules such as required email address and required lenght."
	
			// "the name #forgotPassword must match that of the form id."
			$("#forgotPassword").validate({
					rules: {
						username: {
							required: true,
							minlength: 2
						},			
						securityQuestion: {
							required: true,
							minlength: 3
						},
						emailAddress: {
							required: true,
							email: true
						}

					},
					messages: {
						// "display an error if rules are broken"
						username: {
							required: "<?php echo "<br>Enter a username."; ?>",
							minlength: "<?php echo "<br>Username is too small."; ?>"
						},
						securityQuestion: {
							required: "<?php echo "<br>Security question is required."; ?>",
							minlength: "<?php echo "<br>Security question is too small."; ?>"
						},
						emailAddress: "<?php echo "<br>Provide an email address."; ?>"

					}
				});

		});
</script>

</head>
<body>
<!-- "id='forgotPassword' must match that of the validator." -->
<form id='forgotPassword' action="forgotPassword2.php" method="POST">
	<input type="hidden" name="op" value="forgot"> <input type="hidden"
	name="newPassword">
	<table class='table4' id='right'>
		<tr>
			<th id='left'>Note</th>
			<th id='left' colspan='2'>Forgot password.</th>
		</tr>
		<tr style="height: 100%; width:50%;">
			<td style="height: 100%" width='50%' rowspan='4'><?php echo "<textarea readonly>";
				require 'includes/forgotPasswordText.php';
				echo "</textarea>"; ?>
			</td>
		</tr>
		<tr>
			<!-- provide username -->
			<td style='width:25%;'><label for='username'><?php echo "Username."; ?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%' id='username' name="username"
				type='text'>
			</td>
		</tr>
		<tr>
			<!-- provide security question -->
			<td><label for='securityQuestion'><?php echo "Security question."; ?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%' id='securityQuestion' name="securityQuestion"
				type='text'>
			</td>
		</tr>
		<tr>
			<!-- provide current email address -->
			<td><label for='emailAddress'><?php echo "Email address."; ?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%' id='emailAddress'
				name='emailAddress' type='text'>
			</td>
		</tr>
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