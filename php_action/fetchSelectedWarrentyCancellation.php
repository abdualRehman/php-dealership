<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT  warrenty_cancellation.id, warrenty_cancellation.customer_name, warrenty_cancellation.warrenty, warrenty_cancellation.date_cancelled, warrenty_cancellation.refund_des, warrenty_cancellation.finance_manager, warrenty_cancellation.date_sold, warrenty_cancellation.paid, warrenty_cancellation.notes FROM `warrenty_cancellation` WHERE warrenty_cancellation.id = '$id'";

$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_assoc();
} // if num_rows

$connect->close();

echo json_encode($row);
