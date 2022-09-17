<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sql = "SELECT `id`, `dealer_no`, `dealership`, `address`, `city`, `state`, `zip`, `miles`, `travel_time`, `round_trip`, `phone`, `fax`, `main_contact`, `cell`, `preffer` FROM `locations` WHERE status = 1 AND location = '$location'";

$result = $connect->query($sql);
$output = array('data' => array());


if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {

        $id = $row[0];
        $dealer_no = $row[1];  // dealer_no
        $dealership = $row[2];  // dealership
        $address = $row[3];  // address
        


        $output['data'][] = array(
           $id,
           $dealer_no,
           $dealership,
           $address,
        );

        // }
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
