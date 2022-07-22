<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `business_name`, `shop`, `address`, `city`, `state`, `zip`, `contact_person`, `contact_number`, `status` FROM `bodyshops` WHERE `status` = 1 ORDER BY `id` DESC";


$result = $connect->query($sql);
$output = array('data' => array());


if ($result->num_rows > 0) {

    // $row = $result->fetch_array();
    // pg_fetch_assoc()

    while ($row = $result->fetch_assoc()) {
        $output['data'][] = array(
            $row['id'],  // id //0
            $row['shop']  //shop //1
        );
    }
} // if num_rows

$connect->close();

echo json_encode($output);
