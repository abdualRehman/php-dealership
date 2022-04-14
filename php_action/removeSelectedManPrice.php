<?php

require_once 'db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'settingError' => array());

$idArray = $_POST['idArray'];

if ($idArray) {

    foreach ($idArray as $eId) {
        // code to be executed;
        $id = mysqli_real_escape_string($connect, $eId);

        $sql = "UPDATE `manufature_price` SET `status`='2' WHERE id = '$id'";

        // $sql = "UPDATE inventory SET stockno = CONCAT(stockno , '_', id) , status = 2 WHERE stockno = '$stkno'";

        if ($connect->query($sql) === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Removed";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }
    $obj = updateAllMaxtix();
    $obj = json_decode($obj);

    if ($obj->success === 'false') {
        $valid['settingError'][] = $obj->messages;
    }

    $connect->close();
    echo json_encode($valid);
} // /if $_POST