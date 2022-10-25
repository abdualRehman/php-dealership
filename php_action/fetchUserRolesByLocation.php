<?php

require_once 'db/core.php';

$location = $_POST['location'];

$sql = "SELECT * FROM `role` WHERE location_id = '$location' AND role_status != 2 ORDER BY role_name asc";
$result = $connect->query($sql);
$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $role_id = $row[0];
        $name = $row[1];
        $output['data'][] = array(
            $role_id,
            $name,  //name
        );
    } // /while 
} // if num_rows

$connect->close();
echo json_encode($output);
