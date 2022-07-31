<?php 	

require_once 'db/core.php';

$id = $_POST['id'];


$sql = "SELECT id ,today_date , today_availability , off_notes FROM `schedule` WHERE `id` = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
