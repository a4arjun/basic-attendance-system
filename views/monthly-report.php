<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="styles/index.css">
</head>
<body>
	<?php echo sidebar('monthly');?>
	<div class="container">
		<h3>Monthly reports</h3>
		<hr>
		<div class="row">
			<label>Month:</label>
			<input type="text" value="<?php echo currentMonth(date('m'));?>" disabled>
		</div>
		<?php echo getMonthlyStats($db); ?>
	</div>
</body>
</html>