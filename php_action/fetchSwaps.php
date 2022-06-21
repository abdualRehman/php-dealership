<?php

require_once 'db/core.php';


$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}


if ($userRole != $salesConsultantID) {
    $sql = "SELECT swaps.id , locations.dealer_no , locations.dealership , locations.address , 
    swaps.vehicle_in , swaps.color_in , swaps.stock_in , 
    swaps.vehicle_out , swaps.color_out , inventory.stockno as stock_out , swaps.sales_consultant , swaps.notes , users.username , swaps.swap_status , transferred_in , transferred_out
    FROM `swaps` LEFT JOIN locations ON locations.id = swaps.from_dealer LEFT JOIN users ON users.id = swaps.sales_consultant LEFT JOIN inventory ON inventory.id = swaps.stock_out  WHERE swaps.status = 1";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT swaps.id , locations.dealer_no , locations.dealership , locations.address , 
    swaps.vehicle_in , swaps.color_in , swaps.stock_in , 
    swaps.vehicle_out , swaps.color_out , inventory.stockno as stock_out , swaps.sales_consultant , swaps.notes , users.username , swaps.swap_status , transferred_in , transferred_out
    FROM `swaps` LEFT JOIN locations ON locations.id = swaps.from_dealer LEFT JOIN users ON users.id = swaps.sales_consultant LEFT JOIN inventory ON inventory.id = swaps.stock_out  WHERE swaps.status = 1 AND swaps.sales_consultant = '$uid'";
}

$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {

        $id = $row[0];
        $from_dealer = $row[2];

        $vehicle_in = $row[4] . '<br />' .  $row[5] . '<br />' . $row[6];
        $vehicle_out = $row[7] . '<br />' .  $row[8] . '<br />' . $row[9];
        $notes = $row[11];
        $sales_consultant = $row[12];
        $transferIn = ($row[14] == 'on') ? 'Yes' : 'No';
        $transferOut = ($row[15] == 'on') ? 'Yes' : 'No';



        $status = $row[13];
        if ($row[13] == 'pending') {
            $status = "Pending";
        } elseif ($row[13] == 'paperworkDone') {
            $status = "Paperwork Done";
        } elseif ($row[13] == 'completed') {
            $status = "Completed";
        }


        $button = '
        <div class="show d-inline-flex" >
            <button class="btn btn-label-primary btn-icon mr-1" onclick="printDetails(' . $id . ')" >
                <i class="fa fa-print"></i>
            </button>' .
            ((hasAccess("swap", "Remove") !== 'false') ? '<button class="btn btn-label-primary btn-icon" onclick="removeDetails(' . $id . ')" >
                <i class="fa fa-trash"></i>
            </button>'  : '') .
            '</div>';

        $output['data'][] = array(
            $from_dealer,
            $vehicle_in,
            $vehicle_out,
            $sales_consultant,
            $notes,
            $transferIn,
            $transferOut,
            $status,
            $button,
            $id,

        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
