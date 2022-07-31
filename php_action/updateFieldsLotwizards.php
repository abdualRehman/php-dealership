<?php

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());


// code to be executed;
$id = mysqli_real_escape_string($connect, $_POST['id']);
$attribute = mysqli_real_escape_string($connect, $_POST['attribute']);
$value = mysqli_real_escape_string($connect, $_POST['value']);


$updatekSql = "UPDATE `inspections` SET `$attribute` = '$value' WHERE id = '$id'";
if ($connect->query($updatekSql) === true) {
    $valid['success'] = true;
    $valid['messages'] = "Successfully Added";
} else {
    $valid['success'] = false;
    $valid['messages'] = $connect->error;
    $valid['messages'] = mysqli_error($connect);
}


$connect->close();
echo json_encode($valid);
