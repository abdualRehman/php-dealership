<?php

require_once 'db/core.php';

$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}

/* sales consultant id */
if ($userRole != $salesConsultantID) {
    $sql = "SELECT sales.date ,  sales.sale_status, sales.sale_id, inventory.stocktype , inventory.balance 
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant 
    WHERE sales.status = 1 ORDER BY sales.date ASC";
    // $sql = "SELECT sales.date ,  sales.sale_status, sales.sale_id, inventory.stocktype , inventory.balance 
    // FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant 
    // WHERE sales.status = 1 AND sales.date >= '2022-06-01 23:49' AND sales.date <='2022-06-22 17:40' ORDER BY sales.date ASC";

    $sql2 = "SELECT 
        ( SELECT COUNT(registration_problems.id) FROM registration_problems WHERE registration_problems.status = 1 AND registration_problems.p_status = 1 ) as problem ,
        ( SELECT  COUNT(b.sale_todo_id)  FROM `sale_todo` as b INNER JOIN sales ON b.sale_id = sales.sale_id WHERE ( sales.status = 1 AND b.status = 1  AND ((b.vin_check = 'checkTitle' OR b.vin_check = 'need') OR b.inspection = 'need' OR b.trade_title = 'need' OR b.registration = 'pending' OR b.registration = 'done' OR b.inspection = 'need' ))) as todo , 
        ( SELECT COUNT(used_cars.id) FROM `used_cars` LEFT JOIN inventory ON (used_cars.inv_id = inventory.id AND inventory.status = 1 AND inventory.stocktype = 'USED' AND inventory.lot != 'LBO') WHERE (title = 'false' OR title IS NULL) AND date_in != '' AND date_in IS NOT NULL AND inventory.id IS NOT NULL ) as titleIssue WHERE 1";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT sales.date ,  sales.sale_status, sales.sale_id, inventory.stocktype , inventory.balance 
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant 
    WHERE sales.status = 1 AND sales.sales_consultant = '$uid' ORDER BY sales.date ASC";

    $sql2 = "SELECT 
        ( SELECT COUNT(registration_problems.id) FROM registration_problems WHERE registration_problems.status = 1 AND registration_problems.p_status = 1 AND registration_problems.sales_consultant = '$uid') as problem ,
        ( SELECT  COUNT(b.sale_todo_id)  FROM `sale_todo` as b INNER JOIN sales ON b.sale_id = sales.sale_id WHERE ( sales.sales_consultant = '$uid' AND sales.status = 1 AND b.status = 1  AND ((b.vin_check = 'checkTitle' OR b.vin_check = 'need') OR b.inspection = 'need' OR b.trade_title = 'need' OR b.registration = 'pending' OR b.registration = 'done' OR b.inspection = 'need' ))) as todo , 
        ( SELECT COUNT(used_cars.id) FROM `used_cars` LEFT JOIN inventory ON (used_cars.inv_id = inventory.id AND inventory.status = 1 AND inventory.stocktype = 'USED' AND inventory.lot != 'LBO') WHERE (title = 'false' OR title IS NULL) AND date_in != '' AND date_in IS NOT NULL AND inventory.id IS NOT NULL ) as titleIssue WHERE 1";
}





$result = $connect->query($sql);
$output = array('data' => array(), 'graph' => array());
$outputArray = array();


$monthNewCount = 0;
$monthUsedCount = 0;
$monthAllCount = 0;

$todayN = 0;
$todayU = 0;
$todayT = 0;
$avgn = 0;
$avgu = 0;
$avga = 0;
$penu = 0;
$penn = 0;
$pent = 0;


$todayNCount = 0;
$todayUCount = 0;
$todayTCount = 0;

$regCount = 0;
$todoCount = 0;
$tittleCount = 0;

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $TodayDate = date("Y-m-d");
        // $TodayDate = "2022-08-01";
        $timezone = $row['date'];
        $timezone = strtotime($row['date']);
        $timezone = date("Y-m-d", $timezone);


        $first_date = date('Y-m-d', strtotime('first day of this month'));
        $last_date = date('Y-m-d', strtotime('last day of this month'));
        
        if (($timezone >= $first_date) && ($timezone <= $last_date)) {
            // $monthAllCount += 1;
            if ($row['stocktype'] == 'NEW') {
                $monthNewCount += 1;
            }
            if ($row['stocktype'] == 'USED') {
                $monthUsedCount += 1;
            }
        }




        if (!key_exists($timezone, $outputArray)) {
            $outputArray[$timezone] = array(
                'time' => '',
                'stocktype' => '',
                'qty' => 0
            );
        }
        $outputArray[$timezone] = array(
            'time' => $timezone,
            'stocktype' => $row['stocktype'],
            'qty' => $outputArray[$timezone]['qty'] + 1,
        );




        $balance = floatval(preg_replace("/[^0-9.]/", '', $row['balance']));

        if ($timezone == $TodayDate) {
            if ($row['stocktype'] == 'NEW') {
                $todayN += $balance;
                $todayT += $balance;
                $todayNCount += 1;
            }
            if ($row['stocktype'] == 'USED') {
                $todayU += $balance;
                $todayT += $balance;
                $todayUCount += 1;
            }
            $todayTCount += 1;
        }

        if ($row['sale_status'] == 'pending' && $row['stocktype'] == 'USED') {
            $penu += 1;
            $pent += 1;
        }
        if ($row['sale_status'] == 'pending' && $row['stocktype'] == 'NEW') {
            $penn += 1;
            $pent += 1;
        }
    } // /while 

} // if num_rows


$result2 = $connect->query($sql2);
if ($result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
    $regCount = $row2['problem'];
    $todoCount = $row2['todo'];
    $tittleCount = $row2['titleIssue'];
}










$outputArray = array_values($outputArray);
$output['graph'] = $outputArray;

$avgn = ($todayNCount != 0) ? $todayN / $todayNCount : 0;
$avgu = ($todayUCount != 0) ? $todayU / $todayUCount : 0;
$avga = ($todayTCount != 0) ? $todayT / $todayTCount : 0;

// exclude stock type used here
$monthAllCount = $monthNewCount + $monthUsedCount;

$output['data'] = array(
    $avgn,
    $avgu,
    $avga,
    $todayN,
    $todayU,
    $todayT,
    $penn,
    $penu,
    $pent,
    $regCount,
    $todoCount,
    $tittleCount,

    $monthNewCount,
    $monthUsedCount,
    $monthAllCount,

    $todayNCount,
    $todayUCount,
    $todayTCount,

);


$connect->close();
echo json_encode($output);
