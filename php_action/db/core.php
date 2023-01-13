<?php
ini_set('session.gc_maxlifetime', 36000);
session_start();

require_once 'db_connect.php';

$siteurl = $_SESSION['siteurl'];
// $siteurl = "http://localhost/carshop";
// $siteurl = "http://onedealersystem.com";
// $siteurl ="https://www.laughingalbattani5c25df.binfarooqtextile.com";

// date_default_timezone_set('Asia/Karachi');



$branchAdmin = isset($_SESSION['branchAdmin']) ? $_SESSION['branchAdmin'] : '';
$salesConsultantID = isset($_SESSION['salesConsultantID']) ? $_SESSION['salesConsultantID'] : '';
$salesManagerID = isset($_SESSION['salesManagerID']) ? $_SESSION['salesManagerID'] : '';
$generalManagerID = isset($_SESSION['generalManagerID']) ? $_SESSION['generalManagerID'] : '';;
$onlineManagerID = isset($_SESSION['onlineManagerID']) ? $_SESSION['onlineManagerID'] : '';
$inventorySpecialistID = isset($_SESSION['inventorySpecialistID']) ? $_SESSION['inventorySpecialistID'] : '';
$officeID = isset($_SESSION['officeID']) ? $_SESSION['officeID'] : '';
$deliveryCoordinatorID = isset($_SESSION['deliveryCoordinatorID']) ? $_SESSION['deliveryCoordinatorID'] : '';
$bdcManagerID = isset($_SESSION['bdcManagerID']) ? $_SESSION['bdcManagerID'] : '';
$ccsID = isset($_SESSION['ccsID']) ? $_SESSION['ccsID'] : '';
$bdcSalesID = isset($_SESSION['bdcSalesID']) ? $_SESSION['bdcSalesID'] : '';
$financeManagerID = isset($_SESSION['financeManagerID']) ? $_SESSION['financeManagerID'] : '';
$serviceID = isset($_SESSION['serviceID']) ? $_SESSION['serviceID'] : '';

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

	$location = $_SESSION['userLoc'];

	$checkSql = "SELECT * FROM `default_roles` WHERE location_id = '$location' AND status = 1";
	$result = $connect->query($checkSql);
	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$name = $row['role_name'];
			switch ($name) {
				case "BDC Manager":
					$_SESSION['bdcManagerID'] = $row['role_id'];
					echo "<script>localStorage.setItem('bdcManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "BDC Sales":
					$_SESSION['bdcSalesID'] = $row['role_id'];
					echo "<script>localStorage.setItem('bdcSalesID','" . $row['role_id'] . "');</script>";
					break;
				case "Client Care Specialist":
					$_SESSION['ccsID'] = $row['role_id'];
					echo "<script>localStorage.setItem('ccsID','" . $row['role_id'] . "');</script>";
					break;
				case "Delivery Coordinator":
					$_SESSION['deliveryCoordinatorID'] = $row['role_id'];
					echo "<script>localStorage.setItem('deliveryCoordinatorID','" . $row['role_id'] . "');</script>";
					break;
				case "Finance Manager":
					$_SESSION['financeManagerID'] = $row['role_id'];
					echo "<script>localStorage.setItem('financeManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "General Manager":
					$_SESSION['generalManagerID'] = $row['role_id'];
					echo "<script>localStorage.setItem('generalManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "Inventory Specialist":
					$_SESSION['inventorySpecialistID'] = $row['role_id'];
					echo "<script>localStorage.setItem('inventorySpecialistID','" . $row['role_id'] . "');</script>";
					break;
				case "Office":
					$_SESSION['officeID'] = $row['role_id'];
					echo "<script>localStorage.setItem('officeID','" . $row['role_id'] . "');</script>";
					break;
				case "Online Manager":
					$_SESSION['onlineManagerID'] = $row['role_id'];
					echo "<script>localStorage.setItem('onlineManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "Sales Consultant":
					$_SESSION['salesConsultantID'] = $row['role_id'];
					echo "<script>localStorage.setItem('salesConsultantID','" . $row['role_id'] . "');</script>";
					break;
				case "Sales Manager":
					$_SESSION['salesManagerID'] = $row['role_id'];
					echo "<script>localStorage.setItem('salesManagerID','" . $row['role_id'] . "');</script>";
					break;
				case "Branch Admin":
					$_SESSION['branchAdmin'] = $row['role_id'];
					echo "<script>localStorage.setItem('branchAdmin','" . $row['role_id'] . "');</script>";
					break;
				case "Service":
					$_SESSION['serviceID'] = $row['role_id'];
					echo "<script>localStorage.setItem('serviceID','" . $row['role_id'] . "');</script>";
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

function sendNotifiation($from , $to , $message , $appointment_id)
{
	global $connect;
	$insentiveSql = "INSERT INTO `notifications`(`from_user`, `to_user`, `message`, `link`, `is_read`, `is_delivered`, `status`) 
	VALUES ('$from' , '$to' , '$message' , $appointment_id , 0, 0, 1 )";
	$connect->query($insentiveSql);

}

// echo hasAccess("swap", "Add");

if (!isset($_SESSION['userId'])) {
	header('location: index.php');
}
