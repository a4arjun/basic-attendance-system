<?php 
require_once 'includes/functions.php';
require_once 'views/sidebar.php';

if (!loggedIn()) {
	echo 'You are not logged in';
	header('Location:login.php');
	exit;
}else{
	include 'views/view-attendance.php';
}

?>