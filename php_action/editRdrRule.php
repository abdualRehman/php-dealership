<?php

require_once './db/core.php';


$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $ruleId = $_POST['ruleId'];


    $year = mysqli_real_escape_string($connect, $_POST['eyear']);
    $make = mysqli_real_escape_string($connect, $_POST['emake']);
    $model = mysqli_real_escape_string($connect, $_POST['emodel']);


    $certified = mysqli_real_escape_string($connect, $_POST['ecertified']);
    $modelType = mysqli_real_escape_string($connect, $_POST['emodelType']);
    $rdrType = mysqli_real_escape_string($connect, $_POST['erdrType']);


    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    $checkSql = "SELECT * FROM `rdr_rules` WHERE `year` = '$year' AND `make` = '$make' AND `model`='$model' AND `model_type`='$modelType' AND `certified`='$certified' AND status = 1 AND location = '$location' AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule Already Exist";
    } else {

        $sql = "UPDATE `rdr_rules` SET 
        `year`='$year',
        `make`='$make',
        `model`='$model',
        `model_type`='$modelType',
        `certified`='$certified',
        `rdr_type`='$rdrType'
        WHERE id = '$ruleId' ";

        if ($connect->query($sql) === true) {

            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }




    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);