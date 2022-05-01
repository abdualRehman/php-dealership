<?php

require_once 'db/core.php';

$id = $_POST['id'];
// $id = 1634;

$sql = "SELECT inventory.age , inventory.stockno , inventory.vin , inventory.model, inventory.year, inventory.make , inventory.color , 
inventory.mileage, inventory.lot , inventory.balance, inventory.retail, inventory.certified, 
inventory.stocktype , inventory.wholesale , inventory.id as invId , used_cars.* FROM inventory LEFT JOIN used_cars ON inventory.id = used_cars.inv_id WHERE inventory.id = '$id'";
$result = $connect->query($sql);

$output = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $output = $row;
    $submittedBy = $row['submitted_by'];

    if (isset($submittedBy)) {
        $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['submitted_by'] = $row1['username'];
    } else {
        $output['submitted_by'] = "";
    }

} // if num_rows

$connect->close();

// echo json_encode($row);
echo json_encode($output);
