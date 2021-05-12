<?php
	require_once 'includes/functions.php';
	//echo logAttendance($db, 'EMP0002', '1');

	if (isset($_POST['id'], $_POST['gate'])) {
		if (isEmployeeExists($db, strip_tags($_POST['id']))) {
			echo logAttendance($db, strip_tags($_POST['id']), strip_tags($_POST['gate']));
		}
		else{
			echo 'Employee doesn\'t exist';
		}
	}else{
		echo 'Post error';
	}
?>