<?php
// "flat view"

echo "</td>";
echo "<td id='center' valign='top'>";
// "display the users avatar."
echo $username2 . '<br>';

if(isset($avatar) && strlen($avatar) > 10){
	echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsUploadDirectory . $avatar . '" ></center>';
} else{
	if($yourGender == 'm')
	echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsLocalDirectory . 'male/' . $avatar . '" ></center>';
	else echo '<center><img width="' . $imageWidth . '" height="' . $imageHeight . '" id="file" src="' . $pluginUrl . $avatarsLocalDirectory . 'female/' . $avatar . '" ></center>';
}


echo "Joined: " . $dateJoined . ".<br>";
echo "Total Posts: " . $totalPosts . ".";
echo "</td></tr>";
echo "</table>";

?>