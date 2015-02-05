<?php 
require "../../includes/main/header.php";

// "after login, variable basename will return user to the last page before login."
$_SESSION['fullUrl'] = fullUrl();

require '../../includes/buttonsBulletin.php';
require '../../includes/notices.php';

?>

<!-- "display the permissions" -->
<table class='table5' id='right'>
	<tr>
		<th colspan='7' id='center' >View permissions.</th>
	</tr>
	<tr>
		<td id='table-cell1' width='26%'>Permissions.</td>
		<td id='table-cell1' width='6%'>Value.</td>
		<td id='table-cell1' width='26%'>Permissions.</td>
		<td id='table-cell1' width='6%'>Value.</td>
		<td id='table-cell1' width='26%'>Permissions.</td>
		<td id='table-cell1' width='6%'>Value.</td>
	</tr>
	<tr>
		<td id='table-cell3'>Forum new.</td>
		<td id='center'><?php if($permission >= $forumNew) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
			
		<td id='table-cell3'>Forum edit.</td>
		<td id='center'><?php if($permission >= $forumEdit) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Forum delete.</td>
		<td id='center'><?php if($permission >= $forumDelete) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

	</tr>			
	<tr>
		<td id='table-cell3'>Category new.</td>
		<td id='center'><?php if($permission >= $categoryNew) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Category reorder.</td>
		<td id='center'><?php if($permission >= $categoryReorder) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
		
		<td id='table-cell3'>Category edit.</td>
		<td id='center'><?php if($permission >= $categoryEdit) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

	</tr>		
			
	<tr>
		<td id='table-cell3'>Category delete.</td>
		<td id='center'><?php if($permission >= $categoryDelete) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
			
		<td id='table-cell3'>Thread new.</td>
		<td id='center'><?php if($permission >= $threadNew) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Thread delete.</td>
		<td id='center'><?php if($permission >= $threadDelete) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
	</tr>		
			
	<tr>
		<td id='table-cell3'>Thread delete all.</td>
		<td id='center'><?php if($permission >= $threadDeleteAll) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Post reply.</td>
		<td id='center'><?php if($permission >= $postReply) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
			
		<td id='table-cell3'>Post edit.</td>
		<td id='center'><?php if($permission >= $postEdit) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
	</tr>
						
	<tr>
		<td id='table-cell3'>Post edit all.</td>
		<td id='center'><?php if($permission >= $postEditAll) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Post delete.</td>
		<td id='center'><?php if($permission >= $postDelete) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Post delete all.</td>
		<td id='center'><?php if($permission >= $postDeleteAll) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
	</tr>			
			
	<tr>
		<td id='table-cell3'>Attach file to post.</td>
		<td id='center'><?php if($permission >= $attachFileToPost) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
			
		<td id='table-cell3'>Attach file download.</td>
		<td id='center'><?php if($permission >= $attachFileDownload) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
			
		<td id='table-cell3'>Attach file delete.</td>
		<td id='center'><?php if($permission >= $attachFileDelete) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
	</tr>			
			
	<tr>
		<td id='table-cell3'>Poll new.</td>
		<td id='center'><?php if($permission >= $pollNew) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
			
		<td id='table-cell3'>Poll vote.</td>
		<td id='center'><?php if($permission >= $pollVote) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Pin thread.</td>
		<td id='center'><?php if($permission >= $pinThread) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
	</tr>

	<tr>
		<td id='table-cell3'>Lock thread.</td>
		<td id='center'><?php if($permission >= $lockThread) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>

		<td id='table-cell3'>Subscribe forum.</td>
		<td id='center'><?php if($permission >= $subscribeForum) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>			
			
		<td id='table-cell3'>Subscribe thread.</td>
		<td id='center'><?php if($permission >= $subscribeThread) echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "yes.png' alt='Help'></a>"; else echo "<img class='middle' src='" . $pluginUrl . $imagesDirectory . "no.png' alt='Help'></a>";?></td>
	</tr>	
</table>

<?php
require "../../includes/main/footer.php";
?>