<?php
require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());

function reformatDate($date, $from_format = 'm-d-Y H:i', $to_format = 'Y-m-d H:i')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {


    $contractDate = mysqli_real_escape_string($connect, $_POST['contractDate']);
    $contractDate = reformatDate($contractDate);

    $problemDate = mysqli_real_escape_string($connect, $_POST['problemDate']);
    $problemDate = reformatDate($problemDate);

    $customerName = mysqli_real_escape_string($connect, $_POST['customerName']);  // customerName
    $salesConsultant = mysqli_real_escape_string($connect, $_POST['salesConsultant']);
    $financeManager = mysqli_real_escape_string($connect, $_POST['financeManager']);
    $stockId = mysqli_real_escape_string($connect, $_POST['stockId']);
    $vehicle = mysqli_real_escape_string($connect, $_POST['vehicle']);
    $problem = isset($_POST['problem']) ? mysqli_real_escape_string($connect, $_POST['problem']) : "";
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($connect, $_POST['notes']) : "";




    $sql = "INSERT INTO `registration_problems`(`contract_date`, `problem_date`, `customer_name`, `sales_consultant`, `finance_manager`, `stock_id`, `vehicle`, `problem`, `notes`, `status`) 
    VALUES ('$contractDate' , '$problemDate' , '$customerName' , '$salesConsultant' , '$financeManager' , '$stockId' , '$vehicle' , '$problem' , '$notes' , 1)";

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