<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');

if ($_POST) {

    $sale_id = $_POST['sale_id'];

    $vincheck = isset($_POST['vincheck']) ? mysqli_real_escape_string($connect, $_POST['vincheck']) : "";
    $insurance = isset($_POST['insurance']) ? mysqli_real_escape_string($connect, $_POST['insurance']) : "";
    $tradeTitle = isset($_POST['tradeTitle']) ? mysqli_real_escape_string($connect, $_POST['tradeTitle']) : "";
    $registration = isset($_POST['registration']) ? mysqli_real_escape_string($connect, $_POST['registration']) : "";
    $inspection = isset($_POST['inspection']) ? mysqli_real_escape_string($connect, $_POST['inspection']) : "";
    $salePStatus = isset($_POST['salePStatus']) ? mysqli_real_escape_string($connect, $_POST['salePStatus']) : "";
    $paid = isset($_POST['paid']) ? mysqli_real_escape_string($connect, $_POST['paid']) : "";


    $consultantNote = isset($_POST['consultantNote']) ? mysqli_real_escape_string($connect, $_POST['consultantNote']) : "";
    $thankyouCard = isset($_POST['thankyouCard']) ? mysqli_real_escape_string($connect, $_POST['thankyouCard']) : '';

    if ($sale_id) {
        $sql = "UPDATE `sales` SET 
        `consultant_notes`='$consultantNote',
        `thankyou_cards`='$thankyouCard'
        WHERE sale_id = '$sale_id' ";

        $sql1 = "UPDATE `sale_todo` SET 
        `vin_check`='$vincheck',
        `insurance`='$insurance',
        `trade_title`='$tradeTitle',
        `registration`='$registration',
        `inspection`='$inspection',
        `salesperson_status`='$salePStatus',
        `paid`='$paid' 
        WHERE `sale_todo`.`sale_id` = '$sale_id'";

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
