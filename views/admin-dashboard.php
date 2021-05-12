<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="styles/index.css">
	<style>
		.progress-body{
		  margin: 4px;
		  padding: 0;
		  display: flex;
		  align-items: center;
		  justify-content: center;
		  background: rgb(13,2,208);
		  background: linear-gradient(166deg, rgba(13,2,208,1) 0%, rgba(0,212,255,1) 100%);
		  height: 400px;
		  border-radius: 10px;
		  width: 50%;
		}
		.progress {
		  width: 150px;
		  height: 150px;
		  line-height: 150px;
		  background: none;
		  margin: 0 auto;
		  box-shadow: none;
		  position: relative;
		}
		.progress:after {
		  content: "";
		  width: 100%;
		  height: 100%;
		  border-radius: 50%;
		  border: 14px solid #aaa;
		  position: absolute;
		  top: 0;
		  left: 0;
		}
		.progress > span {
		  width: 50%;
		  height: 100%;
		  overflow: hidden;
		  position: absolute;
		  top: 0;
		  z-index: 1;
		}
		.progress .progress-left {
		  left: 0;
		}
		.progress .progress-bar {
		  width: 100%;
		  height: 100%;
		  background: none;
		  border-width: 14px;
		  border-style: solid;
		  position: absolute;
		  top: 0;
		  border-color: white;
		}
		.progress .progress-left .progress-bar {
		  left: 100%;
		  border-top-right-radius: 75px;
		  border-bottom-right-radius: 75px;
		  border-left: 0;
		  -webkit-transform-origin: center left;
		  transform-origin: center left;
		}
		.progress .progress-right {
		  right: 0;
		}
		.progress .progress-right .progress-bar {
		  left: -100%;
		  border-top-left-radius: 75px;
		  border-bottom-left-radius: 75px;
		  border-right: 0;
		  -webkit-transform-origin: center right;
		  transform-origin: center right;
		}
		.progress .progress-value {
		  display: flex;
		  border-radius: 50%;
		  font-size: 36px;
		  text-align: center;
		  line-height: 20px;
		  align-items: center;
		  justify-content: center;
		  height: 100%;
		  font-weight: 300;
		}
		.progress .progress-value div {
		  margin-top: 10px;
		  color: white
		}
		.progress .progress-value span {
		  font-size: 12px;
		  text-transform: uppercase;
		}

		/* This for loop creates the 	necessary css animation names 
		Due to the split circle of progress-left and progress right, we must use the animations on each side. 
		*/
		<?php
		if($perc <= 50){
		  echo'
		  .progress .progress-right .progress-bar {
		    animation: loading-1 1.5s linear forwards;
		  }
		  .progress .progress-left .progress-bar {
		    animation: 0;
		  }
		  @keyframes loading-1 {
		    0% {
		      -webkit-transform: rotate(0deg);
		      transform: rotate(0deg);
		    }
		    100% {
		      -webkit-transform: rotate('.getDegree($perc).');
		      transform: rotate('.getDegree($perc).'deg);
		    }
		  }
		  ';
		}else{
		  echo '
		.progress .progress-right .progress-bar {
		  animation: loading-full 1.5s linear forwards;
		}
		.progress .progress-left .progress-bar {
		  animation: loading-1 1.5s linear forwards 1.5s;
		}
		  @keyframes loading-1 {
		    0% {
		      -webkit-transform: rotate(0deg);
		      transform: rotate(0deg);
		    }
		    100% {
		      -webkit-transform: rotate('.(getDegree($perc)).');
		      transform: rotate('.(getDegree($perc)).'deg);
		    }
		  }
		  @keyframes loading-full {
		    0% {
		      -webkit-transform: rotate(0deg);
		      transform: rotate(0deg);
		    }
		    100% {
		      -webkit-transform: rotate(180);
		      transform: rotate(180deg);
		    }
		  }
		  ';
		}
		?>

	.progress {
	  margin-bottom: 1em;
	}
	.flex-container{
		display: flex;
		flex-direction: row;
	}
	.column{
		flex-direction: column;
	}
</style>
</head>
<body>
	<?php echo sidebar('index');?>
	<div class="container">
		<h1>Howdy <?php echo loggedUser(); ?>!</h1>
		<hr>
		<div class="flex-container">
			<div class="progress-body">

			  	<div class="progress">
			    <span class="progress-left">
			      <span class="progress-bar"></span>
			    </span>
			    <span class="progress-right">
			      <span class="progress-bar"></span>
			    </span>
			    <div class="progress-value">
			      <div>
			      <?php echo $perc; ?>%
			      </div>
			    </div>
				</div>
			</div>		
			<div class="progress-body">
				<div class="column" style="color:white">
					<h3>Today's strength : <?php echo $todays_attendance; ?></h3><br/>
					<h3>Total Employees: <?php echo $total_employees; ?></h3>
				</div>
			</div>	
		</div>
	</div>
</body>
</html>