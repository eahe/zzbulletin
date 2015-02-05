<?php 
if(!isset($_SESSION)){ 
	session_start(); 
} 

$fileName = "../configuration/root.php";
if(file_exists($fileName)){
	unlink('../configuration/root.php');
}
?>

<h3>Welcome to the installation of zzbulletin. This installation is a four step process. The first step is the database settings. The second step is adding the data to the database table. The third step is the website url and path settings. fourth step is the creation of the root.php file. After the installation is complete, delete the install files in the install directory.</h3>

<form method='POST' action='install2.php'>
	<table style='width:100%'>
		<tr>
			<!-- database hostname -->
			<td width='50%'>
				<?php echo "Database server hostname. Contact your server provider if unsure."; ?>
			</td>
			<td><input name="dbHost" type='text' value='localhost'>
			</td>
		</tr>
		<tr>
			<!-- provide database port number -->
			<td>
				<?php echo "Database port number.";?>
			</td>
			<td><input name='dbPort' type='text' value='3306'>
			</td>
		</tr>
		<tr> 
		<tr>
			<!-- database name -->
			<td><?php echo "The name of the database. The database will be created if it does not exist"; ?>
			</td>
			<td><input name='dbName' type='text' value='bulletin'>
			</td>
		</tr>
		<tr>
			<!-- database username -->
			<td><?php echo "The username to access the database.";?>
			</td>
			<td><input name='dbUsername' type='text' value='root'>
			</td>
		</tr>
		<tr>
			<!-- database password -->
			<td><?php echo "Database password."; ?>
			</td>
			<td><input name='dbPassword' type='password'>
			</td>
		</tr>
		<tr>
			<td>
				<button class="btn btn-danger" name="name" type="submit">Proceed to step 2</button>
			</td>
		</tr>
	</table>
</form>

