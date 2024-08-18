<?php

require_once 'db/core.php';

$id = $_POST['id'];
// $id = 43;  //empty
// $id = 35;   /// already = false
// $id = 38;   /// already = true
// $id = 37;   /// already = true


// $sql = "SELECT appointments.* , sales.fname , sales.lname , inventory.id as stock_id , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype FROM sales LEFT JOIN appointments ON (appointments.sale_id = sales.sale_id AND appointments.status = 1) RIGHT JOIN inventory ON sales.stock_id = inventory.id WHERE sales.sale_id = '$id'";
$sql = "SELECT appointments.* , users.username as coordinator_name , users.email as coordinator_email , sales.fname , sales.lname , 
inventory.id as stock_id , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype
FROM sales LEFT JOIN appointments ON (appointments.sale_id = sales.sale_id AND appointments.status = 1) RIGHT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON appointments.coordinator = users.id WHERE sales.sale_id = '$id'";
$result = $connect->query($sql);
$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $output = $row;
    $output['sale_id'] = $id;
    $submittedBy = $row['submitted_by'];
    $manager_override = $row['manager_override'];
    $stock_id = $row['stock_id'];

    if (isset($submittedBy)) {
        $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['submitted_by'] = $row1 ? $row1['username'] : "";
        $output['submitted_by_role'] = $row1 ? $row1['role'] : "";
    } else {
        $output['submitted_by'] = "";
        $output['submitted_by_role'] = "";
    }
    if (isset($manager_override)) {
        $sql1 = "SELECT * FROM `users` WHERE id = '$manager_override'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['manager_overrideName'] = $row1 ? $row1['username'] : "";
    } else {
        $output['manager_overrideName'] = "";
    }

    $output['allready_created'] = null;
    if (!isset($row['id'])) {
        $sql1 = "SELECT COUNT(appointments.stock_id) as allready_created FROM appointments LEFT JOIN sales ON 
        (sales.stock_id = appointments.stock_id AND sales.status = 1 AND sales.sale_status !='cancelled' and sales.sale_id = '$id' ) 
        WHERE appointments.status = 1 AND appointments.stock_id = '$stock_id'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['allready_created'] = $row1['allready_created'] == 0 ? null : $row1['allready_created'];
        if ($row1['allready_created'] > 0) {
            $output['already_have'] = "true";
        } else {
            $output['already_have'] = "false";
        }
    } else {
        $output['already_have'] = $row['already_have'];
    }

    // duplicate will not send to delivery coordinator until it is approved by Manager
    if ($_SESSION['userRole'] == $deliveryCoordinatorID && $row['already_have'] == "true" && !$row['manager_override'] && $row['delivery'] != '') {
        $output['allowDeliveryCoordinator'] = true;
    } else {
        $output['allowDeliveryCoordinator'] = false;
    }
}

$connect->close();

echo json_encode($output);
