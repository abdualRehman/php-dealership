<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `dealer_no`, `dealership`, `address`, `city`, `state`, `zip`, `miles`, `travel_time`, `round_trip`, `phone`, `fax`, `main_contact`, `cell`, `preffer`, `status` FROM `locations` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
        <div class="show" >
            <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#showDetails" onclick="showDetails(' . $id . ')" >
                <i class="fa fa-eye"></i>
            </button>
            <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#editDetails" onclick="editDetails(' . $id . ')" >
                <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-label-primary btn-icon mr-1" onclick="removeLocation(' . $id . ')"  >
                <i class="fa fa-trash"></i>
            </button>    
        </div>
    ';
   

        $output['data'][] = array(
            $row[2],
            $row[1],
            $row[10],
            $row[12],
            $row[13],
            $row[14],
            $row[9],
            $button
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
