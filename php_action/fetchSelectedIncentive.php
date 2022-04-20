<?php 	

require_once 'db/core.php';

$id = $_POST['id'];


$sql = "SELECT sale_incentives.* , users.username as sale_consultant , sales.date , sales.fname , sales.lname , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , sales.state , inventory.vin
FROM sale_incentives INNER JOIN sales ON sale_incentives.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE  sale_incentives.incentive_id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
