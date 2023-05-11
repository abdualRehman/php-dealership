<?php

require_once 'db/core.php';

$id = $_POST['id'];


$sql = "SELECT sales.* , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype, inventory.id , 
( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.sale_id = sales.sale_id 
AND sales.sale_status !='cancelled'  AND appointments.status = 1 AND appointments.delivery != '' ) as has_appointment ,
( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.stock_id = sales.stock_id
AND sales.sale_status !='cancelled'  AND appointments.status = 1 AND sales.status = 1 AND appointments.delivery != '' ) as allready_created 
FROM `sales` LEFT JOIN inventory ON (sales.stock_id = inventory.id ) WHERE sales.sale_id = '$id'";
$result = $connect->query($sql);

$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $output = $row;

    $output['sale_id'] = $id;
    $stock_id = $row['stock_id'];

    if ($row['allready_created'] > 0) {
        $output['already_have'] = "true";
    } else {
        $output['already_have'] = "false";
    }

    $output['sale_id'] = $id;;
    $output['stock_id'] = $row['stock_id'];
    $output['fname'] = $row['fname'];
    $output['lname'] = $row['lname'];
    $output['stockno'] = $row['stockno'];
    $output['vin'] = $row['vin'];
    $output['make'] = $row['make'];
    $output['year'] = $row['year'];
    $output['model'] = $row['model'];
    $output['stocktype'] = $row['stocktype'];
    $output['has_appointment'] = $row['has_appointment'] == 0 ? null : $row['has_appointment'];
    $output['allready_created'] = $row['allready_created'] == 0 ? null : $row['allready_created'];

}

$connect->close();

echo json_encode($output);
