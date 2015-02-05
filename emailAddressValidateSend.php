<?php 
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';
require 'includes/notices.php';

// "cannot view this file if logged in."
if(isset($_COOKIE['username'])){
	$_SESSION['cookieCheck1'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

if($outgoingEmails == "n"){
	$_SESSION['noticesBad'] = "Cannot send email validate link because outgoing emails are not enabled.";
	header("location: {$pluginUrl}index");
	exit;
}

?>

<script	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	$().ready(function() {

			// "this is an input validator which sets the rules for the form. 
			// "rules such as required email address and required lenght."
	
			// "the name #resendValidate must match that of the form id."
			$("#resendValidate").validate({
					rules: {
						username: {
							required: true,
							minlength: 2
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
						// "display an error if rules are broken."
						username: {
							required: "<?php echo "<br>Enter a username."; ?>",
							minlength: "<?php echo "<br>Username is too small."; ?>"
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
<!-- "id='resendValidate' must match that of the validator." -->
<form id='resendValidate' action="emailAddressValidate.php" method="POST">
	<table class='table4' id='right'>
		<tr>
			<th id='left'>Note</th>
			<th id='left' colspan='2'>Resend email address validate link.</th>
		</tr>
		<tr style="height: 100%; width:50%;">
			<td style="height: 100%" width='50%' rowspan='4'><?php echo "<textarea readonly>";
				require 'includes/emailAddressValidateSendText.php';
				echo "</textarea>"; ?></td>
		</tr>
		<tr>
			<!-- provide username -->
			<td style='width:25%;'><label for='username'><?php echo "Username."; ?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%;' id='username' name="username"
				type='text'>
			</td>
		</tr>

		<tr>
			<!-- "provide email address." -->
			<td><label for='emailAddress'><?php echo "Email address."; ?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%;' id='emailAddress' name='emailAddress'
				type='text'>
			</td>
		</tr>
		<tr>
			<!-- "provide email address again." -->
			<td><label for='confirmEmailAddress'><?php echo "Confirm email address."; ?></label>
			</td>
			<td style='text-align: center;'><input style='width:95%;' type='text'
				id='confirmEmailAddress' name='confirmEmailAddress'>
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