<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';


$sql = "SELECT * FROM `web_links` WHERE status = 1 AND location = '$location'";
$result = $connect->query($sql);
$output = array('data' => array());
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $role = $_SESSION['userRole'];
        $id = $row['id'];
        $name = $row['name'];
        $link = $row['link'];

        $output['data'][] = array(
            $id,
            $name,
            $link,
            $role,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
