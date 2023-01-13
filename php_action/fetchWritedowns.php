<?php

require_once 'db/core.php';

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

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

$sql = "SELECT used_cars.retail_status , used_cars.date_in, inventory.id as Inv_id , inventory.stockno , inventory.vin , inventory.year, inventory.make, inventory.model, 
inventory.mileage, used_cars.title, inventory.lot, used_cars.sold_price, inventory.age , inventory.retail , inventory.balance, used_cars.id as used_car_Id , used_cars.mmr
FROM used_cars LEFT JOIN inventory ON used_cars.inv_id = inventory.id  WHERE used_cars.status = 1 AND inventory.status = 1 AND inventory.location = '$location'";


$result = $connect->query($sql);
$output = array('data' => array());

$retailC = 0;
$retailV = 0;

$retailP = 0;
$retailPC = 0;
$retailA = 0;

$wholesaleC = 0;
$wholesaleV = 0;

$mmr_retailV = 0;
$mmr_balanceV = 0;

if ($result->num_rows > 0) {

    // while ($row = $result->fetch_assoc()) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['used_car_Id'];
        $stockDetails = $row['stockno'] . ' ||  ' . $row['vin'];
        $age = $row['age'];
        // $date_in = $row['date_in'];

        $mmr = (isset($row['mmr']) && $row['mmr'] != '') ?  (float)str_replace(array(',', '$'), '', $row['mmr']) : 0;
        $balance = (isset($row['balance']) && $row['balance'] != '') ? (float)str_replace(array(',', '$'), '', $row['balance']) : 0;
        $retail = (isset($row['retail']) && $row['retail'] != '')  ? (float)str_replace(array(',', '$'), '', $row['retail']) : 0;
        $profit = (float)$retail - (float)$balance;
        $profit = round($profit, 2);


        $mmr_balance = $mmr - $balance;
        // $mmr_retail = $retail - $mmr;
        $mmr_retail =  $mmr - $balance;


        // date_default_timezone_set('Asia/Karachi');
        if ($row['date_in'] != '' && !is_null($row['date_in'])) {
            $date = strtotime(date('Y-m-d'));
            $date_in = reformatDate($row['date_in']);
            // $date_in = date('Y-m-d', strtotime('-1 day', strtotime($date_in)));
            $date_in = date('Y-m-d', strtotime('-0 day', strtotime($date_in)));
            $date_in = strtotime($date_in);
            $age = ceil(abs($date - $date_in) / 86400);
        }



        // if ($row['stockno'] == 'O73148XX') {
        // get remaining days from today
        $todayTimestamp = strtotime(date('Y-m-d'));
        $daysRemaining = (int)date('t', $todayTimestamp) - (int)date('j', $todayTimestamp);
        $endOfAge = (int)$age + $daysRemaining;

        $writedown = 0;

        $ruleSql = "SELECT * FROM `writedown_rules` WHERE status = 1 AND location = '$location' AND age_from <= {$endOfAge} AND age_to >= {$endOfAge} AND balance_from <= {$balance} AND balance_to >= {$balance} ORDER BY cast(age_from as int) ASC, cast(age_to as int) DESC , cast(balance_from as int) ASC, cast(balance_to as int) ASC LIMIT 1";
        $result1 = $connect->query($ruleSql);
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();

            $pencent_balance = $row1['pencent_balance'];
            $max_writedown = $row1['max_writedown'];
            $writedown = ($balance * (int)$pencent_balance) / 100;

            if ($writedown > $max_writedown) {
                $writedown = $max_writedown;
            }



            if ($row['retail_status'] == 'retail' && $endOfAge >= 75) {

                $retailC += 1;
                $retailV += $writedown;


                $priceInt = str_replace(array(',', '$'), '', $row['retail']);

                if ($priceInt != '' && $priceInt != 0) {

                    $retailPC += 1;
                    $retailP += $profit;
                }

                // $mmr_balanceV += $mmr_balance;
                $mmr_retailV += $mmr_retail;
            }
            if ($row['retail_status'] == 'wholesale' && $endOfAge >= 75) {

                $wholesaleC += 1;
                $wholesaleV += $writedown;
                
                $mmr_balanceV += $mmr_balance;
            }


            if ($endOfAge >= 75) {
                // if ($row['stockno'] == 'O73148XX') {
                $output['data'][] = array(
                    $id,
                    $stockDetails,
                    $row['year'],
                    $row['make'],
                    $row['model'],
                    $row['mileage'],
                    $row['lot'],
                    $age,
                    $row['balance'],
                    $row['retail'],
                    asDollars($profit),
                    asDollars($writedown),
                    $mmr,
                    asDollars($mmr_balance),
                    asDollars($mmr_retail),
                    $row['Inv_id'],
                    $row['retail_status'],
                );
            }
        }
    } // /while 

} // if num_rows


$retailA = $retailP / ($retailPC != 0 ? $retailPC : 1);
$output['totalNumber'] = array(
    "retailC" => $retailC,
    "retailV" => asDollars($retailV),
    "retailP" => asDollars($retailP),
    "retailA" => asDollars($retailA),
    "wholesaleC" => $wholesaleC,
    "wholesaleV" => asDollars($wholesaleV),
    "mmr_retailV" => asDollars($mmr_retailV),
    "mmr_balanceV" => asDollars($mmr_balanceV),
);



$connect->close();

echo json_encode($output);
