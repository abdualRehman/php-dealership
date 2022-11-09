<?php

require_once 'db/core.php';

// date_default_timezone_set('Asia/Karachi');

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';


$salesConsultantID = $_SESSION['salesConsultantID'];
$deliveryCoordinatorID = $_SESSION['deliveryCoordinatorID'];

$sql = "SELECT schedule.* , users.username , users.role , role.role_name 
FROM users LEFT JOIN schedule ON users.id = schedule.user_id LEFT JOIN role ON users.role = role.role_id 
WHERE users.status = 1 AND users.location = '$location' AND ( users.role = '$salesConsultantID' OR users.role = '$deliveryCoordinatorID') ORDER BY users.username ASC";

// echo $sql . '<br />';
// echo $salesConsultantID . '<br />';

$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    // $row = $result->fetch_array();
    while ($row = $result->fetch_assoc()) {

        $user = $row['username'];
        $role = $row['role_name'];


        $today = date('l'); // wednesday
        $today = substr(strtolower($today), 0, 3);
        $start =  $today . '_start';
        $end =  $today . '_end';
        // $date = date('h:ia'); // 12:00am

        $todayTimings = array('start' => $row[$start], 'end' => $row[$end], 'previous_date' => $row['today_date']);

    
        $manager = $row['manager'];
        $off_notes = $row['off_notes'];
        $status = $row['today_availability'];

        $manager = $row['manager'];
        if (!is_null($manager) &&  $manager != "") {
            $statusSql = "SELECT username FROM users WHERE id = '$manager'";
            $rslt = $connect->query($statusSql);
            if ($rslt->num_rows > 0) {
                while ($r = $rslt->fetch_assoc()) {
                    $manager = $r['username'];
                }
            }
        }



        $id = $row['id'];
        $output['data'][] = array(
            $id,
            $row['mon_start'],
            $row['mon_end'],
            $row['tue_start'],
            $row['tue_end'],
            $row['wed_start'],
            $row['wed_end'],
            $row['thu_start'],
            $row['thu_end'],
            $row['fri_start'],
            $row['fri_end'],
            $row['sat_start'],
            $row['sat_end'],
            $row['sun_start'],
            $row['sun_end'],
            $user,
            $status,
            $off_notes,
            $manager,
            $role,
            $todayTimings,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
