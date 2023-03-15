<?php

require_once 'db/core.php';

$id = $_POST['id'];
// $id = 1634;

$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , inspections.* FROM inventory LEFT JOIN inspections ON inventory.id = inspections.inv_id WHERE inventory.id = '$id'";
$result = $connect->query($sql);

$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $output = $row;
    $submittedBy = $row['submitted_by'];
    $bodyshop = $row['shops'];
    $bodyshop_log = $row['bodyshop_log'];

    if (isset($submittedBy)) {
        $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['submitted_by'] = $row1['username'];
    } else {
        $output['submitted_by'] = "";
    }

    if (isset($bodyshop_log) && $bodyshop_log != '') {
        $sql1 = "SELECT * FROM `bodyshops` WHERE id = '$bodyshop_log'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['bodyshop_log'] = $row1['shop'];
        $output['bodyshop_log_id'] = $row1['id'];
    } else {
        $output['bodyshop_log'] = $row['bodyshop_log'];
        $output['bodyshop_log_id'] = $row['bodyshop_log'];
    }
    if (isset($bodyshop) && $bodyshop != '') {
        $sql1 = "SELECT * FROM `bodyshops` WHERE id = '$bodyshop'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['bodyshop_name'] = $row1['shop'];
    } else {
        $output['bodyshop_name'] = "";
    }

} // if num_rows

$connect->close();

// echo json_encode($row);
echo json_encode($output);
