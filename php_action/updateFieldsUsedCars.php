<?php

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());



$submittedBy = $_SESSION['userId'];
// code to be executed;
$id = mysqli_real_escape_string($connect, $_POST['id']);
$attribute = mysqli_real_escape_string($connect, $_POST['attribute']);
$value = mysqli_real_escape_string($connect, $_POST['value']);


$checkSql = "SELECT * FROM `used_cars` WHERE inv_id = '$id' AND status = 1";
$result = $connect->query($checkSql);
if ($result->num_rows > 0) {

    $updatekSql = "UPDATE `used_cars` SET `$attribute` = '$value' WHERE inv_id = '$id'";
    if ($connect->query($updatekSql) === true) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }
} else {
    $sql = "INSERT INTO `used_cars`( `inv_id`,  `$attribute`, `submitted_by`, `status`) VALUES (
                    '$id' , '$value', '$submittedBy' , 1 )";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }
}


$connect->close();
echo json_encode($valid);
