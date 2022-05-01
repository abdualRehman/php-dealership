<?php

require_once 'db/core.php';

$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , inspections.* FROM inventory LEFT JOIN inspections ON inventory.id = inspections.inv_id WHERE inventory.stocktype = 'USED' AND inventory.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

$notTouched = 0;
$holdForRecon = 0;
$sendToRecon = 0;
$LotNotes = 0;
$CarsToDealers = 0;
$windshield = 0;
$wheels = 0;
$toGo = 0;
$atBodyshop = 0;
$backFromBodyshop = 0;
$retailReady = 0;
$Gone = 0;


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

        // bodyshop name 
        $bodyShopName = "";
        $bodyShop = $row['shops'] ? $row['shops'] : "Null";
        $bodyShop = explode("__", $bodyShop);
        $bodyShop = array_slice($bodyShop, 1, -1);
        if (count($bodyShop) == 1) {
            $bodyId = $bodyShop[0];
            $sql2 = "SELECT * FROM `bodyshops` WHERE id = '$bodyId'";
            $result2 = $connect->query($sql2);
            $row2 = $result2->fetch_assoc();
            $bodyShopName = $row2['shop'];
        } else if (count($bodyShop) > 1) {
            $bodyShopName = "Multiple shops selected";
            // echo $bodyShopName;
        } else if (count($bodyShop) == 0) {
            $bodyShopName =  "blank";
        }
        // --------------------------------------------------------------------------------------------------------

        $balance = $row[9];
        $recon = $row['recon'];
        $notes = $row['lot_notes'];

        $windshield1 = $row['windshield'];
        $windshield1 = $windshield1 ? explode("__", $windshield1) : [];
        $doneEle = array_search('Done', $windshield1);

        $wheels1 = $row['wheels'];
        $wheels1 = $wheels1 ? explode("__", $wheels1) : [];
        $doneEleWheel = array_search('Done', $wheels1);

        $repairs = $row['repairs'];
        $arr = $repairs ? explode("__", $repairs) : [];

        $repairArr = array_slice($arr, 1, -1);

        $repairSent =  $row['repair_sent'];
        $repairReturned =  $row['repair_returned'];


        // not touched old
        // if ($recon == "" || $recon == null || $notes == null || $notes == "" || !$doneEleWheel || !$doneEle || count($arr) == 0) {
        //     $notTouched += 1;
        // }
        if (($recon == "" || $recon == null) && count($repairArr) == 0) {
            $notTouched += 1;
        }
        if ($recon == 'hold' && $balance) {
            $holdForRecon += 1;
        }
        if ($recon == 'send' && $balance) {
            $sendToRecon += 1;
        }
        if ($notes) {
            $LotNotes += 1;
        }
        if ($doneEle) {
            $windshield += 1;
        }
        if ($doneEleWheel) {
            $wheels += 1;
        }
        if (count($arr) > 0 && ($repairSent == "" || $repairSent == null)) {
            $toGo += 1;
        }
        if (count($arr) > 0 && $repairSent) {
            $atBodyshop += 1;
        }
        if ($repairReturned && $repairSent) {
            $backFromBodyshop += 1;
        }
        if ($recon == 'sent') {
            $retailReady += 1;
        }
        if ($balance == '' || $balance == null) {
            $Gone += 1;
        }



        $certified = ($row[11] == 'on') ? "Yes" : "No";
        $wholesale = ($row[13] == 'on') ? "Yes" : "No";


        $button = '
            <div class="show d-flex" >
            <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editInspection(' . $id . ')" >
                    <i class="fa fa-car" ></i>
                </button>
               <!-- <button class="btn btn-label-primary btn-icon mr-1" onclick="removeShop(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button> -->
            </div>
        ';
        $output['data'][] = array(
            $button,
            $row['recon'],
            $row['submitted_by'],
            $row['lot_notes'],
            $bodyShopName,
            $row[0], //model
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
            $row['windshield'],
            $row['wheels'],
            $row['repairs'],
            $row['repair_sent'],
            $row['repair_returned'],


        );
    } // /while 

} // if num_rows

$carsToDealsSql = "SELECT COUNT(inventory.stockno) as totalPending FROM inventory LEFT JOIN car_to_dealers ON inventory.id = car_to_dealers.inv_id WHERE inventory.stocktype = 'USED' AND inventory.status = 1 AND ( car_to_dealers.date_returned = '' OR car_to_dealers.date_returned IS NULL)";
$result3 = $connect->query($carsToDealsSql);
$row3 = $result3->fetch_assoc();
$CarsToDealers = $row3['totalPending'];


$output['totalNumber'] = array(
    "notTouched" => $notTouched,
    "holdForRecon" => $holdForRecon,
    "sendToRecon" => $sendToRecon,
    "LotNotes" => $LotNotes,
    "CarsToDealers" => $CarsToDealers,
    "windshield" => $windshield,
    "wheels" => $wheels,
    "toGo" => $toGo,
    "atBodyshop" => $atBodyshop,
    "backFromBodyshop" => $backFromBodyshop,
    "retailReady" => $retailReady,
    "Gone" => $Gone,
);

$connect->close();

echo json_encode($output);
// echo json_encode($output['totalNumber']);
