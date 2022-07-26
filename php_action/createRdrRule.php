<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {


    $year = (isset($_POST['year'])) ? mysqli_real_escape_string($connect, $_POST['year']) : "";
    $make = (isset($_POST['make'])) ? mysqli_real_escape_string($connect, $_POST['make']) : "";
    $model = (isset($_POST['model'])) ? mysqli_real_escape_string($connect, $_POST['model']) : "";
    $modelType = (isset($_POST['modelType'])) ? mysqli_real_escape_string($connect, $_POST['modelType']) : "";
    $certified = (isset($_POST['certified'])) ? mysqli_real_escape_string($connect, $_POST['certified']) : "";
    $rdrType = (isset($_POST['rdrType'])) ? mysqli_real_escape_string($connect, $_POST['rdrType']) : "";



    $checkSql = "SELECT * FROM `rdr_rules` WHERE `year` = '$year' AND `make` = '$make' AND `model` = '$model' AND `model_type` = '$modelType' AND `certified`='$certified'  AND status = 1";
    $result = $connect->query($checkSql);
    if ($result && $result->num_rows > 0) {
        $valid['errorMessages'][] = $year . ' - ' . $make . ' - ' . $model . ' - ' . $modelType . ' - ' . $certified . ",  Already Exist";
    } else {

        $sql = "INSERT INTO `rdr_rules`( `year`, `make` , `model` , `model_type`, `certified`, `rdr_type` , `status`) 
            VALUES (
                '$year',
                '$make',
                '$model',
                '$modelType',
                '$certified',
                '$rdrType',
                1 )";

        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'][] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'][] = $connect->error;
            // $valid['messages'] = mysqli_error($connect);
        }
    }



    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);