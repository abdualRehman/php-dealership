<?php

require_once 'db/core.php';

$id = $_POST['id'];

// $sql = "SELECT used_cars.retail_status , used_cars.date_in, inventory.stockno , inventory.vin , inventory.year, inventory.make, inventory.model, 
// inventory.mileage, used_cars.title, inventory.lot, inventory.stocktype ,used_cars.sold_price, inventory.age , inventory.retail , inventory.balance, used_cars.id as used_car_Id , 
// writedown.id as writedown_id , writedown.writedown_v, writedown.mmr, writedown.mmr_retail, writedown.mmr_balance 
// FROM used_cars LEFT JOIN inventory ON used_cars.inv_id = inventory.id LEFT JOIN writedown ON used_cars.id = writedown.used_car_id WHERE used_cars.id = '$id'";
$sql = "SELECT used_cars.retail_status , used_cars.date_in, inventory.id as Inv_id , inventory.stockno , inventory.vin , inventory.year, inventory.make, inventory.model, 
inventory.mileage, used_cars.title, inventory.lot, inventory.stocktype ,used_cars.sold_price, inventory.age , inventory.retail , inventory.balance, used_cars.id as used_car_Id , 
used_cars.mmr FROM used_cars LEFT JOIN inventory ON used_cars.inv_id = inventory.id WHERE used_cars.id = '$id'";

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}
$result = $connect->query($sql);
$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $output = $row;

    $age = $row['age'];


    $mmr = (isset($row['mmr']) && $row['mmr'] != '') ?  (float)str_replace(array(',', '$'), '', $row['mmr']) : 0;
    $balance = (isset($row['balance']) && $row['balance'] != '') ? (float)str_replace(array(',', '$'), '', $row['balance']) : 0;
    $retail = (isset($row['retail']) && $row['retail'] != '')  ? (float)str_replace(array(',', '$'), '', $row['retail']) : 0;

    $mmr_balance = $mmr - $balance;
    $mmr_retail = $retail - $mmr;

    // date_default_timezone_set('Asia/Karachi');
    if ($row['date_in'] != '' && !is_null($row['date_in'])) {
        $date = strtotime(date('Y-m-d'));
        $date_in = reformatDate($row['date_in']);
        $date_in = date('Y-m-d', strtotime('-1 day', strtotime($date_in)));
        $date_in = strtotime($date_in);
        $age = ceil(abs($date - $date_in) / 86400);
    }


    $todayTimestamp = strtotime(date('Y-m-d'));
    $daysRemaining = (int)date('t', $todayTimestamp) - (int)date('j', $todayTimestamp);
    $endOfAge = (int)$age + $daysRemaining;

    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    $ruleSql = "SELECT * FROM `writedown_rules` WHERE status = 1 AND location = '$location' AND age_from <= '$endOfAge' AND age_to >= '$endOfAge' AND balance_from <= '$balance' AND balance_to >= '$balance' ORDER BY cast(age_from as int) ASC, cast(age_to as int) DESC , cast(balance_from as int) ASC, cast(balance_to as int) ASC LIMIT 1";
    $result1 = $connect->query($ruleSql);
    $row1 = $result1->fetch_assoc();

    $pencent_balance = $row1['pencent_balance'];
    $max_writedown = $row1['max_writedown'];

    $writedown = ($balance * (int)$pencent_balance) / 100;

    if ($writedown > $max_writedown) {
        $writedown = $max_writedown;
    }



    $output['Inv_id'] = $row['Inv_id'];
    $output['balance'] = $balance;
    $output['retail'] = $retail;
    $output['endOfAge'] = $age;
    $output['mmr_balance'] = $mmr_balance;
    $output['mmr_retail'] = $mmr_retail;
    $output['writedown'] = $writedown;
    $output['mmr'] = $mmr;



} // if num_rows

$connect->close();

echo json_encode($output);
