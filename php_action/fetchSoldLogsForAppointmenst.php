<?php

require_once 'db/core.php';

// not working properly
// $sql = "SELECT sales.* , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype, 
// ( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.stock_id = sales.stock_id 
// AND sales.sale_id != appointments.sale_id AND sales.sale_status !='cancelled'  AND appointments.status = 1 ) as has_appointment 
// FROM `sales` LEFT JOIN inventory ON (sales.stock_id = inventory.id ) WHERE sales.status = 1";

$sql = "SELECT sales.* , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype, 
( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.stock_id = sales.stock_id 
AND sales.sale_status !='cancelled'  AND appointments.status = 1 ) as has_appointment 
FROM `sales` LEFT JOIN inventory ON (sales.stock_id = inventory.id ) WHERE sales.status = 1";


$result = $connect->query($sql);
$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {


        $sale_id = $row['sale_id'];
        $stock_id = $row['stock_id'];
        $fname = $row['fname'];
        $lname = $row['lname'];
        $stockno = $row['stockno'];
        $vin = $row['vin'];
        $make = $row['make'];
        $year = $row['year'];
        $model = $row['model'];
        $stocktype = $row['stocktype'];
        $has_appointment = $row['has_appointment'];


        $output['data'][] = array(
            $sale_id,
            $stock_id,
            $fname,
            $lname,
            $stockno,
            $vin,
            $stocktype,
            $year,
            $make,
            $model,
            $has_appointment == 0 ? null : $has_appointment,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);