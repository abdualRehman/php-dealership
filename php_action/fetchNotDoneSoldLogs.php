<?php

require_once 'db/core.php';

$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}

/* sales consultant id */
if ($userRole != $salesConsultantID) {
    $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
    inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id , inventory.lot , sales.certified, inventory.balance , 
    inventory.status , inventory.age , inventory.stocktype , sales.stock_id , sales.consultant_notes , sales.reconcileDate
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1  AND sales.sale_status = 'delivered' AND sales.thankyou_cards != 'on'";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
    inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id , inventory.lot , sales.certified, inventory.balance , 
    inventory.status , inventory.age , inventory.stocktype , sales.stock_id , sales.consultant_notes , sales.reconcileDate
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1  AND sales.sale_status = 'delivered' AND sales.thankyou_cards != 'on' AND sales.sales_consultant = '$uid'";
}

$result = $connect->query($sql);

$output = array('data' => array());

function asDollars($value)
{
    if ($value < 0) return "-" . asDollars(-$value);
    return '$' . number_format($value, 2);
}

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {

        $stock_id = $row[18];
        $id = $row[11];
        $consultant_notes = $row[19];
       



        $invStatus = $row[15];
        $age = $row[16];

        $certified = ($row[13] == 'on') ? 'Yes' : 'No';
        $balance = ($invStatus == 1) ? $row[14] : "";
        $lot = ($invStatus == 1) ? $row[12] : "";
        // $balance = $row[14];
        // $lot = $row[12];



        $date = $row[0];
        $date = date("M-d-Y", strtotime($date));  // formating date

        $reconcileDate = ($row[20] != '') ? $row[20] : '';
        $reconcileDate = ($row[20] != '') ? date("M-d-Y", strtotime($reconcileDate)) : "";  // formating reconcileDate

        $customerDetails = $row[2] . ' ' . $row[3];


        $vehicle = $row[17] . ' ' . $row[7] . ' ' . $row[8] . ' ' . $row[9]; // vehicle details

        $gross = round(($row[10]), 2);
        $gross = asDollars($gross);



        $output['data'][] = array(
            $id,
            $date,
            $reconcileDate,
            $customerDetails,
            $row[4],  // sales consultant
            $row[1],
            $vehicle,
            $age,
            $certified,
            $lot,
            $gross,
            $row[5],
            $row[6],
            $balance,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
