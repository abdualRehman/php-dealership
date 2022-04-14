<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `calcfrom`, `calculation`, `num_to_calc`, `status` FROM `bdc_rules` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
