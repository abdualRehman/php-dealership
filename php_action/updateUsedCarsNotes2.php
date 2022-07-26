<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $submittedBy = $_SESSION['userId'];

    $vehicleId = (isset($_POST['vehicleId'])) ? mysqli_real_escape_string($connect, $_POST['vehicleId']) : "";


    $notes_1 = (isset($_POST['notes_1'])) ? mysqli_real_escape_string($connect, $_POST['notes_1']) : "";
    $notes_2 = (isset($_POST['notes_2'])) ? mysqli_real_escape_string($connect, $_POST['notes_2']) : "";
    $uci = (isset($_POST['uci'])) ? mysqli_real_escape_string($connect, $_POST['uci']) : "";




    $checkSql = "SELECT * FROM `used_cars` WHERE inv_id = '$vehicleId' AND status = 1";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        // update Inv data if this stock number already exist with deleted id with sale 
        $updatekSql = "UPDATE `used_cars` SET `uci`='$uci', `notes_1`='$notes_1' ,
        `notes_2`='$notes_2'  WHERE inv_id = '$vehicleId'";

        if ($connect->query($updatekSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $sql = "INSERT INTO `used_cars`(
            `inv_id`, `uci` ,`notes_1`, 
            `notes_2`, `status`) VALUES (
                '$vehicleId', '$uci' , '$notes_1',
                '$notes_2' , 1 )";

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
} // /if $_POST
// echo json_encode($valid);