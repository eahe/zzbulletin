<?php 
require "includes/main/header.php";
require 'includes/buttonsBulletin.php';
require 'includes/notices.php';

if(isset($_COOKIE['username'])){
	header("location: {$pluginUrl}index");
	exit;
}

echo "<script type='text/javascript' src='". $pluginUrl  . "jquery/jquery.validate.min.js'></script>";
?>

<script type="text/javascript">
	$().ready(function() {
	
			// "this is an input validator which sets the rules for the form. 
			// "rules such as required email address and required lenght."
	
			// "the name #signupForm2 must match that of the form id."
			$("#signupForm2").validate({
					rules: {
						yourGender: {
							required: true,
						},
						username: {
							required: true,
							minlength: 4
						},
						password: {
							required: true,
							minlength: 5
						},
						confirmPassword: {
							required: true,
							minlength: 5,
							equalTo: "#password"
						},
						securityQuestion: {
							required: true,
							minlength: 3
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
						yourGender: {
							required: "<?php echo "<br>Your gender is required."; ?>"
						},
						username: {
							required: "<?php echo "<br>Enter a username."; ?>",
							minlength: "<?php echo "<br>Username is too small."; ?>"
						},
						password: {
							required: "<?php echo "<br>Provide a password."; ?>",
							minlength: "<?php echo "<br>Password is too small."; ?>"
						},
						confirmPassword: {
							required: "<?php echo "<br>Provide a password."; ?>",
							minlength: "<?php echo "<br>Password is too small."; ?>",
							equalTo: "<?php echo "<br>Passwords do not match."; ?>"
						},
						securityQuestion: {
							required: "<?php echo "<br>Security question is required."; ?>",
							minlength: "<?php echo "<br>Security question is too small."; ?>"
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
<!-- id='signupForm2' must match that of the validator -->
<form id='signupForm2' method='POST' action='userMainSetup.php'>


	<table class='table4' id='right' style="width: 100%; height: 100%; border: solid 2px #000000;">
		<tr>
			<th id='left'>Terms of use</th>
			<th id='left' colspan='2'>Register</th>
		</tr>
		<tr>
			<td style="height: 100%; width:50%;" rowspan='8'><?php echo '<textarea readonly>';
				require 'includes/privacyPolicy.php';
				echo "</textarea>"; ?></td>
		</tr>
		<tr>
			<!-- "provide gender." -->
			<td colspan='3' id='center'>Your gender: <input type='radio'
				class='radio' name='yourGender' value='m' <?php if(isset($_SESSION['yourGender']) && $_SESSION['yourGender'] == 'm') echo 'checked' ?> />Male. <input type='radio'
				class='radio' name='yourGender' value='f' <?php if(isset($_SESSION['yourGender']) && $_SESSION['yourGender'] == 'f') echo 'checked' ?> />Female. <label for="yourGender"
					class="error"><br></label>
			</td>
		</tr>
		<tr>
			<!-- "provide username." -->
			<td style='width:25%' id='right'><input type='hidden' name='back' value='register.php'>
				<input type='hidden' name='op' value='new'> <label for='username'><?php echo "Username."; ?>
				</label>
			</td>
			<td ><input style='width:95%' id='username' name="username" type='text' value='<?php if(isset($_SESSION['registerUsername'])) echo $_SESSION['registerUsername']; ?>'>
			</td>
		</tr>
		<tr>
			<!-- "provide password." -->
			<td id='right'><label for='password'><?php echo "Password.";?></label>
			</td>
			<td><input style='width:95%' id='password' name='password' type='password' value='<?php if(isset($_SESSION['password'])) echo $_SESSION['password']; ?>'>
			</td>
		</tr>
		<tr>
			<!-- "provide password again." -->
			<td id='right'><label for='confirmPassword'><?php echo "Confirm password.";?>
				</label>
			</td>
			<td><input style='width:95%' id='confirmPassword' name='confirmPassword'
				type='password' value='<?php if(isset($_SESSION['password'])) echo $_SESSION['password']; unset($_SESSION['password']); ?>'>
			</td>
		</tr>
		<tr>
			<!-- "provide security question." -->
			<td id='right'><label for='securityQuestion'><?php echo "Security question."; echo "<a class='confirm2' href='' onmouseover='title=\"\"' title='If you have forgotten your password, this security question is used to generate a new password.'><img class='middle' src='" . $pluginUrl . $imagesDirectory . "question.png' alt='Help'></a></p>"; ?>
				</label>
			</td>
			<td><input style='width:95%' id='securityQuestion' name='securityQuestion'
				type='text' value='<?php if(isset($_SESSION['securityQuestion'])) echo $_SESSION['securityQuestion']; unset($_SESSION['securityQuestion']); ?>'>
			</td>
		</tr>
		<tr>
			<!-- "provide email address." -->
			<td id='right'><label for='emailAddress'><?php echo "Email address."; ?>
				</label>
			</td>
			<td><input style='width:95%' id='emailAddress' name='emailAddress' type='text' value='<?php if(isset($_SESSION['emailAddress'])) echo $_SESSION['emailAddress']; ?>'>
			</td>
		</tr>
		<tr>
			<!-- "provide email address again." -->
			<td id='right'><label for='confirmEmailAddress'><?php echo "Confirm email address."; ?>
				</label>
			</td>
			<td><input style='width:95%' type='text' id='confirmEmailAddress'
				name='confirmEmailAddress' value='<?php if(isset($_SESSION['emailAddress'])) echo $_SESSION['emailAddress']; unset($_SESSION['emailAddress']); ?>'>
			</td>
		</tr>
		<tr>
			<!-- "terms of service." -->
			<td colspan='3' id='center'><label for='agree'><?php echo "Please agree to the terms of use."; ?>
				</label> <input type='radio' class='radio' name='agree' value='1'
				checked />Yes <input type='radio' class='radio' name='agree'
				value='0' />No</td>
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