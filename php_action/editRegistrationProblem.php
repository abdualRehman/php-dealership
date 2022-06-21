<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

function reformatDate($date, $from_format = 'm-d-Y H:i', $to_format = 'Y-m-d H:i')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {

    $id = $_POST['problemId'];


    $contractDate = mysqli_real_escape_string($connect, $_POST['econtractDate']);
    $contractDate = reformatDate($contractDate);

    $problemDate = mysqli_real_escape_string($connect, $_POST['eproblemDate']);
    $problemDate = reformatDate($problemDate);

    $customerName = mysqli_real_escape_string($connect, $_POST['ecustomerName']);  // customerName
    $salesConsultant = mysqli_real_escape_string($connect, $_POST['esalesConsultant']);
    $financeManager = isset($_POST['efinanceManager']) ? mysqli_real_escape_string($connect, $_POST['efinanceManager']) : "";
    $stockId = mysqli_real_escape_string($connect, $_POST['estockId']);
    $vehicle = mysqli_real_escape_string($connect, $_POST['evehicle']);
    $problem = isset($_POST['eproblem']) ? mysqli_real_escape_string($connect, $_POST['eproblem']) : "";
    $notes = isset($_POST['enotes']) ? mysqli_real_escape_string($connect, $_POST['enotes']) : "";



    $sql = "UPDATE `registration_problems` SET 
    `contract_date`='$contractDate',
    `problem_date`='$problemDate',
    `customer_name`='$customerName',
    `sales_consultant`='$salesConsultant',
    `finance_manager`='$financeManager',
    `stock_id`='$stockId', 
    `vehicle`='$vehicle',
    `problem`='$problem',
    `notes`='$notes' WHERE id = '$id'";

    if ($connect->query($sql) === true) {

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
// echo json_encode($valid);