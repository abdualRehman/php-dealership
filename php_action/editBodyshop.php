<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $shopId = $_POST['shopId'];


    $bName = (isset($_POST['ebName'])) ? mysqli_real_escape_string($connect, $_POST['ebName']) : "";
    $shop = (isset($_POST['eshop'])) ? mysqli_real_escape_string($connect, $_POST['eshop']) : "";
    $address = (isset($_POST['eaddress'])) ? mysqli_real_escape_string($connect, $_POST['eaddress']) : "";
    $city = (isset($_POST['ecity'])) ? mysqli_real_escape_string($connect, $_POST['ecity']) : "";
    $state = (isset($_POST['estate'])) ? mysqli_real_escape_string($connect, $_POST['estate']) : "";
    $zip = (isset($_POST['ezip'])) ? mysqli_real_escape_string($connect, $_POST['ezip']) : "";
    $contatperson = (isset($_POST['econtatperson'])) ? mysqli_real_escape_string($connect, $_POST['econtatperson']) : "";
    $contatnumber = (isset($_POST['econtatnumber'])) ? mysqli_real_escape_string($connect, $_POST['econtatnumber']) : "";


    $sql = "UPDATE `bodyshops` SET 
    `business_name`='$bName',`shop`='$shop',`address`='$address',`city`='$city',`state`='$state',`zip`='$zip',`contact_person`='$contatperson',`contact_number`='$contatnumber'
    WHERE id = '$shopId'";

    if ($connect->query($sql) === true) {



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
// echo json_encode($valid);