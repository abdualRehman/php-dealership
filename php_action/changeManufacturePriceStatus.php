<?php

require_once 'db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'settingError' => array());

$id = $_POST['id'];

if ($id) {

    $sql = "UPDATE manufature_price SET status = 1 - status WHERE id = '$id'";

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