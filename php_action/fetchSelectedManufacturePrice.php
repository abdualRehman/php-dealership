<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `year`, `model`, `model_code`, `msrp`, `dlr_inv`, `model_des`, `trim`, `net`, `hb`, `invoice`, `bdc`, `status` FROM `manufature_price` WHERE id = '$id' ";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
