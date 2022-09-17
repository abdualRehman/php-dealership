<?php

require_once 'db/core.php';


$TodayDate = date("Y-m-d");
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';


$sql = "SELECT 
            (SELECT COUNT(sales.sale_id) FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id 
            WHERE inventory.stocktype = 'USED' AND sales.status = 1 AND sales.date LIKE '".$TodayDate."%') as used , 
            (SELECT COUNT(sales.sale_id) FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id 
            WHERE inventory.stocktype = 'NEW' AND sales.status = 1 AND sales.location = '$location' AND sales.date LIKE '".$TodayDate."%') as new WHERE 1";

$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total = (int)$row['used'] + (int)$row['new'];
    $output['data'] = array(
        $row['new'],
        $row['used'],
        $total
    );

} // if num_rows

$connect->close();

echo json_encode($output);
