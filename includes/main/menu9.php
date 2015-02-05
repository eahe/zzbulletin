
<!-- "some links will display when the cookie is not set, while others"
"will display when the cookie is set." -->
<?php
	if(isset($brTag1) && $brTag1 == "y")
		echo "<br />";

		if($maintenanceMode != "l" || $username == "admin"){
			if(isset($tenResentBulletinPosts) && $tenResentBulletinPosts == 'y'){
				?>
				<table class='table2' id='left'>
					<tr>
						<th>Ten resent bulletin posts.</th>
					</tr>
					<tr>
						<td>	 
<ul class="filetree browser">
	<!-- "if you want the menu closed by default then use this..."
	"<li class="closed">" -->
	

	<?php	
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE r=1 ORDER BY timestamp DESC LIMIT 10");
	$stmt->execute();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
	
try {
	$stmt2 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE r=1 ORDER BY timestamp DESC LIMIT 1");
	$stmt2->execute();
	$row2 = $stmt2->fetch();	
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
		
	// "determine if a no posts message or the topic title should be displayed."
	$t5 = $row2['t'];
	if($t5 == "")
	echo "No posts to display.";
	else while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$topicTitle = $row['topicTitle'];
		$f5 = $row['f'];
		$c5 = $row['c'];
		$t5 = $row['t'];
		$idTop = $row['id'];
		echo "<ul><li><span class='file'><a href='{$pluginUrl}threadRead/$idTop'><p>" . $topicTitle . "</p></a></span></li></ul>";
	}
	?>
	
</ul>
						</td>
					</tr>
				</table>

				<?php 
			} 
		} ?>
