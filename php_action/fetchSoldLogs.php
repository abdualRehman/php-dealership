<?php

require_once 'db/core.php';

$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
/* sales consultant id */
if ($userRole != $salesConsultantID) {
    $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
    inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id , inventory.lot , sales.certified, inventory.balance , 
    inventory.status , inventory.age , inventory.stocktype , sales.stock_id , sales.consultant_notes , sales.thankyou_cards , sales.reconcileDate , sales.sales_consultant
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1 AND sales.location = '$location'";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
    inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id , inventory.lot , sales.certified, inventory.balance , 
    inventory.status , inventory.age , inventory.stocktype , sales.stock_id , sales.consultant_notes , sales.thankyou_cards , sales.reconcileDate , sales.sales_consultant
    FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1 AND sales.sales_consultant = '$uid' AND sales.location = '$location'";
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



        $confirmed = '';
        $sql3 = "SELECT * FROM `appointments` WHERE status = 1 AND location = '$location' AND sale_id = '$id'";
        $result3 = $connect->query($sql3);
        if ($result3->num_rows > 0) {
            $row3 = $result3->fetch_array();
            $confirmed = $row3['confirmed'];
        }

        $invStatus = $row[15];
        $age = $row[16];

        $certified = ($row[13] == 'on') ? 'Yes' : 'No';
        $balance = ($invStatus == 1) ? $row[14] : "";
        $lot = ($invStatus == 1) ? $row[12] : "";
        // $balance = $row[14];
        // $lot = $row[12];



        // $date = $row[0];
        $sold_date = date("M-d-Y", strtotime($row[0]));  // sold date
        $date =  ($row[21] != '') ? date("M-d-Y", strtotime($row[21])) : date("M-d-Y", strtotime($row[0]));  // get Reconcile Date is exist otherwise get date
        // $reconcile_date = ($row[21] != '') ? date("M-d-Y", strtotime($row[21])) : '';  // Reconcile date
        $vehicle = $row[17] . ' ' . $row[7] . ' ' . $row[8] . ' ' . $row[9]; // vehicle details

        $gross = round(($row[10]), 2);
        $gross = asDollars($gross);


        $button = '
        <div class="show d-inline-flex w-100 justify-content-end" >';

        if (
            ($_SESSION['userRole'] == $salesConsultantID && $confirmed != 'ok') || $_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == $branchAdmin ||
            $_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == $generalManagerID
        ) {
            if ($row['sale_status'] != 'cancelled' && hasAccess("appointment", "Add") !== 'false') {
                $button .= '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#editScheduleModel" onclick="addNewSchedule(' . $id . ')" >
                            <i class="far fa-calendar-alt"></i>
                        </button>';
            }
        }

        if (hasAccess("sale", "Remove") !== 'false') {
            $button .= '<button class="btn btn-label-primary btn-icon" onclick="removeSale(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>';
        }
        $button .= '</div>';


        $codp_warn = "false";
        $lwbn_warn = "false";
        $statusSql2 = "SELECT 
        (SELECT COUNT(inv_id) FROM `car_to_dealers` WHERE car_to_dealers.status = 1 AND inv_id = '$stock_id' AND work_needed != '' AND date_returned = '') as carstodealers , 
        (SELECT COUNT(inv_id) FROM `inspections` WHERE inspections.status = 1 AND inspections.repair_returned != '' AND inspections.repair_paid = '' AND inspections.inv_id = '$stock_id') as lotwizardsBills";
        $rslt2 = $connect->query($statusSql2);
        if ($rslt2->num_rows > 0) {
            $rowStatus = $rslt2->fetch_assoc();
            $codp_warn = $rowStatus['carstodealers'] > 0 ? "true" : "false";
            $lwbn_warn = $rowStatus['lotwizardsBills'] > 0 ? "true" : "false";
        }



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
            $id,
            $row[20],
            $sold_date,
            $codp_warn,
            $lwbn_warn
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
