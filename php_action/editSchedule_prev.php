<?php

require_once './db/core.php';
require_once './sendSMS.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'sms_status' => array());
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

    $scheduleDate = isset($_POST['escheduleDate']) ? mysqli_real_escape_string($connect, $_POST['escheduleDate']) : "";
    $scheduleDate = $scheduleDate != "" ? reformatDateOnly($scheduleDate) : "";

    $stockno = mysqli_real_escape_string($connect, $_POST['estockno']);
    $sale_id = mysqli_real_escape_string($connect, $_POST['esale_id']);
    // $overrideBy = isset($_POST['eoverrideBy']) ? $_SESSION['userId'] : $_POST['eoverrideById']; // edit
    $overrideBy = isset($_POST['eoverrideBy']) ? $_SESSION['userId'] : ($_POST['eoverrideById'] != '' ? $_POST['eoverrideById'] : ""); // create

    $coordinator = isset($_POST['ecoordinator']) ? mysqli_real_escape_string($connect, $_POST['ecoordinator']) : "";
    $delivery = (isset($_POST['edelivery'])) ? mysqli_real_escape_string($connect, $_POST['edelivery']) : "";
    // $additionalServices = (isset($_POST['eadditionalServices'])) ? mysqli_real_escape_string($connect, $_POST['eadditionalServices']) : "";
    $additionalServices = "";
    if (isset($_POST['eadditionalServices'])) {
        $services = $_POST['eadditionalServices'];
        $additionalServices = implode(",", $services);
    }



    $scheduleNotes = (isset($_POST['escheduleNotes'])) ? mysqli_real_escape_string($connect, $_POST['escheduleNotes']) : "";
    $confirmed = (isset($_POST['econfirmed'])) ? mysqli_real_escape_string($connect, $_POST['econfirmed']) : "";
    $complete = (isset($_POST['ecomplete'])) ? mysqli_real_escape_string($connect, $_POST['ecomplete']) : "";


    $customerName = (isset($_POST['ecustomerName'])) ? mysqli_real_escape_string($connect, $_POST['ecustomerName']) : "";


    $has_appointment = (isset($_POST['ehas_appointment'])) ? mysqli_real_escape_string($connect, $_POST['ehas_appointment']) : "null";
    $has_appointment = ($has_appointment != "null" && $has_appointment != '') ? $has_appointment : "false";



    $scheduleTime = mysqli_real_escape_string($connect, $_POST['escheduleTime']);


    $scheduleStart = $scheduleDate . ' ' . $scheduleTime;
    $scheduleStart = date('Y-m-d H:i:s', strtotime($scheduleStart));
    // $scheduleEnd = strtotime((string)$scheduleStart . ':00 +30 minute');
    $scheduleEnd = strtotime($scheduleStart . ' +59 minute');
    $scheduleEnd =  date('Y-m-d H:i:s', $scheduleEnd);

    $scheduleStart_formated = date('m-d-Y H:i:s', strtotime($scheduleStart));

    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    $salesConsultantName = $_SESSION['userName'];

    date_default_timezone_set("America/New_York");
    $timestamp = date("Y-m-d H:i:s");

    $already_appointed = false;
    if (!is_null($scheduleId) && $scheduleId != '') {

        if (
            ($confirmed == 'ok') && ($_SESSION['userRole'] == $deliveryCoordinatorID || $_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == $branchAdmin || $_SESSION['userRole'] == $generalManagerID || $_SESSION['userRole'] == $salesManagerID)
        ) {

            $checkSql = "SELECT * FROM `appointments` WHERE coordinator = '$coordinator' AND id != '$scheduleId' AND status = 1 AND location = '$location' AND confirmed = 'ok' AND cast(schedule_start as datetime )<= '$scheduleStart' and cast(schedule_end as datetime) >= '$scheduleStart'";
            $result2 = $connect->query($checkSql);
            if ($result2->num_rows > 0) {
                $already_appointed = true;
                $valid['success'] = false;
                $valid['messages'] = "You already have an appointment at that time";
            }
        }
        if ($already_appointed == false) {
            $insentiveSql = "UPDATE `appointments` SET 
                `sale_id`='$sale_id',`stock_id`='$stockno',`appointment_date`='$scheduleDate',
                `appointment_time`='$scheduleTime',`coordinator`='$coordinator',`delivery`='$delivery',
                `additional_services`='$additionalServices',`notes`='$scheduleNotes',`manager_override`='$overrideBy',
                `confirmed`='$confirmed',`complete`='$complete',`schedule_start`='$scheduleStart',
                `schedule_end`='$scheduleEnd',`calender_id`='$calenderId' , `already_have` = '$has_appointment'
                WHERE id = '$scheduleId'";

            if ($connect->query($insentiveSql) === true) {

                $from = $submittedBy;
                $to = $coordinator;
                $delivery = preg_split('/(?=[A-Z])/', $delivery);
                $delivery = implode(' ', $delivery);

                $message = 'Appointment Updated: With "' . $customerName . '" On "' . ucwords($delivery) . '" at: "' . $scheduleStart_formated . '"';
                $appointment_id = $scheduleId;
                sendNotifiation($from, $to, $message, $appointment_id);



                $consultant = isset($_POST['esubmittedById']) ? mysqli_real_escape_string($connect, $_POST['esubmittedById']) : "";
                $from = $coordinator;
                $to = $consultant;

                $confirmedStatus = $confirmed == 'ok' ? 'Confirmed' : 'Not Confirmed';
                $completeStatus = $complete == 'ok' ? 'Completed' : 'Denied';
                $statusMsg = ($confirmed == 'ok' && $complete != '') ? $completeStatus : $confirmedStatus;
                $message = 'Appointment Updated: ' . $statusMsg . ' By ' . $_SESSION['userName'];
                $appointment_id = $scheduleId;
                sendNotifiation($from, $to, $message, $appointment_id);


                $sql1 = "SELECT appointments.* , a.username as sales_consultant, sales.fname , sales.lname , b.username as delivery_coordinator FROM `appointments` LEFT JOIN users as a ON appointments.submitted_by = a.id LEFT JOIN users as b ON appointments.coordinator = b.id LEFT JOIN sales ON appointments.sale_id = sales.sale_id WHERE appointments.id = '$scheduleId'";

                $result1 = $connect->query($sql1);
                $row1 = $result1->fetch_assoc();
                $sales_consultant = $row1['sales_consultant'];
                $customerName = $row1['fname'] . ' ' . $row1['lname'];
                $delivery_coordinator = $row1['delivery_coordinator'];
                $coordinator_id = $row1['coordinator'];
                $submitted_by = $row1['submitted_by'];


                $sms_user = "false";

                if ($_SESSION['userRole'] != $deliveryCoordinatorID) {

                    $link = $siteurl . '/index.php?redirect=more/deliveryCoordinators.php?filter=' . $scheduleId;
                    $message = "An appointment on {$scheduleStart_formated} has been updated by {$sales_consultant}
                    Click to confirm: {$link}";
                    $sms_user = send_sms($coordinator_id, $message);
                    if ($sms_user == 'true') {
                        $valid['sms_status'] = "SMS Send";
                    } else {
                        $valid['sms_status'] = "SMS Failed";
                    }
                }
                if ($confirmed == 'ok') {

                    $message = "Congratulations {$sales_consultant}.
                    Your appointment for {$customerName} with  
                    {$delivery_coordinator} was confirmed as of {$scheduleStart_formated}";
                    $sms_user = send_sms($submitted_by, $message);
                } else if ($confirmed == 'showVerified') {

                    $message = "Sorry {$sales_consultant}. Appointment for {$customerName} with 
                    {$delivery_coordinator} was DECLINED as {$scheduleStart_formated}. Please reschedule.";
                    $sms_user = send_sms($submitted_by, $message);
                }

                if ($complete == 'ok') {

                    $message = "The Appointment for {$customerName}
                    has been completed by {$delivery_coordinator}";
                    $sms_user = send_sms($submitted_by, $message);
                } else if ($complete == 'showVerified') {

                    $message = "The Appointment for {$customerName}
                    was not completed by {$delivery_coordinator}";
                    $sms_user = send_sms($submitted_by, $message);
                }

                if ($sms_user == 'true') {
                    $valid['sms_status'] = "SMS Send";
                } else {
                    $valid['sms_status'] = "SMS Failed";
                }



                $valid['success'] = true;
                $valid['messages'] = "Successfully Updated";
            } else {
                $valid['success'] = false;
                $valid['messages'] = $connect->error;
                $valid['messages'] = mysqli_error($connect);
            }
        }
    } else {
        if ($_SESSION['userRole'] != $deliveryCoordinatorID) {
            $insentiveSql = "INSERT INTO `appointments` ( 
                `sale_id`, `stock_id`, `appointment_date`, `appointment_time`, 
                `coordinator`, `delivery`, `additional_services`, `notes`, `submitted_by`, `manager_override`, 
                `confirmed`, `complete`, `schedule_start`, `schedule_end`, `calender_id`, `status` , `location` , `already_have` , `submitted_by_time`
                ) VALUES (
                    '$sale_id' , '$stockno' , '$scheduleDate' , '$scheduleTime',
                    '$coordinator' , '$delivery' , '$additionalServices' , '$scheduleNotes' , '$submittedBy' , '$overrideBy',
                    '$confirmed' , '$complete' , '$scheduleStart' , '$scheduleEnd' , '$calenderId' , 1 , '$location' , '$has_appointment' , '$timestamp'
                )";
            if ($connect->query($insentiveSql) === true) {


                $from = $submittedBy;
                $to = $coordinator;
                $delivery = preg_split('/(?=[A-Z])/', $delivery);
                $delivery = implode(' ', $delivery);
                $message = 'Appointment Created: With "' . $customerName . '" On "' . ucwords($delivery) . '" at: "' . $scheduleStart_formated . '"';
                $appointment_id = $connect->insert_id;
                sendNotifiation($from, $to, $message, $appointment_id);

                $link = $siteurl . '/index.php?redirect=more/deliveryCoordinators.php?filter=' . $appointment_id;
                $message = "An appointment on {$scheduleStart_formated} has been added by {$salesConsultantName}
                        Click to confirm: {$link}";
                $sms_user = send_sms($coordinator, $message);
                if ($sms_user == 'true') {
                    $valid['sms_status'] = "SMS Send";
                } else {
                    $valid['sms_status'] = "SMS Failed";
                }



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
    }





    $connect->close();
    echo json_encode($valid);
} // /if $_POST
