<?php

require_once 'db/core.php';


$sql = "SELECT `location_number`, `damage_type`, `damage_severty`, `grid_loation` FROM `default_options` WHERE status = 1";


$result = $connect->query($sql);
$output = array('data' => array());


if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $location = $row['location_number'];
        $damage_type = $row['damage_type'];
        $damage_severty = $row['damage_severty'];
        $grid_loation = $row['grid_loation'];

        // $location = str_replace("'", "\'", $location);
        // $damage_type = str_replace("'", "\'", $damage_type);
        // $damage_severty = str_replace("'", "\'", $damage_severty);
        // $grid_loation = str_replace("'", "\'", $grid_loation);

        // $location = str_replace('"', '\"', $location);
        // $damage_type = str_replace('"', '\"', $damage_type);
        // $damage_severty = str_replace('"', '\"', $damage_severty);
        // $grid_loation = str_replace('"', '\"', $grid_loation);

        $output['data'][] = array(
            $location,
            $damage_type,
            $damage_severty,
            $grid_loation,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
