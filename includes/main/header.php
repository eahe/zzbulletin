<?php 
if(!isset($_SESSION['noSessionsNoConfig'])){
	require 'sessions.php';

	if(is_file('configuration/root.php'))
		require "configuration/root.php";
	elseif(is_file('../../configuration/root.php'))
		require "../../configuration/root.php";
	else{
		$fileName = "configuration/root.php";
		if(!file_exists($fileName)){
		header("location: install/install1.php");
		exit;
		}
		$fileName = "../../configuration/root.php";
		if(!file_exists($fileName)){
		header("location: ../../install/install1.php");
		exit;
		}
	}
}

if(!isset($_SESSION['noFunctionsNoDatabase'])){
	require 'database.php';
	require $rootPath . 'includes/main/getTablePrefix.php';
	require $rootPath . "includes/main/sessionCookie.php";
	require $pluginPath . 'includes/main/functions.php';

	require $rootPath . 'includes/main/variables.php';
	require $rootPath . 'includes/language/en.php';
}



unset($_SESSION['noFunctionsNoDatabase']);
unset($_SESSION['noSessionsNoConfig']);

$path = 'configuration/';

if(is_dir($path)){
$results = scandir($path);

foreach ($results as $result) {
	if ($result === '.' or $result === '..') continue;
      // "include plugin configuration file."
		if($result != "root.php"){
		require $path . $result;
		
		$pluginHome = substr($result, 0, -4);
		$pluginInstall = substr($result, 0, -4);

			try {
				$stmt = $dbh->prepare("SELECT * FROM {$root}plugin_homepage WHERE plugin=:plugin");
				$stmt->bindParam(':plugin', $pluginHome);
				$stmt->execute();
				$row = $stmt->fetch();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}

			if(isset($row['plugin'])){
				$pluginHome = $row['plugin'];			
			}
			
			// "determine if plugin variables.php should be php included."
			try {
				$stmt = $dbh->prepare("SELECT * FROM {$root}plugin_install WHERE plugin=:result");
				$stmt->bindParam(':result', $pluginInstall);
				$stmt->execute();
				$row = $stmt->fetch();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
			
			if($pluginInstall == $row['plugin']){
				if(file_exists($pluginPath . "includes/main/variables.php"))
					require $pluginPath . "includes/main/variables.php";
					require $pluginPath . "includes/language/en.php";
				}
		}    
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!-- <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1"> -->
	<title></title>

	<?php 
	echo "<script type='text/javascript' src='". $rootUrl  . "jquery/jquery.min.js'></script>";
	echo "<script type='text/javascript' src='". $rootUrl  . "menu/jquery.cookie.and.treeview.js'></script>";
	echo "<link rel='stylesheet' type='text/css' href='" . $rootUrl  . "jquery/jquery-ui.min.css' />"; 
	echo "<link rel='stylesheet' type='text/css' href='" . $rootUrl  . "css/fontAwesome.css' />"; 
	//echo "<link rel='stylesheet' type='text/css' href='" . $rootUrl  . "css/bootstrapTheme.css' />"; 
	echo "<link rel='stylesheet' type='text/css' href='" . $rootUrl  . "css/bootstrap.css' />"; 
	echo "<link rel='stylesheet' type='text/css' href='" . $rootUrl  . "css/pagination2.css' />"; 
	echo "<link rel='stylesheet' type='text/css' href='" . $rootUrl  . "themes/contents.css' />"; ?>

	<!-- "resize textarea to fill html table td." -->
	<!--[if lt IE 9]>
	<script type="text/javascript">
	$(document).ready(function() {
	$('textarea').parent().resize(function() {
	var $t = $(this);
	$t.find('textarea').height($t.height());
	}).resize();
	});
	</script>
	<![endif]-->

	<?php 
		$fileName = "includes/main/dialog.php";
		if(!file_exists($fileName)){
			require "../../includes/main/dialog.php";
		} else require "includes/main/dialog.php";
	?>
	
	<!-- "display page after everything is loaded." -->
 	<script>
		window.onload = function() {setTimeout(function()						
		{document.body.style.opacity="100";},5);};
	</script>
</head>
<body>
<noscript>
	<?php echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=javascriptDisabled.html'>"; ?>
</noscript>
<?php

?>
<table class='table2'>
	<tr>
		<td id='left'><font size='5'> <?php echo $siteName; ?>
			</font>
		</td>
		<td id='right'><?php 
					$fileName = "searchMini.php";
						if(file_exists($fileName)){
						include "searchMini.php"; 
					}					
				?></td>
		<td id='right'><?php 
			if(isset($_COOKIE['username'])){
				echo "Welcome " . $username . " | <a href='{$rootUrl}logout.php'><i></i>Logout</a>";
			}
			// "display the login and logout links."
			if(!isset($_COOKIE['username']))
			echo "<a href='{$rootUrl}login.php'><i></i>Login</a>";

			if($maintenanceMode != "l" || $permission == 4)
			if(!isset($_COOKIE['username']))
			echo " | <a href='{$rootUrl}register.php'><i></i>Register</a>";
			?>
		</td>
	</tr>
</table>

<?php
$maintenanceModeLogin = basename($_SERVER['SCRIPT_FILENAME']);

if($maintenanceMode == "y") echo "<table class='table8' id='center'><tr><td>" . $maintenanceModeText . "</td></tr></table>";
elseif($maintenanceMode == "l" ){
	echo "<table class='table8' id='center'><tr><td>" . $maintenanceModeText . "</td></tr></table>";
	if($maintenanceModeLogin != "login.php" && $maintenanceModeLogin != "userMainSetup.php" && $maintenanceModeLogin != "loginCheck.php" && $maintenanceModeLogin != "logout.php") 
	if($permission < 4) exit;
} elseif($enableTextAnnouncement == "y") echo "<table class='table8' id='center'><tr><td>" . $textAnnouncement . "</td></tr></table>";
?>

<!-- "Main menu begins here" -->
<div id="content">
	<div id="sidebar" style='margin-top: 8px;'>

		<?php 
		if(isset($c) && isset($t)){
			// "get database poll data from thread variables."
			try {
				$stmt = $dbh->prepare("SELECT * FROM {$bulletin}poll_questions WHERE c=:c AND t=:t ORDER BY c DESC LIMIT 1");
				$stmt->bindParam(':c', $c);
				$stmt->bindParam(':t', $t);
				$stmt->execute();
				$row4 = $stmt->fetch();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
			
			$c2 = $row4['c'];
			$t2 = $row4['t'];
		
			// "determine if poll is linked to thread."
			if($c2 == $c && $t2 == $t){
				$_SESSION['pollDisplay'] = 1;
				$_SESSION['f'] = $f;
				$_SESSION['c'] = $c;
				$_SESSION['t'] = $t;
			}
		}

		if(isset($_SESSION['pollDisplay']) && isset($f) && isset($c) && isset($t)){
			echo "<table class='table2' id='left'><tr><th>Poll.</th>";
			echo "</tr><tr><td>";
			$_SESSION['poll'] = 1;
			require "pollStart.php";
			echo "</td></tr></table><br>";
		}
		
		// "display the about box at the main menu."
		if($aboutBox == 'y' && basename($_SERVER['PHP_SELF']) == "index.php"){
			echo "<table class='table2' id='left'><tr><th>About.</th>";
			echo "</tr><tr><td>";
			echo $aboutBoxText;
			echo "</td></tr></table><br>";
		}
		
		if($blockAdvertisement == 'y'){
			echo "<table class='table2' id='left'><tr><th>Advertisement.</th>";
			echo "</tr><tr><td>";
			echo $blockAdvertisementCode;
			echo "</td></tr></table><br>";
		}
		
		unset($_SESSION['pollDisplay']);
		?>

		<table class='table2' id='left'>
			<tr>
				<th>Main menu.</th>
			</tr>
			<tr>
				<td>
				
<script type="text/javascript">
	$(document).ready(function(){
			// treeview example
			$(".browser").treeview({
					animated:"normal",
					persist: "cookie",
					cookieId: "menu"
				});
		});
</script>

<?php 
$fileName = "includes/main/menuHeader.php";
if(file_exists($fileName)){
	require "includes/main/menuHeader.php";
} else require "../../includes/main/menuHeader.php";

for($menu = 1; $menu <= 9; $menu++){
	$fileName = "includes/main/menu" . $menu . ".php";
	if(file_exists($fileName)){
		require "includes/main/menu" . $menu . ".php";
	} 
}

unset($menu);

$fileName = "includes/main/menuFooter.php";
if(file_exists($fileName)){
	require "includes/main/menuFooter.php";
} else require "../../includes/main/menuFooter.php";

?>

				</td>
			</tr>
		</table>
	</div>
</div>
<!-- Main menu ends here -->

<!-- "display page." -->
<style> body  {opacity:0;}</style>

<div id="main-content">

<?php

if($bannerAdvertisement == "y") echo "<table class='table7' style='text-align: center;' id='center'><tr><th>Advertisement</th></tr><tr><td>" . $bannerAdvertisementCode . "</td></tr></table>";
?>