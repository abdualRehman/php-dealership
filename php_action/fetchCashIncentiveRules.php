<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `expire_in`, `model`, `year`, `modelno`, `ex_modelno`, `dealer`, `other`, `lease`, `status` FROM `cash_incentive_rules` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];

        // $fdate = new DateTime($row[1]);

        // date_default_timezone_set('Australia/Melbourne');
        $date = date('Y-m-d');
        $date1 = new DateTime($date);
        // echo $date;
        $tdate = new DateTime($row[1]);

        $abs_diff = $date1->diff($tdate)->format("%r%a");

        if ($abs_diff <= 0) {
            $abs_diff = "Expire";
        } else {
            $abs_diff = $abs_diff . " Days";
        }

        $button = '
            <div class="show d-flex" >' .
            (hasAccess("cashincrule", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>
        ';


        $output['data'][] = array(
            // $row[1],
            $row[2],
            $row[3],
            $row[4],
            $row[5],
            $abs_diff,
            $row[6],
            $row[7],
            $row[8],
            $button,
            $id,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
