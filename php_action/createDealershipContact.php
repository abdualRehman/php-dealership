<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $brand = (isset($_POST['brand'])) ? mysqli_real_escape_string($connect, $_POST['brand']) : "";
    $dealership = (isset($_POST['dealership'])) ? mysqli_real_escape_string($connect, $_POST['dealership']) : "";
    $address = (isset($_POST['address'])) ? mysqli_real_escape_string($connect, $_POST['address']) : "";
    $city = (isset($_POST['city'])) ? mysqli_real_escape_string($connect, $_POST['city']) : "";
    $state = (isset($_POST['state'])) ? mysqli_real_escape_string($connect, $_POST['state']) : "";
    $zip = (isset($_POST['zip'])) ? mysqli_real_escape_string($connect, $_POST['zip']) : "";
    $telephone = (isset($_POST['telephone'])) ? mysqli_real_escape_string($connect, $_POST['telephone']) : "";
    $fax = (isset($_POST['fax'])) ? mysqli_real_escape_string($connect, $_POST['fax']) : "";



    $sql = "INSERT INTO `dealerships`(`brand`, `dealership`, `address`, `city`, `state`, `zip`, `telephone`, `fax` , `status`) 
    VALUES ( '$brand' , '$dealership' , '$address' , '$city' , '$state' , '$zip' , '$telephone' , '$fax' ,1 )";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'][] = $connect->error;
    }


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);