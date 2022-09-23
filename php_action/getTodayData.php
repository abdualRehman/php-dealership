<?php

require_once 'db/core.php';


$TodayDate = date("Y-m-d");
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';


$sql = "SELECT 
            (SELECT COUNT(sales.sale_id) FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id 
            WHERE inventory.stocktype = 'USED' AND sales.status = 1 AND sales.location = '$location' AND sales.date LIKE '" . $TodayDate . "%') as used , 
            (SELECT COUNT(sales.sale_id) FROM `sales` LEFT JOIN inventory ON sales.stock_id = inventory.id 
            WHERE inventory.stocktype = 'NEW' AND sales.status = 1 AND sales.location = '$location' AND sales.date LIKE '" . $TodayDate . "%') as new WHERE 1";

$result = $connect->query($sql);

$output = array('data' => array(), 'notifications' => array());

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total = (int)$row['used'] + (int)$row['new'];
    $output['data'] = array(
        $row['new'],
        $row['used'],
        $total
    );
} // if num_rows


$user_id = $_SESSION['userId'];
// $user_id = 65;

$sql2 = "SELECT * from notifications WHERE to_user = '$user_id' AND status = 1 AND date>= DATE_ADD(CURDATE(), INTERVAL -5 DAY) ORDER BY date DESC";
$result2 = $connect->query($sql2);

if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {

        $id = $row2['id'];
        $sql3 = "UPDATE notifications  SET is_delivered = 1  WHERE id = '$id'";
        $connect->query($sql3);

        $output['notifications'][] = array(
            $row2['id'],
            $row2['date'],
            $row2['from_user'],
            $row2['message'],
            $row2['link'],
            $row2['is_read'],
            $row2['is_delivered'],
        );
    }
} // if num_rows




$connect->close();

echo json_encode($output);
