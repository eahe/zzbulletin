<?php
$fileName = "root.php";
if(file_exists($fileName) && basename($_SERVER['PHP_SELF']) == "root.php"){
	header("location: install1.php");
	exit;
}

if(!isset($_SESSION['createDatabase'])){
	header("location: install1.php");
	exit;
}

$time = time();
require '../includes/main/pdoTablePrefix.php';

// "hide my online Status (userHidden)"
// "rememberMe: cookie will not be deleted when closing website navigator"
// "location: the country you live in"
// "website: your website"
// "birthday: your birthday"
// "validate: cannot login until email address is validated"
// "day, month, year: used for date born."
// "permission: what is permitted at this bulletin"
// "the active time the user is online"
// "topicPosts: the users topic posts."
// "activeOnlineTime: when a user is active then this column name is updated"
// "avatarLocal: used local instead of upload directory."
// "securityQuestion: to recover password."
$sql="CREATE TABLE {$root}users(
`id`                            INT(8) NOT NULL AUTO_INCREMENT,
`yourGender`                    VARCHAR(1) NOT NULL,
`username`                      VARCHAR(30) NOT NULL,
`password`                      VARCHAR(72) NOT NULL,
`passwordValidate`              VARCHAR(60) NOT NULL,
`emailAddress`                  VARCHAR(255) NOT NULL,
`emailAddressValidate`          VARCHAR(60) NOT NULL,
`dateJoined`                    INT(12) NOT NULL DEFAULT '$time',
`sessionId`                     VARCHAR(60) NOT NULL,
`birthdayTimestamp`             INT(12) NOT NULL,
`avatar`                        VARCHAR(60) NOT NULL,
`avatarLocal`                   VARCHAR(1) NOT NULL default 'n',
`rememberMe`                    INT(1) NOT NULL,
`userHidden`                    INT(1) NOT NULL,
`birthdayDay`                   INT(2) NULL,
`birthdayMonth`                 INT(2) NULL,
`birthdayMonthWord`             VARCHAR(9) NULL,
`birthdayYear`                  INT(4) NULL,
`country`                       VARCHAR(60) NOT NULL,
`website`                       VARCHAR(255) NOT NULL,
`totalPosts`                    INT(6) NOT NULL,
`permission`                    INT(1) NOT NULL,
`activeOnlineTime`              INT(11) NOT NULL default '0',
`postSignature`                 VARCHAR(255) NULL,
`securityQuestion`              VARCHAR(255) NULL,
`markAsReadTimestamp`           INT(12) NOT NULL,
`fileAttach`                    VARCHAR(255) NULL,
`bootstrapButtonsDisplay`       VARCHAR(1) NOT NULL DEFAULT 'y',
`paginationAvatarsOnPage`       INT(3) NOT NULL default '40',
`paginationSearchResultsOnPage` INT(3) NOT NULL default '20',
`adjacents`                     INT(3) NOT NULL default '3',
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);
	
	echo "Table users created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$stmt = $dbh->prepare("SELECT username FROM {$root}users WHERE username='admin'"); 
$stmt->execute(); 
$row = $stmt->fetch();
$admin = $row['username'];
	
if($stmt->errorCode() == 0) {
  	$errors = $stmt->errorInfo();
  	echo($errors[2]);
}

if($admin == ""){
	$sql = 'INSERT INTO ' . $root . 'users SET yourGender="m", username="admin", password="$2a$08$WW8chguMHTJY8VZ9q3ny7uSrFg6SfaLjsXsOtWokY38c8aVVa5FBq", emailAddress="root@localhost", emailAddressValidate="0", avatar="1014302516_m3.jpg", rememberMe="1", permission="4"';
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "admin inserted in to table users successfully.<br>"; 
	
		$sql = 'INSERT INTO ' . $root . 'users SET yourGender="m", username="guest", password="$2a$08$jtSHPZDqpM4wh10VcURh.OoomjJLnJ.Y6JoaZkGIv6LcZLFNcpWsG", emailAddress="root@localhost", emailAddressValidate="0", avatar="user-male.png", permission="1"';
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "guest inserted in to table users successfully.<br>";
}

// "userHidden: user is hidden from Statistics / Who is online / friends list"
// "activeOnlineTime: when a user is active then this column name is updated"
$sql="CREATE TABLE `{$root}users_active_online` (
`id`                            INT(11) NOT NULL auto_increment,
`username`                      VARCHAR(30) NOT NULL,
`session`                       CHAR(100) NOT NULL default '',
`activeOnlineTime`              INT(11) NOT NULL default '0',
`userHidden`                    INT(1) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table users_active_online created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$sql="CREATE TABLE `{$root}users_most_online` (
`id`                            INT(7) NOT NULL auto_increment,
`usersTotal`                    INT(7) NOT NULL,
`usersTimestamp`                INT(12) NOT NULL,
`guestsTotal`                   INT(7) NOT NULL,
`guestsTimestamp`               INT(12) NOT NULL,
`membersTotal`                  INT(7) NOT NULL,
`membersTimestamp`              INT(12) NOT NULL,
`hiddenTotal`                   INT(7) NOT NULL,
`hiddenTimestamp`               INT(12) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table users_most_online created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$stmt = $dbh->prepare("SELECT usersTotal FROM {$root}users_most_online"); 
$stmt->execute(); 
$row = $stmt->fetch();
$usersTotal = $row['usersTotal'];
	
if($stmt->errorCode() == 0) {
  	$errors = $stmt->errorInfo();
  	echo($errors[2]);
}

if($usersTotal == ""){
	$sql = 'INSERT INTO ' . $root . 'users_most_online SET usersTotal="1", usersTimestamp="1396802816", guestsTotal="1", guestsTimestamp="1396802816", membersTotal="0", membersTimestamp="1396802816", hiddenTotal="0", hiddenTimestamp="1396802816"';
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "data inserted in to table users_most_online successfully.<br>"; 
}

$sql="CREATE TABLE `{$root}plugin_homepage` (
`id`                            INT(11) NOT NULL auto_increment,
`plugin`                        CHAR(100) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table plugin_homepage created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

// "used to determine if plugin should be displayed at the main menu. deleting a"
// "plugin from the menu will remove that plugin from this table."
$sql="CREATE TABLE `{$root}plugin_install` (
`id`                            INT(11) NOT NULL auto_increment,
`plugin`                        CHAR(100) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table plugin_install created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

// "imageWidth and imageHeight: avatar size."
// "numberOfLocalMaleAvatars in images/avatars/local/Male/ directory. Maximum 2000."
$sql="CREATE TABLE {$root}configuration(
`id`                            INT(8) NOT NULL AUTO_INCREMENT,
`maintenanceMode`               VARCHAR(1) NOT NULL DEFAULT 'n',
`maintenanceModeText`           VARCHAR(255) NOT NULL default 'Maintenance mode is enabled. All save features are disabled.',
`bannerAdvertisement`           VARCHAR(1) NOT NULL default 'n',
`bannerAdvertisementCode`       TEXT,
`siteName`                      VARCHAR(100) NOT NULL default 'zzbulletin',
`aboutBox`                      VARCHAR(1) NOT NULL default 'n',
`aboutBoxText`                  VARCHAR(500),
`blockAdvertisement`            VARCHAR(1) NOT NULL default 'n',
`blockAdvertisementCode`        TEXT,
`outgoingEmails`                VARCHAR(1) NOT NULL default 'y',
`useAvatars`                    INT(1) NOT NULL default '1',
`imageWidth`                    INT(5) NOT NULL default '160',
`imageHeight`                   INT(5) NOT NULL default '160',
`enableTextAnnouncement`        VARCHAR(1) NOT NULL DEFAULT 'n',
`textAnnouncement`              VARCHAR(255) NOT NULL,
`maximumImageUploadSize`        INT(4) NOT NULL DEFAULT '30',
`maximumAttachmentUploadSize`   INT(4) NOT NULL DEFAULT '4',
`usernameCharacters`            INT(3) NOT NULL default '17',
`WebsiteEmailAddress`          VARCHAR(100) NOT NULL default 'root@localhost.com',
`titleCharacterLimit`           INT(3) NOT NULL default '70',
`numberOfLocalMaleAvatars`      INT(4) NOT NULL default '300',
`numberOfLocalFemaleAvatars`    INT(4) NOT NULL default '100',
`postSignatureCharacterLimit`   INT(3) NOT NULL default '70',
PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ";

try { 
	$dbh->exec($sql);

	echo "Table configuration created successfully.<br>";
	} catch(PDOException $e){
	echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine() . "<br>";
}

$stmt = $dbh->prepare("SELECT id FROM {$root}configuration"); 
$stmt->execute(); 
$row = $stmt->fetch();
$id = $row['id'];
	
if($stmt->errorCode() == 0) {
  	$errors = $stmt->errorInfo();
  	echo($errors[2]);
}

if($id == 0){
	$sql = "INSERT INTO {$root}configuration set id=1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
 
	if($stmt->errorCode() == 0) {
   	$errors = $stmt->errorInfo();
   	echo($errors[2]);
	} else echo "New configuration table record created successfully.<br>"; 
}

?>
