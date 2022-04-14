<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `destination`, `hb`, `status` FROM `matrix_rule` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
