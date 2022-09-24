<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

$sql = "SELECT inspections.id , inventory.stockno , inventory.vin , inventory.stocktype ,  inventory.year , inventory.make , inventory.model , inventory.age , 
inspections.repairs , inspections.shops , inspections.repair_sent , inspections.repair_returned , inspections.repair_paid_date , inspections.repair_paid
FROM `inspections` LEFT JOIN inventory ON (inspections.inv_id = inventory.id AND inventory.location = '$location') WHERE inspections.status = 1 AND inventory.location = '$location' AND inspections.repair_returned != ''";
$result = $connect->query($sql);

$output = array('data' => array());


function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}


if ($result->num_rows > 0) {

    // while ($row = $result->fetch_assoc()) {
    while ($row = $result->fetch_array()) {

        $id = $row['id'];
        $stockDetails = $row['stockno'] . ' - ' . $row['vin'];
        $vehicle = $row['stocktype'] . ' - ' . $row['year'] . ' - ' . $row['make'] . ' - ' . $row['model'];



        // bodyshop name 
        $bodyShopName = "";
        $bodyShop = $row['shops'] ? $row['shops'] : "";

        if ($bodyShop != "") {
            $sql2 = "SELECT * FROM `bodyshops` WHERE id = '$bodyShop'";
            $result2 = $connect->query($sql2);
            $row2 = $result2->fetch_assoc();
            $bodyShopName = $row2['shop'];
        } else {
            $bodyShopName = "blank";
        }




        $repairSent =  $row['repair_sent'];
        $repairReturned =  $row['repair_returned'];
        $repair_paid_date =  $row['repair_paid_date'];
        $repair_paid =  $row['repair_paid'];

        // $repair_paid_date = '
        // <div class="show d-flex" >
        //     <input type="text" class="form-control" name="date_in_table" value="' . $row['repair_paid_date'] . '" data-attribute="repair_paid_date" data-id="' . $id . '" autocomplete="off"  />
        // </div>';



        // $button = '
        //     <div class="show d-flex" >' .
        //     (hasAccess("lotWizards", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeInspections(' . $id . ')" >
        //             <i class="fa fa-trash"></i>
        //         </button>' : "") .
        //     '</div>
        // ';




        $output['data'][] = array(
            $id,
            $row['age'],
            $stockDetails,
            $vehicle,
            $row['repairs'],
            $repairSent,
            $repairReturned,
            $repair_paid,
            $repair_paid_date,
            $bodyShopName,
        );
    } // /while 

} // if num_rows



$connect->close();
echo json_encode($output);
