<?php

if(!isset($c) && !isset($t) && !isset($r)){
	$_SESSION['noticesBad'] = "Possible modification of a url at \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}


if($r == 1){
	header("location: {$pluginUrl}threadDelete/$id");
	exit;
}

$byPass = 1;

// "prepare to delete post."
try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE id=:id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$parentId = $row['parentId'];

$rLastPost = 0;
if($deleteOnlyLastPost == "n")
$rLastPost = $r;
else{
	$rLastPost = cleanData($row['r']);
	if($permission >= 3)
	$rLastPost = $r;
}

if($r > 1 && $r == $rLastPost){
	// "delete a post
	if(isset($id)){
		try {
			$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t AND topicTitle=:topicTitle");
			$stmt->bindParam(':topicTitle', $topicTitle);
			$stmt->bindParam(':c', $c);
			$stmt->bindParam(':t', $t);
			$stmt->execute();

		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

		$items = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){		
			$items[] = $row;
		}

		// "create new list grouped by parent id and r."
		$itemsByParent = array();
			
		foreach($items as $item){
			if(!isset($itemsByParent[$item['parentId']])){
				$itemsByParent[$item['parentId']] = array();
			}

			$itemsByParent[$item['parentId']][] = $item;
		}

		$findChild = getAllDescendancy($id,$itemsByParent);
		$findChild = implode(",",$findChild);
		
		$findChild2 = getAllDescendancyR($id,$itemsByParent);
		$findChild2 = implode(",",$findChild2);

//print_r($findChild);exit;


		if($findChild2 != NULL){
			try {	
				$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND r IN($findChild2)");
				$stmt->bindParam(':username', $username);  
				$stmt->bindParam(':c', $c); 
				$stmt->bindParam(':t', $t); 
				$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}

		if($findChild != NULL){
			try {	
				$stmt = $dbh->prepare("DELETE FROM {$bulletin}threads WHERE c=:c AND t=:t AND id IN($findChild)");
				$stmt->bindParam(':c', $c);  
				$stmt->bindParam(':t', $t);  
				$stmt->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
		}

		try {	
			$stmt = $dbh->prepare("DELETE FROM {$bulletin}threads WHERE c=:c AND t=:t AND id=:id");
			$stmt->bindParam(':c', $c);  
			$stmt->bindParam(':t', $t);  
			$stmt->bindParam(':id', $id);  
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		try {	
			$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND r=:r");
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':c', $c);  
			$stmt->bindParam(':t', $t);  
			$stmt->bindParam(':r', $r);    
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

	} else{
		try {	
			$stmt = $dbh->prepare("DELETE FROM {$bulletin}threads WHERE c=:c AND t=:t AND r=:r");
			$stmt->bindParam(':c', $c);  
			$stmt->bindParam(':t', $t);  
			$stmt->bindParam(':r', $r);  
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		try {	
			$stmt = $dbh->prepare("DELETE FROM {$bulletin}mark_as_read WHERE username=:username AND c=:c AND t=:t AND r=:r");
			$stmt->bindParam(':username', $username); 
			$stmt->bindParam(':c', $c);  
			$stmt->bindParam(':t', $t);  
			$stmt->bindParam(':r', $r);   
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}

	}
} else{
	$byPass = 0;
	unset($_SESSION['noticesBad']);

	$_SESSION['noticesFair'] = 'Post not at end of thread. Cannot delete post.';
	header("location: {$pluginUrl}threadRead/$id/$p");
	exit;
}

// "First get total number of rows in threads table."
try {
	$stmt = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}threads WHERE c=:c AND t=:t AND s=0 AND r!=0");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$total_pages = $stmt->fetchColumn();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

try {
	$stmt = $dbh->prepare("SELECT * FROM {$bulletin}threads WHERE c=:c AND t=:t ORDER BY id ASC LIMIT 1");
	$stmt->bindParam(':c', $c);
	$stmt->bindParam(':t', $t);
	$stmt->execute();
	$row = $stmt->fetch();
} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	exit;
}

$lastpage = ceil($total_pages/$limit); // "lastpage is = total pages / items per p, rounded up."

if($byPass == 1)
$_SESSION['noticesGood'] = 'The post deleted successfully.';

$id = $row['id'];

if($threadDisplay == 2)
$id = $parentId;

// "if post is deleted at the end of the thread then $p = lastpage"
// "or else display the post under the post that was deleted."

	header("location: {$pluginUrl}threadRead/$id/$lastpage");
	exit;


?>