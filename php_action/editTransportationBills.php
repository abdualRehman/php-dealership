<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $id = $_POST['usedCarId'];

    $date_in_paid = (isset($_POST['date_in_paid'])) ? mysqli_real_escape_string($connect, $_POST['date_in_paid']) : "";
    $date_out_paid = (isset($_POST['date_out_paid'])) ? mysqli_real_escape_string($connect, $_POST['date_out_paid']) : "";
    $notes = (isset($_POST['notes'])) ? mysqli_real_escape_string($connect, $_POST['notes']) : "";
  

    $sql = "UPDATE `used_cars` SET `date_in_paid` = '$date_in_paid',
    `date_out_paid`='$date_out_paid',`transportation_notes`='$notes'
    WHERE `inv_id`='$id' ";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'][] = $connect->error;
    }



    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);