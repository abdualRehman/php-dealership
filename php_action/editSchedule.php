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
    $scheduleId = $_POST['scheduleId'];

    $submittedBy = $_SESSION['userId']; // add

    // $calenderId = isset($_POST['eoverrideBy']) ? $_SESSION['userRole'] : $calenderId; // edit
    $calenderId = isset($_POST['eoverrideBy']) ? $_SESSION['userRole'] : ($_POST['esubmittedByRole'] != '' ? $_POST['esubmittedByRole'] : $_SESSION['userRole']); // create;
    $calenderId = $calenderId == "Admin" ? "1" : $calenderId;

    $scheduleDate = mysqli_real_escape_string($connect, $_POST['escheduleDate']);
    $scheduleDate = reformatDateOnly($scheduleDate);

    $stockno = mysqli_real_escape_string($connect, $_POST['estockno']);
    $sale_id = mysqli_real_escape_string($connect, $_POST['esale_id']);
    // $overrideBy = isset($_POST['eoverrideBy']) ? $_SESSION['userId'] : $_POST['eoverrideById']; // edit
    $overrideBy = isset($_POST['eoverrideBy']) ? $_SESSION['userId'] : ($_POST['eoverrideById'] != '' ? $_POST['eoverrideById'] : ""); // create

    $coordinator = mysqli_real_escape_string($connect, $_POST['ecoordinator']);
    $delivery = (isset($_POST['edelivery'])) ? mysqli_real_escape_string($connect, $_POST['edelivery']) : "";
    $additionalServices = (isset($_POST['eadditionalServices'])) ? mysqli_real_escape_string($connect, $_POST['eadditionalServices']) : "";
    $scheduleNotes = (isset($_POST['escheduleNotes'])) ? mysqli_real_escape_string($connect, $_POST['escheduleNotes']) : "";
    $confirmed = (isset($_POST['econfirmed'])) ? mysqli_real_escape_string($connect, $_POST['econfirmed']) : "";
    $complete = (isset($_POST['ecomplete'])) ? mysqli_real_escape_string($connect, $_POST['ecomplete']) : "";



    $scheduleTime = mysqli_real_escape_string($connect, $_POST['escheduleTime']);


    $scheduleStart = $scheduleDate . ' ' . $scheduleTime;
    $scheduleEnd = strtotime((string)$scheduleStart . ':00 +30 minute');
    $scheduleEnd =  date('Y-m-d H:i:s', $scheduleEnd);




    if (!is_null($scheduleId) && $scheduleId != '') {
        $insentiveSql = "UPDATE `appointments` SET 
        `sale_id`='$sale_id',`stock_id`='$stockno',`appointment_date`='$scheduleDate',
        `appointment_time`='$scheduleTime',`coordinator`='$coordinator',`delivery`='$delivery',
        `additional_services`='$additionalServices',`notes`='$scheduleNotes',`manager_override`='$overrideBy',
        `confirmed`='$confirmed',`complete`='$complete',`schedule_start`='$scheduleStart',
        `schedule_end`='$scheduleEnd',`calender_id`='$calenderId' 
        WHERE id = '$scheduleId'";
    } else {
        $insentiveSql = "INSERT INTO `appointments` ( 
        `sale_id`, `stock_id`, `appointment_date`, `appointment_time`, 
        `coordinator`, `delivery`, `additional_services`, `notes`, `submitted_by`, `manager_override`, 
        `confirmed`, `complete`, `schedule_start`, `schedule_end`, `calender_id`, `status`
        ) VALUES (
            '$sale_id' , '$stockno' , '$scheduleDate' , '$scheduleTime',
            '$coordinator' , '$delivery' , '$additionalServices' , '$scheduleNotes' , '$submittedBy' , '$overrideBy',
            '$confirmed' , '$complete' , '$scheduleStart' , '$scheduleEnd' , '$calenderId' , 1
        )";
    }

    if ($connect->query($insentiveSql) === true) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }



    $connect->close();
    echo json_encode($valid);
} // /if $_POST
