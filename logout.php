<?php
//include config
include_once 'includes/functions.php';
session_unset();
session_destroy();
header('Location: index.php'); 

?>