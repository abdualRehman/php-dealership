<?php 	

require_once 'db/core.php';

$id = $_POST['id'];


// $sql = "SELECT `id` , `mon_start`, `mon_end`, `tue_start`, `tue_end`, `wed_start`, `wed_end`, `thu_start`, `thu_end`, `fri_start`, `fri_end`, `sat_start`, `sat_end`, `sun_start`, `sun_end`, `today_date` , `today_availability` , `off_notes` FROM `schedule` WHERE `id` = '$id'";
$sql = "SELECT users.username , schedule.*  FROM `schedule` LEFT JOIN users ON schedule.user_id = users.id WHERE `schedule`.`id` = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
