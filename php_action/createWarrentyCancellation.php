<?php
require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());

function reformatDate($date, $from_format = 'm-d-Y H:i', $to_format = 'Y-m-d H:i')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {


    $customerName = isset($_POST['customerName']) ? mysqli_real_escape_string($connect, $_POST['customerName']) : "";
    $financeManager = isset($_POST['financeManager']) ? mysqli_real_escape_string($connect, $_POST['financeManager']) : "";

    $warranty = (isset($_POST['warranty'])) ? implode("__", $_POST['warranty']) : "";
    $warranty =  ($warranty ===  "") ? "" :  "__" . $warranty . "__";

    $refundDes = isset($_POST['refundDes']) ? mysqli_real_escape_string($connect, $_POST['refundDes']) : "";
    $dateCancelled = isset($_POST['dateCancelled']) ? mysqli_real_escape_string($connect, $_POST['dateCancelled']) : "";
    $dateSold = isset($_POST['dateSold']) ? mysqli_real_escape_string($connect, $_POST['dateSold']) : "";
    $paid = isset($_POST['paid']) ? "Yes" : "No";
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($connect, $_POST['notes']) : "";





    $sql = "INSERT INTO `warrenty_cancellation`(
        `customer_name`, `warrenty`, `date_cancelled`, `refund_des`, 
        `finance_manager`, `date_sold`, `paid`, `notes` , `status`
        ) VALUES (
        '$customerName' , '$warranty' , '$dateCancelled' , '$refundDes' , 
        '$financeManager' , '$dateSold' , '$paid' , '$notes' , 1)";

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