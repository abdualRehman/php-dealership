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


// ## Search 
$filterQuery = " ";
$searchQuery = " ";

if ($searchByDatePeriod != '') {
    if ($searchByDatePeriod == "pending") {

        $searchQuery .= " and (( a.college != 'No' OR a.military != 'No' OR a.loyalty != 'No' OR a.conquest != 'No' OR a.lease_loyalty != 'No' OR a.misc1 != 'No' OR a.misc2 != 'No' ) AND ( ";
        $searchQuery .= " (a.college = 'Yes' OR (a.college REGEXP '^[0-9]+$' AND a.college_date = ''))  OR";
        $searchQuery .= " (a.military = 'Yes' OR (a.military REGEXP '^[0-9]+$' AND a.military_date = ''))  OR";
        $searchQuery .= " (a.loyalty = 'Yes' OR (a.loyalty REGEXP '^[0-9]+$' AND a.loyalty_date = ''))  OR";
        $searchQuery .= " (a.conquest = 'Yes' OR (a.conquest REGEXP '^[0-9]+$' AND a.conquest_date = ''))  OR";
        $searchQuery .= " (a.lease_loyalty = 'Yes' OR (a.lease_loyalty REGEXP '^[0-9]+$' AND a.lease_loyalty_date = ''))  OR";
        $searchQuery .= " (a.misc1 = 'Yes' OR (a.misc1 REGEXP '^[0-9]+$' AND a.misc1_date = ''))  OR";
        $searchQuery .= " (a.misc2 = 'Yes' OR (a.misc2 REGEXP '^[0-9]+$' AND a.misc2_date = ''))";
        $searchQuery .= " ))";
    } else if ($searchByDatePeriod == "submitted") {

        $searchQuery .= " and (( a.college != 'No' OR a.military != 'No' OR a.loyalty != 'No' OR a.conquest != 'No' OR a.lease_loyalty != 'No' OR a.misc1 != 'No' OR a.misc2 != 'No' ) AND ( ";
        $searchQuery .= " (a.college = 'No' OR (a.college REGEXP '^[0-9]+$' AND a.college_date != ''))  AND";
        $searchQuery .= " (a.military = 'No' OR (a.military REGEXP '^[0-9]+$' AND a.military_date != ''))  AND";
        $searchQuery .= " (a.loyalty = 'No' OR (a.loyalty REGEXP '^[0-9]+$' AND a.loyalty_date != ''))  AND";
        $searchQuery .= " (a.conquest = 'No' OR (a.conquest REGEXP '^[0-9]+$' AND a.conquest_date != ''))  AND";
        $searchQuery .= " (a.lease_loyalty = 'No' OR (a.lease_loyalty REGEXP '^[0-9]+$' AND a.lease_loyalty_date != ''))  AND";
        $searchQuery .= " (a.misc1 = 'No' OR (a.misc1 REGEXP '^[0-9]+$' AND a.misc1_date != ''))  AND";
        $searchQuery .= " (a.misc2 = 'No' OR (a.misc2 REGEXP '^[0-9]+$' AND a.misc2_date != ''))";
        $searchQuery .= " ))";
    } else {
        // $searchQuery .= " ";
    }
}



// // soldF  YYYY-MM-DD
if (isset($_POST['soldF']) && count($_POST['soldF']) != 0) {
    $array = $_POST['soldF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " CAST( b.date AS date ) = '$value' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}


// customerF
if (isset($_POST['customerF']) && count($_POST['customerF']) != 0) {
    $array = $_POST['customerF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $value = strtolower($value);
            $filterQuery .= " CONCAT( b.fname ,' ', b.lname )  LIKE '%$value%' ";
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

if (isset($_POST['vehicleF']) && count($_POST['vehicleF']) != 0) {
    $array = $_POST['vehicleF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " CONCAT( c.stocktype ,' ', c.year ,' ', c.make ,' ', c.model ) LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}



if ($_SESSION['userRole'] != $salesConsultantID) {

    $sqlQuery = "SELECT CAST( b.date AS date ) as date ,
    CONCAT( b.fname ,' ', b.lname ) as customerName,
    c.stockno , CONCAT( c.stocktype , ' ' , c.year , ' ' , c.make , ' ' , c.model) as vehicle,
    IF(a.college REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.college) , a.college ) as college,
    IF(a.military REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.military) , a.military ) as military,
    IF(a.loyalty REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.loyalty) , a.loyalty ) as loyalty,
    IF(a.conquest REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.conquest) , a.conquest ) as conquest,
    IF(a.lease_loyalty REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.lease_loyalty) , a.lease_loyalty ) as lease_loyalty,
    IF(a.misc1 REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.misc1) , a.misc1 ) as misc1,
    IF(a.misc2 REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.misc2) , a.misc2 ) as misc2,
    a.college_date, a.military_date , a.loyalty_date , a.conquest_date , 
    a.lease_loyalty_date, a.misc1_date , a.misc2_date , 
    a.images , a.incentive_id , 
    users.username as sale_consultant , 
    b.state , b.sale_status
    FROM `sale_incentives` as a INNER JOIN sales as b ON a.sale_id = b.sale_id INNER JOIN users ON b.sales_consultant = users.id INNER JOIN inventory as c ON b.stock_id = c.id WHERE b.status = 1 AND b.sale_status != 'cancelled' AND a.status = 1 AND b.location = '$location' " . $filterQuery . " " . $searchQuery . "  ORDER BY b.sales_consultant ASC";
} else {
    $uid = $_SESSION['userId'];

    $sqlQuery = "SELECT CAST( b.date AS date ) as date ,
    CONCAT( b.fname ,' ', b.lname ) as customerName,
    c.stockno , CONCAT( c.stocktype , ' ' , c.year , ' ' , c.make , ' ' , c.model) as vehicle,
    IF(a.college REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.college) , a.college ) as college,
    IF(a.military REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.military) , a.military ) as military,
    IF(a.loyalty REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.loyalty) , a.loyalty ) as loyalty,
    IF(a.conquest REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.conquest) , a.conquest ) as conquest,
    IF(a.lease_loyalty REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.lease_loyalty) , a.lease_loyalty ) as lease_loyalty,
    IF(a.misc1 REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.misc1) , a.misc1 ) as misc1,
    IF(a.misc2 REGEXP '^[0-9]+$', (SELECT CONCAT('Yes/Approved by <br />', username) FROM users WHERE id = a.misc2) , a.misc2 ) as misc2,
    a.college_date, a.military_date , a.loyalty_date , a.conquest_date , 
    a.lease_loyalty_date , a.misc1_date , a.misc2_date , 
    a.images , a.incentive_id , 
    users.username as sale_consultant , 
    b.state , b.sale_status
    FROM `sale_incentives` as a INNER JOIN sales as b ON a.sale_id = b.sale_id INNER JOIN users ON b.sales_consultant = users.id INNER JOIN inventory as c ON b.stock_id = c.id WHERE b.status = 1 AND b.sale_status != 'cancelled' AND a.status = 1 AND b.sales_consultant = '$uid' AND b.location = '$location' " . $filterQuery . " " . $searchQuery . "  ORDER BY b.sales_consultant ASC";
}


$table = <<<EOT
(
    {$sqlQuery}
) temp
EOT;

// echo $table;
// echo "<hr />";


$primaryKey = 'incentive_id';

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
    array('db' => 'college', 'dt' => 4),
    array('db' => 'military',   'dt' => 5),
    array('db' => 'loyalty', 'dt' => 6),
    array('db' => 'conquest', 'dt' => 7),
    array('db' => 'lease_loyalty',   'dt' => 8),
    array('db' => 'misc1',   'dt' => 9),
    array('db' => 'misc2',   'dt' => 10),
    array('db' => 'college_date',   'dt' => 11),
    array('db' => 'military_date',   'dt' => 12),
    array('db' => 'loyalty_date',   'dt' => 13),
    array('db' => 'conquest_date',   'dt' => 14),
    array('db' => 'lease_loyalty_date',   'dt' => 15),
    array('db' => 'misc1_date',   'dt' => 16),
    array('db' => 'misc2_date',   'dt' => 17),
    array('db' => 'images',   'dt' => 18),
    array('db' => 'incentive_id',   'dt' => 19),
    array('db' => 'sale_status',   'dt' => 20),
    array('db' => 'state',   'dt' => 21),
    array('db' => 'sale_consultant',   'dt' => 22),

);


$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $localhost
);

require('ssp.class.php');

$tP = 0;
$tD = 0;
$result = $connect->query($sqlQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $sale_status = $row['sale_status'];
        if ($sale_status == 'pending') {
            $tP += 1;
        }
        if ($sale_status == 'delivered') {
            $tD += 1;
        }
    }
}

$dataObj = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);

$dataObj['totalCount']['tP'] = $tP;
$dataObj['totalCount']['tD'] = $tD;

echo json_encode($dataObj);
