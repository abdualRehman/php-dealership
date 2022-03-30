<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `from_date`, `to_date`, `model`, `year`, `modelno`, `vin_check`, `insurance`, `trade_title`, `registration`, `inspection`, `salesperson_status`, `paid` , `type` FROM `salesperson_rules` WHERE status = 1";
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
        $tdate = new DateTime($row[2]);

        $abs_diff = $date1->diff($tdate)->format("%r%a");

        if($abs_diff <= 0){
            $abs_diff = "Expire";
        }else{
            $abs_diff = $abs_diff . " Days";
        }

        $button = '
            <div class="show d-flex" >
                <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editRule(' . $id . ')" >
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>  
            </div>
        ';


        $vin_check = ($row[6] == 'on') ? "YES" : "N/A";
        $insurance = ($row[7] == 'on') ? "YES" : "N/A";
        $trade_title = ($row[8] == 'on') ? "YES" : "N/A";
        $registration = ($row[9] == 'on') ? "YES" : "N/A";
        $inspection = ($row[10] == 'on') ? "YES" : "N/A";
        $salesperson_status = ($row[11] == 'on') ? "YES" : "N/A";
        $paid = ($row[12] == 'on') ? "YES" : "N/A";
    



        $output['data'][] = array(
            $row[3],
            $row[4],
            $row[5],
            $row[13],  // type
            $abs_diff,
            $vin_check,
            $insurance,
            $trade_title,
            $registration,
            $inspection,
            $salesperson_status,
            $paid,
            $button,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
