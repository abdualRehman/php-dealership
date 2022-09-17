<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sql = "SELECT transportation.* , inventory.stockno , inventory.vin, inventory.model FROM `transportation` LEFT JOIN inventory ON transportation.stock_id = inventory.id WHERE transportation.status = 1 AND inventory.status = 1 AND transportation.location = '$location'";

$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $id = $row['id'];
        $loc_num = $row['loc_num'];
        $damage_type = $row['damage_type'];
        $damage_severity = $row['damage_severity'];
        $damage_grid = $row['damage_grid'];

        $stockno = $row['stockno'];
        $vin = $row['vin'];
        $model = $row['model'];


        $status = $row['transport_status'];
        if ($status == 'pending') {
            $status = "Pending";
        } elseif ($status == 'notRequired') {
            $status = "Repair not required";
        } elseif ($status == 'done') {
            $status = "Done";
        }


        $button = '
        <div class="show d-inline-flex" >' .
            ((hasAccess("tansptDmg", "Remove") !== 'false') ? '<button class="btn btn-label-primary btn-icon" onclick="removeDetails(' . $id . ')" >
                <i class="fa fa-trash"></i>
            </button>'  : '') .
            '</div>';


        $output['data'][] = array(
            $id,
            $stockno . ' - ' . $vin,
            $model,
            $loc_num . ' - ' . $damage_type . ' - ' . $damage_severity . ' - ' . $damage_grid,
            $status,
            $button,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
