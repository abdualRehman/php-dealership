<?php

require_once './db/core.php';
$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $shceduleId = $_POST['shceduleId'];

    $submitted_by = $_SESSION['userId'];
    $availability = mysqli_real_escape_string($connect, $_POST['availability']);
    $offNotes = mysqli_real_escape_string($connect, $_POST['offNotes']);

    $todayDate = date('m-d-Y');


    $sql = "UPDATE `schedule` SET 
    `today_date`='$todayDate',`today_availability`='$availability',
    `off_notes`='$offNotes',`manager`='$submitted_by' WHERE id = '$shceduleId'";

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