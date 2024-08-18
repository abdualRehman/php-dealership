<?php

require_once 'db/core.php';

$id = $_POST['id'];


$sql = "SELECT sales.* , inventory.stockno, inventory.vin , inventory.year , inventory.make , inventory.model , inventory.stocktype , sales.certified , inventory.mileage , inventory.age , inventory.lot , inventory.balance , sales.gross , rdr.delivered, rdr.entered, rdr.approved, rdr.rdr_notes , users.username as salesConsultant 
FROM sales LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN rdr ON sales.sale_id = rdr.sale_id LEFT JOIN users ON sales.sales_consultant = users.id WHERE sales.sale_id = '$id'";
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
        $output['submittedByName'] = $row1 ? $row1['username'] : "";
    } else {
        $output['submittedByName'] = "";
    }
} // if num_rows

$connect->close();

echo json_encode($output);
