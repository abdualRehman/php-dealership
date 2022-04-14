<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `f_24-36`, `f_37-48`, `f_49-60`, `f_61-72` , `f_expire` , `lease_expire` FROM `rate_rule` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];

        // date_default_timezone_set('Australia/Melbourne');
        $date = date('Y-m-d');
        $today = new DateTime($date);
        // echo $date;
        $f_expire = ($row[9] != '' && !is_null($row[9])) ? new DateTime($row[9]) : "";
        $f_expire_diff = ($row[9] != '' && !is_null($row[9])) ? $today->diff($f_expire)->format("%r%a") : 0;

        if ($f_expire_diff < 0) {
            $f_expire_diff = "Expire";
        } else {
            $f_expire_diff = $f_expire_diff . " Days";
        }
        $lease_expire = ($row[10] != '' && !is_null($row[10])) ? new DateTime($row[10]) : "";
        $lease_expire = ($row[10] != '' && !is_null($row[10])) ? $today->diff($lease_expire)->format("%r%a") : 0;

        if ($lease_expire < 0) {
            $lease_expire = "Expire";
        } else {
            $lease_expire = $lease_expire . " Days";
        }


        $button = '
            <div class="show d-flex" >
                <!-- <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#showDetails" onclick="showDetails(' . $id . ')" >
                    <i class="fa fa-eye"></i>
                </button> -->
                <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editRule(' . $id . ')" >
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>  
            </div>
        ';


        $output['data'][] = array(
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $f_expire_diff,
            $lease_expire,
            $row[5],
            $row[6],
            $row[7],
            $row[8],
            $button,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
