<?php
require "../../includes/main/header.php";
require '../../includes/buttonsBulletin.php';

if($permission < 4 || $username != 'admin'){
	header("location: ../../index.php");
	exit;
}

echo "<form method='POST' action='installPlugins.php'>";

$path = '../../plugins/';
$results = scandir($path);

$plugins = array();

$i = 0;
?>
	<table class='table3'><tr>
	<th>Install plugins.</th><th>Use at homepage.</th>
	</tr>
	<tr><td colspan='2'><?php echo "The following plugins will be installed...<br>"; ?></td></tr>
<?php
foreach ($results as $result) {
	if ($result === '.' or $result === '..') continue;

	if (is_dir($path . '/' . $result)) {
	//code to use if directory
	$plugins[$i] = $result;
	echo '<tr>';
	echo '<td><input type="checkbox" name="plugins[]" value="' . $result . '">' . $plugins[$i] . '</td>';
	echo '<td><input type="radio" name="homepage" value="' . $result . '"></td>';
	echo '</tr>';	
	$i++;
    }
}

if(isset($i) && $i != 0){
	echo "<tr><td colspan='2' style='text-align: center;'>";
	require "../../includes/maintenanceModeCheck.php";
	echo "</td></tr></table>";
} else echo "Nothing.";

echo "</form>";
echo "<br>";

// "uninstall plugins."
echo "<form method='POST' action='uninstallPlugins.php'>";

$path = '../../plugins/';
$results = scandir($path);

$plugins = array();
$remove = array();

$i = 0;
?>
	<table class='table3'><tr>
	<th colspan='2'>Uninstall plugins.</th>
	</tr>
	<tr>
	<td colspan='2'>
	<?php echo "The following plugins will be uninstalled...<br>"; ?></td></tr>
<?php
foreach ($results as $result) {
	if ($result === '.' or $result === '..') continue;

	if (is_dir($path . '/' . $result)) {
	//code to use if directory
	$plugins[$i] = $result;
	echo '<tr>';
	echo '<td colspan="2"><input type="checkbox" name="plugins[]" value="' . $result . '">' . $plugins[$i] . '</td>';
	// echo '<td><input type="checkbox" name="remove[]" value="' . $result . '"></td>';
	echo '</tr>';	
	$i++;
    }
}

if(isset($i) && $i != 0){
	echo "<tr><td colspan='2' style='text-align: center;'>";
	
	if($maintenanceMode == "n" || $permission == 4)
	echo "<button class='btn btn-danger confirm' name='name' onmouseover='title=\"\"' title='Are you sure you want to delete the selected plugins?' type='submit'>Submit</button>";
	else echo "<button class='btn btn-danger' name='name' type='submit' disabled>Submit</button>";
	
	echo "</td></tr></table>";
} else echo "Nothing.";

echo "</form>";

?>