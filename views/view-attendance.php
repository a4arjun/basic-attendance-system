
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="styles/index.css">
</head>
<body>
	<?php echo sidebar('attendance');?>
	<div class="container">
		<h3>Howdy <?php echo loggedUser(); ?>!</h3>
		<hr>
		<form action="" method="get">
			<div class="row">
					<label>Start</label> <input type="date" name="start" value="<?php echo date('Y-m-d');?>">
					<label>End</label> <input type="date" name="end" value="<?php echo date('Y-m-d')?>">
					<button style=" border: 0;
		      margin-left: 4px; background: #00bfa5; color: white; border-radius: 4px">SHOW</button>

			</div>
			<hr>
		</form>
		<?php

			if (isset($_GET['start'], $_GET['end']) and strlen($_GET['start']) > 8 and strlen($_GET['end'])) {
				echo '
					<div class="row">
						<p>Showing attendance stats between &nbsp;<b>'.strip_tags($_GET['start']).'</b>  &nbsp; and  &nbsp; <b>'.strip_tags($_GET['end']).'</b>
					</div>
					'.getMyStats($db, loggedUserId(), strip_tags($_GET['start']), strip_tags($_GET['end'])).'
				';
			}else{

				echo '<div style="border: 0.5px solid #e33;padding: 8px;margin: 4px; border-radius: 4px">Please choose two dates to fetch your stats</div>';
			}

		?>
	</div>
</body>
</html>