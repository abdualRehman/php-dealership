<?php

require_once 'db/core.php';

$sql = "SELECT model FROM `manufature_price` WHERE status = 1 GROUP BY model";
$result = $connect->query($sql);

$data = $result->fetch_all();

$connect->close();

echo json_encode($data);

