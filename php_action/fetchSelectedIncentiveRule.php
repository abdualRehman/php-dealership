<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `from_date`, `to_date`, `model`, `year`, `modelno`, `type`, `college`, `military`, `loyalty`, `conquest`, `misc1`, `misc2`, `misc3`, `status` FROM `incentive_rules` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
