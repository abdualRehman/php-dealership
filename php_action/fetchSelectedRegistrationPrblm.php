<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `contract_date`, `problem_date`, `customer_name`, `sales_consultant`, `finance_manager`, `stock_id`, `vehicle`, `problem`, `notes`, 
`status` FROM `registration_problems` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
