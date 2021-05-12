<?php
require_once 'includes/functions.php';
require_once 'views/sidebar.php';
if (!loggedIn()) {
	echo 'You are not logged in';
	header('Location:login.php');
	exit;
}else{
	if(isAdmin()){
		include 'views/daily-report.php';
	}else{
		echo 'Welcome back '.loggedUser();
		header('Location: view-my-attendance.php');
		exit;
	}
}
?>