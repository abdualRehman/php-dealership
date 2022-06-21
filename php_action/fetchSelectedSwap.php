<?php

require_once 'db/core.php';

$id = $_POST['id'];


// $sql = "SELECT `id`, `from_dealer`, `swap_status`, `stock_in`, `vehicle_in`, `color_in`, `inv_received`, `transferred_in`, `vin_in`, `inv_in`, `hb_in`, `msrp_in`, `hdag_in`, `adds_in`, `adds_in_notes`, `hbt_in`, `net_cost_in`, `stock_out`, `vehicle_out`, `color_out`, `inv_sent`, `transferred_out`, `vin_out`, `inv_out`, `hb_out`, `msrp_out`, `hdag_out`, `adds_out`, `adds_out_notes`, `hbt_out`, `net_cost_out`, `notes`, `sales_consultant` , `tagged` , `submitted_by` FROM `swaps` WHERE id = '$id'";
$sql = "SELECT `swaps`.`id`, `from_dealer`, `swap_status`, `stock_in`, `vehicle_in`, `color_in`, `inv_received`, `transferred_in`, `vin_in`, 
`inv_in`, `hb_in`, `msrp_in`, `hdag_in`, `adds_in`, `adds_in_notes`, `hbt_in`, `net_cost_in`, `stock_out`, `vehicle_out`, `color_out`, 
`inv_sent`, `transferred_out`, `vin_out`, `inv_out`, `hb_out`, `msrp_out`, `hdag_out`, `adds_out`, `adds_out_notes`, `hbt_out`, 
`net_cost_out`, `notes`, `sales_consultant` , `tagged` , users.username as submitted_by FROM `swaps` LEFT JOIN users ON swaps.submitted_by = users.id WHERE swaps.id = '$id'";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} // if num_rows

$connect->close();

echo json_encode($row);
