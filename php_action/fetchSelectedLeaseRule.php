<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `24`, `27`, `30`, `33`, `36`, `39`, `42`, `45`, `48`, `51`, `54`, `57`, `60`, `12_24_33`, `12_36_48`, `10_24_33`, `10_36_48`, `status` FROM `lease_rule` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
//  $row = $result->fetch_array();
 $row = $result->fetch_assoc();
} // if num_rows

$connect->close();

echo json_encode($row);
