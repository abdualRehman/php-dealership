<?php

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array(), 'settingError' => array());

$user_id = $_SESSION['userId'];

if ($user_id) {
    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    // $sql2 = "DELETE from notifications WHERE to_user = '$user_id' AND status = 1 AND date>= DATE_ADD(CURDATE(), INTERVAL -5 DAY) ORDER BY date DESC";
    $sql2 = "UPDATE notifications SET `status`='2' WHERE to_user = '$user_id' AND status = 1 AND date>= DATE_ADD(CURDATE(), INTERVAL -5 DAY) ORDER BY date DESC";
    $result2 = $connect->query($sql2);

    $valid['success'] = true;
    $valid['messages'] = "Successfully Removed";

    $connect->close();
    echo json_encode($valid);
} // /if $_POST