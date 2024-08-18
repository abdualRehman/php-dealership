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
$statusPriority = $_POST['statusPriority'];

$OrderByQuery = "";
$orderBy = [];
if (isset($_POST['orderBy'])) {
    $orderBy = $_POST['orderBy'];
}




## Search 
$searchQuery = "";
if ($filterBy != '') {
    if ($filterBy == "addToSheet") {
        $searchQuery .= " AND (used_cars.date_in IS NULL AND inventory.status != 2)";
    } else if ($filterBy == "missingDate") {
        $searchQuery .= " AND ( used_cars.date_in = '' AND used_cars.date_in IS NOT NULL AND inventory.status != 2  ) ";
    } else if ($filterBy == 'titleIssue') {

        if ($statusPriority != '') {
            $searchQuery .= " AND ( (used_cars.title = 'false' OR used_cars.title IS NULL) AND used_cars.date_in IS NOT NULL AND inventory.status != 2 AND used_cars.title_priority = '$statusPriority' ) ";
        } else {
            $searchQuery .= " AND ( (used_cars.title = 'false' OR used_cars.title IS NULL) AND used_cars.date_in IS NOT NULL AND inventory.status != 2  ) ";
        }
    } else if ($filterBy == 'readyToShip') {
        $searchQuery .= " AND ( (used_cars.title = 'true' AND used_cars.retail_status = 'wholesale' AND used_cars.key = 'false') AND inventory.status != 2  ) ";
    } else if ($filterBy == 'keysPulled') {

        $searchQuery .= " AND ( (used_cars.title = 'true' AND used_cars.retail_status = 'wholesale' AND used_cars.key = 'true' AND used_cars.date_sent = '' AND used_cars.date_sold = '' ) AND inventory.status != 2 ) ";
    } else if ($filterBy == 'atAuction') {
        $searchQuery .= " AND ( (used_cars.title = 'true' AND used_cars.retail_status = 'wholesale' AND used_cars.key = 'true' AND used_cars.date_sent != '' AND used_cars.date_sold = '' ) AND inventory.status != 2 ) ";
    } else if ($filterBy == 'soldAtAuction') {
        $searchQuery .= " AND (used_cars.title = 'true' AND used_cars.retail_status = 'wholesale' AND used_cars.key = 'true' AND used_cars.date_sent != '' AND used_cars.date_sold != '' )";
    } else if ($filterBy == 'retail') {
        $searchQuery .= " AND (used_cars.retail_status != 'wholesale' AND inventory.balance != '' AND used_cars.id IS NOT NULL AND inventory.status != 2 )";
    } else if ($filterBy == 'sold') {
        $searchQuery .= " AND ( (inventory.balance = '' OR inventory.balance IS NULL) AND used_cars.date_in IS NOT NULL) ";
    }
}

// ------------------------------ Custom Filters -------------------------------------------
if (isset($_POST['retailF']) && count($_POST['retailF']) != 0) {
    $array = $_POST['retailF'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " used_cars.retail_status = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}
if (isset($_POST['uciF']) && count($_POST['uciF']) != 0) {
    $array = $_POST['uciF'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " used_cars.uci = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}
if (isset($_POST['uciokF']) && count($_POST['uciokF']) != 0) {
    $array = $_POST['uciokF'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " used_cars.oci_ok = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}

if (isset($_POST['purchaseF']) && count($_POST['purchaseF']) != 0) {
    $array = $_POST['purchaseF'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " used_cars.purchase_from = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}
if (isset($_POST['titleF']) && count($_POST['titleF']) != 0) {
    $array = $_POST['titleF'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " used_cars.title_priority = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}
if (isset($_POST['soldF']) && count($_POST['soldF']) != 0) {
    $array = $_POST['soldF'];
    $searchQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $searchQuery .= " used_cars.date_sold = '$value' ";
            if (next($array) == true) $searchQuery .= " OR ";
        }
    }
    $searchQuery .= " ) ";
}




// working....
// $sqlQuery = "SELECT inventory.id as invId , used_cars.date_in as cdkAge , '' as button , '' as arr, CONCAT( inventory.stockno ,' || ', inventory.vin) as stockDetails , 
// CAST(inventory.age AS INT) as age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
// inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified as certified_inv, 
// inventory.stocktype , inventory.wholesale , inventory.status as invStatus , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.location = '$location'";

// working on previus file March 1, 2023
// $sqlQuery = "SELECT inventory.id as invId , IF((used_cars.date_in != ''), STR_TO_DATE(used_cars.date_in, '%m-%d-%Y') , 'zzz') as cdkAge , '' as button , '' as arr, CONCAT( inventory.stockno ,' || ', inventory.vin) as stockDetails , 
// CAST(inventory.age AS INT) as age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
// inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified as certified_inv, 
// inventory.stocktype , inventory.wholesale , inventory.status as invStatus, IF((used_cars.date_sold != ''), STR_TO_DATE(used_cars.date_sold, '%m-%d-%Y') , 'zzz') as date_sold_modified , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.location = '$location'";

// $sqlQuery = "SELECT inventory.id as invId , IF((used_cars.date_in != ''), STR_TO_DATE(used_cars.date_in, '%m-%d-%Y') , 'zzz') as cdkAge , '' as button , '' as arr, CONCAT( inventory.stockno ,' || ', inventory.vin) as stockDetails , 
// CAST(inventory.age AS INT) as age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
// CAST(inventory.mileage AS INT) AS mileage, inventory.lot ,
// CASE WHEN inventory.balance = '' THEN ''
//        ELSE ROUND( CAST(REPLACE(REPLACE(inventory.balance, ',', ''), '$', '') AS FLOAT) , 2)
//   END AS balance, inventory.retail, inventory.certified as certified_inv, 
// inventory.stocktype , inventory.wholesale , inventory.status as invStatus, IF((used_cars.date_sold != ''), STR_TO_DATE(used_cars.date_sold, '%m-%d-%Y') , 'zzz') as date_sold_modified , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.location = '$location'";


$sqlQuery = "SELECT inventory.id as invId , IF((used_cars.date_in != ''), STR_TO_DATE(used_cars.date_in, '%m-%d-%Y') , 'zzz') as cdkAge , '' as button , '' as arr, CONCAT( inventory.stockno ,' || ', inventory.vin) as stockDetails , 
CAST(inventory.age AS INT) as age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
CAST(inventory.mileage AS INT) AS mileage, inventory.lot, inventory.balance, inventory.retail, inventory.certified as certified_inv, 
inventory.stocktype , inventory.wholesale , inventory.status as invStatus, IF((used_cars.date_sold != ''), STR_TO_DATE(used_cars.date_sold, '%m-%d-%Y') , 'zzz') as date_sold_modified , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.location = '$location'";

// echo $sqlQuery . '<br />';

$table = <<<EOT
(
    {$sqlQuery} {$searchQuery}
) as temp
EOT;

// echo $table;


$primaryKey = 'invId';

$columns = array(
    array('db' => 'invId', 'dt' => 0, 'formatter' => function ($d, $row) {
        return $d;
        // $id = $row['invId'];
    }),
    array('db' => 'cdkAge',  'dt' => 1, 'formatter' => function ($d, $row) {
        $cdkAge = 0;
        // if ($row['date_in'] != '' && !is_null($row['date_in'])) {
        if ($d != 'zzz' && !is_null($d)) {
            // date_default_timezone_set('Asia/Karachi');
            $date = strtotime(date('Y-m-d'));
            $date_in = reformatDate($row['date_in']);
            // $date_in = date('Y-m-d', strtotime('-0 day', strtotime($date_in)));
            $date_in = date('Y-m-d',  strtotime($date_in));
            $date_in = strtotime($date_in);
            $cdkAge = ceil(abs($date_in - $date) / 86400);
        }
        return (int)$cdkAge;
    }),
    array('db' => 'stockDetails',   'dt' => 2, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'date_in',   'dt' => 3, 'formatter' => function ($d, $row) {
        global $onlineManagerID;
        $id = $row['invId'];
        $date_in = $d;
        if ($_SESSION['userRole'] == $onlineManagerID || hasAccess("usedCars", "Edit") === 'false') {
            $date_in = $row['date_in'];
        } else if ($_SESSION['userRole'] != $onlineManagerID && hasAccess("usedCars", "Edit") !== 'false') {
            $date_in = '
                <div class="show d-flex" >
                    <input type="text" class="form-control" name="date_in_table" value="' . $row['date_in'] . '" data-attribute="date_in" data-id="' . $id . '" autocomplete="off"  />
                </div>';
        }
        return $date_in;
    }),
    array('db' => 'key',   'dt' => 4, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'year',   'dt' => 5, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'make',   'dt' => 6, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'model',   'dt' => 7, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'color',   'dt' => 8, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'mileage',   'dt' => 9, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'lot',   'dt' => 10, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'title',   'dt' => 11, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'title_priority',   'dt' => 12, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'customer',   'dt' => 13, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'sales_consultant',   'dt' => 14, 'formatter' => function ($d, $row) {
        global $connect;
        if (isset($d)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$d'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $d = $row1 ? $row1['username'] : "";
        } else {
            $d = "";
        }
        return $d;
    }),
    array('db' => 'title_notes',   'dt' => 15, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'balance',   'dt' => 16, 'formatter' => function ($d, $row) {
        // return $d != "" ? asDollars($d) : "";
        return $d;
    }),
    array('db' => 'retail',   'dt' => 17, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'certified_inv',   'dt' => 18, 'formatter' => function ($d, $row) {
        return ($d == 'on') ? "Yes" : "No";
    }),
    array('db' => 'stocktype',   'dt' => 19, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'wholesale',   'dt' => 20, 'formatter' => function ($d, $row) {
        return ($d == 'on') ? "Yes" : "No";
    }),
    array('db' => 'date_sent',   'dt' => 21, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'date_sold_modified',   'dt' => 22, 'formatter' => function ($d, $row) {
        if ($d != 'zzz' && !is_null($d)) {
            return $d;
        } else {
            return "";
        }
    }),
    array('db' => 'retail_status',   'dt' => 23, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'date_in',   'dt' => 24, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'wholesale_notes',   'dt' => 25, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'sold_price',   'dt' => 26, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'profit',   'dt' => 27, 'formatter' => function ($d, $row) {
        if ($d != '') {
            $profit = round($d, 2);
            $profit = asDollars($d);
            return $profit;
        } else {
            $balance = (isset($row['balance']) && $row['balance'] != '' && $row['invStatus'] == 1) ? (float)str_replace(array(',', '$'), '', $row['balance']) : 0;
            $sold_price = (isset($row['sold_price']) && $row['sold_price'] != '')  ? $row['sold_price'] : 0;
            $profit = (int)$sold_price - (int)$balance;
            $profit = round($profit, 2);
            $profit = asDollars($profit);
            return $profit;
        }
    }),
    array('db' => 'uci',   'dt' => 28, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'purchase_from', 'dt' => 29, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'arr',   'dt' => 30, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'button', 'dt' => 31, 'formatter' => function ($d, $row) {
        $id = $row['invId'];
        $button = '
        <div class="show d-flex" >
        ' .
            (hasAccess("usedCars", "Edit") !== 'false' ? ' <button class="btn btn-label-primary btn-icon mr-1" onclick="removeUsedCar(' . $id . ')" >
                <i class="fa fa-trash"></i>
            </button>' : '') . '
        </div>
        ';
        return $button;
    }),
    array('db' => 'id', 'dt' => 32, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'uci_ro', 'dt' => 33, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'invStatus', 'dt' => 34, 'formatter' => function ($d, $row) {
        return $d;
    }),
    array('db' => 'submitted_by', 'dt' => 35, 'formatter' => function ($d, $row) {
        global $connect;
        if (isset($d)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$d'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $d = $row1 ? $row1['username'] : "";
        } else {
            $d = "";
        }
        return $d;
    })
);


$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $localhost
);

require('ssp.class.php');


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


// echo $sqlQuery;

$searhStatusArray = array();
$result = $connect->query($sqlQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {

        $_addToSheet = false;
        $_missingDate = false;
        $_titleIssue = false;
        $_readyToship = false;
        $_keyPulled = false;
        $_atAuction = false;
        $_soldAtAuction = false;
        $_retail = false;
        $_sold = false;


        $id = $row['invId'];
        $carshopId = $row['id'];
        $balance = $row['balance'];
        $date_in =  $row['date_in'];
        $title = $row['title'];
        $retail_status = $row['retail_status'];
        $key = $row['key'];
        $date_sent = $row['date_sent'];
        $date_sold = $row['date_sold'];
        $invStatus = $row['invStatus'];


        if ($date_in !== '' && $date_in === null && $invStatus != 2) {
            $addToSheet += 1;
            $_addToSheet = "Add To Sheet";
        }

        if (($date_in === '' || $date_in === 'undefined') && $date_in !== null && $invStatus != 2) {
            $missingDate += 1;
            $_missingDate = "Missing Date";
        }


        if (($title == 'false' || $title == null) && ($date_in !== null) && $invStatus != 2) {
            $titleIssue += 1;
            $_titleIssue = "Title Issue";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'false' && $invStatus != 2) {
            $readyToShip += 1;
            $_readyToship = "Ready To Ship";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'true' && !$date_sent && !$date_sold && $invStatus != 2) {
            $keysPulled += 1;
            $_keyPulled = "Keys Pulled";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'true' && $date_sent && !$date_sold && $invStatus != 2) {
            $atAuction += 1;
            $_atAuction = "At Auction";
        }

        if ($title == 'true' && $retail_status == 'wholesale' && $key == 'true' && $date_sent && $date_sold) {
            $soldAtAuction += 1;
            $_soldAtAuction = "Sold At Auction";
        }

        if ($retail_status != 'wholesale' && $balance !== '' && ($carshopId !== '' && $carshopId !== null) && $invStatus != 2) {
            $retail += 1;
            $_retail = "Retail";
        }

        if (($balance === "" || $balance === null) && $date_in !== null) {
            $sold += 1;
            $_sold = "Sold";
        }


        $age = $row['age']; // age
        $cdkAge = "";

        if ($row['date_in'] != '' && !is_null($row['date_in'])) {
            $date = strtotime(date('Y-m-d'));
            $date_in = reformatDate($row['date_in']);
            $date_in = date('Y-m-d', strtotime('-0 day', strtotime($date_in)));
            $date_in = strtotime($date_in);
            $cdkAge = ceil(abs($date_in - $date) / 86400);
        }


        $age = (int)$age;
        $cdkAge = (int)$cdkAge;

        $fixed_status = $row['fixed_status']; // fixed_status

        if ($id != null && $carshopId != null && $row['date_in'] != '' && !is_null($row['date_in']) && $age != $cdkAge && $fixed_status != "true" && $invStatus == 1) {
            $fixAge += 1;
        }


        $searhStatusArray[]  = array(
            'stockDetails' => $row['stockDetails'],
            'stockAvailibility' => array($_addToSheet, $_missingDate, $_titleIssue, $_readyToship, $_keyPulled, $_atAuction, $_soldAtAuction, $_retail, $_sold),
        );
    }
}



$dataObj = SSP::complex($_POST, $sql_details, $table, $primaryKey, $columns);



$dataObj['searhStatusArray'] = $searhStatusArray;



$dataObj['totalNumber'] = array(
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



echo json_encode($dataObj);

// echo json_encode(
//     SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns)
// );
// echo json_encode(
//     SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
// );
