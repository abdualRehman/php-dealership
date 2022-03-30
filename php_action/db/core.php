<?php 

session_start();

require_once 'db_connect.php';

$siteurl ="http://localhost/carshop";
// $siteurl ="https://www.laughingalbattani5c25df.binfarooqtextile.com";

if(!$_SESSION['userId']) {
	header('location: index.php');	
} 




?>