<?php
function fullUrl(){
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }

// "variables that pass from page to page use this function."
function cleanData($output){
	$dbh = $GLOBALS['dbh'];

	//	"if (get_magic_quotes_gpc())."
	//		$output = stripslashes($db, $output);

	// "the variables that use this function will have extra"
	// "white space removed."
	$output = str_replace('&nbsp;','',$output);
	$output = str_replace('\r','',$output);
	$output = str_replace('\n','',$output);

	return $output;
}

// "cut the string in size char and add append ... to the string."
function truncateText($text, $char, $append='...'){
	if(strlen($text) > $char){
		$char = $char + 3;
		$text = substr($text, 0, $char);
		$text .= $append;
	}

	return $text;
}

// "notices are the messages near the top of the screen with a red,"
// "yellow and greed background. functions are notices(bad, fair and good."
function noticesBad(){
	?>
	<table class='table1'>
		<tr>
			<td style='text-align: center; background-color: #f39099; color: black; padding: 0px;'><?php
				echo $_SESSION['noticesBad'];
				?>
			</td>
		</tr>
	</table>
	<?php 
}

function noticesFair(){
	?>
	<table class='table1'>
		<tr>
			<td style='text-align: center; background-color: #FFFF99; color: black; padding: 0px;'><?php echo $_SESSION['noticesFair']; ?>
			</td>
		</tr>
	</table>
	<?php 
}

function noticesGood(){
	?>
	<table class='table1'>
		<tr>
			<td style='text-align: center; background-color: #CCFFCC; color: black; padding: 0px;'><?php echo $_SESSION['noticesGood']; ?>
			</td>
		</tr>
	</table>
	<?php 
}

// "create a random string with lenght of 20."
function randomString($length = 20){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for($i = 0; $i < $length; $i++){
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

// "date to timestamp."
function dateTimestamp($timestamp){
	$h = 0;// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
	$hm = $h * 60;
	$ms = $hm * 60;
	$gmdate = gmdate("M d Y g:i:s a", time()); // the "-" can be switched to a plus if that's what your time zone is.
	$timestamp = strtotime($gmdate);
	return $timestamp;
}

// "timestamp to date."
function timestampDate($timestamp){
	$timestamp = gmdate("M d Y h:i:s a", $timestamp);
	return $timestamp;
}

// #############################################################
// "threaded view functions below this line."
// #############################################################







// "print list recursively."
function printList($items, $parentId = 1, $iByPass){
	$_SESSION['threadedMode'] = 1;	
	
	$pluginUrl = $GLOBALS['pluginUrl'];
	$f = $GLOBALS['f'];
	$c = $GLOBALS['c'];
	$t = $GLOBALS['t'];
	$p = $GLOBALS['p'];
	$username = cleanData($GLOBALS['username']);

	if(isset($GLOBALS['r']))
	$r2 = $GLOBALS['r'];

	if(isset($GLOBALS['topicBodySelect']))
	$topicBodySelect = cleanData($GLOBALS['topicBodySelect']);
	
	if($iByPass == 0){
		echo "<ul class='treeview2 filetree browser'><ul>";
	} else{
		if($iByPass < 22)
		echo "<ul><ul>";
	}
	foreach($items[$parentId] as $item){
		$iByPass++;
		if($iByPass < 22)
		echo "<li><div style='text-align:left' class='file'>";
		else 	echo "<div style='text-align:left' class='file'>\n\r";
		$r = $item['r'];
		$topicTitle = $item['topicTitle'];
		$topicBody = $item['topicBody'];
		$username2 = $item['username'];
		
		if($item['username'] != "guest"){
			if(isset($_COOKIE['timezone']))
				$timestamp = $item['timestamp'] + $_COOKIE['timezone'];
			else $timestamp = $item['timestamp'];
		}else $timestamp = $item['timestamp'];		
		
		if(isset($_COOKIE['timezone']))
		$timestamp1 = $timestamp - $_COOKIE['timezone'];
		$timestamp = timestampDate($timestamp);
		
		$dbh = $GLOBALS['dbh'];
		$id = $item['id'];
		$permission = $GLOBALS['permission'];
		$postDelete = $GLOBALS['postDelete'];
		$threadDelete = $GLOBALS['threadDelete'];
		$postEdit = $GLOBALS['postEdit'];
		$threadDeleteAll = $GLOBALS['threadDeleteAll'];
		$postEditAll = $GLOBALS['postEditAll'];
		$topicTitle = str_replace('<p>','',$topicTitle);
		$topicTitle = str_replace('</p>','',$topicTitle);
		$bulletin = $GLOBALS['bulletin'];

		// "determine if username has mark read as forum in database."		
		try {
			$stmt8 = $dbh->prepare("SELECT * FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND r=:r AND r!=0");
			$stmt8->bindParam(':username', $username);
			$stmt8->bindParam(':c', $c);
			$stmt8->bindParam(':t', $t);
			$stmt8->bindParam(':r', $r);
			$stmt8->execute();
			$row8 = $stmt8->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$markAsRead = $row8['username'];
		$mark = $row8['mark'];

		if($markAsRead == NULL){
		
			try {
				$stmt = $dbh->prepare("INSERT INTO {$bulletin}mark_as_read (mark, username, f, c, t, r ) VALUES(0, :username, :f, :c, :t, :r)");
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':f', $f);
				$stmt->bindParam(':c', $c);
				$stmt->bindParam(':t', $t);
				$stmt->bindParam(':r', $r);
				$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}

		if(isset($GLOBALS['topicBodySelect']) && $item['topicBody'] == $topicBodySelect && $r2 == $r){
			if($r>1){
			// "delete post."
			if($permission >= $postDelete && $username == $username2)
			echo "<a class='iconColor confirm' href='{$pluginUrl}postDelete/$id/$p' onmouseover='title=\"\"' title='Are you sure you want to delete this post and any posts under it?'><i class='fa removeRed'></i></a>";
			}

			else { 
						// "delete thread."
				if($permission >= $threadDelete && $username == $username2 || $permission >= $threadDeleteAll)			
				echo "<a class='iconColor confirm' href='{$pluginUrl}threadDelete/$id' onmouseover='title=\"\"' title='Are you sure you want to delete this thread and a poll that might be associated with it?'><i class='fa removeRed'></i></a>";
			}				
			// "post edit."
			if($permission >= $postEdit && $username == $username2 || $permission >= $postEditAll)
			echo "<a class='iconColor' href='{$pluginUrl}postEdit/$id/$p'><i class='fa file-textBlue fa-lg'></i></a>";
			
			if($mark == 0){
			if($username2 != $username)
			echo "New: ";
			}
			
			// "display topic title and timestamp."					
			echo "<span class='threadedBGColor'>" . $topicTitle . " - " . $username2 . ", " . $timestamp . "</span>";
			//echo " <a class='iconColor confirm2' href='{$pluginUrl}postDelete/$id/$p' onmouseover='title=\"\"' title='".$topicBody."'><i class='fa comment-o'></i></a>";
		} elseif($r==1){
			// "delete thread if post is the first post."
			if($permission >= $threadDelete && $username == $username2 || $permission >= $threadDeleteAll)
			echo "<a class='iconColor confirm' href='{$pluginUrl}threadDelete/$id' onmouseover='title=\"\"' title='Are you sure you want to delete this thread and a poll that might be associated with it?'><i class='fa removeRed'></i></a>";
			
			// "display topic title and timestamp."
			if($permission >= $postEdit && $username == $username2 || $permission >= $postEditAll)
			echo "<a class='iconColor' href='{$pluginUrl}postEdit/$id/$p'><i class='fa file-textBlue fa-lg'></i></a>";
		
			if($mark == 0){
			echo "New: ";
			}
			
			echo "<a href='{$pluginUrl}threadRead/$id'>" . $topicTitle . "</a> - " . $username2 . ", " . $timestamp ;
			//echo " <a class='iconColor confirm2' href='{$pluginUrl}postDelete/$id/$p' onmouseover='title=\"\"' title='".$topicBody."'><i class='fa comment-o'></i></a>";
		} else{
			// "display delete post button."
			if($permission >= $postDelete && $username == $username2 && $r > 1)
			echo "<a class='iconColor confirm' href='{$pluginUrl}postDelete/$id/$p' onmouseover='title=\"\"' title='Are you sure you want to delete this post and any posts under it?'><i class='fa removeRed'></i></a>";
			
			// "display topic title and timestamp."
			if($permission >= $postEdit && $username == $username2 || $permission >= $postEditAll)
			echo "<a class='iconColor' href='{$pluginUrl}postEdit/$id/$p'><i class='fa file-textBlue fa-lg'></i></a>";
			
			if($mark == 0){
			echo "New: ";
			}
			
			echo "<a href='{$pluginUrl}threadRead/$id'>" . $topicTitle . "</a> - " . $username2 . ", " . $timestamp ;
			//echo " <a class='iconColor confirm2' href='{$pluginUrl}postDelete/$id/$p' onmouseover='title=\"\"' title='".$topicBody."'><i class='fa comment-o'></i></a>";

		}

		$curId = $item['id'];
		//if there are children
		if(!empty($items[$curId])){
			printList($items, $curId, $iByPass);
		}
		if($iByPass < 22)
		echo '</div></li>';
		else echo "</div>";
		$iByPass--;
	}
	if($iByPass < 22)
	echo '</ul></ul>';
	$iByPass = 0;
}



/***************Extra Functionality 1****************/

function findTopParent($id,$ibp){
	foreach($ibp as $parentID=>$children){
		foreach($children as $child){
			if($child['id']==$id){
				if($child['parentId']!=0){

					//echo $child['parentId'];
					return findTopParent($child['parentId'],$ibp);

				}else{ return $child['title'];
				}
			}
		}
	}
}

//$itemID=7;
//$TopParent= findTopParent($itemID,$itemsByParent);





/***************Extra Functionality 2****************/

function getAllParents($id,$ibp){ //full path
	foreach($ibp as $parentID=>$nodes){
		foreach($nodes as $node){
			if($node['id']==$id){
				if($node['parentId']!=0){
					$a=getAllParents($node['parentId'],$ibp);
					$a = $node['parentId'];
					return $a;
				}else{
					return array();
				}
			}
		}
	}
}


//$FullPath= getAllParents(3,$itemsByParent);
//print_r($FullPath);

/*
Array
(
[0] => 1
[1] => 2
)
*/

/***************Extra Functionality 3****************/


// "this function gets all offspring(subnodes); children, grand children, etc..."
function getAllDescendancy($id,$ibp){
	if(array_key_exists($id,$ibp)){
		$findChild=array();
		foreach($ibp[$id] as $child){
			array_push($findChild,$child['id']);
			if(array_key_exists($child['id'],$ibp))
			$findChild=array_merge($findChild,getAllDescendancy($child['id'],$ibp));
	
		}

		return $findChild;
	}else{
		return array();
	}
}

//print_r(getAllDescendancy(1,$itemsByParent));
/*
Array
(
[0] => 2
[1] => 3
)
*/


//print_r(getAllDescendancy(4,$itemsByParent));
/*
Array
(
[0] => 5
[1] => 6
)
*/


//print_r(getAllDescendancy(0,$itemsByParent));
/*
Array
(
[0] => 1
[1] => 2
[2] => 3
[3] => 4
[4] => 5
[5] => 6
)

*/

// "this function gets all offspring(subnodes); children, grand children, etc..."
function getAllDescendancyR($id,$ibp){
	if(array_key_exists($id,$ibp)){
		$findChild2=array();
		foreach($ibp[$id] as $child){
		array_push($findChild2,$child['r']);
			
			if(array_key_exists($child['id'],$ibp))
			$findChild2=array_merge($findChild2,getAllDescendancyR($child['id'],$ibp));
	
		}

		return $findChild2;
	}else{
		return array();
	}
}

?>