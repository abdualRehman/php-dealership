<?php
require_once './db/core.php';
$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
    $locId = $_POST['locId'];
    $elocName = (isset($_POST['elocName'])) ? mysqli_real_escape_string($connect, $_POST['elocName']) : "";

    $sql = "UPDATE `user_location` SET `name`='$elocName'  WHERE id = '$locId'";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
    }




    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);