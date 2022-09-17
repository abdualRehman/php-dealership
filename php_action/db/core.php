<?php

session_start();

require_once 'db_connect.php';

$siteurl = "http://localhost/carshop";
// $siteurl ="https://www.laughingalbattani5c25df.binfarooqtextile.com";

// date_default_timezone_set('Asia/Karachi');



$branchAdmin = '';
$salesConsultantID = '';
$salesManagerID = '';
$generalManagerID = '';
$onlineManagerID = '';
$inventorySpecialistID = '';
$officeID = '';
$deliveryCoordinatorID = '';
$bdcManagerID = '';
$ccsID = '';
$bdcSalesID = '';
$financeManagerID = '';
// $branchAdmin = '';
// $bdcManagerID = 60; // sets in the database role 
// $bdcSalesID = '61';
// $deliveryCoordinatorID = 62; // sets in the database role 
// $financeManagerID = '64';
// $officeID = 65; // sets in the database role 
// $salesConsultantID = 66; // sets in the database role 
// $salesManagerID = 67; // sets in the database role 
// $generalManagerID = 69; // sets in the database role 
// $onlineManagerID = 70; // sets in the database role 
// $inventorySpecialistID = 71; // sets in the database role 
// $ccsID = 72; // sets in the database role 

function loadDefaultRoles()
{
	global $connect;
	global $branchAdmin, $salesConsultantID, $salesManagerID, $generalManagerID, $onlineManagerID, $inventorySpecialistID, $officeID, $deliveryCoordinatorID, $bdcManagerID, $ccsID, $bdcSalesID, $financeManagerID;

	$location = $_SESSION['userLoc'];

	$checkSql = "SELECT * FROM `default_roles` WHERE location_id = '$location' AND status = 1";
	$result = $connect->query($checkSql);
	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$name = $row['role_name'];
			switch ($name) {
				case "BDC Manager":
					$bdcManagerID = $row['role_id'];
					echo "<script>localStorage.setItem('bdcManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "BDC Sales":
					$bdcSalesID = $row['role_id'];
					echo "<script>localStorage.setItem('bdcSalesID','" . $row['role_id'] . "');</script>";
					break;
				case "Client Care Specialist":
					$ccsID = $row['role_id'];
					echo "<script>localStorage.setItem('ccsID','" . $row['role_id'] . "');</script>";
					break;
				case "Delivery Coordinator":
					$deliveryCoordinatorID = $row['role_id'];
					echo "<script>localStorage.setItem('deliveryCoordinatorID','" . $row['role_id'] . "');</script>";
					break;
				case "Finance Manager":
					$financeManagerID = $row['role_id'];
					echo "<script>localStorage.setItem('financeManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "General Manager":
					$generalManagerID = $row['role_id'];
					echo "<script>localStorage.setItem('generalManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "Inventory Specialist":
					$inventorySpecialistID = $row['role_id'];
					echo "<script>localStorage.setItem('inventorySpecialistID','" . $row['role_id'] . "');</script>";
					break;
				case "Office":
					$officeID = $row['role_id'];
					echo "<script>localStorage.setItem('officeID','" . $row['role_id'] . "');</script>";
					break;
				case "Online Manager":
					$onlineManagerID = $row['role_id'];
					echo "<script>localStorage.setItem('onlineManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "Sales Consultant":
					$salesConsultantID = $row['role_id'];
					echo "<script>localStorage.setItem('salesConsultantID','" . $row['role_id'] . "');</script>";
					break;
				case "Sales Manager":
					$salesManagerID = $row['role_id'];
					echo "<script>localStorage.setItem('salesManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "Branch Admin":
					$branchAdmin = $row['role_id'];
					echo "<script>localStorage.setItem('branchAdmin','" . $row['role_id'] . "');</script>";
					break;
				default:
					break;
			}
		}
	}
}



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



	// for testing with now
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
