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
    $submittedBy = $_SESSION['userId'];
    $calenderId = $_SESSION['userRole'];
    $calenderId = $calenderId == "Admin" ? "1" : $calenderId;


    $scheduleDate = isset($_POST['scheduleDate']) ? mysqli_real_escape_string($connect, $_POST['scheduleDate']) : "";
    $scheduleDate = $scheduleDate != "" ? reformatDateOnly($scheduleDate) : "";

    $stockno = mysqli_real_escape_string($connect, $_POST['stockno']);
    $sale_id = isset($_POST['sale_id']) ? mysqli_real_escape_string($connect, $_POST['sale_id']) : "";

    // Validate sale_id
    if (empty($sale_id)) {
        $valid['success'] = false;
        $valid['messages'] = "Sale ID is required.";
        echo json_encode($valid);
        exit();
    }


    $overrideBy = isset($_POST['overrideBy']) ? $_SESSION['overrideById'] : "";
    $coordinator = isset($_POST['coordinator']) ? mysqli_real_escape_string($connect, $_POST['coordinator']) : "";
    $delivery = (isset($_POST['delivery'])) ? mysqli_real_escape_string($connect, $_POST['delivery']) : "";
    // $additionalServices = (isset($_POST['additionalServices'])) ? mysqli_real_escape_string($connect, $_POST['additionalServices']) : "";
    $additionalServices = "";
    if (isset($_POST['additionalServices'])) {
        $services = $_POST['additionalServices'];
        $additionalServices = implode(",", $services);
    }

    $scheduleNotes = (isset($_POST['scheduleNotes'])) ? mysqli_real_escape_string($connect, $_POST['scheduleNotes']) : "";
    $confirmed = (isset($_POST['confirmed'])) ? mysqli_real_escape_string($connect, $_POST['confirmed']) : "";
    $complete = (isset($_POST['complete'])) ? mysqli_real_escape_string($connect, $_POST['complete']) : "";


    $customerName = (isset($_POST['customerName'])) ? mysqli_real_escape_string($connect, $_POST['customerName']) : "";

    $has_appointment = (isset($_POST['has_appointment'])) ? mysqli_real_escape_string($connect, $_POST['has_appointment']) : "null";
    $has_appointment = ($has_appointment != "null" && $has_appointment != '') ? $has_appointment : "false";



    $scheduleTime = mysqli_real_escape_string($connect, $_POST['scheduleTime']);


    $scheduleStart_v = $scheduleDate . ' ' . $scheduleTime;
    // line 58 was working
    $scheduleStart = date('Y-m-d H:i:s', strtotime($scheduleStart_v));
    // $scheduleStart = date('Y-m-d H:i:s', strtotime($scheduleStart_v  . ' -59 minute'));

    // $scheduleEnd = strtotime((string)$scheduleStart . ':00 +30 minute');
    $scheduleEnd = strtotime($scheduleStart_v . ' +59 minute');
    $scheduleEnd =  date('Y-m-d H:i:s', $scheduleEnd);

    $scheduleStart_formated = date('m-d-Y H:i:s', strtotime($scheduleStart));

    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    $salesConsultantName = $_SESSION['userName'];

    date_default_timezone_set("America/New_York");
    $timestamp = date("Y-m-d H:i:s");


    $checkDeliveryStatus = true;

    if ($delivery != "") {
        $countSQL = "SELECT COUNT(appointments.stock_id) as totalStock FROM appointments LEFT JOIN sales ON appointments.stock_id = sales.stock_id WHERE
        sales.sale_status !='delivered' AND appointments.status = 1 AND sales.status = 1 AND appointments.delivery != '' AND sales.stock_id='$stockno'";
        $result3 = $connect->query($countSQL);
        $row3 = $result3->fetch_assoc();
        $totalStock = $row3['totalStock'];

        if ($totalStock > 0) {
            $checkDeliveryStatus = false;
            $valid['success'] = false;
            $valid['messages'] = "Error! - Stock No: {$stockno} has already been scheduled for a delivery";
        } else {
            $checkDeliveryStatus = true;
        }
    }

    if ($checkDeliveryStatus == true) {
        $already_appointed = false;
        // check if coordinator has any confirm appointment in time range
        if ($_SESSION['userRole'] == $deliveryCoordinatorID || $_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == $generalManagerID || $_SESSION['userRole'] == $salesManagerID) {
            $checkSql = "SELECT * FROM `appointments` WHERE coordinator = '$coordinator' AND status = 1 AND confirmed = 'ok' AND cast(schedule_start as datetime )<= '$scheduleStart' and cast(schedule_end as datetime) >= '$scheduleStart'";
            $result2 = $connect->query($checkSql);
            if ($result2->num_rows > 0) {
                $already_appointed = true;
                $valid['success'] = false;
                $valid['messages'] = "You already have an appointment at that time";
            }
        }

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
                $message = "An appointment on {$scheduleStart_formated} has been added by {$salesConsultantName}";
                $sms_user = send_sms($coordinator, $message, $link);
                if ($sms_user == 'true') {
                    $valid['sms_status'] = "SMS Send";
                } else {
                    $valid['sms_status'] = "SMS Failed";
                }

                // sendNotifiation()
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
