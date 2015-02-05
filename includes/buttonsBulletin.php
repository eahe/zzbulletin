<?php
// "this file is similar to bread crumbs. the buttons here are used for"
// "quick navigation of the bulletin."

// "if any page other than this page has the variable of $i,"
// "then set $i to 0 so that it will not conflict."
if(!isset($i)){
	$i = 0;
}

if($bootstrapButtonsDisplay == "y")
echo "<div id='buttonsTextAlign' style='line-height:2.4em;'>";
else echo "<div id='buttonsTextAlign' style='line-height:1.5em;'>";

echo "<table  class='table7' border='0'><tr>";

##### bulletin buttons start here #####

// "display the homepage button."
if($bootstrapButtonsDisplay == "y"){
	echo "<a class='btn btn-primary' href='{$rootUrl}'><i class='fa home fa-lg'></i><span>Homepage</span></a>&nbsp;";
} else {
	echo "|&nbsp;<a href='{$rootUrl}'><span style='white-space: nowrap;'>Homepage</span></a>&nbsp;|";
}

echo "&nbsp;";






echo "</td>";
echo "</tr></table></div>";

?>