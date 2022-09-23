<?php

require_once 'db/core.php';

$id = $_POST['id'];

// $sql = "SELECT `id`, `sale_id`, `stock_id`, `appointment_date`, `appointment_time`, `coordinator`, `delivery`, `additional_services`, `notes`, `submitted_by`, `manager_override`, `confirmed`, `complete`, `schedule_start`, `schedule_end`, `calender_id`, `status` FROM `appointments` WHERE id = '$id'";
$sql = "SELECT appointments.id, `sale_id`, `stock_id`, `appointment_date`, `appointment_time`, `coordinator` , users.username as coordinator_name , users.email as coordinator_email , 
`delivery`, `additional_services`, `notes`, `submitted_by`, `manager_override`, `confirmed`, `complete`, `schedule_start`, `schedule_end`, `calender_id` , `already_have`
FROM `appointments` LEFT JOIN users ON appointments.coordinator = users.id WHERE appointments.id = '$id'";
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
        $output['submitted_by_id'] = $row1['id'];
        $output['submitted_by_role'] = $row1['role'];
    } else {
        $output['submitted_by'] = "";
        $output['submitted_by_role'] = "";
        $output['submitted_by_id'] = "";
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
