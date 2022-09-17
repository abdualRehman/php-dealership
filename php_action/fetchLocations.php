<?php

require_once 'db/core.php';
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sql = "SELECT `id`, `dealer_no`, `dealership`, `address`, `city`, `state`, `zip`, `miles`, `travel_time`, `round_trip`, `phone`, `fax`, `main_contact`, `cell`, `preffer`, `status` FROM `locations` WHERE status = 1 AND location='$location'";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
        <div class="show" >' .
            (hasAccess("swploc", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#editDetails" onclick="editDetails(' . $id . ')" >
                <i class="fa fa-edit"></i>
            </button>' : "") .
            (hasAccess("swploc", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeLocation(' . $id . ')"  >
                <i class="fa fa-trash"></i>
            </button>' : "") .
            '</div>
    ';


        $output['data'][] = array(
            $row[2],
            $row[1],
            $row[10],
            $row[12],
            $row[13],
            $row[14],
            $row[9],
            $button,
            $id
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
