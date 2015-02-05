<?php 
require "../../includes/main/header.php";

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

if(!isset($_COOKIE['username'])){	
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

if($permission < 4 && $username != 'admin'){
	$_SESSION['basename'] = basename($_SERVER['PHP_SELF']);
	$_SESSION['noticesBad'] = 0;
	header("location: {$pluginUrl}index");
	exit;
}

require 'includes/notices.php';
?>

<form id='permissions' method='POST' action='permissionsBulletin2.php'>

	<!-- "assign the permissions." -->
	<table class='table5' id='right'>
		<tr>
			<th colspan='6' id='center' >Bulletin permissions.</th>
		</tr>
		<tr>
			<td id='left' colspan='6'>
				<?php require "includes/userPermissionsText.php";?>
			</td></tr><tr>

			<td id='table-cell3' ></td>
			<td id='table-cell1' width='15%'>Guests.</td>
			<td id='table-cell1' width='15%'>Members.</td>
			<td id='table-cell1' width='15%'>Moderators.</td>
			<td id='table-cell1' width='15%'>Administrators.</td>
		</tr>
		<tr>
			<td id='table-cell2'>Forum new.</td>
			<td id='center'><input type='radio' class='radio' name='forumNew' value='1' <?php if($forumNew == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='forumNew' value='2' <?php if($forumNew == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='forumNew' value='3' <?php if($forumNew == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='forumNew' value='4' <?php if($forumNew == 4) echo 'checked';?>></td>
		</tr>
			
		<tr>
			<td id='table-cell2'>Forum edit.</td>
			<td id='center'><input type='radio' class='radio' name='forumEdit' value='1' <?php if($forumEdit == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='forumEdit' value='2' <?php if($forumEdit == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='forumEdit' value='3' <?php if($forumEdit == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='forumEdit' value='4' <?php if($forumEdit == 4) echo 'checked';?>></td>
		</tr>
			
		<tr>
			<td id='table-cell2'>Forum delete.</td>
			<td id='center'><input type='radio' class='radio' name='forumDelete' value='1' <?php if($forumDelete == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='forumDelete' value='2' <?php if($forumDelete == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='forumDelete' value='3' <?php if($forumDelete == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='forumDelete' value='4' <?php if($forumDelete == 4) echo 'checked';?>></td>
		</tr>
			
		<tr>
			<td id='table-cell2'>Category new.</td>
			<td id='center'><input type='radio' class='radio' name='categoryNew' value='1' <?php if($categoryNew == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='categoryNew' value='2' <?php if($categoryNew == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryNew' value='3' <?php if($categoryNew == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryNew' value='4' <?php if($categoryNew == 4) echo 'checked';?>></td>
		</tr>
			
		<tr>
			<td id='table-cell2'>Category reorder.</td>
			<td id='center'><input type='radio' class='radio' name='categoryReorder' value='1' <?php if($categoryReorder == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='categoryReorder' value='2' <?php if($categoryReorder == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryReorder' value='3' <?php if($categoryReorder == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryReorder' value='4' <?php if($categoryReorder == 4) echo 'checked';?>></td>
		</tr>
			
		<tr>
			<td id='table-cell2'>Category edit.</td>
			<td id='center'><input type='radio' class='radio' name='categoryEdit' value='1' <?php if($categoryEdit == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='categoryEdit' value='2' <?php if($categoryEdit == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryEdit' value='3' <?php if($categoryEdit == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryEdit' value='4' <?php if($categoryEdit == 4) echo 'checked';?>></td>
		</tr>		
			
		<tr>
			<td id='table-cell2'>Category delete.</td>
			<td id='center'><input type='radio' class='radio' name='categoryDelete' value='1' <?php if($categoryDelete == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='categoryDelete' value='2' <?php if($categoryDelete == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryDelete' value='3' <?php if($categoryDelete == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='categoryDelete' value='4' <?php if($categoryDelete == 4) echo 'checked';?>></td>
		</tr>					
			
		<tr>
			<td id='table-cell2'>Thread new.</td>
			<td id='center'><input type='radio' class='radio' name='threadNew' value='1' <?php if($threadNew == 1) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadNew' value='2' <?php if($threadNew == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadNew' value='3' <?php if($threadNew == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadNew' value='4' <?php if($threadNew == 4) echo 'checked';?>></td>
		</tr>		
			
		<tr>
			<td id='table-cell2'>Thread delete.</td>
			<td id='center'><input type='radio' class='radio' name='threadDelete' value='1' <?php if($threadDelete == 1) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadDelete' value='2' <?php if($threadDelete == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadDelete' value='3' <?php if($threadDelete == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadDelete' value='4' <?php if($threadDelete == 4) echo 'checked';?>></td>
		</tr>		
			
		<tr>
			<td id='table-cell2'>Thread delete all.</td>
			<td id='center'><input type='radio' class='radio' name='threadDeleteAll' value='1' <?php if($threadDeleteAll == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='threadDeleteAll' value='2' <?php if($threadDeleteAll == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadDeleteAll' value='3' <?php if($threadDeleteAll == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='threadDeleteAll' value='4' <?php if($threadDeleteAll == 4) echo 'checked';?>></td>
		</tr>				
			
		<tr>
			<td id='table-cell2'>Post reply.</td>
			<td id='center'><input type='radio' class='radio' name='postReply' value='1' <?php if($postReply == 1) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postReply' value='2' <?php if($postReply == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postReply' value='3' <?php if($postReply == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postReply' value='4' <?php if($postReply == 4) echo 'checked';?>></td>
		</tr>		
			
		<tr>
			<td id='table-cell2'>Post edit.</td>
			<td id='center'><input type='radio' class='radio' name='postEdit' value='1' <?php if($postEdit == 1) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postEdit' value='2' <?php if($postEdit == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postEdit' value='3' <?php if($postEdit == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postEdit' value='4' <?php if($postEdit == 4) echo 'checked';?>></td>
		</tr>
						
		<tr>
			<td id='table-cell2'>Post edit all.</td>
			<td id='center'><input type='radio' class='radio' name='postEditAll' value='1' <?php if($postEditAll == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='postEditAll' value='2' <?php if($postEditAll == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postEditAll' value='3' <?php if($postEditAll == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postEditAll' value='4' <?php if($postEditAll == 4) echo 'checked';?>></td>
		</tr>
			
		<tr>
			<td id='table-cell2'>Post delete.</td>
			<td id='center'><input type='radio' class='radio' name='postDelete' value='1' <?php if($postDelete == 1) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postDelete' value='2' <?php if($postDelete == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postDelete' value='3' <?php if($postDelete == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postDelete' value='4' <?php if($postDelete == 4) echo 'checked';?>></td>
		</tr>
			
		<tr>
			<td id='table-cell2'>Post delete All.</td>
			<td id='center'><input type='radio' class='radio' name='postDeleteAll' value='1' <?php if($postDeleteAll == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='postDeleteAll' value='2' <?php if($postDeleteAll == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postDeleteAll' value='3' <?php if($postDeleteAll == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='postDeleteAll' value='4' <?php if($postDeleteAll == 4) echo 'checked';?>></td>
		</tr>					
			
		<tr>
			<td id='table-cell2'>Attach file to post.</td>
			<td id='center'><input type='radio' class='radio' name='attachFileToPost' value='1' <?php if($attachFileToPost == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='attachFileToPost' value='2' <?php if($attachFileToPost == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='attachFileToPost' value='3' <?php if($attachFileToPost == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='attachFileToPost' value='4' <?php if($attachFileToPost == 4) echo 'checked';?>></td>
		</tr>	
			
		<tr>
			<td id='table-cell2'>Attach file download.</td>
			<td id='center'><input type='radio' class='radio' name='attachFileDownload' value='1' <?php if($attachFileDownload == 1) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='attachFileDownload' value='2' <?php if($attachFileDownload == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='attachFileDownload' value='3' <?php if($attachFileDownload == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='attachFileDownload' value='4' <?php if($attachFileDownload == 4) echo 'checked';?>></td>
		</tr>	
			
		<tr>
			<td id='table-cell2'>Attach file delete.</td>
			<td id='center'><input type='radio' class='radio' name='attachFileDelete' value='1' <?php if($attachFileDelete == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='attachFileDelete' value='2' <?php if($attachFileDelete == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='attachFileDelete' value='3' <?php if($attachFileDelete == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='attachFileDelete' value='4' <?php if($attachFileDelete == 4) echo 'checked';?>></td>
		</tr>				
			
		<tr>
			<td id='table-cell2'>Poll new.</td>
			<td id='center'><input type='radio' class='radio' name='pollNew' value='1' <?php if($pollNew == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='pollNew' value='2' <?php if($pollNew == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='pollNew' value='3' <?php if($pollNew == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='pollNew' value='4' <?php if($pollNew == 4) echo 'checked';?>></td>
		</tr>	
			
		<tr>
			<td id='table-cell2'>Poll vote.</td>
			<td id='center'><input type='radio' class='radio' name='pollVote' value='1' <?php if($pollVote == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='pollVote' value='2' <?php if($pollVote == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='pollVote' value='3' <?php if($pollVote == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='pollVote' value='4' <?php if($pollVote == 4) echo 'checked';?>></td>
		</tr>				
			
		<tr>
			<td id='table-cell2'>Pin thread.</td>
			<td id='center'><input type='radio' class='radio' name='pinThread' value='1' <?php if($pinThread == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='pinThread' value='2' <?php if($pinThread == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='pinThread' value='3' <?php if($pinThread == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='pinThread' value='4' <?php if($pinThread == 4) echo 'checked';?>></td>
		</tr>	
			
		<tr>
			<td id='table-cell2'>Lock thread.</td>
			<td id='center'><input type='radio' class='radio' name='lockThread' value='1' <?php if($lockThread == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='lockThread' value='2' <?php if($lockThread == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='lockThread' value='3' <?php if($lockThread == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='lockThread' value='4' <?php if($lockThread == 4) echo 'checked';?>></td>
		</tr>	
			
		<tr>
			<td id='table-cell2'>Subscribe forum.</td>
			<td id='center'><input type='radio' class='radio' name='subscribeForum' value='1' <?php if($subscribeForum == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='subscribeForum' value='2' <?php if($subscribeForum == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='subscribeForum' value='3' <?php if($subscribeForum == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='subscribeForum' value='4' <?php if($subscribeForum == 4) echo 'checked';?>></td>
		</tr>	
			
		<tr>
			<td id='table-cell2'>Subscribe thread.</td>
			<td id='center'><input type='radio' class='radio' name='subscribeThread' value='1' <?php if($subscribeThread == 1) echo 'checked';?> disabled></td>
			<td id='center'><input type='radio' class='radio' name='subscribeThread' value='2' <?php if($subscribeThread == 2) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='subscribeThread' value='3' <?php if($subscribeThread == 3) echo 'checked';?>></td>
			<td id='center'><input type='radio' class='radio' name='subscribeThread' value='4' <?php if($subscribeThread == 4) echo 'checked';?>></td>
		</tr>				
			
		<tr>
			<td id='center' colspan='7'>
				<?php include "includes/maintenanceModeCheck.php"; ?>	
			</td>
		</tr>
	</table>
</form>
<?php 
require "../../includes/main/footer.php";
?>