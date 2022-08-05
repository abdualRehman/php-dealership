<?php

require_once 'db/core.php';

$id = $_POST['id'];

// $sql = "SELECT a.id , b.fname, b.lname , b.sale_id , c.stocktype , c.year , c.make , c.model  , a.stock_id, a.appointment_date, a.appointment_time, a.coordinator, a.delivery, a.additional_services, a.notes, a.submitted_by, a.manager_override, a.confirmed, a.complete, a.schedule_start, a.schedule_end, a.calender_id, a.status FROM `appointments` as a LEFT JOIN sales as b ON a.sale_id = b.sale_id LEFT JOIN inventory as c ON a.stock_id = c.id WHERE a.id = '$id'";
$sql = "SELECT `id`, `sale_id`, `stock_id`, `appointment_date`, `appointment_time`, `coordinator`, `delivery`, `additional_services`, `notes`, `submitted_by`, `manager_override`, `confirmed`, `complete`, `schedule_start`, `schedule_end`, `calender_id`, `status` FROM `appointments` WHERE id = '$id'";
$result = $connect->query($sql);
$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $output = $row;
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
} // if num_rows

$connect->close();

echo json_encode($output);
