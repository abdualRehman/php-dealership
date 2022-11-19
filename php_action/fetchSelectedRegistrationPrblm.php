<?php

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT `id`, `contract_date`, `problem_date`, `customer_name`, `sales_consultant`, `finance_manager`, `stock_id`, `vehicle`, `problem`, `notes`, 
`status` FROM `registration_problems` WHERE id = '$id'";
$result = $connect->query($sql);
$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $output = $row;

    $salesConsultantEdit = true;
    if ($_SESSION['userRole'] != 'Admin') {
        if ($_SESSION['userId'] == $row['sales_consultant']) {
            $salesConsultantEdit = false;
        }else{
            $salesConsultantEdit = true;
        }
    } else if ($_SESSION['userRole'] == 'Admin') {
        $salesConsultantEdit = false;
    }

    $output['consultantEdit'] = $salesConsultantEdit;    



} // if num_rows

$connect->close();

echo json_encode($output);
