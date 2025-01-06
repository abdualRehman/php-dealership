<?php

require_once 'db/core.php';

$id = $_POST['id'];


// $sql = "SELECT `id`, `from_dealer`, `swap_status`, `stock_in`, `vehicle_in`, `color_in`, `inv_received`, `transferred_in`, `vin_in`, `inv_in`, `hb_in`, `msrp_in`, `hdag_in`, `adds_in`, `adds_in_notes`, `hbt_in`, `net_cost_in`, `stock_out`, `vehicle_out`, `color_out`, `inv_sent`, `transferred_out`, `vin_out`, `inv_out`, `hb_out`, `msrp_out`, `hdag_out`, `adds_out`, `adds_out_notes`, `hbt_out`, `net_cost_out`, `notes`, `sales_consultant` , `tagged` , `submitted_by` FROM `swaps` WHERE id = '$id'";
// $sql = "SELECT transportation.* , inventory.stockno , inventory.vin, inventory.model FROM `transportation` LEFT JOIN inventory ON transportation.stock_id = inventory.id WHERE transportation.id = '$id'";
$sql = "SELECT t.id as tid, td.id as tdid, t.stock_id, t.notes, td.loc_num, td.damage_type,td.damage_severity,td.damage_grid,t.transport_status, i.stockno , i.vin, i.model FROM `transportation` as t LEFT JOIN inventory as i ON t.stock_id = i.id LEFT JOIN transportation_damages as td ON t.id = td.transportation_id WHERE t.id = '$id' AND td.status = 1";

$result = $connect->query($sql);

$data = []; // Initialize an empty array to store all rows

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Append each row to the array
    }
}

$connect->close();

echo json_encode($data); // Return the array as a JSON response