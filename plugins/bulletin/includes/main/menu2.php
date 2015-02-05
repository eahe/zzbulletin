<ul class="filetree browser">
	<!-- "if you want the menu closed by default then use this..."
	"<li class="closed">" -->
	<li>
<?php
	// "menu 'configuration' starts here"
	if(isset($_COOKIE['username']) && $username != 'guest'){
		echo "<li><span class='folder'><a href='#'>Configuration</a></span><ul>";
		
		echo "<li><span class='file'><a href='{$pluginUrl}yourPreferences.php'><p>Your preferences.</p></a></span></li></ul>";

	}
			
	if(isset($_COOKIE['username']) AND $username == 'admin'){
		echo "<ul><li><span class='file'><a href='{$pluginUrl}bulletinConfiguration.php'><p>Bulletin configuration.</p></a></span></li></ul>";

		echo "<ul><li><span class='file'><a href='{$pluginUrl}permissionsBulletin.php'><p>Bulletin permissions.</p></a></span></li></ul>";

	}
	?>

</ul>

