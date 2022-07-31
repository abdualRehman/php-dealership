<?php

session_start();

require_once 'db_connect.php';

$siteurl = "http://localhost/carshop";
// $siteurl ="https://www.laughingalbattani5c25df.binfarooqtextile.com";

$salesConsultantID = 66; // sets in the database role 
$salesManagerID = 67; // sets in the database role 
$generalManagerID = 69; // sets in the database role 
$onlineManagerID = 70; // sets in the database role 
$inventorySpecialistID = 71; // sets in the database role 
$officeID = 65; // sets in the database role 
$deliveryCoordinatorID = 62; // sets in the database role 
$bdcManagerID = 60; // sets in the database role 
$ccsID = 72; // sets in the database role 

function hasAccess($module, $function)
{
	$permissionsArray = $_SESSION['permissionsArray'];
	$search = ['modules' => $module, 'functions' => $function];
	$keys = array_keys(
		array_filter(
			$permissionsArray,
			function ($v) use ($search) {
				return $v['modules'] == $search['modules'] && $v['functions'] == $search['functions'];
			}
		)
	);
	if ($keys) {
		return $permissionsArray[$keys[0]]['permission'];
	}



	// // for testing with now
	// global $connect;
	// $roleId = $_SESSION['userRole'];
	// $checkSql = "SELECT permission FROM `role_mod` WHERE role_id = '$roleId' AND modules = '$module' AND functions = '$function'";
	// $result = $connect->query($checkSql);
	// if ($result && $result->num_rows > 0) {
	// 	$row = $result->fetch_array();
	// 	return $row[0];
	// }
}

// echo hasAccess("swap", "Add");

if (!$_SESSION['userId']) {
	header('location: index.php');
}
