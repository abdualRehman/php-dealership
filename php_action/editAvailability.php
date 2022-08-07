<?php

require_once './db/core.php';
$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

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