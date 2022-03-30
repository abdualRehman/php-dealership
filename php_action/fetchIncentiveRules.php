<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `from_date`, `to_date`, `model`, `year`, `modelno`, `college`, `military`, `loyalty`, `conquest`, `misc1`, `misc2`, `misc3` , `type` FROM `incentive_rules` WHERE status = 1";
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


        $college = ($row[6] == 'on') ? "YES" : "N/A";
        $military = ($row[7] == 'on') ? "YES" : "N/A";
        $loyalty = ($row[8] == 'on') ? "YES" : "N/A";
        $conquest = ($row[9] == 'on') ? "YES" : "N/A";
        $misc1 = ($row[10] == 'on') ? "YES" : "N/A";
        $misc2 = ($row[11] == 'on') ? "YES" : "N/A";
        $misc3 = ($row[12] == 'on') ? "YES" : "N/A";
        // if ($row[6] == 'on') {
        //     $college = "YES";
        // } else {
        //     $college = "N/A";
        // }



        $output['data'][] = array(
            $row[3],
            $row[4],
            $row[5],
            $row[13],  // type
            $abs_diff,
            $college,
            $military,
            $loyalty,
            $conquest,
            $misc1,
            $misc2,
            $misc3,
            $button,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
