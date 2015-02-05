<?php
// "get the pdo table prefix of root and plugins."
$path = 'plugins/';

if(!is_dir($path))
	$path = "../../" . $path;
	
$results = scandir($path);

foreach ($results as $result) {
    if ($result === '.' or $result === '..') continue;

    if (is_dir($path . '/' . $result . "/includes/main")) {
        //code to use if directory
        require $path . '/' . $result . '/includes/main/pdoTablePrefix.php';
	//echo $result . "<br>";
    }
}

require $rootPath . 'includes/main/pdoTablePrefix.php';
?>