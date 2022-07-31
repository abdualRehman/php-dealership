<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $id = $_POST['wcId'];

    $customerName = isset($_POST['ecustomerName']) ? mysqli_real_escape_string($connect, $_POST['ecustomerName']) : "";
    $financeManager = isset($_POST['efinanceManager']) ? mysqli_real_escape_string($connect, $_POST['efinanceManager']) : "";

    $warranty = (isset($_POST['ewarranty'])) ? implode("__", $_POST['ewarranty']) : "";
    $warranty =  ($warranty ===  "") ? "" :  "__" . $warranty . "__";

    $refundDes = isset($_POST['erefundDes']) ? mysqli_real_escape_string($connect, $_POST['erefundDes']) : "";
    $dateCancelled = isset($_POST['edateCancelled']) ? mysqli_real_escape_string($connect, $_POST['edateCancelled']) : "";
    $dateSold = isset($_POST['edateSold']) ? mysqli_real_escape_string($connect, $_POST['edateSold']) : "";
    $paid = isset($_POST['epaid']) ? "Yes" : "No";
    $notes = isset($_POST['enotes']) ? mysqli_real_escape_string($connect, $_POST['enotes']) : "";



    $sql = "UPDATE `warrenty_cancellation` SET 
    `customer_name`='$customerName',`warrenty`='$warranty',`date_cancelled`='$dateCancelled',
    `refund_des`='$refundDes',`finance_manager`='$financeManager',`date_sold`='$dateSold',
    `paid`='$paid',`notes`='$notes' WHERE `id` = '$id'";

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