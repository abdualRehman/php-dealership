<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';


// $sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
// inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
// inventory.stocktype , inventory.wholesale , inventory.id as invId , inspections.* FROM inventory LEFT JOIN inspections ON inventory.id = inspections.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1 AND inventory.location = '$location'";
$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , inventory.status as invStatus , inspections.* FROM inventory LEFT JOIN inspections ON inventory.id = inspections.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.location = '$location'";
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

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}


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
        // $bodyShopName = "";
        // $bodyShop = $row['shops'] ? $row['shops'] : "Null";
        // $bodyShop = explode("__", $bodyShop);
        // $bodyShop = array_slice($bodyShop, 1, -1);
        // if (count($bodyShop) == 1) {
        //     $bodyId = $bodyShop[0];
        //     $sql2 = "SELECT * FROM `bodyshops` WHERE id = '$bodyId'";
        //     $result2 = $connect->query($sql2);
        //     $row2 = $result2->fetch_assoc();
        //     $bodyShopName = $row2['shop'];
        // } else if (count($bodyShop) > 1) {
        //     $bodyShopName = "Multiple shops selected";
        //     // echo $bodyShopName;
        // } else if (count($bodyShop) == 0) {
        //     $bodyShopName =  "blank";
        // }
        $bodyShopName = "";
        $bodyShop = $row['shops'] ? $row['shops'] : "";

        if ($bodyShop != "") {
            $sql2 = "SELECT * FROM `bodyshops` WHERE id = '$bodyShop'";
            $result2 = $connect->query($sql2);
            $row2 = $result2->fetch_assoc();
            $bodyShopName = $row2['shop'];
        } else {
            $bodyShopName = "blank";
        }
        // --------------------------------------------------------------------------------------------------------

        $invStatus = $row['invStatus'];

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




        $_notTouched = false;
        $_holdRecon = false;
        $_sendRecon = false;
        $_lotNotes = false;
        $_windshield = false;
        $_wheels = false;
        $_toGo = false;
        $_atBodyshop = false;
        $_backBodyshop = false;
        $_retailReady = false;
        $_gone = false;





        // not touched old
        // if ($recon == "" || $recon == null || $notes == null || $notes == "" || !$doneEleWheel || !$doneEle || count($arr) == 0) {
        //     $notTouched += 1;
        // }
        if (($recon == "" || $recon == null) && count($repairArr) == 0 && $invStatus == 1) {
            $notTouched += 1;
            $_notTouched = 'Not Touched';
        }
        if ($recon == 'hold' && $balance && $invStatus == 1) {
            $holdForRecon += 1;
            $_holdRecon = 'Hold Recon';
        }
        if ($recon == 'send' && $balance && $invStatus == 1) {
            $sendToRecon += 1;
            $_sendRecon = 'Send Recon';
        }
        if ($notes && $invStatus == 1) {
            $LotNotes += 1;
            $_lotNotes = 'Lot Notes';
        }
        if (count($windshield1) > 0 && !$doneEle && ($balance && $balance != '0') && $invStatus == 1) {
            $windshield += 1;
            $_windshield = 'Windshield';
        }
        if (count($wheels1) > 0 &&  !$doneEleWheel && ($balance && $balance != '0') && $invStatus == 1) {
            $wheels += 1;
            $_wheels = "Wheels";
        }
        // To go = repairs selected….repair sent bank
        if (count($arr) > 0 && ($repairSent == "" || $repairSent == null) && $invStatus == 1) {
            $toGo += 1;
            $_toGo = "To Go";
        }
        // At bodyshop- repairs, bodyshop & repair sent selected…….repair returned blank
        if (count($arr) > 0 && ($repairSent != "" && $repairSent != null) && ($repairReturned == "" || $repairReturned == null) && $invStatus == 1) {
            $atBodyshop += 1;
            $_atBodyshop = "At Bodyshop";
        }
        // Back from bodyshop- repair returned selected and recon is blank
        if ($repairReturned && $repairSent && ($recon == "" || $recon == null) && $invStatus == 1) {
            $backFromBodyshop += 1;
            $_backBodyshop = "Back Bodyshop";
        }
        if ($recon == 'sent' && $invStatus == 1) {
            $retailReady += 1;
            $_retailReady = "Retail Ready";
        }
        if ($balance == '' || $balance == null || $balance == '0') {
            $Gone += 1;
            $_gone = "Gone";
        }



        $certified = ($row[11] == 'on') ? "Yes" : "No";
        $wholesale = ($row[13] == 'on') ? "Yes" : "No";


        $button = '
            <div class="show d-flex" >' .
            // (hasAccess("lotWizards", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editInspection(' . $id . ')" >
            //         <i class="fa fa-car" ></i>
            //     </button>' : "") .
            (hasAccess("lotWizards", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeInspections(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>
        ';


        $daysout = "NULL";
        $TodayDate = date('Y-m-d');
        $today = new DateTime($TodayDate);
        $repairSent = $row['repair_sent'];
        $repairReturned = $row['repair_returned'];

        if ($repairSent != '' && !is_null($repairSent)) {
            $repairSent = reformatDate($repairSent);
            $repairSent = new DateTime($repairSent);
        }
        if ($repairReturned != '' && !is_null($repairReturned)) {
            $repairReturned = reformatDate($repairReturned);
            $repairReturned = new DateTime($repairReturned);
        }

        if (
            ($repairReturned != '' && !is_null($repairSent)) && ($repairSent != '' && !is_null($repairSent))
        ) {
            // $daysout = ($repairSent != '' && !is_null($repairSent)) ? $repairReturned->diff($repairSent)->format("%r%a") : -1;
            $daysout = ($repairSent != '' && !is_null($repairSent)) ? $repairSent->diff($repairReturned)->format("%r%a") : -1;
        } else 
        if (
            ($repairSent != '' && !is_null($repairSent))
        ) {
            // $daysout = ($repairSent != '' && !is_null($repairSent)) ? $today->diff($repairSent)->format("%r%a") : -1;
            $daysout = ($repairSent != '' && !is_null($repairSent)) ? $repairSent->diff($today)->format("%r%a") : -1;
        }



        
        
        // $output['data'][] = array(
        //     $button,
        //     $row['recon'],
        //     $row['submitted_by'],
        //     $row['lot_notes'],
        //     $bodyShopName,
        //     $daysout,
        //     $row[0], //age
        //     $stockDetails,
        //     $row[1], // stock only
        //     $row[4], // year
        //     $row[5], // make
        //     $row[3], //model
        //     $row[6], // color
        //     $row[7], // mileage
        //     $row[8], // lot
        //     $row[9], // balance
        //     $row[10], // retail
        //     $certified, // certificate
        //     $row[12], // stock type
        //     $wholesale, // wholesale
        //     $row['windshield'],
        //     $row['wheels'],
        //     $row['repairs'],
        //     $row['repair_sent'],
        //     $row['repair_returned'],
        //     $id,
        //     array($_notTouched, $_holdRecon, $_sendRecon, $_lotNotes, $_windshield, $_wheels, $_toGo, $_atBodyshop, $_backBodyshop, $_retailReady, $_gone),
        // );
        
        $output['data'][] = array(
            $button,
            $row['recon'],
            $row['submitted_by'],
            $row['lot_notes'],
            $bodyShopName,
            $daysout,
            $row['windshield'],
            $row['wheels'],
            $row[0], //age
            $stockDetails,
            $row[1], // stock only
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
            $row['repairs'],
            $row['repair_sent'],
            $row['repair_returned'],
            $id,
            array($_notTouched, $_holdRecon, $_sendRecon, $_lotNotes, $_windshield, $_wheels, $_toGo, $_atBodyshop, $_backBodyshop, $_retailReady, $_gone),
            $invStatus,
        );
    } // /while 

} // if num_rows

// $carsToDealsSql = "SELECT COUNT(inventory.stockno) as totalPending FROM inventory LEFT JOIN car_to_dealers ON inventory.id = car_to_dealers.inv_id 
// WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1 AND ( car_to_dealers.date_returned = '' OR car_to_dealers.date_returned IS NULL)";
$carsToDealsSql = "SELECT COUNT(inventory.stockno) as totalPending FROM inventory LEFT JOIN car_to_dealers ON inventory.id = car_to_dealers.inv_id 
WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1 AND inventory.location = '$location' AND ( car_to_dealers.work_needed != '' AND car_to_dealers.work_needed IS NOT NULL) AND ( car_to_dealers.date_returned = '' OR car_to_dealers.date_returned IS NULL)";

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
