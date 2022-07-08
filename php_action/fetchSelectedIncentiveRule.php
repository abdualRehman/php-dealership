<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT * FROM `incentive_rules` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_assoc();
} // if num_rows

$connect->close();

echo json_encode($row);
