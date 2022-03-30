<?php 	

require_once 'db/core.php';

$userId = $_POST['userId'];

$sql = "SELECT username, email, password, role FROM users WHERE id = $userId";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
