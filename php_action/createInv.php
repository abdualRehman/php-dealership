<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {

    $stockno = mysqli_real_escape_string($connect, $_POST['stockno']);
    $year = mysqli_real_escape_string($connect, $_POST['year']);
    $make = mysqli_real_escape_string($connect, $_POST['make']);
    $model = mysqli_real_escape_string($connect, $_POST['model']);
    $modelno = mysqli_real_escape_string($connect, $_POST['modelno']);
    $color = mysqli_real_escape_string($connect, $_POST['color']);
    $lot = mysqli_real_escape_string($connect, $_POST['lot']);
    $vin = mysqli_real_escape_string($connect, $_POST['vin']);
    $mileage = mysqli_real_escape_string($connect, $_POST['mileage']);
    $age = mysqli_real_escape_string($connect, $_POST['age']);
    $balance = mysqli_real_escape_string($connect, $_POST['balance']);
    $retail = mysqli_real_escape_string($connect, $_POST['retail']);
    $stockType = mysqli_real_escape_string($connect, $_POST['stockType']);
    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';


    $certified = "off";
    if (isset($_POST['certified'])) {
        $certified = mysqli_real_escape_string($connect, $_POST['certified']);
    }
    $wholesale = "off";
    if (isset($_POST['wholesale'])) {
        $wholesale = mysqli_real_escape_string($connect, $_POST['wholesale']);
    }

    // $checkSql = "SELECT id, stockno FROM inventory WHERE stockno LIKE ('$stockno'_%')";
    $checkSql = "SELECT * FROM `inventory` WHERE `stockno` = '$stockno' AND location = '$location'";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        // update Inv data if this stock number already exist with deleted id with sale 
        $updatekSql = "UPDATE `inventory` SET `year`='$year', `make`='$make',
        `model`='$model',`modelno`='$modelno',`color`='$color',`lot`='$lot',`vin`='$vin',`mileage`='$mileage',`age`='$age',
        `balance`='$balance',`retail`='$retail',`certified`='$certified',`stocktype`='$stockType',`wholesale`='$wholesale' , `status`=1 WHERE stockno = '$stockno' AND location = '$location'";

        if ($connect->query($updatekSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $sql = "INSERT INTO `inventory`(`stockno`, `year`, `make`, `model`, `modelno`, `color`, `lot`, `vin`, `mileage`, `age`, `balance`, `retail`, `certified`, `stocktype`, `wholesale`,  `status` , `location`) 
        VALUES (
            '$stockno',
            '$year',
            '$make',
            '$model',
            '$modelno',
            '$color',
            '$lot',
            '$vin',
            '$mileage',
            '$age',
            '$balance',
            '$retail',
            '$certified',
            '$stockType',
            '$wholesale',
            1,
            '$location' )";

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