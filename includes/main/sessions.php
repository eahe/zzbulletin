<?php
ob_start();
session_start();

session_name('BulletinBoard');

// "Report all PHP errors."
ini_set('error_reporting', E_ALL);
// Set the display_errors directive to On
ini_set('display_errors', 1);

// "secure the cookies, so that sessions will be more difficult to hyjack."
ini_set("display_errors", 1);
ini_set('session.cookie_secure',1);
ini_set('session.cookie_httponly',1);

function errHandle($errNo, $errStr, $errFile, $errLine) {
    $msg = "$errStr in $errFile on line $errLine";
    if ($errNo == E_NOTICE || $errNo == E_WARNING) {
        throw new ErrorException($msg, $errNo);
    } else {
        echo $msg;
    }
}

set_error_handler('errHandle');

?>