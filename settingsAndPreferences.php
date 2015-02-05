<?php
require "includes/main/header.php";
require 'includes/main/tinymce.php';

// "from buttonsBulletin.php variable $forumNew5."
if(isset($_SESSION['getF']))
	$forumNew5 = $_SESSION['getF'];
// "from buttonsBulletin.php variable $c[$i]."
if(isset($_SESSION['getC']))
	$c = $_SESSION['getC'];
// "from buttonsBulletin.php variable $p."
if(isset($_SESSION['getP']))
	$p = $_SESSION['getP'];

if(!isset($_COOKIE['username'])){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/buttonsBulletin.php';
?>
<!-- "Bootstrap styles." -->
<link rel="stylesheet" href="css/bootstrap.css">
<link	rel="stylesheet" href="css/jqueryFileUpload.css">

<?php
require 'includes/notices.php';
?>

<script>
	// "resize image."
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#file')
				.attr('src', e.target.result)
				.width(<?php echo $imageWidth;?>)
				.height(<?php echo $imageHeight;?>);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>

<?php
// "birthday variables."
$birthdayDay = NULL; $birthdayMonthWord = NULL;
$birthdayYear = 1900;

?>

<!-- "prepare the form for uploading of images." -->
<form enctype="multipart/form-data" action="settingsAndPreferences2.php"
	method="POST" id="MyUploadForm">
	<table class='table6'>
		<tr>
			<th colspan='2'>Settings and preferences.</th>
		</tr>
		<tr>
			<td>Select an image to upload as your avatar and then click the
				submit button to save your image as <?php if(isset($imageWidth)) echo $imageWidth; ?>x<?php if(isset($imageHeight)) echo $imageHeight; ?>
				pixels. <br> <br> gif's and png's will not lose animation at any
				dimension and will preserve image transparency.
			</td>

			<!-- "display the avatar." -->
			<td width='50%' id='center'><?php 

			if(isset($avatar) && strlen($avatar) > 10){
					echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $avatarsUploadDirectory . $avatar . '" ></center>';
				} else{
					if($yourGender == 'm')
						echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $avatarsLocalDirectory . 'male/' . $avatar . '" ></center>';
					else echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $avatarsLocalDirectory . 'female/' . $avatar . '" ></center>';
				}
				if($useAvatars != 2)
					echo "<label for='file'></label> <span class='btn btn-primary fileinput-button'> <i class='fa avatar fa-lg'></i> <span>Upload avatar.</span> <input id='input2' type='file' name='file' onchange='readURL(this);' /> </span>";
				?>
			</td>
		</tr>
		<tr>
			<!-- "birthday." -->
			<td>Select your age. This bulletin will remind you when your birthday
				is near or at that day.</td>
			<td><?php 
			$i = 0;
			// "birthday day."
			echo 'Day ';
			echo "<select name='birthdayDay'>";
			echo '<option></option>';

			while($birthdayDay < 31){
					$birthdayDay++;
					if($birthdayDay2 == $birthdayDay)
						echo "<option value='" . $birthdayDay2 . "' selected />". $birthdayDay2;
					else echo "<option value='" . $birthdayDay . "'/>". $birthdayDay;
				}
				echo "</select>";

				// "Birthday month."
				$ii = -1;
				echo ' Month ';
				echo "<select class='font1' name='birthdayMonthWord'>";
				$birthdayMonthWord = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
				echo '<option></option>';

				while($ii < 11){
					$ii = $ii + 1;
					if($birthdayMonthWord2 == $birthdayMonthWord[$ii]){
						echo "<option value='" . $birthdayMonthWord2 . "' selected />". $birthdayMonthWord2;
					} else echo "<option value='" .$birthdayMonthWord[$ii]. "' />" . $birthdayMonthWord[$ii];
				}
				echo "</select>";

				// "birthday year."
				echo ' Year ';
				$birthdayYearCurrent = date('Y') - 5;
				$birthdayYearStart = $birthdayYearCurrent - 125;
				echo "<select name='birthdayYear' >";
				echo '<option></option>';
				// "in the dropdown list, display the birthday years and starting from"
				// "$birthdayYearCurrent and going backwards to $birthdayYearStart."
				while($birthdayYearStart < $birthdayYearCurrent){
					$birthdayYearCurrent--;
					if($birthdayYear2 == $birthdayYearCurrent)
						echo "<option value='" . $birthdayYear2 . "' selected />". $birthdayYear2;
					echo "<option value='" . $birthdayYearCurrent . "' />" . $birthdayYearCurrent;
				}
				echo "</select>";
				?></td>
		</tr>
		<tr>

			<!-- "website." -->
			<td>Website address, including "http://www." without quotes.</td>
			<td width='50%'><input id='website' name='website' type='text'
				value='<?php if(isset($website)) if($website != '') echo $website; ?>'>
			</td>
		</tr>
		<tr>

			<!-- "country." -->
			<td>The country where you live in.</td>
			<td><input id='country' name='country' type='text'
				value='<?php if(isset($country)) echo $country; ?>'>
		
			</td>		
		</tr>
		<tr><!-- "bootstrap buttons display." -->
			<td>Would you like to display the bulletin buttons instead of text hyperlinks?</td>
			<td><input type='radio' class='radio' name='bootstrapButtonsDisplay' value='y'
				<?php if(isset($bootstrapButtonsDisplay)) if($bootstrapButtonsDisplay =='y') echo 'checked';?>>yes. <input
				type='radio' class='radio' name='bootstrapButtonsDisplay' value='n'
				<?php if(isset($bootstrapButtonsDisplay)) if($bootstrapButtonsDisplay =='n') echo 'checked';?>>no.</td>
		</tr>
		<tr>
			<!-- "Adjacent pages." -->
			<td><label><?php echo "Number of adjacent pages that are shown at the middle of pagination."; ?>
				</label>
			</td>
			<td><?php 
				$ii = 2;
				echo "<select name='adjacents'>";
			
				while($ii < 7){
					$ii = $ii + 1;
					if($adjacents == $ii)
					echo '<option value=' . $adjacents . " selected />". $adjacents;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?></td>
		</tr>
		<tr>
			<!-- "Number of local avatars on page." -->
			<td><label><?php echo "Number of local avatars shown on page at the same time."; ?>
				</label>
			</td>
			<td><?php 
				$ii = 4;
				echo "<select name='limit3'>";			

				while($ii < 80){
					$ii = $ii + 4;
					if($limit3 == $ii)
					echo '<option value=' . $limit3 . " selected />". $limit3;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>		
		<tr>
			<!-- "Number of search results on page." -->
			<td><label><?php echo "Number of search results shown on page at the same time."; ?>
				</label>
			</td>
			<td><?php 
				$ii = 10;
				echo "<select name='limit4'>";			

				while($ii < 80){
					$ii = $ii + 10;
					if($limit4 == $ii)
					echo '<option value=' . $limit4 . " selected />". $limit4;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>
		</tr>	
	</table>
	<br>
	<table class='table2'><tr>
				<!-- "post signature." -->
			<td id='center' colspan='2'>Your signature: The text displayed at the bottom of your post.</td>
			</tr><tr><td id='center' colspan='2'><textarea id='3' name='postSignature'>
				<?php if(isset($postSignature)) echo $postSignature; ?>
			</textarea>
		<tr>
			<td id='center' colspan='2'><?php include "includes/maintenanceModeCheck.php"; ?>
			</td>
		</tr>
	</table>
</form>

<?php 

require 'includes/main/footer.php';

?>