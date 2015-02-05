<?php 
session_start();
ini_set('display_errors', 0);

if(isset($_POST['pluginUrl'])){
	$_SESSION['pluginUrl'] = $_POST['pluginUrl'];
	if($_SESSION['pluginUrl'] == ""){
		echo "Base url cannot be empty.";
		echo "<br>";
		$configuration = 1;
	}
}

$fileName = $_SESSION['pluginUrl'] . "index.php";
$file_headers = @get_headers($fileName);
if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
	echo "The website url does not exist.";
	echo "<br>";
	$configuration = 1;
}

if(isset($_POST['rootPath'])){
	$_SESSION['rootPath'] = $_POST['rootPath'];
	if($_SESSION['rootPath'] == ""){
		echo "Server path cannot be empty.";
		echo "<br>";
		$configuration = 1;
	}
}

$fileName = $_SESSION['rootPath'] . "index.php";
if(!file_exists($fileName)){
	echo "The website path does not exist.";
	echo "<br>";
	$configuration = 1;
}

$url = $_SESSION['pluginUrl'];
if(!@file_get_contents($url)){
	echo 'The base url is incorrect.';
	echo "<br>";
	$configuration = 1;
}

if(isset($configuration)){
	echo "<a href='install3.php'>Click here to try again.</a>";
	exit;
}

$fileName = "../configuration/root.php";
$string =
"<?php
// db = Database. These are the variables to make a database connection.\n";
		
file_put_contents($fileName, $string);		
		
$string	= 
"$" . "dbHost = " . "\"" . $_SESSION['dbHost'] . "\"" . ";\n" . 
"$" . "dbPort = " . "\"" . $_SESSION['dbPort'] . "\"" . ";\n" . 
"$" . "dbName = " . "\"" . $_SESSION['dbName'] . "\"" . ";\n" .
"$" . "dbUsername = " . "\"" . $_SESSION['dbUsername'] . "\"" . ";\n" . 
"$" . "dbPassword = " . "\"" . $_SESSION['dbPassword'] . "\"" . ";\n\n" .
"// The base url to the website. Ends with a '/' (slash) without quotes.\n" .
"$" . "pluginUrl = " . "\"" . $_SESSION['pluginUrl'] . "\";\n" .
"$" . "rootUrl = " . "\"" . $_SESSION['pluginUrl'] . "\";\n\n" .
"// Specify server path to the index.php. Ends with / (slash) without quotes.\n" .
"$" . "pluginPath = " . "\"" . $_SESSION['rootPath'] . "\";\n" .
"$" . "rootPath = " . "\"" . $_SESSION['rootPath'] . "\";\n\n" .
"// ####### DO NOT CHANGE ANYTHING BELOW THIS LINE. ####### \n\n" .
"// This is the folder where avatars and banners are kept. \n" .
"// Ends with / (slash) without quotes.\n" .
"$" . "imagesDirectory = " . "\"images/\";\n" .
"$" . "avatarsLocalDirectory = " . "\"images/avatars/local/\";\n" .
"$" . "avatarsUploadDirectory = " . "\"images/avatars/uploads/\";\n" .
"$" . "archiveAttachmentDirectory = " . "\"archive/attachment/\";\n\n";

file_put_contents($fileName, $string, FILE_APPEND | LOCK_EX);

$fileName = "../configuration/root.php";
if(!file_exists($fileName)){
	echo "The root.php could not be created. Possibly a folder permission problem.";
	echo "<br>";
	$configuration = 1;
}

if(isset($configuration)){
	echo "<a href='install4.php'>Click here to try again.</a>";
	exit;
}

echo "Data has been writen to the root.php file in the folder install at root.<br><br>";

$filePermissions = substr(sprintf('%o', fileperms('root.php')), -4);
if($filePermissions = "0644"){
echo "The root.php file has proper chmod permissions of 0644 for nobody.<br><br>";

echo "Delete all the install files that are inside the folder of install.<br><br>";
echo "Login with the username of admin with the password of admin. Do not forget to change your password once you have logged in.";
} else echo "You need to chmod the root.php file to 0644 for nobody.<br><br>";

?>

<form id='signupForm2' method='POST' action='../index.php'>
	<table>
		<tr>
			<td>
				<button class="btn btn-danger" name="name" type="submit">Proceed to board index</button>
			</td>
		</tr>
	</table>
</form>