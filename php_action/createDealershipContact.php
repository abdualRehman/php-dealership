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

    $generalManager = (isset($_POST['generalManager'])) ? mysqli_real_escape_string($connect, $_POST['generalManager']) : "";
    $generalManagerContact = (isset($_POST['generalManagerContact'])) ? mysqli_real_escape_string($connect, $_POST['generalManagerContact']) : "";
    $usedcarManager = (isset($_POST['usedcarManager'])) ? mysqli_real_escape_string($connect, $_POST['usedcarManager']) : "";
    $usedcarManagerContact = (isset($_POST['usedcarManagerContact'])) ? mysqli_real_escape_string($connect, $_POST['usedcarManagerContact']) : "";

    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';


    $sql = "INSERT INTO `dealerships`(`brand`, `dealership`, `address`, `city`, `state`, `zip`, `telephone`, `fax`, `gmanager` , `gmanager_contact` , `usedcmanager` , `usedcmanager_contact` , `status` , `location`) 
    VALUES ( '$brand' , '$dealership' , '$address' , '$city' , '$state' , '$zip' , '$telephone' , '$fax' , '$generalManager' , '$generalManagerContact' , '$usedcarManager' , '$usedcarManagerContact' , 1 , '$location' )";

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