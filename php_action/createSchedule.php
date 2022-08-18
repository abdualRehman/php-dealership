<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
function reformatDate($date, $from_format = 'm-d-Y H:i', $to_format = 'Y-m-d H:i')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}
function reformatDateOnly($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}
if ($_POST) {

    // on create form
    $submittedBy = $_SESSION['userId'];
    $calenderId = $_SESSION['userRole'];
    $calenderId = $calenderId == "Admin" ? "1" : $calenderId;


    $scheduleDate = isset($_POST['scheduleDate']) ? mysqli_real_escape_string($connect, $_POST['scheduleDate']) : "";
    $scheduleDate = $scheduleDate != "" ? reformatDateOnly($scheduleDate) : "";

    $stockno = mysqli_real_escape_string($connect, $_POST['stockno']);
    $sale_id = isset($_POST['sale_id']) ? mysqli_real_escape_string($connect, $_POST['sale_id']) : "";
    $overrideBy = isset($_POST['overrideBy']) ? $_SESSION['overrideById'] : "";
    $coordinator = isset($_POST['coordinator']) ? mysqli_real_escape_string($connect, $_POST['coordinator']) : "";
    $delivery = (isset($_POST['delivery'])) ? mysqli_real_escape_string($connect, $_POST['delivery']) : "";
    $additionalServices = (isset($_POST['additionalServices'])) ? mysqli_real_escape_string($connect, $_POST['additionalServices']) : "";
    $scheduleNotes = (isset($_POST['scheduleNotes'])) ? mysqli_real_escape_string($connect, $_POST['scheduleNotes']) : "";
    $confirmed = (isset($_POST['confirmed'])) ? mysqli_real_escape_string($connect, $_POST['confirmed']) : "";
    $complete = (isset($_POST['complete'])) ? mysqli_real_escape_string($connect, $_POST['complete']) : "";



    $scheduleTime = mysqli_real_escape_string($connect, $_POST['scheduleTime']);


    $scheduleStart = $scheduleDate . ' ' . $scheduleTime;
    // $scheduleEnd = strtotime((string)$scheduleStart . ':00 +30 minute');
    $scheduleEnd = strtotime((string)$scheduleStart . ':00 +59 minute');
    $scheduleEnd =  date('Y-m-d H:i:s', $scheduleEnd);


    // $already_appointed = false;
    // // check if coordinator has any confirm appointment in time range
    // if ($_SESSION['userRole'] == $deliveryCoordinatorID || $_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == $generalManagerID || $_SESSION['userRole'] == $salesManagerID) {
    //     $checkSql = "SELECT * FROM `appointments` WHERE coordinator = '$coordinator' AND status = 1 AND confirmed = 'ok' AND cast(schedule_start as datetime )<= '$scheduleStart' and cast(schedule_end as datetime) >= '$scheduleStart'";
    //     $result2 = $connect->query($checkSql);
    //     if ($result2->num_rows > 0) {
    //         $already_appointed = true;
    //         $valid['success'] = false;
    //         $valid['messages'] = "You already have an appointment at that time";
    //     }
    // }

    if ($_SESSION['userRole'] != $deliveryCoordinatorID) {
        $insentiveSql = "INSERT INTO `appointments` ( 
            `sale_id`, `stock_id`, `appointment_date`, `appointment_time`, 
            `coordinator`, `delivery`, `additional_services`, `notes`, `submitted_by`, `manager_override`, 
            `confirmed`, `complete`, `schedule_start`, `schedule_end`, `calender_id`, `status`
            ) VALUES (
                '$sale_id' , '$stockno' , '$scheduleDate' , '$scheduleTime',
                '$coordinator' , '$delivery' , '$additionalServices' , '$scheduleNotes' , '$submittedBy' , '$overrideBy',
                '$confirmed' , '$complete' , '$scheduleStart' , '$scheduleEnd' , '$calenderId' , 1
            )";

        if ($connect->query($insentiveSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Access Denied!";
    }






    $connect->close();
    echo json_encode($valid);
} // /if $_POST
