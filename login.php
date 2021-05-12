<?php require_once 'includes/functions.php';
if (loggedIn()) {
	header('Location: index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Employee Login</title>
	<link rel="stylesheet" type="text/css" href="styles/index.css">
	<style type="text/css">
		.login{
			display: flex;
			justify-content: center;
			align-content: center;
		}
		.card{
			background: #fff;
			box-shadow: 0 0 5px #aaa;
			padding: 20px;
			border-radius: 10px;
			margin-top: 10%;
			min-width: 300px;
		}

		button{
			border:0;
			background: #0277bd;
			color: white;
			padding: 8px;
			margin: 4px;
		}
	</style>
</head>
<body>
	<?php 

		if (isset($_POST['username'], $_POST['password'])) {
			print_r(employeeLogin($db, $_POST['username'], $_POST['password']));
		}

	?>
	<div class="login">
		<div class="card">
			<h4>Employee Login</h4>
			<form action="" method="post">
				<input type="text" name="username" size="50%" placeholder="Employee ID"><br/>
				<input type="password" name="password" size="50%" placeholder="Password"><br/>
				<button>LOGIN</button>				
			</form>
		</div>
	</div>
</body>
</html>