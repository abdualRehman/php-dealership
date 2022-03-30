<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `dealer_no`, `dealership`, `address`, `city`, `state`, `zip`, `miles`, `travel_time`, `round_trip`, `phone`, `fax`, `main_contact`, `cell`, `preffer`, `status` FROM `locations` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
