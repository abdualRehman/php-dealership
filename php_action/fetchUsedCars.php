<?php

require_once 'db/core.php';

$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1";
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
$fixAge = 0;


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



if ($result->num_rows > 0) {

    // while ($row = $result->fetch_assoc()) {
    while ($row = $result->fetch_array()) {
        $carshopId = $row['id'];
        $id = $row['invId'];
        $stockDetails = $row[1] . ' ||  ' . $row[2];
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


        $certified = ($row[11] == 'on') ? "Yes" : "No";
        $wholesale = ($row[13] == 'on') ? "Yes" : "No";


        $balance = $row[9];
        $date_in =  $row['date_in'];
        $title = $row['title'];

        $retail_status = $row['retail_status'];
        $key = $row['key'];
        $date_sent = $row['date_sent'];
        $date_sold = $row['date_sold'];


        $_addToSheet = false;
        $_missingDate = false;
        $_titleIssue = false;
        $_readyToship = false;
        $_keyPulled = false;
        $_atAuction = false;
        $_soldAtAuction = false;
        $_retail = false;
        $_sold = false;














        // if (($date_in != '' && $date_in != null) && $retail_status != 'wholesale') {
        //     $addToSheet += 1;
        // }
        if ($date_in !== '' && $date_in === null) {
            $addToSheet += 1;
            $_addToSheet = "Add To Sheet";
        }

        if (($date_in === '' || $date_in === 'undefined') && $date_in !== null && $balance !== '') {
            $missingDate += 1;
            $_missingDate = "Missing Date";
        }

        // if (($title == 'false' || $title == null) && ($date_in !== '' && $date_in !== null)) {
        //     $titleIssue += 1;
        //     $_titleIssue = "Title Issue";
        // }
        if (($title == 'false' || $title == null) && ($date_in !== null)) {
            $titleIssue += 1;
            $_titleIssue = "Title Issue";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'false' && ($date_in !== '' && $date_in !== null)) {
            $readyToShip += 1;
            $_readyToship = "Ready To Ship";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'true' && ($date_in !== '' && $date_in !== null) && !$date_sent && !$date_sold) {
            $keysPulled += 1;
            $_keyPulled = "Keys Pulled";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'true' && ($date_in !== '' && $date_in !== null) && $date_sent && !$date_sold) {
            $atAuction += 1;
            $_atAuction = "At Auction";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'true' && ($date_in !== '' && $date_in !== null) && $date_sent && $date_sold) {
            $soldAtAuction += 1;
            $_soldAtAuction = "Sold At Auction";
        }

        // if ($wholesale == 'No' && $balance !== '' && $date_in !== null) {
        //     $retail += 1;
        //     $_retail = "Retail";
        // }
        if ($retail_status != 'wholesale' && $balance !== '' && ($carshopId !== '' && $carshopId !== null)) {
            $retail += 1;
            $_retail = "Retail";
        }

        if (($balance === "" || $balance === null) && $date_in !== null) {
            $sold += 1;
            $_sold = "Sold";
        }


        $age = $row[0]; // age
        $cdkAge = "";

        if ($row['date_in'] != '' && !is_null($row['date_in'])) {
            // date_default_timezone_set('Asia/Karachi');
            $date = strtotime(date('Y-m-d'));
            $date_in = reformatDate($row['date_in']);
            $date_in = date('Y-m-d', strtotime('-1 day', strtotime($date_in)));
            $date_in = strtotime($date_in);
            $cdkAge = ceil(abs($date_in - $date) / 86400);

            // $date = date('Y-m-d', strtotime('+2 days'));
            // $today = new DateTime($date);
            // $date_in = reformatDate($row['date_in']);
            // $date_in = new DateTime($date_in);
            // $cdkAge =  $date_in->diff($today)->format("%r%a");
        }


        $age = (int)$age;
        $cdkAge = (int)$cdkAge;

        if ($id != null && $carshopId != null && $row['date_in'] != '' && !is_null($row['date_in']) && $age != $cdkAge) {
            $fixAge += 1;
        }




        // $button = '
        //     <div class="show d-flex" >' .
        //     (hasAccess("usedCars", "Edit") !== 'false' ?  '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editUsedCar(' . $id . ')" >
        //             <i class="fa fa-car" ></i>
        //         </button>' : "") .
        //     '<!-- <button class="btn btn-label-primary btn-icon mr-1" onclick="removeShop(' . $id . ')" >
        //             <i class="fa fa-trash"></i>
        //         </button> -->
        //     </div>
        // ';
        $button = '
            <div class="show d-flex" >
                <button class="btn btn-label-primary btn-icon mr-1" onclick="removeCarshop(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        ';

        // $date_in = '
        // <div class="show d-flex" >
        //     <div class="custom-control custom-control-lg custom-checkbox">
        //         <input type="checkbox" name="' . $id . 'checkbox" class="custom-control-input editCheckbox" id="' . $id . '" ' . (($row[10] == '1') ? '' : 'checked="checked"') . ' >
        //         <label class="custom-control-label" for="' . $id . '"></label> 
        //     </div>
        // </div>';
        if ($_SESSION['userRole'] == $onlineManagerID) {
            $date_in = $row['date_in'];
        } else {
            $date_in = '
            <div class="show d-flex" >
                <input type="text" class="form-control" name="date_in_table" value="' . $row['date_in'] . '" data-attribute="date_in" data-id="' . $id . '" autocomplete="off"  />
            </div>';
        }


        $balance = (isset($row[9]) && $row[9] != '') ? (float)str_replace(array(',', '$'), '', $row[9]) : 0;
        $sold_price = (isset($row['sold_price']) && $row['sold_price'] != '')  ? $row['sold_price'] : 0;
        $profit = (int)$sold_price - (int)$balance;
        $profit = round($profit, 2);
        $profit = asDollars($profit);


        $output['data'][] = array(
            $id,
            $cdkAge, //age
            $stockDetails,
            $date_in,
            $key,
            $row[4], // year
            $row[5], // make
            $row[3], //model
            $row[6], // color
            $row[7], // mileage
            $row[8], // lot
            $title,
            $row['title_priority'],
            $row['customer'],
            $sales_consultant,
            $row['title_notes'],
            $row[9], // balance
            $row[10], // retail
            $certified, // certificate
            $row[12], // stock type
            $wholesale, // wholesale
            $row['date_sent'],
            $row['date_sold'],
            $row['retail_status'],
            $row['date_in'],
            $row['wholesale_notes'],
            $row['sold_price'],
            $profit,
            $row['uci'],
            $row['purchase_from'],
            array($_addToSheet, $_missingDate, $_titleIssue, $_readyToship, $_keyPulled, $_atAuction, $_soldAtAuction, $_retail, $_sold),
            $button,
            $row['id'],

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
    "fixAge" => $fixAge,
);





$connect->close();

echo json_encode($output);
// echo json_encode($output['totalNumber']);
