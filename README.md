# basic-attendance-system

<b>Installation</b>
<ul>
  <li>Copy files to the project directory inside your htdocs or server root.</li>
  <li>Import db.sql using Adminer/phpmyadmin or create it manually*</li>
  <li>Edit includes/functions.php matching your configuration</li>
</ul>


*by default, db.sql contains several employee data. Passwords are md5 encrypted. <br>*

Default admin user : EMP0001<br>
Default admin pass : password


<h3>Usage</h3>

Attendance is logged through http post request on /api.log.php<br/>

<b>(Try: curl -X POST -d "id=EMP0003&gate=1" http://<path_to_project_directory>/api.log.php)</b>

<b>id</b> is the employee id and the <b>gate</b> is the gate number through which employee enters or leaves.

<h3>Screenshots</h3>

1. Login page

![Login page](https://github.com/a4arjun/basic-attendance-system/blob/main/screenshots/login.png?raw=true)



2. Admin dashboard

![Dashboard](https://github.com/a4arjun/basic-attendance-system/blob/main/screenshots/admin-dashboard.png?raw=true)



3. Daily reports

![Daily reports](https://github.com/a4arjun/basic-attendance-system/blob/main/screenshots/admin-daily-reports.png?raw=true)

