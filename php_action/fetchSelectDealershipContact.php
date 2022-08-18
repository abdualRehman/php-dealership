<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `brand`, `dealership`, `address`, `city`, `state`, `zip`, `telephone`, `fax` , `gmanager`, `gmanager_contact`, `usedcmanager`, `usedcmanager_contact` FROM `dealerships` WHERE id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
