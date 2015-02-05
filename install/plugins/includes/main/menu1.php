<?php
for($menu = 1; $menu <= 9; $menu++){
	$fileName = "../../includes/main/menu" . $menu . ".php";
	if(file_exists($fileName)){
		require "../../includes/main/menu" . $menu . ".php";
	} 
}

//unset($menu);
?>

