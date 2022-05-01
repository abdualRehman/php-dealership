<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {


    $bName = (isset($_POST['bName'])) ? mysqli_real_escape_string($connect, $_POST['bName']) : "";
    $shop = (isset($_POST['shop'])) ? mysqli_real_escape_string($connect, $_POST['shop']) : "";
    $address = (isset($_POST['address'])) ? mysqli_real_escape_string($connect, $_POST['address']) : "";
    $city = (isset($_POST['city'])) ? mysqli_real_escape_string($connect, $_POST['city']) : "";
    $state = (isset($_POST['state'])) ? mysqli_real_escape_string($connect, $_POST['state']) : "";
    $zip = (isset($_POST['zip'])) ? mysqli_real_escape_string($connect, $_POST['zip']) : "";
    $contatperson = (isset($_POST['contatperson'])) ? mysqli_real_escape_string($connect, $_POST['contatperson']) : "";
    $contatnumber = (isset($_POST['contatnumber'])) ? mysqli_real_escape_string($connect, $_POST['contatnumber']) : "";



    $sql = "INSERT INTO `bodyshops`(`business_name`, `shop`, `address`, `city`, `state`, `zip`, `contact_person`, `contact_number`, `status`) VALUES (
                '$bName',
                '$shop',
                '$address',
                '$city',
                '$state',
                '$zip',
                '$contatperson',
                '$contatnumber',
                1 )";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'][] = $connect->error;
        // $valid['messages'] = mysqli_error($connect);
    }


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);