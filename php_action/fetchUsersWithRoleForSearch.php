<?php

require_once 'db/core.php';

$id = $_POST['id'];
// $sql = "SELECT id , username , email FROM `users` LEFT JOIN role ON users.role = role.role_id WHERE role.role_id = '$id' AND users.status = 1";
$sql = "SELECT users.id , username , email , schedule.mon_start ,schedule.mon_end , schedule.tue_start ,schedule.tue_end , schedule.wed_start ,schedule.wed_end , schedule.thu_start, schedule.thu_end , schedule.fri_start,schedule.fri_end , schedule.sat_start , schedule.sat_end , schedule.sun_start,schedule.sun_end FROM `users` LEFT JOIN role ON users.role = role.role_id LEFT JOIN schedule ON users.id = schedule.user_id WHERE role.role_id = '$id' AND users.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $schedule = array(
            'monday' => array($row[3] , $row[4]),
            'tuesday' => array($row[5] , $row[6]),
            'wednesday' => array($row[7] , $row[8]),
            'thursday' => array($row[9] , $row[10]),
            'friday' => array($row[11] , $row[12]),
            'saturday' => array($row[13] , $row[14]),
            'sunday' => array($row[15] , $row[16]),
        );
        $output['data'][] = array(
            $row[0],  // id
            $row[1],  //username
            $row[2],  // email
            $schedule,
        );
    } // /while 
} // if num_rows

$connect->close();
echo json_encode($output);
