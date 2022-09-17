<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

$sql = "SELECT model FROM `manufature_price` WHERE status = 1 AND location = '$location' GROUP BY model";
$result = $connect->query($sql);

$data = $result->fetch_all();

$connect->close();

echo json_encode($data);

