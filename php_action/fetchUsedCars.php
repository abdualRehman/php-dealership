<?php

require_once 'db/core.php';

$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

$addToSheet = 0;
$missingDate = 0;
$titleIssue = 0;
$readyToShip = 0;
$keysPulled = 0;
$atAuction = 0;
$soldAtAuction = 0;
$retail = 0;
$sold = 0;



if ($result->num_rows > 0) {

    // while ($row = $result->fetch_assoc()) {
    while ($row = $result->fetch_array()) {
        $id = $row['invId'];
        $stockDetails = $row[1] . ' ||  ' . $row[2];
        $submittedBy = $row['submitted_by'];
        if (isset($submittedBy)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $row['submitted_by'] = $row1['username'];
        } else {
            $row['submitted_by'] = "";
        }


        $certified = ($row[11] == 'on') ? "Yes" : "No";
        $wholesale = ($row[13] == 'on') ? "Yes" : "No";


        $balance = $row[9];
        $date_in =  $row['date_in'];
        $title = $row['title'];

        $retail_status = $row['retail_status'];
        $key = $row['key'];
        $date_sent = $row['date_sent'];
        $date_sold = $row['date_sold'];

        if (($date_in != '' && $date_in != null) && $retail_status != 'wholesale') {
            $addToSheet += 1;
        }

        if (($date_in == '' || $date_in == null) && $balance) {
            $missingDate += 1;
        }

        if ($title == 'false' && $balance) {
            $titleIssue += 1;
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'false') {
            $readyToShip += 1;
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'true') {
            $keysPulled += 1;
        }

        // if (($date_sent != "" || $date_sent != null) && ($date_sold == "" || $date_sold == null)) {
        //     $atAuction += 1;
        // }
        if ($date_sent && !$date_sold) {
            $atAuction += 1;
        }

        if ($date_sold != "" && $date_sold != null) {
            $soldAtAuction += 1;
        }

        if ($wholesale == 'No' && $balance) {
            $retail += 1;
        }

        if ($balance = "" || $balance == null) {
            $sold += 1;
        }



        $button = '
            <div class="show d-flex" >' .
            (hasAccess("usedCars", "Edit") !== 'false' ?  '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editUsedCar(' . $id . ')" >
                    <i class="fa fa-car" ></i>
                </button>' : "") .
            '<!-- <button class="btn btn-label-primary btn-icon mr-1" onclick="removeShop(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button> -->
            </div>
        ';

        $output['data'][] = array(
            $button,
            $row[0], //age
            $stockDetails,
            $row[3], //model
            $row[4], // year
            $row[5], // make
            $row[6], // color
            $row[7], // mileage
            $row[8], // lot
            $row[9], // balance
            $row[10], // retail
            $certified, // certificate
            $row[12], // stock type
            $wholesale, // wholesale
            $row['date_in'],
            $row['title'],
            $row['key'],
            $row['date_sent'],
            $row['date_sold'],
            $row['retail_status'],

        );
    } // /while 

} // if num_rows

$output['totalNumber'] = array(
    "addToSheet" => $addToSheet,
    "missingDate" => $missingDate,
    "titleIssue" => $titleIssue,
    "readyToShip" => $readyToShip,
    "keysPulled" => $keysPulled,
    "atAuction" => $atAuction,
    "soldAtAuction" => $soldAtAuction,
    "retail" => $retail,
    "sold" => $sold,
);





$connect->close();

echo json_encode($output);
// echo json_encode($output['totalNumber']);
