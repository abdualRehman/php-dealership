<?php

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array());


function convertToNumeric($value)
{
    $value = str_replace(",", "", $value); // remove commas
    $value = preg_replace("/[^0-9\.\-]/", "", $value); // remove all characters except digits, decimal point, and minus sign
    if ($value == "") {
        return 0; // if empty, return 0
    }
    $negative = false;
    if (substr($value, 0, 1) == "-") {
        $negative = true; // check if value is negative
    }
    $value = preg_replace("/[^0-9\.]/", "", $value); // remove all non-numeric characters except decimal point
    $value = floatval($value); // convert to float
    if ($negative) {
        $value = -$value; // add negative sign back
    }
    return round($value, 2); // round to 2 decimal places
}

if ($_POST) {

    $invId = $_POST['invId'];

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
    $balance = convertToNumeric($balance);
    $retail = mysqli_real_escape_string($connect, $_POST['retail']);
    $stockType = mysqli_real_escape_string($connect, $_POST['stockType']);

    $certified = "off";
    if (isset($_POST['certified'])) {
        $certified = mysqli_real_escape_string($connect, $_POST['certified']);
    }
    $wholesale = "off";
    if (isset($_POST['wholesale'])) {
        $wholesale = mysqli_real_escape_string($connect, $_POST['wholesale']);
    }


    $sql = "UPDATE `inventory` SET 
    `stockno`='$stockno', `year`='$year', `make`='$make',
    `model`='$model',`modelno`='$modelno',`color`='$color',`lot`='$lot',`vin`='$vin',`mileage`='$mileage',`age`='$age',
    `balance`='$balance',`retail`='$retail',`certified`='$certified',`stocktype`='$stockType',`wholesale`='$wholesale' WHERE id = '$invId'";

    if ($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }

    $connect->close();

    echo json_encode($valid);
} // /if $_POST