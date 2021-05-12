<?php 
require_once 'includes/functions.php';
require_once 'views/sidebar.php';
$total_employees = getEmployeesCount($db);
$todays_attendance = getAttendance($db);
//$perc = ($todays_attendance/$total_employees) * 100;
$perc = ($todays_attendance / $total_employees) *100;

if (!loggedIn()) {
	echo 'You are not logged in';
	header('Location:login.php');
	exit;
}else{
	if(isAdmin()){
		include 'views/admin-dashboard.php';
	}else{
		echo 'Welcome back '.loggedUser();
		header('Location: view-my-attendance.php');
		exit;
	}
}

?>