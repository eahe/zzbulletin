			<?php if($permission >= $pinThread){
	echo "<br><td colspan='3' id='right'><label for='pin'>Pin / Sticky this thread.</label> ";
	echo "<input type='radio' class='radio' name='pin' value='1' />Yes <input type='radio' class='radio' name='pin' value='0' checked/>No <br>";
} else echo "<td colspan='3' id='right'>"; 
?>
<?php if($permission >= $subscribeThread){
	echo "<label for='follow'>Follow this thread. </label>";
	echo "<input type='radio' class='radio' name='follow' value='1' />Yes <input type='radio' class='radio' name='follow' value='0' checked />No </td>";
} ?>
