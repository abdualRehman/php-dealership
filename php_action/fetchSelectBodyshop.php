<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `business_name`, `shop`, `address`, `city`, `state`, `zip`, `contact_person`, `contact_number`, `status` FROM `bodyshops` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
