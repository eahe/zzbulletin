<script>
	// "resize image"
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#file')
				.attr('src', e.target.result)
				.width(<?php echo $imageWidth;?>)
				.height(<?php echo $imageHeight;?>);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>

<?php
include "includes/threadDisplay.php";

$CTemp = $c[$i];
try {
	$stmt8 = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:CTemp AND t=:t AND r=0");
	$stmt8->bindParam(':CTemp', $CTemp);
	$stmt8->bindParam(':t', $t);
	$stmt8->execute();
	$row8 = $stmt8->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

// "if s = 1 then display button as unpin."
$lock = $row8['l'];

if(!isset($_SESSION['replyOtherProfile'])){
	// "post reply button."
	if($lock == 0){
		if($permission >= $postReply){
			if($bootstrapButtonsDisplay == "y"){
				echo "<a class='btn btn-default' href='{$pluginUrl}postReply/$idRead/$p'><i class='fa reply fa-lg'></i><span>Reply</span></a>&nbsp;";
			}
			else {
				echo "|&nbsp;<a href='{$pluginUrl}postReply/$idRead/$p'><span style='white-space: nowrap;'>Reply</span></a>&nbsp;|";
			}
			
			echo "&nbsp;";

			// "quote button."
			if($bootstrapButtonsDisplay == "y"){
				echo "<a class='btn btn-default' href='{$pluginUrl}postReply/$idRead/$p/$quote'><i class='fa quote-left fa-lg'></i><span>Quote</span></a>&nbsp;";
			}
			else {
				echo "<a href='{$pluginUrl}postReply/$idRead/$p/$quote'><span style='white-space: nowrap;'>Quote</span></a>&nbsp;|";
			}				
			echo "&nbsp;";
		}

		// "post edit button."
		if($permission >= $postEdit && $username == $username2 || $permission >= $postEditAll){
			if($bootstrapButtonsDisplay == "y"){
				echo "<a class='btn btn-default' href='{$pluginUrl}postEdit/$id/$p'><i class='fa file-text fa-lg'></i><span>Edit post</span></a>&nbsp;";
			}
			else {
				echo "<a href='{$pluginUrl}postEdit/$id/$p'><span style='white-space: nowrap;'>Edit post</span></a>&nbsp;|";
			}
			echo "&nbsp;";
		}

		// "delete one post in thread."
		if($permission >= $postDelete && $username == $username2 || $permission >= $postDeleteAll){

			// "display delete post button at the end of thread or all your posts in a thread."
			// "mods and admin can delete any post."
			$rLastPost = 0;
			if(!isset($r))
			$r = 1;	
		
			if($deleteOnlyLastPost == "n")
			$rLastPost = $r;
			else{
				if(isset($row3['r']))
				$rLastPost = cleanData($row3['r']);

				if($permission >= 3)
				$rLastPost = $r;
			}

			if($r > 1 && $r == $rLastPost){
				if($bootstrapButtonsDisplay == "y"){
					echo "<a class='btn btn-danger confirm' href='{$pluginUrl}postDelete/$idRead/$p' onmouseover='title=\"\"' title='Are you sure you want to delete this post and any posts under it?'><i class='fa removeBlack fa-lg'></i><span>Delete post</span></a>&nbsp;";
				}
				else{
					echo "<a class='confirm' href='{$pluginUrl}postDelete/$idRead/$p' onmouseover='title=\"\"' title='Are you sure you want to delete this post and any posts under it?'><span style='white-space: nowrap;'>Delete post</span></a>&nbsp;|";
				}
				
				echo "&nbsp;";
			}
		}
	}

	// "delete the thread if $r = 1."
	if($r == 1){
		if($permission >= $threadDelete && $username == $username2 || $permission >= $threadDeleteAll){
			if($bootstrapButtonsDisplay == "y"){
				echo "<a class='btn btn-danger confirm' href='{$pluginUrl}threadDelete/$id' onmouseover='title=\"\"' title='Are you sure you want to delete this thread and a poll that might be associated with it?'><i class='fa removeWhite fa-lg'></i><span>Delete thread</span></a>&nbsp;";
			}
			else{
				echo "<a class='confirm' href='{$pluginUrl}threadDelete/$id' onmouseover='title=\"\"' title='Are you sure you want to delete this thread and a poll that might be associated with it?'><span style='white-space: nowrap;'>Delete thread</span></a>&nbsp;|";
			}
				
			echo "&nbsp;";
		}
	}

	echo "</div>";
}
/*
// "prepare to delete post."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t  ORDER BY r DESC LIMIT 1");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}
*/
unset($_SESSION['replyOtherProfile']);
if($lock == 1){
	echo "<br>";
	echo "<font color='red'>This thread is locked.</font>";
}

?>