<!-- "some links will display when the cookie is not set, while others"
"will display when the cookie is set." -->
<ul class="filetree browser">
	<!-- "if you want the menu closed by default then use this..."
	"<li class="closed">" -->
	<li><span class="folder"> <?php 
		// "menu 'account help' starts here."
		echo "<a href='#'><i></i>Account</a>"; ?>
	</span> <?php 
	if(!isset($_COOKIE['username']))
	echo "<ul><li><span class='file'><a href='{$rootUrl}forgotUsername.php'><p>Forget username.</p></a></span></li></ul>";

	if(isset($_COOKIE['username']))
	echo "<ul><li><span class='file'><a href='{$rootUrl}changePassword.php'><p>Change password.</p></a></span></li></ul>";

	if(!isset($_COOKIE['username']) && $outgoingEmails == "y" )
	echo "<ul><li><span class='file'><a href='{$rootUrl}forgotPassword.php'><p>Forget password.</p></a></span></li></ul>";

	if(isset($_COOKIE['username']))
	echo "<ul><li><span class='file'><a href='{$rootUrl}changeEmailAddress.php'><p>Change email address.</p></a></span></li></ul>";

	if(!isset($_COOKIE['username']))
	if($outgoingEmails == "y")
	echo "<ul><li><span class='file'><a href='{$rootUrl}emailAddressValidateSend.php'><p>Email address Validate.</p></a></span></li></ul>";

	if(isset($_COOKIE['username']))
	echo "<ul><li><span class='file'><a href='{$rootUrl}forgotEmailAddress.php'><p>Forgot email address.</p></a></span></li></ul>";

	echo "<ul><li><span class='file'><a href='{$pluginUrl}yourPermissions.php'><p>Your permissions.</p></a></span></li></ul>";
	
	?>

</ul>

