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

<form id='settings' method='POST' action='bulletinConfiguration2.php'>
	<table class='table6'>
		<tr>
			<th colspan='2'>Bulletin configuration.</th>
		</tr>
		<tr>
			<td>How many posts for a thread to be marked as hot.</td>
			<td width='50%'><?php 
				$ii = 5;
				echo "<select name='hotTopic'>";
			
				while($ii < 50){
					$ii = $ii + 5;
					if($hotTopic == $ii)
					echo '<option value=' . $hotTopic . " selected >". $hotTopic;
					else echo '<option value=' . $ii . "/>". $ii;
				}
				echo "</select>";
				?>
			</td>		
		</tr>
		<tr>
			<td>Delete only your last post? Selecting "no" will give you permission to delete any of your posts.</td>
			<td><input type='radio' class='radio' name='deleteOnlyLastPost' value='y'
				<?php if(isset($deleteOnlyLastPost)) if($deleteOnlyLastPost =='y') echo 'checked';?>>Yes. <input
				type='radio' class='radio' name='deleteOnlyLastPost' value='n'
				<?php if(isset($deleteOnlyLastPost)) if($deleteOnlyLastPost =='n') echo 'checked';?>>No.</td>
		</tr>		
		
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