<?php

require_once 'db/core.php';

$sql = "SELECT a.id , a.contract_date , a.problem_date , a.customer_name, inventory.stockno ,a.vehicle , a.problem , a.notes , b.username as sales_consultant , c.username as finance_manager 
FROM `registration_problems` as a LEFT JOIN users as b ON b.id = a.sales_consultant LEFT JOIN users as c ON c.id = a.finance_manager LEFT JOIN inventory ON inventory.id = a.stock_id
WHERE a.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {
        $id = $row[0];

        // date_default_timezone_set('Australia/Melbourne');
        $date = date('Y-m-d H:i');
        // $date1 = new DateTime($date);  // Today
        // echo $date ."<br />";
        // $tdate = new DateTime($row[2]);  // contract Date
        // echo $row[1] ."<br />";

        // $abs_diff = $date1->diff($tdate)->format("%r%a");


        $diff = strtotime($row[1]) - strtotime($date);
        $abs_diff = abs(round($diff / 86400));
        if ($abs_diff == 0) {
            $abs_diff = "Today";
        } else {
            $abs_diff = $abs_diff;
        }



        $contract_date = DateTime::createFromFormat('Y-m-d H:i', $row[1]);
        $contract_date = $contract_date->format('M-d-Y');

        $problem_date = DateTime::createFromFormat('Y-m-d H:i', $row[2]);
        $problem_date = $problem_date->format('M-d-Y');

        // echo $problem_date . '<br />';

        $button = '
            <div class="show d-flex" >' .
            (hasAccess("regp", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editProblem(' . $id . ')" >
                    <i class="fa fa-edit"></i>
                </button>' : "") .
            (hasAccess("regp", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeProblem(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>
        ';


        $output['data'][] = array(
            $contract_date, // contract date
            $abs_diff,
            $problem_date, // problem date
            $row[3], // customer Date
            $row[8], // sales cunsultant 
            $row[9], // finance manager
            $row[5], // vehicle
            $row[6], // problem
            $row[7], // notes
            $button,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
