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
$houseDealFilter =  ($_POST['houseDealFilter'] == 'on') ? "true" : "false";



// ## Search 
$filterQuery = " ";
$searchQuery = " ";


if ($searchByDatePeriod != '') {
    if ($searchByDatePeriod == "opened") {

        $searchQuery .= " AND ( a.salesperson_status != 'cancelled' AND NOT (";
        $searchQuery .= " (a.vin_check != 'checkTitle' AND a.vin_check != 'need') AND a.insurance != 'need' AND a.trade_title != 'need' AND (a.registration != 'pending' AND a.registration != 'done') AND a.inspection != 'need' AND (a.salesperson_status = 'cancelled' OR a.salesperson_status = 'delivered') ";
        $searchQuery .= " ) ) ";

    } else if ($searchByDatePeriod == "completed") {

        $searchQuery .= " AND ( salesperson_status = 'cancelled' OR ";
        $searchQuery .= " (salesperson_status = 'delivered' AND ( (vin_check != 'checkTitle' AND vin_check != 'need') AND insurance != 'need' AND trade_title != 'need' AND registration NOT IN ('pending', 'done') AND inspection != 'need' )) ";
        $searchQuery .= " ) ";
    } else {
        // $searchQuery .= " ";
    }
}

if ($houseDealFilter == "false") {
    $searchQuery .= " and c.username != 'House Deal' ";
}


// sale_consultant
if (isset($_POST['consultantF']) && count($_POST['consultantF']) != 0) {
    $array = $_POST['consultantF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = strtolower($value);
            $filterQuery .= " c.username  LIKE '%$value%' ";
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
            $filterQuery .= " d.stockno LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}

if (isset($_POST['vehicleF']) && count($_POST['vehicleF']) != 0) {
    $array = $_POST['vehicleF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " CONCAT( d.stocktype ,' ', d.year ,' ', d.make ,' ', d.model ) LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}

// // customerF
if (isset($_POST['stateF']) && count($_POST['stateF']) != 0) {
    $array = $_POST['stateF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = strtolower($value);
            $filterQuery .= " b.state  LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}




if ($_SESSION['userRole'] != $salesConsultantID) {

    // $sqlQuery = "SELECT STR_TO_DATE( DATE(b.date) ,'%Y-%m-%d') as date , CONCAT( b.fname ,' ', b.lname ) as customerName , 
    // d.stockno , CONCAT( d.stocktype , ' ' , d.year , ' ' , d.make , ' ' , d.model) as vehicle,
    // b.state, a.vin_check , a.insurance , a.trade_title , a.registration , a.inspection , a.salesperson_status , a.paid ,
    // a.sale_todo_id , c.username as sale_consultant 
    // FROM `sale_todo` as a INNER JOIN sales as b ON a.sale_id = b.sale_id INNER JOIN users as c ON b.sales_consultant = c.id INNER JOIN inventory as d ON b.stock_id = d.id WHERE b.status = 1 AND b.sale_status = 'delivered' AND a.status = 1 AND b.location = '$location' AND b.sale_status != 'cancelled' "  . $searchQuery . " " . $filterQuery . "  ORDER BY b.sales_consultant ASC";

    $sqlQuery = "SELECT CAST( IF((b.reconcileDate != ''), b.reconcileDate , b.date ) as date ) as date , CONCAT( b.fname ,' ', b.lname ) as customerName , 
    d.stockno , CONCAT( d.stocktype , ' ' , d.year , ' ' , d.make , ' ' , d.model) as vehicle,
    b.state, a.vin_check , a.insurance , a.trade_title , a.registration , a.inspection , a.salesperson_status , a.paid ,
    a.sale_todo_id , c.username as sale_consultant 
    FROM `sale_todo` as a INNER JOIN sales as b ON a.sale_id = b.sale_id INNER JOIN users as c ON b.sales_consultant = c.id INNER JOIN inventory as d ON b.stock_id = d.id WHERE b.status = 1 AND b.sale_status = 'delivered' AND a.status = 1 AND b.location = '$location' AND b.sale_status != 'cancelled' "  . $searchQuery . " " . $filterQuery . "  ORDER BY b.sales_consultant ASC";
} else {
    $uid = $_SESSION['userId'];

    // $sqlQuery = "SELECT STR_TO_DATE( DATE(b.date) ,'%Y-%m-%d') as date , CONCAT( b.fname ,' ', b.lname ) as customerName , 
    // d.stockno , CONCAT( d.stocktype , ' ' , d.year , ' ' , d.make , ' ' , d.model) as vehicle,
    // b.state, a.vin_check , a.insurance , a.trade_title , a.registration , a.inspection , a.salesperson_status , a.paid ,
    // a.sale_todo_id , c.username as sale_consultant
    // FROM `sale_todo` as a INNER JOIN sales as b ON a.sale_id = b.sale_id INNER JOIN users as c ON b.sales_consultant = c.id INNER JOIN inventory as d ON b.stock_id = d.id WHERE b.status = 1 AND b.sale_status = 'delivered' AND a.status = 1 AND b.sales_consultant = '$uid' AND b.location = '$location'  AND b.sale_status != 'cancelled' "  . $searchQuery . " " . $filterQuery . "  ORDER BY b.sales_consultant ASC";

    $sqlQuery = "SELECT CAST( IF((b.reconcileDate != ''), b.reconcileDate , b.date ) as date ) as date , CONCAT( b.fname ,' ', b.lname ) as customerName , 
    d.stockno , CONCAT( d.stocktype , ' ' , d.year , ' ' , d.make , ' ' , d.model) as vehicle,
    b.state, a.vin_check , a.insurance , a.trade_title , a.registration , a.inspection , a.salesperson_status , a.paid ,
    a.sale_todo_id , c.username as sale_consultant
    FROM `sale_todo` as a 
    INNER JOIN sales as b ON a.sale_id = b.sale_id 
    INNER JOIN users as c ON b.sales_consultant = c.id 
    INNER JOIN inventory as d ON b.stock_id = d.id 
    WHERE b.status = 1 
    AND (b.sale_status = 'delivered' OR b.sale_status = 'pending' ) 
    AND a.status = 1 
    AND b.sales_consultant = '$uid'  
    AND b.location = '$location' 
    AND b.sale_status != 'cancelled' 
    "  . $searchQuery . " " . $filterQuery . "  ORDER BY b.sales_consultant ASC";
}


$table = <<<EOT
(
    {$sqlQuery}
) temp
EOT;

// echo $table;
// echo "<hr />";


$primaryKey = 'sale_todo_id';

$columns = array(

    array(
        'db' => 'date',  'dt' => 0,
        'formatter' => function ($d, $row) {
            $date =  ($d != '') ? date("M-d-Y", strtotime($d)) : '';
            return $date;
        }
    ),
    array('db' => 'customerName',  'dt' => 1),
    array('db' => 'stockno',   'dt' => 2),
    array('db' => 'vehicle',   'dt' => 3),
    array('db' => 'state', 'dt' => 4),
    array('db' => 'vin_check',   'dt' => 5),
    array('db' => 'insurance', 'dt' => 6),
    array('db' => 'trade_title', 'dt' => 7),
    array('db' => 'registration',   'dt' => 8),
    array('db' => 'inspection',   'dt' => 9),
    array('db' => 'salesperson_status',   'dt' => 10),
    array('db' => 'paid',   'dt' => 11),
    array('db' => 'sale_todo_id',   'dt' => 12),
    array('db' => 'sale_consultant',   'dt' => 13),


);


$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $localhost
);

require('ssp.class.php');

$array = array();

$result = $connect->query($sqlQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $key = $row['sale_consultant'];
        if (array_key_exists($key, $array)) {
            $array[$key] = $array[$key] + 1;
        } else {
            $array[$key] = 1;
        }
    }
}

$dataObj = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);

$dataObj['totalCount']['sc'] = $array;

echo json_encode($dataObj);
