<?php

require_once 'db/core.php';


// $sql = "SELECT * FROM `appointments` WHERE appointments.status = 1";
// $sql = "SELECT a.id , b.fname, b.lname , b.sale_id , c.stockno , c.stocktype , c.year , c.make , c.model  , a.stock_id, 
// a.appointment_date, a.appointment_time, a.coordinator, a.delivery, a.additional_services, a.notes, a.submitted_by, a.manager_override, 
// a.confirmed, a.complete, a.schedule_start, a.schedule_end, a.calender_id, a.status 
// FROM `appointments` as a LEFT JOIN sales as b ON a.sale_id = b.sale_id LEFT JOIN inventory as c ON a.stock_id = c.id WHERE a.status = '1'";

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

if ($_SESSION['userRole'] != $deliveryCoordinatorID) {
    $sql = "SELECT a.id , b.fname, b.lname , b.sale_id , c.stockno , c.stocktype , c.year , c.make , c.model  , a.stock_id, 
        a.appointment_date, a.appointment_time, a.coordinator, a.delivery, a.additional_services, a.notes, a.submitted_by, a.manager_override, 
        a.confirmed, a.complete, a.schedule_start, a.schedule_end, a.calender_id, a.status , b.sale_status , b.sales_consultant , a.already_have , c.vin
        FROM `appointments` as a LEFT JOIN sales as b ON a.sale_id = b.sale_id LEFT JOIN inventory as c ON a.stock_id = c.id WHERE a.status = 1 AND a.location = '$location' AND b.status = 1";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT a.id , b.fname, b.lname , b.sale_id , c.stockno , c.stocktype , c.year , c.make , c.model  , a.stock_id, 
        a.appointment_date, a.appointment_time, a.coordinator, a.delivery, a.additional_services, a.notes, a.submitted_by, a.manager_override, 
        a.confirmed, a.complete, a.schedule_start, a.schedule_end, a.calender_id, a.status , b.sale_status , b.sales_consultant , a.already_have , c.vin
        FROM `appointments` as a LEFT JOIN sales as b ON a.sale_id = b.sale_id LEFT JOIN inventory as c ON a.stock_id = c.id WHERE a.status = 1 AND a.location = '$location' AND a.coordinator = '$uid' AND b.status = 1";
}



$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    // $row = $result->fetch_array(); cencelled
    while ($row = $result->fetch_assoc()) {

        if ($row['sale_status'] != 'cancelled') {

            $id = $row['id'];
            $sale_id = $row['sale_id'];
            $schedule_start = $row['schedule_start'];
            $schedule_end = $row['schedule_end'];
            $submitted_by = $row['manager_override'] == '' ? $row['submitted_by'] : $row['manager_override'];
            // $salesConsultant = $row['manager_override'] == '' ? $row['submitted_by'] : $row['manager_override'];
            $salesConsultant = $row['sales_consultant'];
            $calender_id = $row['calender_id'];

            $additional_services = $row['additional_services'];

            $already_have = $row['already_have'];


            $confirmed = $row['confirmed'];
            $complete = $row['complete'];
            $customerName = $row['fname'] . ' ' . $row['lname'];
            $appointment_date = $row['appointment_date'];
            $appointment_time = $row['appointment_time'];
            $coordinator = $row['coordinator'];
            $stockno = $row['stockno'];
            $vehicle = $row['stocktype'] . ' ' . $row['year'] . ' ' . $row['make'] . ' ' . $row['model'];
            $notes = $row['notes'];
            $coordinator_color = "";

            $vin = $row['vin'];
            $delivery = preg_replace('/(?<=\\w)(?=[A-Z])/', " ", $row['delivery']);
            $delivery = ucfirst($delivery);


            if (isset($submitted_by)) {
                $sql1 = "SELECT * FROM `users` WHERE id = '$submitted_by'";
                $result1 = $connect->query($sql1);
                $row1 = $result1->fetch_assoc();
                $submitted_by = $row1['username'];
            } else {
                $submitted_by = "Blank";
            }


            if (isset($salesConsultant)) {
                $sql1 = "SELECT * FROM `users` WHERE id = '$salesConsultant'";
                $result1 = $connect->query($sql1);
                $row1 = $result1->fetch_assoc();
                $salesConsultant = $row1['username'];
            } else {
                $salesConsultant = "Blank";
            }

            if (isset($coordinator)) {
                $sql1 = "SELECT * FROM `users` WHERE id = '$coordinator'";
                $result1 = $connect->query($sql1);
                $row1 = $result1->fetch_assoc();
                $coordinator = $row1['username'];
                $coordinator_color = '#'.$row1['color'];
            } else {
                $coordinator = "Blank";
                $coordinator_color = "";
            }

            $allowEdit = false;

            $editManagerApproval = false;

            $button = '';
            if (
                ($_SESSION['userRole'] == $salesConsultantID && $_SESSION['userId'] == $row['sales_consultant'] && $confirmed != 'ok') || $_SESSION['userRole'] == 'Admin' ||
                $_SESSION['userRole'] == $branchAdmin || $_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == $generalManagerID ||
                $_SESSION['userRole'] == $deliveryCoordinatorID
            ) {
                $allowEdit = true;
                $button .= '
                        <div class="show d-flex" >' .
                    ((hasAccess("appointment", "Remove") !== 'false') ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeSchedule(' . $id . ')" >
                            <i class="fa fa-trash"></i>
                        </button>'  : '') .
                    '</div>';
            }

            if ($_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == $generalManagerID || $_SESSION['userRole'] == $branchAdmin) {
                $editManagerApproval = true;
            }

            $output['data'][] = array(
                $id,
                $sale_id,
                $calender_id,
                $submitted_by,
                $schedule_start,
                $schedule_end,

                $confirmed,
                $complete,
                $customerName,
                $appointment_date,
                $appointment_time,
                $coordinator,
                $stockno,
                $vehicle,
                $salesConsultant,
                $notes,
                $button,
                $allowEdit,
                $additional_services,
                $already_have,
                $row['manager_override'],
                $editManagerApproval,
                $vin,
                $delivery,
                $coordinator_color,
            );
        } // /if
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
