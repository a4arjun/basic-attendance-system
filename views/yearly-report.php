<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="styles/index.css">
</head>
<body>
	<?php echo sidebar('yearly'); ?>
	<div class="container">
		<h3>Yearly reports</h3>
		<p>Summary Report of 2021</p>
		<hr>
		<div class="row">
		</div>
		<?php echo getYearlyStats($db); ?>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">$(document).ready( function () {
    $('table').DataTable(
    	{'order': [[2, 'desc']]}
    );

} );</script>
</body>
</html>