<?php 
$_SESSION['searchSubmit'] = 1;

if($maintenanceMode != "l" || $permission == 4){
	?>
	<form id='' method='POST' action= <?php echo "'{$pluginUrl}search2.php?p=1'" ?>>
		<table class='table2' id='left'>
			<tr>
				<td style='width:18%; text-align: left;'>
					<input name='keywords' type='text' size='13'>
					<input type='hidden' name='searchWithin' value='r>0'>
					<input name='author' type='hidden'>
					<input type='hidden' name='searchOrder' value='desc'></td><td>
					<?php 
					try {
						$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c>0 ORDER BY id ASC");
						$stmt->execute();						
					} catch (PDOException $e) {
						echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
						exit;
					}
					
					$searchCategoryTitle = array();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){	
						$id = $row['id'];			
						$searchCategoryTitle = $row['categoryTitle'];
						$searchCategoryTitle = str_replace('<p>','',$searchCategoryTitle);
						$searchCategoryTitle = str_replace('</p>','',$searchCategoryTitle
					);

						echo "<input type='hidden' name = 'searchCategoryTitle[]' value=" . $row['c'] . ">";
					}
					?>
				</td>
				<td><?php include "includes/maintenanceModeCheck.php"; ?></td><td style='width:28%; text-align: left;'><?php echo"<a href=\"" . $pluginUrl . "search2.php" . "\">Advanced</a>"; ?>
				</td>
			</tr>			
		</table>
	</form>
	<?php
}
unset($_SESSION['searchSubmit']);
?>