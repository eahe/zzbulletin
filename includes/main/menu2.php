<ul class="filetree browser">
	<!-- "if you want the menu closed by default then use this..."
	"<li class="closed">" -->
	<li>
<?php
	// "menu 'configuration' starts here"
	if(isset($_COOKIE['username']) && $username != 'guest'){
		echo "<li><span class='folder'><a href='#'>Configuration</a></span><ul>";

		echo "<li><span class='file'><a href='{$rootUrl}settingsAndPreferences.php'><p>Setting and preferences.</p></a></span></li></ul>";

		if($useAvatars != '3')
		echo "<ul><li><span class='file'><a href='{$rootUrl}avatarsLocal.php'><p>Avatars local.</p></a></span></li></ul>";
			
	}
			
	?>

</ul>

