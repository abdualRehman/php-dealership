<?php

require_once 'db/core.php';

$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.id as invId , car_to_dealers.* FROM inventory LEFT JOIN car_to_dealers ON inventory.id = car_to_dealers.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    // while ($row = $result->fetch_assoc()) {
    while ($row = $result->fetch_array()) {
        $id = $row['invId'];
        $age =  (int)$row[0];
        $stockDetails = $row[1] . ' ||  ' . $row[2];
        $yearMakeModel = $row[4] . ' ' . $row[5] . ' ' . $row[3];

        $submittedBy = $row['submitted_by'];
        if (isset($submittedBy)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $row['submitted_by'] = $row1['username'];
        } else {
            $row['submitted_by'] = "";
        }






        $button = '
            <div class="show d-flex" >
            <!-- <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal9" onclick="editCashToDealers(' . $id . ')" >
                    <i class="fa fa-car" ></i>
                </button> -->
            ' .
            (hasAccess("lotWizards", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeCarsToDealers(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>
        ';
        $output['data'][] = array(
            $button,
            $age, //age
            $stockDetails,
            $yearMakeModel,
            $row['work_needed'], //work_needed	
            $row['notes'], // notes
            $row['date_sent'], // date_sent
            $row['date_returned'], // date_returned
            $id

        );
    } // /while 

} // if num_rows

$carsToDealsSql = "SELECT COUNT(inventory.stockno) as totalPending FROM inventory LEFT JOIN car_to_dealers ON inventory.id = car_to_dealers.inv_id WHERE inventory.stocktype = 'USED' AND inventory.lot != 'LBO' AND inventory.status = 1 AND ( car_to_dealers.work_needed != '' AND car_to_dealers.work_needed IS NOT NULL) AND ( car_to_dealers.date_returned = '' OR car_to_dealers.date_returned IS NULL)";
$result3 = $connect->query($carsToDealsSql);
$row3 = $result3->fetch_assoc();
$CarsToDealers = $row3['totalPending'];

$output['totalNumber'] = array(
    "CarsToDealers" => $CarsToDealers
);


$connect->close();

echo json_encode($output);
