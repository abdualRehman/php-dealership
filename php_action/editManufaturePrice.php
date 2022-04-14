<?php

require_once 'db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array() , 'settingError' => array());

if ($_POST) {

    $manId = $_POST['manId'];

    $year = mysqli_real_escape_string($connect, $_POST['year']);
    $model = mysqli_real_escape_string($connect, $_POST['model']);
    $modelCode = mysqli_real_escape_string($connect, $_POST['modelCode']);
    $msrp = mysqli_real_escape_string($connect, $_POST['msrp']);
    $dlrInv = mysqli_real_escape_string($connect, $_POST['dlrInv']);
    $modelDescription = mysqli_real_escape_string($connect, $_POST['modelDescription']);
    $trim = mysqli_real_escape_string($connect, $_POST['trim']);

    if ($trim == "") {
        $trim = $modelDescription;
    }



    $sql = "UPDATE `manufature_price` SET `year`='$year',`model`='$model',`model_code`='$modelCode',`msrp`='$msrp',`dlr_inv`='$dlrInv',`model_des`='$modelDescription' , `trim`='$trim' WHERE `id` = '$manId'";

    if ($connect->query($sql) === TRUE) {

        // update All Matrix Becaause we don't know about weather used delete exclude model no or not
        $obj = updateAllMaxtix();
        $obj = json_decode($obj);

        if ($obj->success === 'false') {
            $valid['settingError'][] = $obj->messages;
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