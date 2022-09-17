<?php

require_once 'db/core.php';

// $sql = "SELECT fname , lname FROM `sales` WHERE status = 1";
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

$sql = "SELECT fname , lname FROM `sales` WHERE status = 1 AND location = '$location' GROUP BY fname, lname";
$result = $connect->query($sql);
$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $customerName = $row[0] . " ". $row[1];
        array_push($output['data'] , $customerName);
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
