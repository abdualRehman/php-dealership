<?php

require_once 'db/core.php';

$sql = "SELECT used_cars.* , inventory.stockno , inventory.vin , inventory.age , inventory.year , inventory.make , inventory.model  FROM `used_cars` LEFT JOIN inventory ON used_cars.inv_id = inventory.id WHERE used_cars.status = 1 AND retail_status != 'wholesale'";
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

        $stockDetails = $row['stockno'];
        $vin =  $row['vin'];
        $age = $row['age'];


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
        );
    } // /while 

} // if num_rows



$connect->close();

echo json_encode($output);
// echo json_encode($output['totalNumber']);
