<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `f_24-36`, `f_37-48`, `f_49-60`, `f_61-72`, `f_659_610_24-36`, `f_659_610_37-60`, `f_659_610_61-72`, `f_expire`, `lease_660`, `lease_659_610`, `lease_one_pay_660`, `lease_one_pay_659_610`, `lease_expire`, `status` FROM `rate_rule` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
