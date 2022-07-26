<?php 	

require_once 'db/core.php';

$id = $_POST['id'];


$sql = "SELECT sales.* , inventory.stockno, inventory.vin , inventory.year , inventory.make , inventory.model , inventory.stocktype , inventory.certified , inventory.mileage , inventory.age , inventory.lot , inventory.balance , sales.gross , rdr.delivered, rdr.entered, rdr.approved, rdr.rdr_notes , users.username as salesConsultant 
FROM sales LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN rdr ON sales.sale_id = rdr.sale_id LEFT JOIN users ON sales.sales_consultant = users.id WHERE sales.sale_id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_assoc();
} // if num_rows

$connect->close();

echo json_encode($row);
