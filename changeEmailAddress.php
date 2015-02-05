<?php
require "includes/main/header.php";

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
	$_SESSION['cookieCheck2'] = basename($_SERVER['PHP_SELF']);
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/notices.php';
?>

<script	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	$().ready(function() {

			// "this is an input validator which sets the rules for the form. 
			// "rules such as required email address and required lenght."
	
			// "the name #changeEmailAddress must match that of the form id."
			$("#changeEmailAddress").validate({
					rules: {
						currentEmailAddress: {
							required: true,
							email: true
						},
						newEmailAddress: {
							required: true,
							email: true
						},
						confirmNewEmailAddress: {
							required: true,
							equalTo: "#newEmailAddress"
						}				
					},
					messages: {
						// "display an error if rules are broken"
						currentEmailAddress: "<?php echo "<br>Not valid email address."; ?>",
						newEmailAddress: "<?php echo "<br>Not valid email address."; ?>",
						confirmNewEmailAddress: {
							required: "<?php echo "<br>Not valid email address."; ?>",
							equalTo: "<?php echo "<br>No match for new email address."; ?>"
						}
					}
				});

		});
</script>

<!-- "id='changeEmailAddress' must match that of the validator." -->
<form id='changeEmailAddress' action="changeEmailAddress2.php" method="POST">

	<table class='table4' id='right'>
		<tr>
			<th id='left'>Note.</th>
			<th id='left' colspan='2'>Change email address.</th>
		</tr>
		<tr style="height: 100%; width:50%;">
			<td style="height: 100%" width='50%' rowspan='4'><?php echo "<textarea readonly>";
				require 'includes/changeEmailAddressText.php';
				echo "</textarea>"; ?></td>
		</tr>
		<tr>
			<!-- "provide current email address." -->
			<td style='width:25%;'><label for='currentEmailAddress'><?php echo "Current email address.";?></label>
			</td>
			<td style='text-align:center;'><input style='width:95%;' id='currentEmailAddress' name='currentEmailAddress' type='text'>
			</td>
		</tr>
		<tr>
				
			<!-- "provide new email address." -->
			<td><label for='newEmailAddress'><?php echo "New email address.";?>
				</label>
			</td>
			<td style='text-align:center;'><input style='width:95%;' id='newEmailAddress' name='newEmailAddress'
				type='text'>
			</td>
		</tr>
		<tr>
					
			<!-- "provide new email address again." -->
			<td><label for='confirmNewEmailAddress'><?php echo "Confirm new email address.";?>
				</label>
			</td>
			<td style='text-align:center;'><input style='width:95%;' id='confirmNewEmailAddress' name='confirmNewEmailAddress'
				type='text'>
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