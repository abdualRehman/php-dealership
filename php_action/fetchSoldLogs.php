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
    inventory.status , inventory.age , inventory.stocktype , sales.stock_id , sales.consultant_notes
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
    inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id , inventory.lot , sales.certified, inventory.balance , 
    inventory.status , inventory.age , inventory.stocktype , sales.stock_id , sales.consultant_notes
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1 AND sales.sales_consultant = '$uid'";
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
        $sales_consultant_status = "";

        $countRow = 0;

        $sql1 = "SELECT stock_id, COUNT(stock_id) FROM sales WHERE sales.sale_status != 'cancelled' AND stock_id = '$stock_id' GROUP BY stock_id HAVING COUNT(stock_id) > 1";
        $result1 = $connect->query($sql1);
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_array();
            $countRow = $row1[1];
        }
        $sql2 = "SELECT salesperson_status FROM `sale_todo` WHERE sale_id = '$id'";
        $result2 = $connect->query($sql2);
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_array();
            $sales_consultant_status = $row2[0];
        }




        $invStatus = $row[15];
        $age = $row[16];

        $certified = ($row[13] == 'on') ? 'Yes' : 'No';
        $balance = ($invStatus == 1) ? $row[14] : "";
        $lot = ($invStatus == 1) ? $row[12] : "";
        // $balance = $row[14];
        // $lot = $row[12];



        $date = $row[0];
        $date = date("M-d-Y", strtotime($date));  // formating date
        $vehicle = $row[17] . ' ' . $row[7] . ' ' . $row[8] . ' ' . $row[9]; // vehicle details

        $gross = round(($row[10]), 2);
        $gross = asDollars($gross);


        $button = '
        <div class="show d-inline-flex" >
        ' .
            (hasAccess("appointment", "Add") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#addNewSchedule" onclick="addNewSchedule(' . $id . ')" >
                <i class="far fa-calendar-alt"></i>
            </button>' : "") .
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
            $consultant_notes,
            $sales_consultant_status,
            $button,
            $row[17],
            $countRow,
            $id
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
