<?php
function sidebar($selected){
	$home_url = '<a href="index.php">Dashboard</a>';
	$daily_reports = '<a href="report-today.php">Todays report</a>';
	$monthly_reports = '<a href="report-monthly.php">Monthly Report</a>';
	$yearly_reports = '<a href="report-yearly.php">Yearly Report</a>';
	$attendance = '<a href="view-my-attendance.php">View My Attendance</a>';
	$search_employees = '<a href="search-employees.php">Search Employees</a>';

	switch ($selected) {
		case 'index':
			$home_url = '<a href="index.php" class="active">Dashboard</a>';
			break;
		case 'daily':
			$daily_reports = '<a href="report-today.php" class="active">Todays report</a>';
			break;
		case 'monthly':
			$monthly_reports = '<a href="report-monthly.php" class="active">Monthly Report</a>';
			break;
		case 'yearly':
			$yearly_reports = '<a href="report-yearly.php" class="active">Yearly Report</a>';
			break;
		case 'attendance':
			$attendance = '<a href="view-my-attendance.php" class="active">View My Attendance</a>';
			break;
		case 'employees':
			$search_employees = '<a href="search-employees.php" class="active">Search Employees</a>';
			break;
	}
	$admin_only = '
		<div class="sidebar">
			<ul>
				<li>'.$home_url.'</li>
				<li>'.$daily_reports.'</li>
				<li>'.$monthly_reports.'</li>
				<li>'.$yearly_reports.'</li>
				<li>'.$attendance.'</li>
				<li>'.$search_employees.'</li>
				<li style="bottom:0;position:fixed; display:block"><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	';

	$user_only = '
	<div class="sidebar">
		<ul>
			<li>'.$attendance.'</li>
			<li style="bottom:0;position:fixed; display:block"><a href="logout.php">Logout</a></li>
		</ul>
	</div>';

	if (isAdmin()) {
		return $admin_only;
	}
	else{
		return $user_only;
	}
}