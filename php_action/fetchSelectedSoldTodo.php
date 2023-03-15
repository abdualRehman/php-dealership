<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

// $sql = "SELECT `vin_check`, `insurance`, `trade_title`, `registration`, `inspection`, `salesperson_status`, `paid` FROM `sale_todo` WHERE  `sale_todo_id` = '$id'";
$sql = "SELECT sale_todo.sale_todo_id , users.username as sale_consultant , sales.date , sales.fname , sales.lname , sale_todo.vin_check , sale_todo.insurance , sale_todo.trade_title , sale_todo.registration , sale_todo.inspection , sale_todo.salesperson_status , sale_todo.paid , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , inventory.vin , sales.state , sales.consultant_notes
FROM `sale_todo` INNER JOIN sales ON sale_todo.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE  `sale_todo_id` = '$id' ";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
