<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="styles/index.css">
</head>
<body>
	<?php echo sidebar('daily');?>
	<div class="container">
		<h3>Today's report</h3>
		<hr>
		<div class="row">
			<p><b>Date:</b> <?php echo date('Y-m-d'); ?>
		</div>
		<?php echo getDailyStats($db, date('Y-m-d')); ?>
	</div>
</body>
</html>