<?php
require '../../includes/main/header.php';

// "from buttonsBulletin.php variable $forumNew5."
if(isset($_SESSION['getF']))
$forumNew5 = $_SESSION['getF'];
// "from buttonsBulletin.php variable $c[$i]."
if(isset($_SESSION['getC']))
$c = $_SESSION['getC'];
// "from buttonsBulletin.php variable $p."
if(isset($_SESSION['getP']))
$p = $_SESSION['getP'];

require 'includes/buttonsBulletin.php';

// "exit if there are no categories to search."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums WHERE c='0' AND forumName!=''");
	$stmt->execute();
	$row1 = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$forumName = cleanData($row1['forumName']);

if($forumName == NULL){
	$_SESSION['noticesFair'] = "No categories to search.";
}

if(isset($_SESSION['noticesFair'])){
	noticesFair();
	unset($_SESSION['noticesFair']);
}

$_SESSION['searchSubmit'] = 1;
?>
<body>
<form id='' method='POST' action='search2.php?p=1'>
	<table class='table6' id='center'>
		<tr>
			<th colspan='2' id='center'>Search.</th>
		</tr>
		<tr>
			<td>
				<label><?php echo "<b>Search for keywords:</b><br>
					Two words minimum for a search. Exact Search: Words in quotes. Boolean Search: Use + in front of a word that you want to find, - in front of a word that you do not want to find and use * as a wildcard, example admin* for administrator."; ?>
				</label>
			</td>
			<td>
				<input name='keywords' type='text'>
			</td>
		</tr>
		<tr>
			<td>
				<label>
					<?php  echo "<b>Search for author:</b><br>
					Exact match search or boolean search. Use * as a wildcard."; ?>
				</label>
			</td>
			<td>
				<input name='author' type='text'>
			</td>
		</tr>
		<tr>
			<td>Select a forum. Hold down "ctrl" on the keyboard and mouse click to select multiple forums.</td>
			<td>

				<?php 
				$searchCategoryCount = 0;
				
				try {
					$stmt = $dbh->prepare("SELECT * FROM {$bulletin}forums ORDER BY id ASC");
					$stmt->execute();
					$row1 = $stmt->fetch();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}

				// this html select tag is needed to select the forum"
				// "that the category can be displayed in." 
				echo "<select name = 'searchCategoryTitle[]' size=5 multiple>";
				while($row1 = $stmt->fetch(PDO::FETCH_ASSOC)){		
		
					// "put every forumName inside of the html select tag options."
					$searchCategoryTitle = $row1['categoryTitle'];
					$searchCategoryTitle = str_replace('<p>','',$searchCategoryTitle);
					$searchCategoryTitle = str_replace('</p>','',$searchCategoryTitle);
				
					if($row1['c'] ==  0)
					echo "<option value='" . $row1['f'] . "'disabled>" . truncateText($row1['forumName'], 35) . "</option>";
					elseif($searchCategoryCount == 0){
					echo "<option value=" . $row1['c'] . " selected>&nbsp;&nbsp;" . truncateText($row1['categoryTitle'], 35) . "</option>";
					$searchCategoryCount++;
					} else echo "<option value=" . $row1['c'] . ">&nbsp;&nbsp;" . truncateText($searchCategoryTitle, 35) . "</option>";
				}
				echo "</select>";
				?>
			</td>
		</tr>			
		<tr>
			<td>Search within topicTitle and/or topicBody text:</td>
			<td><input type='radio' name='searchWithin' value='r=1' />First topic title only.
				<input type='radio' name='searchWithin' value='r!=0' />All topic body.<br>
				<input type='radio' name='searchWithin' value='r>0' checked/>First topic title and all topic body.
				<input type='radio' name='searchWithin' value='r=2' />Second post of threads.			
			</td>
		</tr>
		<tr>
			<td>Sort results by:</td>
			<td>				
				<input type='radio' name='searchOrder' value='desc' checked />Descending. 
				<input type='radio' name='searchOrder' value='asc' />Ascending.
			</td>
		</tr>
		<tr>
			<td colspan='2' id='center'>
				<?php include "includes/maintenanceModeCheck.php"; ?>	
			</td>
		</tr>
	</table>
</form>

<?php
unset($_SESSION['searchSubmit']);
	
require '../../includes/main/footer.php';
?>