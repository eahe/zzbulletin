<?php 
if(isset($_SESSION['searchSubmit'])){
	if($maintenanceMode == "n" || $permission == 4)
	echo "<button class='btn btn-danger' name='name' type='submit'>Search</button>"; 
	else echo "<button class='btn btn-danger' name='name' type='submit' disabled>Search</button>";
} else{
	if($maintenanceMode == "n" || $permission == 4)
	echo "<button class='btn btn-danger' name='name' type='submit'>Submit</button>";
	else echo "<button class='btn btn-danger' name='name' type='submit' disabled>Submit</button>";
}
?>