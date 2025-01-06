<?php
require_once 'db/core.php';
date_default_timezone_set("America/New_York");
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
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



$sqlQuery = '';
## Custom Field value

$filterBy = $_POST['filterBy'];
$subFilterValue = $_POST['subFilterValue'];

$orderBy = [];
if (isset($_POST['orderBy'])) {
    $orderBy = $_POST['orderBy'];
}

## Search 
$searchQuery = "";
if ($filterBy != '') {
    if ($filterBy == "notTouched") {
        // working
        $searchQuery .= " AND ( (inspections.recon = '' OR inspections.recon IS NULL) AND (inspections.repairs = '' OR  inspections.repairs IS NULL) AND inventory.status != 2 ) ";
    } else if ($filterBy == "holdForRecon") {

        $searchQuery .= " AND ( inspections.recon = 'hold' AND inventory.balance != '' AND inventory.status != 2  ) ";
    } else if ($filterBy == 'sendToRecon') {

        $searchQuery .= " AND ( inspections.recon = 'send' AND inventory.balance != '' AND inventory.status != 2  ) ";
    } else if ($filterBy == 'LotNotes') {
        $searchQuery .= " AND ( (inspections.lot_notes != '' AND inspections.lot_notes IS NOT NULL) AND inventory.status != 2  ) ";
    } else if ($filterBy == 'windshield') {
        if ($subFilterValue == 'pending') {
            $searchQuery .= " AND ( inspections.windshield != '' AND inspections.windshield NOT LIKE '%Done%' AND ( inventory.balance != '' OR inventory.balance != 0 ) AND inventory.status != 2 ) ";
        } else {
            $searchQuery .= " AND ( inspections.windshield != '' AND inspections.windshield LIKE '%Done%' AND ( inventory.balance != '' OR inventory.balance != 0 ) AND inventory.status != 2 ) ";
        }
    } else if ($filterBy == 'wheels') {
        if ($subFilterValue == 'pending') {
            $searchQuery .= " AND ( inspections.wheels != '' AND inspections.wheels NOT LIKE '%Done%' AND ( inventory.balance != '' OR inventory.balance != 0 ) AND inventory.status != 2 ) ";
        } else {
            $searchQuery .= " AND ( inspections.wheels != '' AND inspections.wheels LIKE '%Done%' AND ( inventory.balance != '' OR inventory.balance != 0 ) AND inventory.status != 2 ) ";
        }
    } else if ($filterBy == 'toGo') {
        $searchQuery .= " AND ( inspections.repairs != '' AND  ( inspections.repair_sent = '' OR inspections.repair_sent IS NULL ) AND inventory.status != 3 AND inventory.wholesale != 'on') ";
    } else if ($filterBy == 'wholesale') {
        $searchQuery .= " AND ( ( inventory.balance != '' AND inventory.balance != 0 AND  inventory.balance != '0.00' AND inventory.balance IS NOT NULL ) AND inventory.status != 3 AND inventory.wholesale = 'on' ) ";
    } else if ($filterBy == 'atBodyshop') {

        $searchQuery .= " AND ( inspections.repairs != '' AND  ( inspections.repair_sent != '' ) AND ( inspections.repair_returned = '' OR inspections.repair_returned IS NULL ) AND inventory.status != 3 ) ";
    } else if ($filterBy == 'backFromBodyshop') {
        $searchQuery .= " AND ( inspections.repair_returned != '' AND inspections.repair_sent != '' AND ( inspections.recon = '' OR inspections.recon IS NULL ) AND inventory.status != 3 ) ";
    } else if ($filterBy == 'retailReady') {
        $searchQuery .= " AND ( inspections.recon = 'sent' AND inventory.status != 2 AND inventory.wholesale != 'on') ";
    } else if ($filterBy == 'Gone') {
        $searchQuery .= " AND ( (inventory.balance = '' OR inventory.balance = 0 OR inventory.balance IS NULL ) AND inventory.stocktype != 'NEW' AND inventory.status = 2  ) ";
    }
}

// ------------------------------ Custom Filters -------------------------------------------
if (isset($_POST['wholesaleFilter']) && count($_POST['wholesaleFilter']) != 0) {
    $array = $_POST['wholesaleFilter'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " inventory.wholesale = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}
if (isset($_POST['stockTypeFilter']) && count($_POST['stockTypeFilter']) != 0) {
    $array = $_POST['stockTypeFilter'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " inventory.stocktype = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}

if (isset($_POST['makeFilter']) && count($_POST['makeFilter']) != 0) {
    $array = $_POST['makeFilter'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " inventory.make = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}
if (isset($_POST['modalFilter']) && count($_POST['modalFilter']) != 0) {
    $array = $_POST['modalFilter'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " inventory.model = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}



// working on previus file March 1, 2023
// $sqlQuery = "SELECT '' as button , inspections.shops as bodyshopName , IF((inspections.repair_returned != '' AND inspections.repair_sent IS NOT NULL AND inspections.repair_sent != '' ), inspections.repair_returned , inspections.repair_sent ) as daysout ,  '' as arr, CAST(inventory.age AS INT) as age , CONCAT( inventory.stockno ,' || ', inventory.vin) as stockDetails ,
// inventory.stockno , inventory.year, inventory.make , inventory.model, inventory.color , 
// inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
// inventory.stocktype , inventory.wholesale , inventory.id as invId , inventory.status as invStatus , inspections.* FROM inventory LEFT JOIN inspections ON inventory.id = inspections.inv_id WHERE inventory.lot != 'LBO' AND inventory.location = '$location'";

// $sqlQuery = "SELECT '' as button , inspections.shops as bodyshopName , IF((inspections.repair_returned != '' AND inspections.repair_sent IS NOT NULL AND inspections.repair_sent != '' ), inspections.repair_returned , inspections.repair_sent ) as daysout ,  '' as arr, CAST(inventory.age AS INT) as age , CONCAT( inventory.stockno ,' || ', inventory.vin) as stockDetails ,
// inventory.stockno , inventory.year, inventory.make , inventory.model, inventory.color , 
// CAST(inventory.mileage AS INT) AS mileage, inventory.lot , 
// CASE WHEN inventory.balance = '' THEN ''
//        ELSE ROUND( CAST(REPLACE(REPLACE(inventory.balance, ',', ''), '$', '') AS FLOAT) , 2)
//   END AS balance, 
// inventory.retail, inventory.certified, 
// inventory.stocktype , inventory.wholesale , inventory.id as invId , inventory.status as invStatus , inspections.* FROM inventory LEFT JOIN inspections ON inventory.id = inspections.inv_id WHERE inventory.lot != 'LBO' AND inventory.location = '$location'";

$sqlQuery = "SELECT '' as button , inspections.shops as bodyshopName , IF((inspections.repair_returned != '' AND inspections.repair_sent IS NOT NULL AND inspections.repair_sent != '' ), inspections.repair_returned , inspections.repair_sent ) as daysout ,  '' as arr, CAST(inventory.age AS INT) as age , CONCAT( inventory.stockno ,' || ', inventory.vin) as stockDetails ,
inventory.stockno , inventory.year, inventory.make , inventory.model, inventory.color , 
CAST(inventory.mileage AS INT) AS mileage, inventory.lot, inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , inventory.status as invStatus , inspections.* FROM inventory LEFT JOIN inspections ON inventory.id = inspections.inv_id WHERE inventory.lot != 'LBO' AND inventory.location = '$location'";

// echo $sqlQuery . '<br />';

$table = <<<EOT
(
    {$sqlQuery} {$searchQuery}
) as temp
EOT;

// echo $table;

$primaryKey = 'invId';

$columns = array(
    array(
        'db' => 'button',
        'dt' => 0,
        'formatter' => function ($d, $row) {
            $id = isset($row['invId']) ? $row['invId'] : "";
            $button = '
            <div class="show d-flex" >' .
                (hasAccess("lotWizards", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeInspections(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
                '</div>';
            return $button;
        }
    ),
    array('db' => 'recon',  'dt' => 1, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array(
        'db' => 'submitted_by',
        'dt' => 2,
        'formatter' => function ($d, $row) {
            global $connect;
            $submittedBy = $d;
            if (isset($submittedBy)) {
                $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
                $result1 = $connect->query($sql1);
                $row1 = $result1->fetch_assoc();
                return $row1 ? $row1['username'] : "";
            } else {
                return "";
            }
        }
    ),
    array('db' => 'lot_notes',   'dt' => 3, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array(
        'db' => 'bodyshopName',
        'dt' => 4,
        'formatter' => function ($d, $row) {
            global $connect;
            $bodyShop = isset($row['shops']) ? $row['shops'] : "";
            $bodyshopName = "Blank";
            if ($bodyShop != "") {
                $sql2 = "SELECT * FROM `bodyshops` WHERE id = '$bodyShop'";
                $result2 = $connect->query($sql2);
                $row2 = $result2->fetch_assoc();
                $bodyshopName = $row2 ?  $row2['shop'] : "";
            } else {
                $bodyshopName = "Blank";
            }
            return $bodyshopName;
        }
    ),
    array(
        'db' => 'daysout',
        'dt' => 5,
        'formatter' => function ($d, $row) {
            $daysout = "NULL";
            $TodayDate = date('Y-m-d');
            $today = new DateTime($TodayDate);
            $repairReturned = $row['repair_returned'];
            $repairSent = $row['repair_sent'];

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
                $daysout = ($repairSent != '' && !is_null($repairSent)) ? $repairSent->diff($repairReturned)->format("%r%a") : -1;
            } else 
            if (
                ($repairSent != '' && !is_null($repairSent))
            ) {
                $daysout = ($repairSent != '' && !is_null($repairSent)) ? $repairSent->diff($today)->format("%r%a") : -1;
            }

            return $daysout;
        }
    ),
    array('db' => 'windshield',   'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'wheels',   'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'age',   'dt' => 8, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'stockDetails',   'dt' => 9, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'stockno',   'dt' => 10, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'year',   'dt' => 11, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'make',   'dt' => 12, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'model',   'dt' => 13, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'color',   'dt' => 14, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'mileage',   'dt' => 15, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'lot',   'dt' => 16, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'balance',   'dt' => 17, 'formatter' => function ($d, $row) {
        return $d != "" ? asDollars($d) : "";
    }),
    array('db' => 'retail',   'dt' => 18, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'certified',   'dt' => 19, 'formatter' => function ($d, $row) {
        return ($row['certified'] == 'on') ? "Yes" : "No";
    }),
    array('db' => 'stocktype',   'dt' => 20, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'wholesale',   'dt' => 21, 'formatter' => function ($d, $row) {
        // return ($row['wholesale'] == 'on') ? "Yes" : "No";
        $wholesale = (string)(($row['wholesale'] == 'on') ? "Yes" : "No");
        return $wholesale;
        // return $d;
    }),
    array('db' => 'repairs',   'dt' => 22, 'formatter' => function ($d, $row) {
        return $row['repairs'];
    }),
    array('db' => 'repair_sent',   'dt' => 23, 'formatter' => function ($d, $row) {
        return $row['repair_sent'];
    }),
    array('db' => 'repair_returned',   'dt' => 24, 'formatter' => function ($d, $row) {
        return $row['repair_returned'];
    }),
    array('db' => 'id',   'dt' => 25, 'formatter' => function ($d, $row) {
        return isset($row['invId']) ? $row['invId'] : "";
    }),
    array(
        'db' => 'arr',
        'dt' => 26,
        'formatter' => function ($d, $row) {
            return "";
        }
    ),
    array('db' => 'invStatus',   'dt' => 27, 'formatter' => function ($d, $row) {
        return $row['invStatus'];
    }),
    array('db' => 'invId',   'dt' => 28, 'formatter' => function ($d, $row) {
        return isset($row['invId']) ? $row['invId'] : "";
    }),
    array('db' => 'shops',   'dt' => 29, 'formatter' => function ($d, $row) {
        return isset($row['shops']) ? $row['shops'] : "";
    }),
);


$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $localhost
);

require('ssp.class.php');


$notTouched = 0;
$holdForRecon = 0;
$sendToRecon = 0;
$LotNotes = 0;
$CarsToDealers = 0;
$windshield = 0;
$windshieldP = 0;
$windshieldC = 0;
$wheels = 0;
$wheelsP = 0;
$wheelsC = 0;
$toGo = 0;
$wholesale = 0;
$atBodyshop = 0;
$backFromBodyshop = 0;
$retailReady = 0;
$Gone = 0;


// echo $sqlQuery;

$searhStatusArray = array();
$result = $connect->query($sqlQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {

        $invStatus = $row['invStatus'];

        $balance = $row['balance'];
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
        $_wholesale = false;
        $_atBodyshop = false;
        $_backBodyshop = false;
        $_retailReady = false;
        $_gone = false;



        if (($recon == "" || $recon == null) && count($repairArr) == 0 && $row['wholesale'] != 'on' && $row['stocktype'] != 'NEW' && $invStatus != 2) {
            $notTouched += 1;
        }

        if (($recon == "" || $recon == null) && count($repairArr) == 0 && $invStatus != 2) {
            $_notTouched = 'Not Touched';
        }


        if ($recon == 'hold' && $balance && $invStatus != 2) {
            $holdForRecon += 1;
            $_holdRecon = 'Hold Recon';
        }
        if ($recon == 'send' && $balance && $invStatus != 2) {
            $sendToRecon += 1;
            $_sendRecon = 'Send Recon';
        }
        if ($notes && $invStatus != 2) {
            $LotNotes += 1;
            $_lotNotes = 'Lot Notes';
        }
        // also count total pending
        if (count($windshield1) > 0 && !$doneEle && ($balance && $balance != '0') && $invStatus != 2) {
            $windshield += 1;
            $windshieldP += 1;
            $_windshield = 'Windshield';
        }
        // also count total compelte
        if (count($windshield1) > 0 && $doneEle && ($balance && $balance != '0') && $invStatus != 2) {
            $windshieldC += 1;
        }


        if (count($wheels1) > 0 &&  !$doneEleWheel && ($balance && $balance != '0') && $invStatus != 2) {
            $wheelsP += 1;
            $wheels += 1;
            $_wheels = "Wheels";
        }
        if (count($wheels1) > 0 &&  $doneEleWheel && ($balance && $balance != '0') && $invStatus != 2) {
            $wheelsC += 1;
        }


        if (count($arr) > 0 && ($repairSent == "" || $repairSent == null) && $invStatus != 3 && $row['wholesale'] != 'on') {
            $toGo += 1;
            $_toGo = "To Go";
        }
        if (($balance != '' && $balance != null && $balance != 0 && $balance != '0.00') && $invStatus != 3 && $row['wholesale'] == 'on') {
            $wholesale += 1;
            $_wholesale = "Wholesale";
        }
        if (count($arr) > 0 && ($repairSent != "" && $repairSent != null) && ($repairReturned == "" || $repairReturned == null) && $invStatus != 3) {
            $atBodyshop += 1;
            $_atBodyshop = "At Bodyshop";
        }
        if ($repairReturned && $repairSent && ($recon == "" || $recon == null) && $invStatus != 3) {
            $backFromBodyshop += 1;
            $_backBodyshop = "Back Bodyshop";
        }
        if ($recon == 'sent' && $invStatus != 2 && $row['wholesale'] != 'on') {
            $retailReady += 1;
            $_retailReady = "Retail Ready";
        }
        if (($balance == '' || $balance == null || $balance == 0) && $row['stocktype'] != 'NEW' && $invStatus == 2) {
            $Gone += 1;
            $_gone = "Gone";
        }

        $searhStatusArray[]  = array(
            'stockDetails' => $row['stockDetails'],
            'stockAvailibility' => array($_notTouched, $_holdRecon, $_sendRecon, $_lotNotes, $_windshield, $_wheels, $_toGo, $_wholesale, $_atBodyshop, $_backBodyshop, $_retailReady, $_gone),
        );
    }
}


// Fetch and output the data
// $dataObj = SSP::complex($_POST, $sql_details, $table, $primaryKey, $columns);
$dataObj = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);


$carsToDealsSql = "SELECT COUNT(inventory.stockno) as totalPending FROM inventory LEFT JOIN car_to_dealers ON inventory.id = car_to_dealers.inv_id 
WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1 AND inventory.location = '$location' AND ( car_to_dealers.work_needed != '' AND car_to_dealers.work_needed IS NOT NULL) AND ( car_to_dealers.date_returned = '' OR car_to_dealers.date_returned IS NULL)";

$result3 = $connect->query($carsToDealsSql);
$row3 = $result3->fetch_assoc();
$CarsToDealers = $row3['totalPending'];


$sql4 = "SELECT COUNT(inspections.id) as totalWizardBills
FROM `inspections` LEFT JOIN inventory ON (inspections.inv_id = inventory.id AND inventory.location = '$location') WHERE inspections.status = 1 AND inventory.location = '$location' AND inspections.repair_returned != '' AND (inspections.repair_paid = '' OR inspections.repair_paid IS NULL )";
$result4 = $connect->query($sql4);
$row4 = $result4->fetch_assoc();
$totalWizardBills = $row4['totalWizardBills'];

$dataObj['searhStatusArray'] = $searhStatusArray;


$dataObj['table'] = $table;
$dataObj['totalNumber'] = array(
    "notTouched" => $notTouched,
    "holdForRecon" => $holdForRecon,
    "sendToRecon" => $sendToRecon,
    "LotNotes" => $LotNotes,
    "CarsToDealers" => $CarsToDealers,
    "windshield" => $windshield,
    "windshieldP" => $windshieldP,
    "windshieldC" => $windshieldC,
    "wheels" => $wheels,
    "wheelsP" => $wheelsP,
    "wheelsC" => $wheelsC,
    "toGo" => $toGo,
    "wholesale" => $wholesale,
    "atBodyshop" => $atBodyshop,
    "backFromBodyshop" => $backFromBodyshop,
    "retailReady" => $retailReady,
    "Gone" => $Gone,
    "wizardBills" => $totalWizardBills
);



echo json_encode($dataObj);

// echo json_encode(
//     SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns)
// );
// echo json_encode(
//     SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
// );
