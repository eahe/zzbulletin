<?php
if($bootstrapButtonsDisplay == "y")
echo "<div id='buttonsTextAlign' style='line-height:2.4em;'>";
else echo "<div id='buttonsTextAlign' style='line-height:1.5em;'>";

echo "<table  class='table7' border='0'><tr><td>";

##### bulletin buttons start here #####
// "display the home button."
if($bootstrapButtonsDisplay == "y"){
	echo "<a class='btn btn-primary' href='{$rootUrl}'><i class='fa home fa-lg'></i><span>Homepage</span></a>&nbsp;";
} else {
	echo "|&nbsp;<a href='{$rootUrl}'><span style='white-space: nowrap;'>Homepage</span></a>&nbsp;|";
}

echo "&nbsp;";

// "display the bulletin home button."
if($bootstrapButtonsDisplay == "y"){
	echo "<a class='btn btn-primary' href='{$pluginUrl}'><i class='fa home fa-lg'></i><span>Skeleton home</span></a>&nbsp;";
} else {
	echo "|&nbsp;<a href='{$pluginUrl}'><span style='white-space: nowrap;'>Skeleton home</span></a>&nbsp;|";
}

echo "&nbsp;";
echo "</td></tr></table></div>";
?>