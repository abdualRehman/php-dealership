<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

$statusPriority = $_POST['statusPriority'] ?? '';

$searchQuery = '';
if ($statusPriority != '') {
    $searchQuery .= " AND t.transport_status = '$statusPriority' ";
}

// // Clear the transportation_damages table before adding new data
// $clearTableSQL = "TRUNCATE TABLE transportation_damages";
// if (!$connect->query($clearTableSQL)) {
//     die("Error clearing transportation_damages table: " . $connect->error);
// }
// // Fetch records from the transportation table
// $sql1 = "SELECT id, stock_id, loc_num, damage_type, damage_severity, damage_grid, transport_status, status, location FROM transportation";
// $result2 = $connect->query($sql1);
// if ($result2->num_rows > 0) {
//     while ($row = $result2->fetch_assoc()) {
//         // Prepare the INSERT statement for transportation_damages
//         $stmt = $connect->prepare(
//             "INSERT INTO transportation_damages (transportation_id, loc_num, damage_type, damage_severity, damage_grid, status) 
//             VALUES (?, ?, ?, ?, ?, ?)"
//         );
//         // Bind parameters
//         $stmt->bind_param(
//             "isssss",
//             $row['id'],
//             $row['loc_num'],
//             $row['damage_type'],
//             $row['damage_severity'],
//             $row['damage_grid'],
//             $row['status']
//         );

//         // Execute the statement
//         if (!$stmt->execute()) {
//             echo "Error inserting record: " . $stmt->error;
//         }

//         // Close the prepared statement
//         $stmt->close();
//     }
//     echo "Data copied successfully from transportation to transportation_damages.";
// } else {
//     echo "No records found in the transportation table.";
// }
// return false;


$sql = "SELECT t.id, t.notes, t.stock_id , t.transport_status, t.status, i.stockno, i.vin, i.model, 
td.id as tdid, td.loc_num, td.damage_type, td.damage_severity, td.damage_grid
        FROM transportation t
        LEFT JOIN inventory i ON t.stock_id = i.id
        LEFT JOIN transportation_damages td ON t.id = td.transportation_id
        WHERE t.status = 1 AND td.status = 1 AND i.status = 1 AND t.location = '$location' {$searchQuery} 
        ORDER BY FIELD(t.transport_status, 'pending', 'pendingInspection' , 'partsNeeded' , 'partsRequested' , 'partsArrivedPendingService' , 'bodyshopNeeded' , 'atBodyshop', 'bodyshopCompleted', 'completedAwaitingPayment' , 'notRequired', 'repairNotRequired' , 'done')";

$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $tdid = $row['tdid'];
        $id = $row['id'];
        $notes = $row['notes'];
        $loc_num = $row['loc_num'];
        $damage_type = $row['damage_type'];
        $damage_severity = $row['damage_severity'];
        $damage_grid = $row['damage_grid'];

        $stockno = $row['stockno'];
        $vin = $row['vin'];
        $model = $row['model'];


        $status = $row['transport_status'];

        $button = '
        <div class="show d-inline-flex" >' .
            ((hasAccess("tansptDmg", "Remove") !== 'false') ? '<button class="btn btn-label-primary btn-icon" onclick="removeDetails(' . $tdid . ' , ' . $id . ')" >
                <i class="fa fa-trash"></i>
            </button>'  : '') .
            '</div>';


        $output['data'][] = array(
            $id,
            $stockno . ' - ' . $vin,
            $model,
            $notes,
            $loc_num . ' - ' . $damage_type . ' - ' . $damage_severity . ' - ' . $damage_grid,
            $status,
            $button,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
