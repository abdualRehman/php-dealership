<?php

require_once 'db/core.php';


// $sql = "SELECT * FROM `appointments` WHERE appointments.status = 1";
$sql = "SELECT a.id , b.fname, b.lname , b.sale_id , c.stockno , c.stocktype , c.year , c.make , c.model  , a.stock_id, a.appointment_date, a.appointment_time, a.coordinator, a.delivery, a.additional_services, a.notes, a.submitted_by, a.manager_override, a.confirmed, a.complete, a.schedule_start, a.schedule_end, a.calender_id, a.status FROM `appointments` as a LEFT JOIN sales as b ON a.sale_id = b.sale_id LEFT JOIN inventory as c ON a.stock_id = c.id WHERE a.status = '1'";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    // $row = $result->fetch_array();
    while ($row = $result->fetch_assoc()) {

        $id = $row['id'];
        $sale_id = $row['sale_id'];
        $schedule_start = $row['schedule_start'];
        $schedule_end = $row['schedule_end'];
        $submitted_by = $row['submitted_by'];
        $salesConsultant = $row['manager_override'] == '' ? $row['submitted_by'] : $row['manager_override'];
        $calender_id = $row['calender_id'];


        $confirmed = $row['confirmed'];
        $complete = $row['complete'];
        $customerName = $row['fname'] . ' ' . $row['lname'];
        $appointment_date = $row['appointment_date'];
        $appointment_time = $row['appointment_time'];
        $coordinator = $row['coordinator'];
        $stockno = $row['stockno'];
        $vehicle = $row['stocktype'] . ' ' . $row['year'] . ' ' . $row['make'] . ' ' . $row['model'];
        $notes = $row['notes'];

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
        } else {
            $coordinator = "Blank";
        }


        $button = '
        <div class="show d-flex" >'.
            ((hasAccess("appointment", "Remove") !== 'false') ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeSchedule(' . $id . ')" >
            <i class="fa fa-trash"></i>
        </button>'  : '') .
        '</div>';





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
            $button

        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
