<?php

require_once './db/core.php';
require_once './sendSMS.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array(), 'sms_status' => array());

if ($_POST) {

    $shceduleId = $_POST['shceduleId'];

    $submitted_by = $_SESSION['userId'];
    $availability = mysqli_real_escape_string($connect, $_POST['availability']);
    $offNotes = mysqli_real_escape_string($connect, $_POST['offNotes']);

    $todayDate = date('m-d-Y');

    $monStart = $_POST['smonStart'];
    $monEnd = ($monStart != "") ? $_POST['smonEnd'] : "";
    $tueStart = $_POST['stueStart'];
    $tueEnd = ($tueStart != "") ? $_POST['stueEnd'] : "";
    $wedStart = $_POST['swedStart'];
    $wedEnd = ($wedStart != "") ? $_POST['swedEnd'] : "";
    $thuStart = $_POST['sthuStart'];
    $thuEnd = ($thuStart != "") ? $_POST['sthuEnd'] : "";
    $friStart = $_POST['sfriStart'];
    $friEnd = ($friStart != "") ? $_POST['sfriEnd'] : "";
    $satStart = $_POST['ssatStart'];
    $satEnd = ($satStart != "") ? $_POST['ssatEnd'] : "";
    $sunStart = $_POST['ssunStart'];
    $sunEnd = ($sunStart != "") ? $_POST['ssunEnd'] : "";



    $sql = "UPDATE `schedule` SET 
    `today_date`='$todayDate',`today_availability`='$availability', `off_notes`='$offNotes',
    `manager`='$submitted_by' , `mon_start`='$monStart',`mon_end`='$monEnd',
    `tue_start`='$tueStart',`tue_end`='$tueEnd',`wed_start`='$wedStart',`wed_end`='$wedEnd',
    `thu_start`='$thuStart',`thu_end`='$thuEnd',`fri_start`='$friStart',`fri_end`='$friEnd',
    `sat_start`='$satStart',`sat_end`='$satEnd',`sun_start`='$sunStart',`sun_end`='$sunEnd' WHERE id = '$shceduleId'";

    if ($connect->query($sql) === true) {


        $sql1 = "SELECT schedule.*, users.username as consultant_name , users.role as consultant_role FROM `schedule` LEFT JOIN users ON schedule.user_id = users.id WHERE schedule.id = '$shceduleId'";

        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $consultant_name = $row1['consultant_name'];
        $consultant_id = $row1['user_id'];
        $consultant_role = $row1['consultant_role'];
        $sms_user = "false";
        $username = $_SESSION['userName'];

        if ($consultant_role == $salesConsultantID) {

            if ($availability != 'OFF' && $availability != '' && $availability != 'Vacation') {
                if($availability == 'See Notes'){
                    $message = "You Have been marked Off BDC by {$username}…Reason - {$offNotes}";
                }else{
                    $message = "You have been marked {$availability} by {$username}";
                }
                $sms_user = send_sms($consultant_id, $message);
                if ($sms_user == 'true') {
                    $valid['sms_status'] = "SMS Send";
                } else {
                    $valid['sms_status'] = "SMS Failed";
                }

            }
        }

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
// echo json_encode($valid);