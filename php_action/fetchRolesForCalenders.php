<?php

require_once 'db/core.php';

$sql = "SELECT `role_id`, `role_name`, `role_des`, `color_code`, `role_status` FROM `role` WHERE role_status != 2";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $output['data'][] = array(
            $row[0],  // id
            $row[1],  // name
            $row[3],  // color code
        );
    } // /while 
} // if num_rows

$connect->close();
echo json_encode($output);
