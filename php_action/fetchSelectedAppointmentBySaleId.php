<?php

require_once 'db/core.php';

$id = $_POST['id'];


// $sql = "SELECT appointments.* , sales.fname , sales.lname , inventory.id as stock_id , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype FROM sales LEFT JOIN appointments ON (appointments.sale_id = sales.sale_id AND appointments.status = 1) RIGHT JOIN inventory ON sales.stock_id = inventory.id WHERE sales.sale_id = '$id'";
$sql = "SELECT appointments.* , users.username as coordinator_name , users.email as coordinator_email , sales.fname , sales.lname , inventory.id as stock_id , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype FROM sales LEFT JOIN appointments ON (appointments.sale_id = sales.sale_id AND appointments.status = 1) RIGHT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON appointments.coordinator = users.id WHERE sales.sale_id = '$id'";
$result = $connect->query($sql);
$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $output = $row;
    $output['sale_id'] = $id;
    $submittedBy = $row['submitted_by'];
    $manager_override = $row['manager_override'];

    if (isset($submittedBy)) {
        $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['submitted_by'] = $row1['username'];
        $output['submitted_by_role'] = $row1['role'];
    } else {
        $output['submitted_by'] = "";
        $output['submitted_by_role'] = "";
    }
    if (isset($manager_override)) {
        $sql1 = "SELECT * FROM `users` WHERE id = '$manager_override'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['manager_overrideName'] = $row1['username'];
    } else {
        $output['manager_overrideName'] = "";
    }
}

$connect->close();

echo json_encode($output);
