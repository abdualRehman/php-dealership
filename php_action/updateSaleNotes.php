<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');

if ($_POST) {

    $sale_id = $_POST['sale_id'];

    $salePStatus = isset($_POST['salePStatus']) ? mysqli_real_escape_string($connect, $_POST['salePStatus']) : "";
    $consultantNote = isset($_POST['consultantNote']) ? mysqli_real_escape_string($connect, $_POST['consultantNote']) : "";
    $thankyouCard = isset($_POST['thankyouCard']) ? mysqli_real_escape_string($connect, $_POST['thankyouCard']) : '';

    if ($sale_id) {
        $sql = "UPDATE `sales` SET 
        `consultant_notes`='$consultantNote',
        `thankyou_cards`='$thankyouCard'
        WHERE sale_id = '$sale_id' ";

        $sql1 = "UPDATE `sale_todo` SET `salesperson_status` = '$salePStatus' WHERE `sale_todo`.`sale_id` = '$sale_id'";

        if ($connect->query($sql) === true && $connect->query($sql1) === true) {
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
