<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

$sql = "SELECT used_cars.inv_id , inventory.stockno , inventory.vin ,  inventory.year , inventory.make , inventory.model , 
used_cars.date_in , used_cars.purchase_from , used_cars.date_sent , used_cars.date_in_paid , used_cars.date_out_paid , used_cars.transportation_notes
FROM used_cars LEFT JOIN inventory ON (used_cars.inv_id = inventory.id AND inventory.location = '$location' ) WHERE used_cars.status = 1 AND inventory.location = '$location' AND (used_cars.purchase_from = 'auction' OR used_cars.date_sent != '')";

$result = $connect->query($sql);

$output = array('data' => array());

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}


if ($result->num_rows > 0) {

    // while ($row = $result->fetch_assoc()) {
    while ($row = $result->fetch_array()) {

        $id = $row['inv_id'];
        $stockno = $row['stockno'];
        $vin = $row['vin'];
        $year = $row['year'];
        $make = $row['make'];
        $model = $row['model'];

        $date_in =  $row['date_in'];
        $purchase_from =  $row['purchase_from'];
        $date_sent =  $row['date_sent'];
        $date_in_paid =  $row['date_in_paid'];
        $date_out_paid =  $row['date_out_paid'];
        $transportation_notes =  $row['transportation_notes'];



        $output['data'][] = array(
            $id,
            $date_in,
            $stockno,
            $year,
            $make,
            $model,
            $vin,
            $purchase_from,
            $date_in_paid,
            $date_sent,
            $date_out_paid,
            $transportation_notes
        );
    } // /while 

} // if num_rows



$connect->close();
echo json_encode($output);
