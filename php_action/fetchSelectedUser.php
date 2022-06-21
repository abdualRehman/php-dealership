<?php 	

require_once 'db/core.php';

$userId = $_POST['userId'];

// $sql = "SELECT username, email, password, role FROM users WHERE id = $userId";
$sql = "SELECT users.username , users.email, users.role , users.location, users.extention , users.mobile , schedule.* FROM `users` LEFT JOIN schedule ON users.id = schedule.user_id WHERE users.id = '$userId'";

$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_assoc();
} // if num_rows

$connect->close();

echo json_encode($row);
