<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Kolkata");

define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','kav');

$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


function getEmployeesCount($db){
	try {
	  $stmt = $db->query('SELECT * FROM employees');
	  $total_employees = $stmt->rowCount();
	  return $total_employees;

	} catch(PDOException $e) {
	  echo $e->getMessage();
	}
}


function getAllEmployees($db){
  try {
    $stmt = $db->query('SELECT * FROM employees');
    
    $employee_ids = array();

    if ($stmt->rowCount() > 0) {
      while ($row = $stmt->fetch()) {
        array_push($employee_ids, $row['employee_id']);
      }
    }
   
    return $employee_ids;

  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function getAttendance($db){
  try {
    $stmt = $db->prepare('SELECT * FROM attendance_log WHERE punch_date = :punch_date AND punch_type = :type');
    $stmt->execute(array(
      ':punch_date' => date('Y-m-d'),
      ':type' => 'IN',
    ));

    return $stmt->rowCount();
    
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function isEmployeeExists($db, $employee_id){
  try {
    $stmt = $db->prepare('SELECT * FROM employees WHERE employee_id = :employee_id');
    $stmt->execute(array(
      ':employee_id' => strip_tags($employee_id)
    ));

    if ($stmt->rowCount()) {
      return true;
    }else{
      return false;
    }
    
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function getEmployeeName($db, $employee_id){
  try {
    $stmt = $db->prepare('SELECT * FROM employees WHERE employee_id = :employee_id');
    $stmt->execute(array(
      ':employee_id' => strip_tags($employee_id)
    ));

    $name = $stmt->fetch();
    return $name['employee_name'];
    
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function getDailyStats($db, $day){
  try {
    $stmt = $db->prepare('SELECT * FROM attendance_log WHERE punch_date = :punch_date AND punch_type = :type');
    $stmt->execute(array(
      ':punch_date' => date('Y-m-d'),
      ':type' => 'IN',
    ));
    $data = '

        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>Date</th>
              <th>Name</th>
              <th>Shift start</th>
              <th>Punch IN</th>
              <th>Early/Late IN</th>
              <th>Shift End</th>
              <th>Punch OUT</th>
              <th>Early/Late OUT</th>
              <th>Entry Point</th>
              <th>Exit Point</th>
            </tr>
          </thead>
          <tbody>
            
    ';

    while ($row = $stmt->fetch()) {

      $in = date_create(getInPunchTime($db, $row['employee_id'], $row['punch_date']));
      $in = date_format($in, 'H:i');
      if (getOutPunchTime($db, $row['employee_id'], $row['punch_date']) != 'Working') {
        $out = date_create(getOutPunchTime($db, $row['employee_id'], $row['punch_date']));
        $out = date_format($out, 'H:i');
      }else{
        $out = 'Working';
      }

      $shift_start = strtotime('07:00:00');
      $shift_end = strtotime('17:00:00');

      $in_diff = round((strtotime($in) - $shift_start) / 60);
      

      if ($in_diff < 0) {
        $in_diff = abs($in_diff);
        $in_diff = '<font color="green">+'.$in_diff.' minutes</font>';
      }else{
        $in_diff = -$in_diff;
        $in_diff = '<font color="red">'.$in_diff.' minutes</font>';
      }
      
      if ($out != 'Working') {
        $out_diff = round((strtotime($out) - $shift_end) / 60);
        if ($out_diff < 0) {
          $out_diff = '<font color="red">'.$out_diff.' minutes</font>';
        }else{
          $out_diff = '<font color="green">'.$out_diff.' minutes</font>';
        }
      }else{
        $out_diff = 'Working';
      }


      $data .= '
      <tr>
        <td>'.$row['punch_date'].'</td>
        <td>'.getEmployeeName($db, $row['employee_id']).'</td>
        <td>07:00</td>
        <td>'.$in.'</td>
        <td>'.$in_diff.'</td>
        <td>17:00</td>
        <td>'.$out.'</td>
        <td>'.$out_diff.'</td>
        <td>Gate '.$row['gate_number'].'</td>
        <td>Gate '.$row['gate_number'].'</td>
      </tr>
    ';
    }

    $data .= '</tbody></table><br><br>';
    $data .= getAbsent($db);
    return $data;
    
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}

function employeeLogin($db, $username, $password){
  try {
    $stmt = $db->prepare('SELECT * FROM employees WHERE employee_id = :employee_id AND password = :password');
    $stmt->execute(array(
      ':employee_id' => strip_tags($username),
      ':password' => strip_tags(md5($password))
    ));
    

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch();
      $_SESSION['id'] = $row['employee_id'];
      $_SESSION['user'] = $row['employee_name'];
      $_SESSION['user_role'] = $row['employee_role'];

      header('Location: index.php');
      exit;
    }
    else
      return 'false';

  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function loggedIn(){
  $logged = true;
  if (isset($_SESSION['user'], $_SESSION['user'], $_SESSION['user_role'])) {
    return $logged;
  }
  else{
    return false;
  }
}

function isAdmin(){
  if (loggedIn()) {
    if ($_SESSION['user_role'] == 'manager') {
      return true;
    }
    else{
      return false;
    }
  }
}

function loggedUser(){
  if (loggedIn()) {
    return $_SESSION['user'];
  }
}

function loggedUserId(){
  if (loggedIn()) {
    return $_SESSION['id'];
  }
}


function getMonthlyStats($db){
  try {
    $stmt = $db->prepare('SELECT * FROM attendance_log WHERE (punch_date BETWEEN :month_start AND :month_end) AND punch_type = :type ORDER BY punch_date DESC');
    $stmt->execute(array(
      ':month_start' => date('Y-m-1 H:i:s'),
      ':month_end' => date('Y-m-d H:i:s'),
      ':type' => 'IN'
    ));
    $data = '

        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>Date</th>
              <th>Name</th>
              <th>Shift start</th>
              <th>Punch IN</th>
              <th>Early/Late IN</th>
              <th>Shift End</th>
              <th>Punch OUT</th>
              <th>Early/Late OUT</th>
              <th>Entry Point</th>
              <th>Exit Point</th>
            </tr>
          </thead>
          <tbody>
            
    ';

    while ($row = $stmt->fetch()) {
      $in = date_create(getInPunchTime($db, $row['employee_id'], $row['punch_date']));
      $in = date_format($in, 'H:i');
      if (getOutPunchTime($db, $row['employee_id'], $row['punch_date']) != 'Working') {
        $out = date_create(getOutPunchTime($db, $row['employee_id'], $row['punch_date']));
        $out = date_format($out, 'H:i');
      }else{
        $out = 'Working';
      }

      $shift_start = strtotime('07:00:00');
      $shift_end = strtotime('17:00:00');

      $in_diff = round((strtotime($in) - $shift_start) / 60);
      

      if ($in_diff < 0) {
        $in_diff = abs($in_diff);
        $in_diff = '<font color="green">+'.$in_diff.' minutes</font>';
      }else{
        $in_diff = -$in_diff;
        $in_diff = '<font color="red">'.$in_diff.' minutes</font>';
      }
      
      if ($out != 'Working') {
        $out_diff = round((strtotime($out) - $shift_end) / 60);
        if ($out_diff < 0) {
          $out_diff = '<font color="red">'.$out_diff.' minutes</font>';
        }else{
          $out_diff = '<font color="green">'.$out_diff.' minutes</font>';
        }
      }else{
        $out_diff = 'Working';
      }


      $data .= '
      <tr>
        <td>'.$row['punch_date'].'</td>
        <td>'.getEmployeeName($db, $row['employee_id']).'</td>
        <td>07:00</td>
        <td>'.$in.'</td>
        <td>'.$in_diff.'</td>
        <td>17:00</td>
        <td>'.$out.'</td>
        <td>'.$out_diff.'</td>
        <td>Gate '.$row['gate_number'].'</td>
        <td>Gate '.$row['gate_number'].'</td>
      </tr>
    ';
     }
     $data .= '</tbody></table>';

     return $data;
    
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function getYearlyStats($db){
  try {
    $stmt = $db->prepare('SELECT * FROM attendance_log WHERE (punch_date BETWEEN :year_start AND :month_end) AND punch_type = :type');
    $stmt->execute(array(
      ':year_start' => date('Y-01-01 H:i:s'),
      ':month_end' => date('Y-m-d H:i:s'),
      ':type' => 'IN'
    ));
    $data = '

        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>EmpID</th>
              <th>Name</th>
              <th>Date</th>
              <th>Punch IN</th>
              <th>Punch OUT</th>
              <th>Shift</th>
            </tr>
          </thead>
          <tbody>
            
    ';

    while ($row = $stmt->fetch()) {
      $in = date_create(getInPunchTime($db, $row['employee_id'], $row['punch_date']));
      $in = date_format($in, 'H:i:s');

      if (getOutPunchTime($db, $row['employee_id'], $row['punch_date']) != 'Working') {
        $out = date_create(getOutPunchTime($db, $row['employee_id'], $row['punch_date']));
        $out = date_format($out, 'H:i:s');
      }else{
        $out = 'Working';
      }
      
      $data .= '
        <tr>
          <td>'.$row['employee_id'].'</td>
          <td>'.getEmployeeName($db, $row['employee_id']).'</td>
          <td>'.$row['punch_date'].'</td>
          <td>'.$in.'</td>
          <td>'.$out.'</td>
          <td>A</td>
        </tr>
      ';
     }
     $data .= '</tbody></table>';

     return $data;
    
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function getAbsent($db){
  try {
    $stmt = $db->prepare('SELECT * FROM attendance_log WHERE punch_date = :punch_date AND punch_type = :type');
    $stmt->execute(array(
      ':punch_date' => date('Y-m-d'),
      ':type' => 'IN',
    ));

    $present = array();
    $absent = array();
    $all_employees = getAllEmployees($db);

    while ($row = $stmt->fetch()) {
      array_push($present, $row['employee_id']);
    }
    // print_r($present);
    $absent = array_diff($all_employees, $present);
    $data = 'People who\'s absent today:<br/><br/><table><thead><tr><th style="width:150px">EmpID</th><th>Name</th></tr></thead><tbody>';
    foreach ($absent as $employee) {
      $data .= '<tr>
      <td>'.$employee.'</td>
      <td>'.getEmployeeName($db, $employee).'</td>
      </tr>';
    }
    $data .= '</tbody></table>';

    return $data;
    
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}


function getMyStats($db, $employee_id, $start_date, $end_date){
  try {
      $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND (punch_date BETWEEN :start AND :end_date) AND punch_type = :type');
      $stmt->execute(array(
        ':employee_id' => $employee_id,
        ':start' => strip_tags($start_date),
        ':end_date' => strip_tags($end_date),
        ':type' => 'IN'
      ));

      $data = '
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>EmpID</th>
              <th>Name</th>
              <th>Date</th>
              <th>Punch IN</th>
              <th>Punch OUT</th>
              <th>Shift</th>
            </tr>
          </thead>
          <tbody>
      ';

      while ($row = $stmt->fetch()) {
        $in = date_create(getInPunchTime($db, $row['employee_id'], $row['punch_date']));
        $in = date_format($in, 'H:i:s');

        if (getOutPunchTime($db, $row['employee_id'], $row['punch_date']) != 'Working') {
          $out = date_create(getOutPunchTime($db, $row['employee_id'], $row['punch_date']));
          $out = date_format($out, 'H:i:s');
        }else{
          $out = 'Working';
        }
        
        $data .= '
          <tr>
            <td>'.$row['employee_id'].'</td>
            <td>'.getEmployeeName($db, $row['employee_id']).'</td>
            <td>'.$row['punch_date'].'</td>
            <td>'.$in.'</td>
            <td>'.$out.'</td>
            <td>A</td>
          </tr>
        ';
      }

      $data .= '</tbody></table>';

      return $data;

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function punchStatus($db, $employee_id){
  try {
      $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND punch_date = :punch_date');
      $stmt->execute(array(
        ':employee_id' => $employee_id,
        ':punch_date' => date('Y-m-d')
      ));

      $count = $stmt->rowCount();
      if ($count % 2 == 0) {
      	return 'IN';
      }
      else{
      	return 'OUT';
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function getPunchCount($db, $employee_id) {
  try {
      $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND punch_date = :punch_date');
      $stmt->execute(array(
        ':employee_id' => $employee_id,
        ':punch_date' => date('Y-m-d')
      ));

      $count = $stmt->rowCount();

      return $count;

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function getLastUpdatedId($db, $employee_id){
  try {
      $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND punch_date = :punch_date ORDER BY id DESC');
      $stmt->execute(array(
        ':employee_id' => $employee_id,
        ':punch_date' => date('Y-m-d')
      ));

      $id = $stmt->fetch();

      return $id['id'];

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function logAttendance($db, $employee_id, $gate_number){
	if(getPunchCount($db, $employee_id) < 2){
	 	try {
	    $stmt = $db->prepare('INSERT INTO attendance_log (employee_id, punch_time, punch_type, gate_number, punch_date) VALUES (:employee_id, :punch_time, :punch_type, :gate_number, :punch_date)') ;
	    $stmt->execute(array(
	      ':employee_id' => strip_tags($employee_id),
	      ':punch_type' => punchStatus($db, $employee_id, $gate_number),
	      ':gate_number' => strip_tags($gate_number),
	      ':punch_time' => date('Y-m-d H:i:s'),
	      ':punch_date' => date('Y-m-d')
	    ));

	    header('Location: index.php?added=true');
	    exit;

	  } catch(PDOException $e) {
	      echo $e->getMessage();
	  }
  }else{
	  try {
	    $stmt = $db->prepare('UPDATE attendance_log SET punch_time = :punch_time WHERE id = :id') ;
	    $stmt->execute(array(
	      ':punch_time' => date('Y-m-d H:i:s'),
	      ':id' => getLastUpdatedId($db, $employee_id)
	    ));

	    echo "Punch time updated";

	  } catch(PDOException $e) {
	      echo $e->getMessage();
	  }
  }
}


// function checkInPunch($db, $employee_id, $date){
//   try {
//       $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND punch_date = :punch_date');
//       $stmt->execute(array(
//         ':employee_id' => $employee_id,
//         ':punch_date' => $date
//       ));

//       $hours = array();
//       while($row = $stmt->fetch()){
//       	array_push($hours, $row['punch_time']);
//       }

//       $shift_start = new DateTime($hours[0]);
//       $work_finished = new DateTime($hours[1]);

//       $total_time = $work_started->diff($work_finished);

//       echo $total_time->format('%H:%I:%S');

//   } catch(PDOException $e) {
//       echo $e->getMessage();
//   }
// }


function getInPunchTime($db, $employee_id, $date) {
  try {
      $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND punch_date = :punch_date AND punch_type = :type');
      $stmt->execute(array(
        ':employee_id' => $employee_id,
        ':punch_date' => $date,
        ':type' => 'IN'
      ));

      if ($stmt->rowCount() > 0) {

      	$row = $stmt->fetch();

      	return $row['punch_time'];
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function getOutPunchTime($db, $employee_id, $date) {
  try {
      $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND punch_date = :punch_date AND punch_type = :type');
      $stmt->execute(array(
        ':employee_id' => $employee_id,
        ':punch_date' => $date,
        ':type' => 'OUT'
      ));

      if ($stmt->rowCount() > 0) {

      	$row = $stmt->fetch();

      	return $row['punch_time'];
      }else{
        return 'Working';
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}



function getTotalWorkHours($db, $employee_id, $date){
  try {
      $stmt = $db->prepare('SELECT * FROM attendance_log WHERE employee_id = :employee_id AND punch_date = :punch_date');
      $stmt->execute(array(
        ':employee_id' => $employee_id,
        ':punch_date' => $date
      ));

      $work_started = new DateTime(getInPunchTime($db, $employee_id, $date));
      $work_finished = new DateTime(getOutPunchTime($db, $employee_id, $date));

      $total_time = $work_started->diff($work_finished);

      echo $total_time->format('%H:%I:%S');

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}

function getDegree($perc){
  if($perc <= 50){
    $perc = ((($perc/100)*360));
    return $perc;
  }

  $perc = ((($perc/100)*360)-180);
  return $perc;
}

function currentMonth($month){
  $dateObject   = DateTime::createFromFormat('!m', $month);
  $month = $dateObject->format('F');
  return $month;
}
?>