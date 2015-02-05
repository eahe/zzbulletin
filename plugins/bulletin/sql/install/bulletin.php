<?php
if($permission < 4 || $username != 'admin'){
	header("location: ../../index.php");
	exit;
}

$time = time();
require '../../includes/main/pdoTablePrefix.php';

// "f is the f int for the forum name while forumName is the forum name"
// "l is used for location of moved category using the arrow icons"
// "c is the category number"
// "timestamp is used for the time of the most recent post."
$sql="CREATE TABLE {$bulletin}forums(
`id`                            INT(10) NOT NULL AUTO_INCREMENT,
`f`                             INT(10) NOT NULL,
`c`                             INT(10) NULL,
`l`                             INT(10) NOT NULL,
`forumName`                     VARCHAR(255) NOT NULL,
`categoryTitle`                 VARCHAR(255) NOT NULL,
`categoryBody`                  TEXT NOT NULL,
`timestamp`                     INT(12) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table forums created successfully.<br>";
	} catch (PDOException $e) {
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

// f = forum
// c = category
// t = topics
// r is the topic reply number
// s is for sticky / pin thread
// l is for lock thread
// views = topic views
// timestamp is used for the time of a post
$sql="CREATE TABLE {$bulletin}threads(
`id`                            INT(10) NOT NULL AUTO_INCREMENT,
`parentId`                      INT(10) NOT NULL DEFAULT '0',
`f`                             INT(10) NOT NULL,
`c`                             INT(10) NOT NULL,
`t`                             INT(10) NOT NULL,
`r`                             INT(10) NOT NULL,
`s`                             INT(1) NOT NULL,
`l`                             INT(1) NOT NULL,
`topicTitle`                    TEXT NOT NULL,
`topicBody`                     TEXT NOT NULL,
`views`                         INT(10) NOT NULL,
`username`                      VARCHAR(30) NOT NULL,
`timestamp`                     INT(12) NOT NULL,
`attachFile`                    VARCHAR(255),
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table threads created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE {$bulletin}permissions(
`id`                            INT(8) NOT NULL AUTO_INCREMENT,
`forumNew`                      INT(1) NOT NULL DEFAULT '4',
`forumEdit`                     INT(1) NOT NULL DEFAULT '3',
`forumDelete`                   INT(1) NOT NULL DEFAULT '4',
`categoryNew`                   INT(1) NOT NULL DEFAULT '4',
`categoryReorder`               INT(1) NOT NULL DEFAULT '4',
`categoryEdit`                  INT(1) NOT NULL DEFAULT '3',
`categoryDelete`                INT(1) NOT NULL DEFAULT '4',
`threadNew`                     INT(1) NOT NULL DEFAULT '2',
`threadDelete`                  INT(1) NOT NULL DEFAULT '2',
`threadDeleteAll`               INT(1) NOT NULL DEFAULT '4',
`postReply`                     INT(1) NOT NULL DEFAULT '2',
`postEdit`                      INT(1) NOT NULL DEFAULT '2',
`postEditAll`                   INT(1) NOT NULL DEFAULT '3',
`postDelete`                    INT(1) NOT NULL DEFAULT '2',
`postDeleteAll`                 INT(1) NOT NULL DEFAULT '3',
`attachFileToPost`              INT(1) NOT NULL DEFAULT '2',
`attachFileDownload`            INT(1) NOT NULL DEFAULT '2',
`attachFileDelete`              INT(1) NOT NULL DEFAULT '2',
`pollNew`                       INT(1) NOT NULL DEFAULT '2',
`pollVote`                      INT(1) NOT NULL DEFAULT '2',
`pollDeleteAll`                 INT(1) NOT NULL DEFAULT '3',
`pollDelete`                    INT(1) NOT NULL DEFAULT '2',
`bulletinView`                  INT(1) NOT NULL DEFAULT '1',
`pinThread`                     INT(1) NOT NULL DEFAULT '3',
`lockThread`                    INT(1) NOT NULL DEFAULT '3',
`subscribeForum`                INT(1) NOT NULL DEFAULT '2',		
`subscribeThread`               INT(1) NOT NULL DEFAULT '2',	
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table permissions created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$stmt = $dbh->prepare("SELECT id FROM {$bulletin}permissions"); 
$stmt->execute(); 
$row = $stmt->fetch();
$id = $row['id'];
	
if($stmt->errorCode() == 0) {
  	$errors = $stmt->errorInfo();
  	echo($errors[2]);
}

if($id == 0){
	$sql = "INSERT INTO {$bulletin}permissions (id)
   VALUES ('$id')";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "New permissions table record created successfully<br>."; 
}

// "hotTopic is a popular topic / lots of posts."
// "titleCharacterLimit: the maximum characters in a title."
$sql="CREATE TABLE {$bulletin}configuration(
`id`                            INT(8) NOT NULL AUTO_INCREMENT,
`hotTopic`                      INT(3) NOT NULL default '10',
`deleteOnlyLastPost`            VARCHAR(1) NOT NULL DEFAULT 'n',
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table configuration created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$stmt = $dbh->prepare("SELECT id FROM {$bulletin}configuration"); 
$stmt->execute(); 
$row = $stmt->fetch();
$id = $row['id'];
	
if($stmt->errorCode() == 0) {
  	$errors = $stmt->errorInfo();
  	echo($errors[2]);
}

if($id == 0){
	$sql = "INSERT INTO {$bulletin}configuration set id=1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "New configuration table record created successfully.<br>"; 
}

// "paginationThreadsOnPage: Number of threads to show at the same time."
// "brTag: keep or remove <br> tags"
$sql="CREATE TABLE {$bulletin}preferences(
`id`                            INT(8) NOT NULL AUTO_INCREMENT,
`username`                      VARCHAR(30) NOT NULL,
`tenResentBulletinPosts`        VARCHAR(1) NOT NULL default 'y',
`threadDisplay`                 INT(1) NOT NULL DEFAULT '1',
`paginationPostsOnPage`         INT(3) NOT NULL default '10',
`paginationThreadsOnPage`       INT(3) NOT NULL default '30',
`brTag1`                        VARCHAR(1) NOT NULL DEFAULT 'y',
`brTag2`                        VARCHAR(1) NOT NULL DEFAULT 'y',
`brTag3`                        VARCHAR(1) NOT NULL DEFAULT 'y',

PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table preferences created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$stmt = $dbh->prepare("SELECT username FROM {$bulletin}preferences WHERE username='admin'"); 
$stmt->execute(); 
$row = $stmt->fetch();
$admin = $row['username'];
	
if($stmt->errorCode() == 0) {
  	$errors = $stmt->errorInfo();
  	echo($errors[2]);
}

if($admin == ""){	
	$sql = "INSERT INTO {$bulletin}preferences SET username='admin'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "New preferences table record created successfully.<br>"; 
	
	$sql = "INSERT INTO {$bulletin}preferences SET username='guest'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "New preferences table record created successfully.<br>"; 
}

$sql="CREATE TABLE {$bulletin}mark_as_read(
`id`                            INT(11) NOT NULL AUTO_INCREMENT,
`username`                      VARCHAR(30) NOT NULL,
`f`                             INT(10) NOT NULL default '0',
`c`                             INT(10) NOT NULL default '0',
`t`                             INT(10) NOT NULL default '0',
`r`                             INT(10) NOT NULL default '0',
`mark`                          INT(1) NOT NULL default '0',
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table mark_as_read created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE {$bulletin}poll_answers(
`id`                            INT(11) NOT NULL AUTO_INCREMENT,
`f`                             INT(10) NOT NULL,
`c`                             INT(10) NOT NULL,
`t`                             INT(10) NOT NULL,
`value`                         VARCHAR(300) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);
   
	echo "Table poll_answers created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE {$bulletin}poll_questions (
`id`                            INT(11) NOT NULL auto_increment,
`f`                             INT(10) NOT NULL,
`c`                             INT(10) NOT NULL,
`t`                             INT(10) NOT NULL,
`question`                      TEXT NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table poll_questions created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE {$bulletin}poll_votes (
`id`                            INT(11) NOT NULL auto_increment,
`f`                             INT(10) NOT NULL,
`c`                             INT(10) NOT NULL,
`t`                             INT(10) NOT NULL,
`answer_id`                     INT(11) NOT NULL,
`ip`                            VARCHAR(16) default NULL,
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table poll_votes created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE {$bulletin}cookies (
`id`                            INT(11) NOT NULL auto_increment,
`username`                      VARCHAR(30) NOT NULL,
`f`                             INT(10) NOT NULL,
`c`                             INT(10) NOT NULL,
`t`                             INT(10) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table cookies created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE {$bulletin}subscribe_forum (
`id`                            INT(11) NOT NULL auto_increment,
`username`                      VARCHAR(30) NOT NULL,
`emailAddress`                  VARCHAR(255) NOT NULL,
`unsubscribe`                   VARCHAR(60) NOT NULL,
`c`                             INT(10) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table subscribe_forum created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE {$bulletin}subscribe_thread (
`id`                            INT(11) NOT NULL auto_increment,
`username`                      VARCHAR(30) NOT NULL,
`emailAddress`                  VARCHAR(255) NOT NULL,
`unsubscribe`                   VARCHAR(60) NOT NULL,
`c`                             INT(10) NOT NULL,
`t`                             INT(10) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table subscribe_thread created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}





?>
