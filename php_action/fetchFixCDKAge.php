<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1 AND inventory.location = '$location'";
$result = $connect->query($sql);

$output = array('data' => array());

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}
function asDollars($value)
{
    if ($value < 0) return "-" . asDollars(-$value);
    return '$' . number_format($value, 2);
}


$fixAge = 0;
if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {

        $carshopId = $row['id'];
        $id = $row['invId'];
        $stockDetails = $row[1] . ' ||  ' . $row[2];
        $submittedBy = $row['submitted_by'];
        $sales_consultant = $row['sales_consultant'];

        $certified = ($row[11] == 'on') ? "Yes" : "No";
        $wholesale = ($row[13] == 'on') ? "Yes" : "No";


        $balance = $row[9];
        $date_in =  $row['date_in'];
        $title = $row['title'];

        $retail_status = $row['retail_status'];
        $key = $row['key'];
        $date_sent = $row['date_sent'];
        $date_sold = $row['date_sold'];

        $age = $row[0]; // age

        $cdkAge = "";
        if ($row['date_in'] != '' && !is_null($row['date_in'])) {
            // date_default_timezone_set('Asia/Karachi');
            $date = strtotime(date('Y-m-d'));
            $date_in = reformatDate($row['date_in']);
            $date_in = date('Y-m-d', strtotime('-1 day', strtotime($date_in)));
            $date_in = strtotime($date_in);
            $cdkAge = ceil(abs($date_in - $date) / 86400);
        }

        $age = (int)$age;
        $cdkAge = (int)$cdkAge;

        $fixed_status = $row['fixed_status']; // fixed_status

        if ($id != null && $carshopId != null && $row['date_in'] != '' && !is_null($row['date_in']) && $age != $cdkAge && $fixed_status != "true") {
            $fixAge += 1;
            $output['data'][] = array(
                $id,
                $row['date_in'],
                $age, //age
                $cdkAge,
                $fixed_status,
                $stockDetails,
                $row[4], // year
                $row[5], // make
                $row[3], //model
                $row[6], // color
                $row[7], // mileage
                $row[8], // lot
                $row[9], // balance
                $row[10], // retail
                $certified, // certificate
                $row[12], // stock type
                $wholesale, // wholesale
            );
        }
    } // /while 

} // if num_rows



$output['totalNumber'] = array(
    "fixAge" => $fixAge,
);




$connect->close();

echo json_encode($output);
