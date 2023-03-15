<?php

require_once 'db/core.php';

date_default_timezone_set("America/New_York");

$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sqlQuery = '';


## Custom Field value
$searchByDatePeriod = $_POST['searchByDatePeriod'];
$customStart = $_POST['customStart'];
$customEnd = $_POST['customEnd'];


// ## Search 
$filterQuery = " ";
$searchQuery = " ";

if ($searchByDatePeriod != '') {
    if ($searchByDatePeriod == "thisMonth") {

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $searchQuery .= " and ( STR_TO_DATE(a.date, '%m-%d-%Y') BETWEEN '" . $startDate . "' AND '" . $endDate . "' ) ";
    } else if ($searchByDatePeriod == "lastMonth") {

        $first_day_last_month = strtotime('first day of last month');
        $last_day_last_month = strtotime('last day of last month');
        $startDate = date('Y-m-d', $first_day_last_month);
        $endDate = date('Y-m-d', $last_day_last_month);
        $searchQuery .= " and ( STR_TO_DATE(a.date, '%m-%d-%Y') BETWEEN '" . $startDate . "' AND '" . $endDate . "' ) ";
    } else if ($searchByDatePeriod == "all") {
        $searchQuery = " ";
    }
}

if ($customStart != '' && $customEnd != '') {

    $searchQuery .= " and ( STR_TO_DATE(a.date, '%m-%d-%Y') BETWEEN '" . $customStart . "' AND '" . $customEnd . "' ) ";
} else {
    $searchQuery .= " and (STR_TO_DATE(a.date, '%m-%d-%Y') = STR_TO_DATE(a.date, '%m-%d-%Y'))";
}




// sale_consultant
if (isset($_POST['ccsF']) && count($_POST['ccsF']) != 0) {
    $array = $_POST['ccsF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = strtolower($value);
            $filterQuery .= " u2.username LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}
if (isset($_POST['consultantF']) && count($_POST['consultantF']) != 0) {
    $array = $_POST['consultantF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = strtolower($value);
            $filterQuery .= " u1.username LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}

if (isset($_POST['soldF']) && count($_POST['soldF']) != 0) {
    $array = $_POST['soldF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " a.lead_status LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}

if (isset($_POST['newF']) && count($_POST['newF']) != 0) {
    $array = $_POST['newF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " a.lead_type LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}

// // customerF
if (isset($_POST['sourceF']) && count($_POST['sourceF']) != 0) {
    $array = $_POST['sourceF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = lcfirst(str_replace(' ', '', ucwords($value)));
            $filterQuery .= " a.source LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}


if ($_SESSION['userRole'] != $ccsID) {

    $sqlQuery = "SELECT a.id, STR_TO_DATE(a.date, '%m-%d-%Y') as date , u2.username as ccs, a.lname, a.fname, a.entity, a.vehicle, u1.username  as sales_consultant, 
    a.lead_status, a.lead_type, a.source, a.notes, a.verified, '' as button , u2.color as colorCode , a.verified_by 
    FROM bdc_lead as a LEFT JOIN users as u1 ON u1.id = a.sales_consultant LEFT JOIN users as u2 ON u2.id = a.ccs 
    WHERE a.status = 1 AND a.location = '$location' " . $searchQuery . " " . $filterQuery;
} else {
    $uid = $_SESSION['userId'];

    $sqlQuery = "SELECT a.id, STR_TO_DATE(a.date, '%m-%d-%Y') as date , u2.username as ccs, a.lname, a.fname, a.entity, a.vehicle, u1.username as sales_consultant, 
    a.lead_status, a.lead_type, a.source, a.notes, a.verified, '' as button , u2.color as colorCode , a.verified_by 
    FROM bdc_lead as a LEFT JOIN users as u1 ON u1.id = a.sales_consultant LEFT JOIN users as u2 ON u2.id = a.ccs 
    WHERE a.status = 1 AND a.ccs = '$uid' AND a.location = '$location' " . $searchQuery  . " " . $filterQuery;
}


$table = <<<EOT
(
    {$sqlQuery}
) temp
EOT;

// echo $table;
// echo "<hr />";


$primaryKey = 'id';

$columns = array(

    array('db' => 'id',  'dt' => 0),
    array(
        'db' => 'date',  'dt' => 1,
        'formatter' => function ($d, $row) {
            $date =  ($d != '') ? date("M-d-Y", strtotime($d)) : '';
            return $date;
        }
    ),

    array('db' => 'ccs',   'dt' => 2),
    array('db' => 'lname',   'dt' => 3),
    array('db' => 'fname',   'dt' => 4),
    array('db' => 'entity', 'dt' => 5),
    array('db' => 'vehicle',   'dt' => 6),
    array('db' => 'sales_consultant', 'dt' => 7),
    array(
        'db' => 'lead_status', 'dt' => 8,
        'formatter' => function ($d, $row) {
            return ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $d));
        }
    ),
    array(
        'db' => 'lead_type',   'dt' => 9,
        'formatter' => function ($d, $row) {
            return ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $d));
        }
    ),
    array(
        'db' => 'source',   'dt' => 10,
        'formatter' => function ($d, $row) {
            return ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $d));
        }
    ),
    array('db' => 'notes',   'dt' => 11),
    array(
        'db' => 'verified',   'dt' => 12,
        'formatter' => function ($d, $row) {
            return ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $d));
        }
    ),
    array(
        'db' => 'button',   'dt' => 13,
        'formatter' => function ($d, $row) {
            $id = $row[0];
            $button = '
            <div class="show d-flex" >' .
                (hasAccess("bdc", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeLead(' . $id . ')" >
                <i class="fa fa-trash"></i>
                    </button>' : "") .
                '</div>';

            return $button;
        }
    ),
    array('db' => 'colorCode',   'dt' => 14),
);


$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $localhost
);

require('ssp.class.php');

$counterObj = array();
$vehicleArray = array();
function assignKey(&$obj, $key)
{
    if (!isset($obj[$key])) {
        $obj[$key] = 1;
    } else {
        $obj[$key]++;
    }
}

$result = $connect->query($sqlQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {

        // $myArray = array('key1' => 0, 'key2' => 0);
        // assignKey($array, 'key1');

        $Status = $row[8];
        $Type = $row[9];
        $Source = $row[10];
        $Verified = $row[12];
        $vehicle = $row[6];

        $vehicleArray[] = $vehicle;



        if ($Status == 'sold') {
            assignKey($counterObj, 'totalSold');
            if ($Type == 'new') {
                assignKey($counterObj, 'newSold');
                if ($Verified == 'ok') {
                    assignKey($counterObj, 'newSoldv');
                    assignKey($counterObj, 'totalSoldv');
                }
            } else if ($Type == 'used') {
                assignKey($counterObj, 'usedSold');
                if ($Verified == 'ok') {
                    assignKey($counterObj, 'usedSoldv');
                    assignKey($counterObj, 'totalSoldv');
                }
            }
        }
        if ($Source == 'internet') {
            assignKey($counterObj, 'totalInt');
            if ($Type == 'new') {
                assignKey($counterObj, 'newInt');
                if ($Verified == 'ok') {
                    assignKey($counterObj, 'newIntv');
                    assignKey($counterObj, 'totalIntv');
                }
            } else if ($Type == 'used') {
                assignKey($counterObj, 'usedInt');
                if ($Verified == 'ok') {
                    assignKey($counterObj, 'usedIntv');
                    assignKey($counterObj, 'totalIntv');
                }
            }
        }
        if ($Source == 'autoAlert') {
            assignKey($counterObj, 'totalAa');
            if ($Type == 'new') {
                assignKey($counterObj, 'newAa');
                if ($Verified == 'ok') {
                    assignKey($counterObj, 'newAav');
                    assignKey($counterObj, 'totalAav');
                }
            } else if ($Type == 'used') {
                assignKey($counterObj, 'usedAa');
                if ($Verified == 'ok') {
                    assignKey($counterObj, 'usedAav');
                    assignKey($counterObj, 'totalAav');
                }
            }
        }


        if ($Status == 'show' || $Status == '') {
            assignKey($counterObj, 'unSoldLead');
            assignKey($counterObj, 'totalLead');
            if ($Verified == 'showVerified') {
                assignKey($counterObj, 'unSoldLeadv');
                assignKey($counterObj, 'totalLeadv');
            }
        } else if ($Status == 'sold') {
            assignKey($counterObj, 'soldLead');
            assignKey($counterObj, 'totalLead');
            if ($Verified == 'ok') {
                assignKey($counterObj, 'SoldLeadv');
                assignKey($counterObj, 'totalLeadv');
            }
        }
    }
}

$dataObj = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);

$dataObj['counterObj'] = $counterObj;
$dataObj['vehicleArray'] = $vehicleArray;

echo json_encode($dataObj);
