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
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if($permission < 4){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/notices.php';
?>

<script	type="text/javascript" src="jquery/jquery.validate.min.js"></script>

<script type="text/javascript">
	$().ready(function() {
	
			// "this is an input validator which sets the rules for the form," 
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
						// "display an error if rules are broken."
						WebsiteEmailAddress: "<?php echo "<br>Provide an email address"; ?>"
					}
				});

		});
</script>

<form id='settings' method='POST' action='websiteConfiguration2.php'>
	<table class='table6'>
		<tr>
			<th colspan='2'>Bulletin configuration.</th>
		</tr>
		<tr>
			<!-- "Website name." -->
			<td><label><?php echo "What is the name of this website?"; ?></label>
			</td>
			<td><input name='siteName' type='text'
				value='<?php echo $siteName; ?>'>
			</td>
		</tr>
		<tr>
			<!-- "Website email address." -->
			<td><label for='WebsiteEmailAddress'><?php echo "Website email address. All emails will be sent from this email address. Outgoing emails must be enabled."; ?></label>
			</td>
			<td><input name='WebsiteEmailAddress' type='text'
				value='<?php echo $WebsiteEmailAddress; ?>'>
			</td>
		</tr>
		<tr>
			<td>Maximum characters for post signature.</td>
			<td width='50%'><?php 
				$ii = 45;
				echo "<select name='postSignatureCharacterLimit'>";
			
				while($ii < 120){
					$ii = $ii + 5;
					if($postSignatureCharacterLimit == $ii)
					echo '<option value=' . $postSignatureCharacterLimit . " selected >". $postSignatureCharacterLimit;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>		
		</tr>
		<tr>
			<td>Number of male avatars in images/avatars/local/male/ directory. Maximum avatars is 2000.</td>
			<td width='50%'><?php 
				$ii = 0;
				echo "<select name='numberOfLocalMaleAvatars'>";
			
				while($ii < 2000){
					$ii = $ii + 1;
					if($numberOfLocalMaleAvatars == $ii)
					echo '<option value=' . $numberOfLocalMaleAvatars . " selected >". $numberOfLocalMaleAvatars;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>		
		</tr>
		<tr>
			<td>Number of female avatars in images/avatars/local/female/ directory. Maximum avatars is 2000.</td>
			<td width='50%'><?php 
				$ii = 0;
				echo "<select name='numberOfLocalFemaleAvatars'>";
			
				while($ii < 2000){
					$ii = $ii + 1;
					if($numberOfLocalFemaleAvatars == $ii)
					echo '<option value=' . $numberOfLocalFemaleAvatars . " selected >". $numberOfLocalFemaleAvatars;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>		
		</tr>
		<tr>
			<!-- at threadViewAllFlat, Number of titles to display at the same time are truncated at this value. -->
			<td><label><?php echo "Maximum characters for topic titles and category titles are truncated at this value."; ?></label>
			</td>
			<td>
				<?php 
				$ii = 45;
				echo "<select name='titleCharacterLimit'>";
			
				while($ii < 120){
					$ii = $ii + 5;
					if($titleCharacterLimit == $ii)
					echo '<option value=' . $titleCharacterLimit . " selected >". $titleCharacterLimit;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<!-- "enable text announcement." -->
		<tr>
			<td>Enable the text announcement?</td>
			<td><input type='radio' class='radio' name='enableTextAnnouncement' value='y'
				<?php if(isset($enableTextAnnouncement)) if($enableTextAnnouncement =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='enableTextAnnouncement' value='n'
				<?php if(isset($enableTextAnnouncement)) if($enableTextAnnouncement =='n') echo 'checked';?>>No.</td>
		</tr>			
		<tr>
			<!-- "display text announcement." -->
			<td>Your text announcement displayed near the top of the page. HTML allowed.</td>
			<td><textarea style='height:100px;' name='textAnnouncement'><?php if(isset($textAnnouncement)) echo $textAnnouncement; ?></textarea>
			</td>
		</tr>
		<tr>
			<!-- "maintenance mode." -->
			<td><?php echo "Enable maintenance mode? All save features will be disabled. Note: maintenance mode will override the text announcement feature. "?>
			</td>
			<td>
				<input type='radio'
				class='radio' name='maintenanceMode' value='y' <?php if($maintenanceMode == "y") echo "checked"; ?> />Yes, but full website access.<br> 
				<input type='radio'
				class='radio' name='maintenanceMode' value='l' <?php if($maintenanceMode == "l") echo "checked"; ?> />Yes. Limit everyone but the admin to the front door. 
				<input type='radio'
				class='radio' name='maintenanceMode' value='n' <?php if($maintenanceMode == "n") echo "checked"; ?> />No. 
			</td>
		</tr>
		<tr>
			<!-- "maintenance mode text." -->
			<td>Maintenance mode text. HTML allowed.</td>
			<td><textarea style='height:100px;' name='maintenanceModeText'><?php if(isset($maintenanceModeText)) echo $maintenanceModeText; ?></textarea>
			</td>
		</tr>	
				<!-- "enable banner advertisement." -->
		<tr>
			<td>Enable banner advertisement?</td>
			<td><input type='radio' class='radio' name='bannerAdvertisement' value='y'
				<?php if(isset($bannerAdvertisement)) if($bannerAdvertisement =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='bannerAdvertisement' value='n'
				<?php if(isset($bannerAdvertisement)) if($bannerAdvertisement =='n') echo 'checked';?>>No.</td>
		</tr>			
		<tr>
			<!-- "display banner advertisement." -->
			<td>Your banner advertisement code to be displayed near the top of the page. HTML allowed.</td>
			<td><textarea style='height:100px;' name='bannerAdvertisementCode'><?php if(isset($bannerAdvertisementCode)) echo $bannerAdvertisementCode; ?></textarea>
			</td>
		</tr>	
		<tr>
			<!-- "aboutbox." -->
			<td><?php echo "Should the aboutbox be enabled?"?>
			</td>
			<td>
				<input type='radio'
				class='radio' name='aboutBox' value='y' <?php if($aboutBox == "y") echo "checked"; ?> />Yes.
				<input type='radio'
				class='radio' name='aboutBox' value='n' <?php if($aboutBox == "n") echo "checked"; ?> />No. 
			</td>
		</tr>
		<tr>
			<!-- "aboutbox text." -->
			<td><label><?php echo "About box text. html allowed."; ?></label>
			</td>
			<td><textarea style='height:100px;' name='aboutBoxText'><?php if(isset($aboutBoxText)) echo $aboutBoxText; ?></textarea>
			</td>
		</tr>
		<!-- "enable block advertisment." -->
		<tr>
			<td>Enable block advertisement?</td>
			<td><input type='radio' class='radio' name='blockAdvertisement' value='y'
				<?php if(isset($blockAdvertisement)) if($blockAdvertisement =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='blockAdvertisement' value='n'
				<?php if(isset($blockAdvertisement)) if($blockAdvertisement =='n') echo 'checked';?>>No.</td>
		</tr>			
		<tr>
			<!-- "display block advertisment." -->
			<td>Block advertisement to be displayed at the side panel. HTML allowed.</td>
			<td><textarea style='height:100px;' name='blockAdvertisementCode'><?php if(isset($blockAdvertisementCode)) echo $blockAdvertisementCode; ?></textarea>
			</td>
		</tr>	
		<tr>
			<!-- "outgoing emails." -->
			<td><?php echo "Should outgoing emails, including emails for registration and forgot password be enabled? "?>
			</td>
			<td>
				<input type='radio'
				class='radio' name='outgoingEmails' value='y' <?php if($outgoingEmails == "y") echo "checked"; ?> />Yes.
				<input type='radio'
				class='radio' name='outgoingEmails' value='n' <?php if($outgoingEmails == "n") echo "checked"; ?> />No. 
			</td>
		</tr>
		<tr>
			<td>Use avatars.</td>
			<td width='50%'>
				<select name='useAvatars'>
					<option value='1' <?php if($useAvatars == 1) echo "selected"; ?>> Use both local and upload avatars.
					<option value='2' <?php if($useAvatars == 2) echo "selected"; ?>> Use only local avatars.
					<option value='3' <?php if($useAvatars == 3) echo "selected"; ?>> Use only uploaded avatars.				
				</select>
			</td>		
		</tr>
		<tr>
			<!-- "Avatars width." -->
			<td><label><?php echo "All avatars width will be resized to this width."; ?></label>
			</td>
			<td>
				<?php 
				$ii = 54;
				echo "<select name='imageWidth'>";
			
				while($ii < 160){
					$ii = $ii + 1;
					if($imageWidth == $ii)
					echo '<option value=' . $imageWidth . " selected >". $imageWidth;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>	
		<tr>
			<!-- "Avatars height." -->
			<td><label><?php echo "All avatars height will be resized to this height."; ?></label>
			</td>
			<td>
				<?php 
				$ii = 54;
				echo "<select name='imageHeight'>";
			
				while($ii < 180){
					$ii = $ii + 1;
					if($imageHeight == $ii)
					echo '<option value=' . $imageHeight . " selected >". $imageHeight;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>	
		<tr>
			<!-- maximum image upload size in bytes -->
			<td><label><?php echo "maximum image upload size in Kilobytes."; ?></label>
			</td>
			<td>
				<?php 
				$ii = 0;
				echo "<select name='maximumImageUploadSize'>";
			
				while($ii < 1024){
					$ii = $ii + 1;
					if($maximumImageUploadSize == $ii)
					echo '<option value=' . $maximumImageUploadSize . " selected >". $maximumImageUploadSize;
					else echo '<option value=' . $ii . ">". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>	
		<tr>
			<!-- maximum file attachment upload size in megabytes -->
			<td><label><?php echo "maximum file attachment upload size in megabytes."; ?></label>
			</td>
			<td>
				<?php 
				$ii = 0;
				echo "<select name='maximumAttachmentUploadSize'>";
			
				while($ii < 1024){
					$ii = $ii + 1;
					if($maximumAttachmentUploadSize == $ii)
					echo '<option value=' . $maximumAttachmentUploadSize . " selected >". $maximumAttachmentUploadSize;
					else echo '<option value=' . $ii . ">". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>	
		<tr>
			<!-- Username characters -->
			<td><label><?php echo "What is the maximum characters for a username?"; ?></label>
			</td>
			<td>
				<?php 
				$ii = 13;
				echo "<select name='usernameCharacters'>";
			
				while($ii < 30){
					$ii = $ii + 1;
					if($usernameCharacters == $ii)
					echo '<option value=' . $usernameCharacters . " selected >". $usernameCharacters;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>	
		<tr>
			<td id='center' colspan='2'>
				<?php include "includes/maintenanceModeCheck.php"; ?>				
			</td>
		</tr>
	</table>
</form>

<?php 
require 'includes/main/footer.php';

?>