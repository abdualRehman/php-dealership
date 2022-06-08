<?php

require_once 'db/core.php';

// $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
// inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id
// FROM `sales` INNER JOIN inventory ON sales.stock_id = inventory.id INNER JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1";

$sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id , inventory.lot , inventory.certified, inventory.balance , 
inventory.status , inventory.age , inventory.stocktype , sales.stock_id
FROM `sales` INNER JOIN inventory ON sales.stock_id = inventory.id INNER JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

function asDollars($value) {
    if ($value<0) return "-".asDollars(-$value);
    return '$' . number_format($value, 2);
  }

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {

        $stock_id = $row[18];

        $countRow = 0;

        $sql1 = "SELECT stock_id, COUNT(stock_id) FROM sales WHERE sales.sale_status != 'cancelled' AND stock_id = '$stock_id' GROUP BY stock_id HAVING COUNT(stock_id) > 1";
        $result1 = $connect->query($sql1);
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_array();
            $countRow = $row1[1];
        }




        $invStatus = $row[15];
        $age = $row[16];

        $certified = ($row[13] == 'on') ? 'Yes' : 'No';
        $balance = ($invStatus == 1) ? $row[14] : "";
        $lot = ($invStatus == 1) ? $row[12] : "";
        // $balance = $row[14];
        // $lot = $row[12];


        $id = $row[11];

        $date = $row[0];
        $date = date("M-d-Y", strtotime($date));  // formating date
        $vehicle = $row[17] . ' ' . $row[7] . ' ' . $row[8] . ' ' . $row[9]; // vehicle details

        $gross = round(($row[10]), 2);
        $gross = asDollars($gross);


        $button = '
        <div class="show d-inline-flex" >' .
            (hasAccess("sale", "Edit") !== 'false' ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=edit&i=' . $id . '" class="btn btn-label-primary btn-icon mr-1" >
                <i class="fa fa-edit"></i>
            </a>' : "") .
            (hasAccess("sale", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon" onclick="removeSale(' . $id . ')" >
                <i class="fa fa-trash"></i>
            </button>' : "")
            . '</div>';

        $output['data'][] = array(

            $date,
            $row[2],
            $row[3],
            $row[4],
            $row[1],
            $vehicle,
            $age,
            $certified,
            $lot,
            $gross,
            $row[5],
            $row[6],
            $balance,
            $button,
            $row[17],
            $countRow,
            $id,

        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
