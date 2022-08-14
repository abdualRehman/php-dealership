<?php

require_once 'db/core.php';

// $sql = "SELECT used_cars.* , inventory.stockno , inventory.vin , inventory.age , inventory.year , inventory.make , inventory.model  FROM `used_cars` LEFT JOIN inventory ON used_cars.inv_id = inventory.id WHERE used_cars.status = 1 AND retail_status != 'wholesale'";
$sql = "SELECT used_cars.* , inventory.id as Inv_id , inventory.stockno , inventory.vin , inventory.age , inventory.year , inventory.make , inventory.model  
FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1 AND used_cars.retail_status IS NOT NULL"; // AND (used_cars.retail_status != 'wholesale' OR used_cars.retail_status IS NULL)
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
        $id = $row['Inv_id'];

        $stockDetails = $row['stockno'];
        $vin =  $row['vin'];
        
        
        // $age = $row['age'];
        $age = "";

        if ($row['date_in'] != '' && !is_null($row['date_in'])) {
            // date_default_timezone_set('Asia/Karachi');
            $date = strtotime(date('Y-m-d'));
            $date_in = reformatDate($row['date_in']);
            $date_in = date('Y-m-d', strtotime('-1 day', strtotime($date_in)));
            $date_in = strtotime($date_in);
            $age = ceil(abs($date_in - $date) / 86400);
        }





        $submittedBy = $row['submitted_by'];
        $sales_consultant = $row['sales_consultant'];

        if (isset($submittedBy)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $row['submitted_by'] = $row1['username'];
        } else {
            $row['submitted_by'] = "";
        }

        if (isset($sales_consultant)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$sales_consultant'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $sales_consultant = $row1['username'];
        } else {
            $sales_consultant = "";
        }



        $output['data'][] = array(
            $id,
            $stockDetails, //stock
            $vin,
            $age,     
            $row['year'], // year
            $row['make'], // make
            $row['model'], //model
            $row['notes_1'], // notes_1
            $row['notes_2'], // notes_2
            $row['uci'], // uci
            $row['retail_status'],
        );
    } // /while 

} // if num_rows



$connect->close();

echo json_encode($output);
// echo json_encode($output['totalNumber']);
