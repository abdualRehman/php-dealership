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
$filterById = $_POST['filterById'];


// ## Search 
$filterQuery = " ";
$searchQuery = " ";

if ($searchByDatePeriod != '' && (!isset($filterById) || $filterById == null)) {
    if ($searchByDatePeriod == "thisMonth") {

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        // working
        $searchQuery .= " and ( CAST( a.appointment_date as date) BETWEEN '" . $startDate . "' AND '" . $endDate . "' ) ";
    } else if ($searchByDatePeriod == "lastMonth") {

        $first_day_last_month = strtotime('first day of last month');
        $last_day_last_month = strtotime('last day of last month');
        $startDate = date('Y-m-d', $first_day_last_month);
        $endDate = date('Y-m-d', $last_day_last_month);

        $searchQuery .= " and ( CAST( a.appointment_date as date) BETWEEN '" . $startDate . "' AND '" . $endDate . "' ) ";
    } else if ($searchByDatePeriod == 'approvals') {

        $searchQuery .= " and (a.already_have = 'true' AND a.manager_override = '' AND a.delivery != '' )";
    } else if ($searchByDatePeriod == 'all') {
        $searchQuery .= " ";
    }
} else {

    // filterById
    $searchQuery .= " and a.id = '$filterById' ";
}

// consultantF
if (isset($_POST['coordinatorF']) && count($_POST['coordinatorF']) != 0) {
    $array = $_POST['coordinatorF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = strtolower($value);
            $filterQuery .= " LOWER(u3.username)  LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}
// consultantF
if (isset($_POST['consultantF']) && count($_POST['consultantF']) != 0) {
    $array = $_POST['consultantF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = strtolower($value);
            $filterQuery .= " LOWER(u2.username)  LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}
if (isset($_POST['stockF']) && count($_POST['stockF']) != 0) {
    $array = $_POST['stockF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " c.stockno LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}
if (isset($_POST['additionalServiceF']) && count($_POST['additionalServiceF']) != 0) {
    $array = $_POST['additionalServiceF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " a.additional_services = '$value' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}



if ($_SESSION['userRole'] != $deliveryCoordinatorID) {

    $sqlQuery = "SELECT a.id , b.sale_id , a.calender_id, 
        (SELECT username FROM users WHERE id = IF((a.manager_override = ''), a.submitted_by , a.manager_override )) AS submitted_by, 
        a.schedule_start, a.schedule_end, a.confirmed, a.complete, 
        CONCAT( b.fname ,' ', b.lname ) as customerName, a.appointment_date, a.appointment_time, 
        u3.username as coordinator, c.stockno, CONCAT( c.stocktype , ' ' , c.year , ' ' , c.make , ' ' , c.model) as vehicle,
        u2.username as sales_consultant, a.notes, '' as button, '' as allowEdit ,  a.additional_services,  a.already_have,
        a.manager_override, '' as editManagerApproval, c.vin, a.delivery, u3.color as coordinator_color, a.stock_id, a.status , b.sale_status , u2.id as sales_consultant_ID
        FROM `appointments` as a LEFT JOIN sales as b ON a.sale_id = b.sale_id LEFT JOIN inventory as c ON a.stock_id = c.id 
        LEFT JOIN users u2 ON b.sales_consultant = u2.id LEFT JOIN users u3 ON a.coordinator = u3.id
        WHERE a.status = 1 AND a.location = '$location' AND b.status = 1 ";
} else {
    $uid = $_SESSION['userId'];

    $sqlQuery = "SELECT a.id , b.sale_id , a.calender_id, 
        (SELECT username FROM users WHERE id = IF((a.manager_override = ''), a.submitted_by , a.manager_override )) AS submitted_by, 
        a.schedule_start, a.schedule_end, a.confirmed, a.complete, 
        CONCAT( b.fname ,' ', b.lname ) as customerName, a.appointment_date, a.appointment_time, 
        u3.username as coordinator, c.stockno, CONCAT( c.stocktype , ' ' , c.year , ' ' , c.make , ' ' , c.model) as vehicle,
        u2.username as sales_consultant, a.notes, '' as button, '' as allowEdit ,  a.additional_services,  a.already_have,
        a.manager_override, '' as editManagerApproval, c.vin, a.delivery, u3.color as coordinator_color, a.stock_id, a.status , b.sale_status , u2.id as sales_consultant_ID
        FROM `appointments` as a LEFT JOIN sales as b ON a.sale_id = b.sale_id LEFT JOIN inventory as c ON a.stock_id = c.id 
        LEFT JOIN users u2 ON b.sales_consultant = u2.id LEFT JOIN users u3 ON a.coordinator = u3.id
        WHERE a.status = 1 AND a.location = '$location' AND a.coordinator = '$uid' AND b.status = 1 ";
}

// echo $sqlQuery . '<br />';



$table = <<<EOT
(
    {$sqlQuery} {$filterQuery} {$searchQuery}
) temp
EOT;

// echo $table;
// echo "<hr />";


$primaryKey = 'id';

$columns = array(

    array('db' => 'id',  'dt' => 0),
    array('db' => 'sale_id',  'dt' => 1),
    array('db' => 'calender_id',   'dt' => 2),
    array('db' => 'submitted_by',   'dt' => 3),
    array('db' => 'schedule_start', 'dt' => 4),
    array('db' => 'schedule_end',   'dt' => 5),
    array('db' => 'confirmed', 'dt' => 6),
    array('db' => 'complete', 'dt' => 7),
    array('db' => 'customerName',   'dt' => 8),
    array('db' => 'appointment_date',   'dt' => 9),
    array('db' => 'appointment_time',   'dt' => 10),
    array('db' => 'coordinator',   'dt' => 11),
    array('db' => 'stockno',   'dt' => 12),
    array('db' => 'vehicle',   'dt' => 13),
    array('db' => 'sales_consultant',   'dt' => 14),
    array('db' => 'notes',   'dt' => 15),
    array(
        'db' => 'button',   'dt' => 16,
        'formatter' => function ($d, $row) {
            global $deliveryCoordinatorID, $salesConsultantID, $branchAdmin, $salesManagerID, $generalManagerID;
            $sales_consultant_id = $row['sales_consultant_ID'];
            $confirmed = $row['confirmed'];
            $button = '';
            $id = $row['id'];
            if (
                ($_SESSION['userRole'] == $salesConsultantID && $_SESSION['userId'] == $sales_consultant_id && $confirmed != 'ok') || $_SESSION['userRole'] == 'Admin' ||
                $_SESSION['userRole'] == $branchAdmin || $_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == $generalManagerID ||
                $_SESSION['userRole'] == $deliveryCoordinatorID
            ) {
                $button .= '
                        <div class="show d-flex" >' .
                    ((hasAccess("appointment", "Remove") !== 'false') ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeSchedule(' . $id . ')" >
                            <i class="fa fa-trash"></i>
                        </button>'  : '') .
                    '</div>';
            }

            return $button;
        }
    ),
    array(
        'db' => 'allowEdit',   'dt' => 17,
        'formatter' => function ($d, $row) {
            global $deliveryCoordinatorID, $salesConsultantID, $branchAdmin, $salesManagerID, $generalManagerID;

            $sales_consultant_id = $row['sales_consultant_ID'];
            $confirmed = $row['confirmed'];

            $allowEdit = false;
            if (
                ($_SESSION['userRole'] == $salesConsultantID && $_SESSION['userId'] == $sales_consultant_id && $confirmed != 'ok') || $_SESSION['userRole'] == 'Admin' ||
                $_SESSION['userRole'] == $branchAdmin || $_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == $generalManagerID ||
                $_SESSION['userRole'] == $deliveryCoordinatorID
            ) {
                $allowEdit = true;
            }

            return $allowEdit;
        }
    ),
    array('db' => 'additional_services',   'dt' => 18),
    array('db' => 'already_have',   'dt' => 19),
    array('db' => 'manager_override',   'dt' => 20),
    array(
        'db' => 'editManagerApproval',   'dt' => 21,
        'formatter' => function ($d, $row) {
            global $branchAdmin, $salesManagerID, $generalManagerID;

            $editManagerApproval = false;
            if ($_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == $generalManagerID || $_SESSION['userRole'] == $branchAdmin) {
                $editManagerApproval = true;
            }
            return $editManagerApproval;
        }
    ),
    array('db' => 'vin',   'dt' => 22),
    array(
        'db' => 'delivery',   'dt' => 23,
        'formatter' => function ($d, $row) {
            $delivery = preg_replace('/(?<=\\w)(?=[A-Z])/', " ", $d);
            $delivery = ucfirst($delivery);
            return $delivery;
        }
    ),
    array('db' => 'coordinator_color',   'dt' => 24),
    array('db' => 'stock_id',   'dt' => 25),
    array('db' => 'status',   'dt' => 26),
    array('db' => 'sale_status',   'dt' => 27),
    array('db' => 'sales_consultant_ID',   'dt' => 28),
);


$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $localhost
);

require('ssp.class.php');



$lmconfirmed = 0;
$tmconfirmed = 0;


$startDate_cm = date('Y-m-01');
$endDate_cm = date('Y-m-t');


$first_day_last_month = strtotime('first day of last month');
$last_day_last_month = strtotime('last day of last month');
$startDate_pm = date('Y-m-d', $first_day_last_month);
$endDate_pm = date('Y-m-d', $last_day_last_month);

$result = $connect->query($sqlQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        // $date = $row[4];  // schedule state   // previous coding
        $appointment_date = $row['appointment_date'];
        $confirm = $row['confirmed'];
        $complete = $row['complete'];
        $additional_services = $row['additional_services'];

        $date = DateTime::createFromFormat('Y-m-d', $appointment_date);

        if ($date >= new DateTime($startDate_pm) && $date <= new DateTime($endDate_pm)) {
            if ($confirm == 'ok' && $complete == 'ok' && $additional_services != '') {
                $lmconfirmed += 1;
            }
        }

        if ($date >= new DateTime($startDate_cm) && $date <= new DateTime($endDate_cm)) {
            if ($confirm == 'ok' && $complete == 'ok') {
                $tmconfirmed += 1;
            }
        }
    }
}


$dataObj = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);
$dataObj['totalCount']['lmconfirmed'] = $lmconfirmed;
$dataObj['totalCount']['tmconfirmed'] = $tmconfirmed;
echo json_encode($dataObj);
