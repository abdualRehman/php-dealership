<?php 

require_once 'php_action/db/core.php';

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 
// echo

header('location: index.php');


?>