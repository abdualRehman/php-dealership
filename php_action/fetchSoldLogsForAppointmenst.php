<?php

require_once 'db/core.php';

$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

/* sales consultant id */
if ($userRole != $salesConsultantID) {
    $sql = "SELECT sales.* , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype, 
    ( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.sale_id = sales.sale_id 
    AND sales.sale_status !='cancelled'  AND appointments.status = 1 AND appointments.delivery != '' ) as has_appointment ,
    ( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.stock_id = sales.stock_id
    AND sales.sale_status !='cancelled'  AND appointments.status = 1 AND sales.status = 1 AND appointments.delivery != '' ) as allready_created 
    FROM `sales` LEFT JOIN inventory ON (sales.stock_id = inventory.id ) WHERE sales.status = 1 AND sales.location = '$location' AND sales.sale_status !='cancelled' ORDER BY inventory.stockno DESC";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT sales.* , inventory.stockno , inventory.year, inventory.make , inventory.model , inventory.vin, inventory.stocktype, 
    ( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.sale_id = sales.sale_id 
    AND sales.sale_status !='cancelled'  AND appointments.status = 1 AND appointments.delivery != '' ) as has_appointment , 
    ( SELECT COUNT(appointments.stock_id) FROM appointments WHERE appointments.stock_id = sales.stock_id
    AND sales.sale_status !='cancelled'  AND appointments.status = 1 AND sales.status = 1 AND appointments.delivery != '' ) as allready_created 
    FROM `sales` LEFT JOIN inventory ON (sales.stock_id = inventory.id ) WHERE sales.status = 1 AND sales.location = '$location' AND sales.sale_status !='cancelled' AND sales.sales_consultant = '$uid' ORDER BY inventory.stockno DESC";
}



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
        $allready_created = $row['allready_created'];


        $output['data'][] = array(
            $sale_id,  // 0
            $stock_id, // 1
            $fname, // 2
            $lname, // 3
            $stockno, // 4
            $vin, // 5
            $stocktype, // 6
            $year, // 7
            $make, // 8
            $model, // 9
            $has_appointment == 0 ? null : $has_appointment, // 10
            $allready_created == 0 ? null : $allready_created, // 11
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
