<?php
if(isset($_SESSION['noticesGood'])){
	noticesGood();
	unset($_SESSION['noticesGood']);
}

if(isset($_SESSION['noticesFair'])){
	noticesFair();
	unset($_SESSION['noticesFair']);
}

if(isset($_SESSION['noticesBad'])){
	noticesBad();
	unset($_SESSION['noticesBad']);
}
?>