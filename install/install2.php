<?php 
session_start();

$_SESSION['dbHost'] = $_POST['dbHost'];
if($_SESSION['dbHost'] == ""){
	echo "Database host cannot be empty.";
	echo "<br>";
	$databaseFields = 1;
}

$_SESSION['dbPort'] = $_POST['dbPort'];
if($_SESSION['dbPort'] == ""){
	echo "Database port cannot be empty.";
	echo "<br>";
	$databaseFields = 1;
}

$_SESSION['dbUsername'] = $_POST['dbUsername'];
if($_SESSION['dbUsername'] == ""){
	echo "Database username cannot be empty.";
	echo "<br>";
	$databaseFields = 1;
}
$_SESSION['dbPassword'] = $_POST['dbPassword'];
if($_SESSION['dbPassword'] == ""){
	echo "Database password cannot be empty.";
	echo "<br>";
	$databaseFields = 1;
}

$_SESSION['dbName'] = $_POST['dbName'];
if($_SESSION['dbName'] == ""){
	echo "Database name cannot be empty.";
	echo "<br>";
	$databaseFields = 1;
}

$dbHost = $_SESSION['dbHost'];
$dbPort = $_SESSION['dbPort'];
$dbUsername = $_SESSION['dbUsername'];
$dbPassword = $_SESSION['dbPassword'];
$dbName = $_SESSION['dbName'];

if(isset($databaseFields)){
	echo "<a href='install1.php'>Click here to try again.</a>";
	exit;
}

require "../includes/main/database.php";

$_SESSION['createDatabase'] = 1;
require "../sql/install/root.php";
unset($_SESSION['createDatabase']);
?>
<form method='POST' action='install3.php'>
	<table>
		<tr>
			<td>
				<button class="btn btn-danger" name="name" type="submit">Proceed to step 3</button>
			</td>
		</tr>
	</table>
</form>
