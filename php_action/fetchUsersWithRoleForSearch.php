<?php

require_once 'db/core.php';

$id = $_POST['id'];

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
// $sql = "SELECT id , username , email FROM `users` LEFT JOIN role ON users.role = role.role_id WHERE role.role_id = '$id' AND users.status = 1";
$sql = "SELECT users.id , username , email , schedule.mon_start ,schedule.mon_end , schedule.tue_start ,schedule.tue_end , schedule.wed_start ,schedule.wed_end , 
schedule.thu_start, schedule.thu_end , schedule.fri_start,schedule.fri_end , schedule.sat_start , schedule.sat_end , schedule.sun_start,schedule.sun_end , 
schedule.today_date , schedule.today_availability FROM `users` LEFT JOIN role ON users.role = role.role_id LEFT JOIN schedule ON users.id = schedule.user_id WHERE role.role_id = '$id' AND users.location = '$location' AND users.status = 1 ORDER BY users.username ASC";
$result = $connect->query($sql);
$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $user_id = $row[0];
        $appointments = array();
        if ($id == $deliveryCoordinatorID) {
            $sql1 = "SELECT appointment_date, appointment_time, schedule_start, schedule_end FROM `appointments` WHERE coordinator = '$user_id' AND confirmed = 'ok' AND status = 1";
            $result1 = $connect->query($sql1);
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    $scheduleStart = date('Y-m-d H:i:s', strtotime($row1['schedule_start']  . ' -59 minute'));
                    $appointments[] = array(
                        // "schedule_start" => $row1['schedule_start'],
                        "schedule_start" => $scheduleStart,
                        "schedule_end" => $row1['schedule_end'],
                    );
                }
            }
        }



        // $today_date = $row[17];
        // $todayDate = date('m-d-Y');
        // if (strtotime($today_date) == strtotime($todayDate)) {
        //     echo "The date is today's date";
        // } else {
        //     echo "The date is not today's date";
        // }

        $today_availability = $row[18];
        $available_today = true;
        if ($today_availability == 'Lunch') {
            $available_today = false;
        }



        $schedule = array(
            'monday' => array($row[3], $row[4]),
            'tuesday' => array($row[5], $row[6]),
            'wednesday' => array($row[7], $row[8]),
            'thursday' => array($row[9], $row[10]),
            'friday' => array($row[11], $row[12]),
            'saturday' => array($row[13], $row[14]),
            'sunday' => array($row[15], $row[16]),
        );
        $output['data'][] = array(
            $user_id,
            $row[1],  //username
            $row[2],  // email
            $schedule,
            $appointments,
            $available_today,
        );
    } // /while 
} // if num_rows

$connect->close();
echo json_encode($output);
