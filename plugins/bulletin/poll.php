<?php
require '../../includes/main/sessions.php';
require 'configuration/bulletin.php';
require '../../includes/main/database.php';
require '../../includes/main/getTablePrefix.php';
require 'includes/main/functions.php';
require "../../includes/main/sessionCookie.php";
require "includes/main/variables.php";
require "../../includes/main/variables.php";

if(isset($_SESSION['f']))
$f = $_SESSION['f'];
if(isset($_SESSION['c']))
$c = $_SESSION['c'];
if(isset($_SESSION['t']))
$t = $_SESSION['t'];

if(!isset($f) || !isset($c) || !isset($t))
exit;

if(isset($_SESSION['poll1']) || isset($_SESSION['poll2'])){
	if(!isset($_POST['poll']) || !isset($_POST['pollid'])){
		try {
			$stmt = $dbh->prepare("SELECT * FROM {$bulletin}poll_questions WHERE f=:f AND c=:c AND t=:t ORDER BY c DESC LIMIT 1");
			$stmt->bindParam(':f', $f);
			$stmt->bindParam(':c', $c);
			$stmt->bindParam(':t', $t);
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){	
			// "display question."
			echo "<p class='pollques'>".$row['question']."</p>";
			echo "<hr style='width:80%'>";
			
			$c = $row['c'];

		}
		if(!isset($_SESSION['poll2'])){
			$_SESSION['noticesBad'] = "Direct access denied for \"" . basename($_SERVER['PHP_SELF']) . "\".";
			header("location: {$pluginUrl}index");
			exit;
		}

		try {
			$stmt = $dbh->prepare("SELECT * FROM {$bulletin}cookies WHERE username=:username AND c=:c AND t=:t");
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':c', $c);
			$stmt->bindParam(':t', $t);
			$stmt->execute();
			$row6 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
			exit;
		}
		
		$username2 = $row6['username'];
		
		if($username == $username2){
			// "if already voted or asked for result."
			showresults($dbh, $f, $c, $t);
			unset($_SESSION['poll2']);
			exit;
		}
		else{
			// "display answers with radio buttons."
			try {
				$stmt1 = $dbh->prepare("SELECT COUNT(*) FROM {$bulletin}poll_answers WHERE f=:f AND c=:c AND t=:t");
				$stmt1->bindParam(':f', $f);
				$stmt1->bindParam(':c', $c);
				$stmt1->bindParam(':t', $t);
				$stmt1->execute();
			} catch (PDOException $e) {
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
				exit;
			}
			
			if($stmt1->fetchColumn() > 0){
				if(isset($_SESSION['poll2'])){
					echo '<div id="formcontainer" ><form method="post" id="pollform" action="'.$_SERVER['PHP_SELF'].'" >';
					echo '<input type="hidden" name="pollid" value="' . $f . $c . $t .'" />';
					
					try {
						$stmt = $dbh->prepare("SELECT id, value FROM {$bulletin}poll_answers WHERE f=:f AND c=:c AND t=:t");
						$stmt->bindParam(':f', $f);
						$stmt->bindParam(':c', $c);
						$stmt->bindParam(':t', $t);
						$stmt->execute();
					} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
					}
					
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){	
						echo "<p>";
						if($permission < $pollVote){
							echo '<input type="radio" disabled class="radio" name="poll" value="'.$row['id'].'" id="option-'.$row['id'].'" />';

						} else{
							echo '<input type="radio" class="radio" name="poll" value="'.$row['id'].'" id="option-'.$row['id'].'" />';
						}
						echo '<label for="option-'.$row['id'].'" >'.$row['value'].'</label>';
						echo "</p>";
					}
					if($permission >= $pollVote){
						echo "<p><div style='text-align: center;'>";
						include "includes/maintenanceModeCheck.php";
						echo '</div></p>';
					} else echo "guest cannot vote.";
					echo "</form>";
					unset($_SESSION['poll2']);
				}
			}
		}
	}
	else{
			// "Check if selected answers value is there in database."
			$poll = $_POST["poll"];
			$serverAddress = $_SERVER['REMOTE_ADDR'];
			
				try {
					$stmt3 = $dbh->prepare("INSERT INTO {$bulletin}poll_votes(f, c, t, answer_id, ip) VALUES(:f, :c, :t, :poll, :serverAddress)");
					$stmt3->bindParam(':f', $f);
					$stmt3->bindParam(':c', $c);
					$stmt3->bindParam(':t', $t);
					$stmt3->bindParam(':poll', $poll);
					$stmt3->bindParam(':serverAddress', $serverAddress);
					$stmt3->execute();
				} catch (PDOException $e) {
					echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
					exit;
				}
						
					// "vote added to database."
					try {
						$stmt4 = $dbh->prepare("INSERT INTO {$bulletin}cookies (username, f, c, t) VALUES (:username, :f, :c, :t)");
						$stmt4->bindParam(':username', $username);
						$stmt4->bindParam(':f', $f);
						$stmt4->bindParam(':c', $c);
						$stmt4->bindParam(':t', $t);
						$stmt4->execute();
					} catch (PDOException $e) {
						echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
						exit;
					}
		showresults($dbh, $f, $c, $t, intval($_POST['pollid']));
	}
} else{
	$_SESSION['noticesBad'] = "Direct access denied for \"" . basename($_SERVER['PHP_SELF']) . "\".";
	header("location: {$pluginUrl}index");
	exit;
}



function showresults($dbh, $f, $c, $t){
	// "get vote total."
	$bulletin = $GLOBALS['bulletin'];

	try {
		$stmt4 = $dbh->prepare("SELECT COUNT(*) as totalvotes FROM {$bulletin}poll_votes WHERE f=:f AND c=:c AND t=:t");
		$stmt4->bindParam(':f', $f);
		$stmt4->bindParam(':c', $c);
		$stmt4->bindParam(':t', $t);
		$stmt4->execute();
		$total = $stmt4->fetchColumn();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}
	
	try {
		$stmt5 = $dbh->prepare("SELECT {$bulletin}poll_answers.id, {$bulletin}poll_answers.value, COUNT(*) as votes FROM {$bulletin}poll_votes, {$bulletin}poll_answers WHERE {$bulletin}poll_votes.answer_id={$bulletin}poll_answers.id AND {$bulletin}poll_votes.answer_id IN(SELECT id FROM {$bulletin}poll_answers WHERE f=:f AND c=:c AND t=:t) GROUP BY {$bulletin}poll_votes.answer_id");
		$stmt5->bindParam(':f', $f);
		$stmt5->bindParam(':c', $c);
		$stmt5->bindParam(':t', $t);
		$stmt5->execute();
	} catch (PDOException $e) {
		echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
		exit;
	}

	// "display the votes."
	while($row = $stmt5->fetch(PDO::FETCH_ASSOC)){	
		$percent=round(($row['votes']*100)/$total);
		$percent2=round(($row['votes']*100)/$total);

		if(isset($row['votes']) && $row['votes'] == 1){
			echo '<div class="option" ><p>'.$row['value'].' (<em>'.$percent.'%, '.$row['votes'].' vote</em>)</p>';
		}
		else echo '<div class="option" ><p>'.$row['value'].' (<em>'.$percent.'%, '.$row['votes'].' votes</em>)</p>';
		echo '<div class="bar ';

		echo '" style="width: '.$percent2.'%; " ></div></div>';
	}
	echo "<p>Total votes:&nbsp;&nbsp;" . $total . "</p>";
	unset($_SESSION['poll1']);
}

?>