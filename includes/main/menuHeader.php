<?php
// "menu 'configuration' starts here"
try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$root}plugin_install");
	$stmt->execute();
	$pluginCount = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

if(isset($pluginCount) && $pluginCount != NULL) {
?>
	<ul class="filetree browser">
	<!-- "if you want the menu closed by default then use this..."
	"<li class="closed">" -->
	<li>
	<?php
	echo "<span class='folder'><a href='#'>Plugins</a></span>";

	$path = 'plugins/';

	if(file_exists($path)){
		$results = scandir($path);
	}
	else {
		$path = "../../plugins/";
	$results = scandir($path);
	}

	foreach ($results as $result) {
		if ($result === '.' or $result === '..') continue;

		if (is_dir($path . '/' . $result)) {

		try {
			$stmt = $dbh->prepare("SELECT * FROM {$root}plugin_install WHERE plugin=:result");
			$stmt->bindParam(':result', $result);
			$stmt->execute();
			$row = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		// "determine if a plugin should be at homepage."
		if($row['plugin'] == $result)
			echo "<ul><li><span class='file'><a href='{$rootUrl}plugins/{$result}'><p>$result</p></a></span></li></ul>";	
		}
	}
}			
	?>

</ul>

