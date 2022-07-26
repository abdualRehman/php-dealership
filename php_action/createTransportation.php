<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {


    $stockId = (isset($_POST['stockId'])) ? mysqli_real_escape_string($connect, $_POST['stockId']) : "";
    $status = (isset($_POST['status'])) ? mysqli_real_escape_string($connect, $_POST['status']) : "";
    $locNum = (isset($_POST['locNum'])) ? mysqli_real_escape_string($connect, $_POST['locNum']) : "";
    $damageType = (isset($_POST['damageType'])) ? mysqli_real_escape_string($connect, $_POST['damageType']) : "";
    $damageSeverity = (isset($_POST['damageSeverity'])) ? mysqli_real_escape_string($connect, $_POST['damageSeverity']) : "";
    $damageGrid = (isset($_POST['damageGrid'])) ? mysqli_real_escape_string($connect, $_POST['damageGrid']) : "";


    $sql = "INSERT INTO `transportation`(`stock_id`, `loc_num`, `damage_type`, `damage_severity`, `damage_grid`, `transport_status`, `status`) 
    VALUES ('$stockId' , '$locNum' , '$damageType' , '$damageSeverity' , '$damageGrid' , '$status' , 1 )";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'][] = $connect->error;
        // $valid['messages'] = mysqli_error($connect);
    }



    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);