<?php
	// "menu 'configuration' starts here"
	if(isset($_COOKIE['username']) && $username != 'guest'){

		echo "<ul class='filetree browser'>";
		// "if you want the menu closed by default then use this..."
		echo "<li class='closed'><li>";

		echo "<table class='table2' id='left'><tr><th>Configuration.</th></tr><tr><td>";	

		echo "<li><span class='folder'><a href='#'>Control panel</a></span><ul>";
		
		if($permission == 4 && $username == 'admin')
			echo "<ul><li><span class='file'><a href='{$rootUrl}install/plugins/index.php'><p>Plugins configuration.</p></a></span></li></ul>";
		if($permission == 4 && $username == 'admin')
			echo "<ul><li><span class='file'><a href='{$rootUrl}websiteConfiguration.php'><p>Website configuration.</p></a></span></li></ul>";
	
				
	}
	
	echo "</ul>";
	echo "</td></tr></table>";		
	?>



