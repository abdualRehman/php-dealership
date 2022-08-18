<?php
require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $dealershipId = $_POST['dealershipId'];


    $brand = (isset($_POST['ebrand'])) ? mysqli_real_escape_string($connect, $_POST['ebrand']) : "";
    $dealership = (isset($_POST['edealership'])) ? mysqli_real_escape_string($connect, $_POST['edealership']) : "";
    $address = (isset($_POST['eaddress'])) ? mysqli_real_escape_string($connect, $_POST['eaddress']) : "";
    $city = (isset($_POST['ecity'])) ? mysqli_real_escape_string($connect, $_POST['ecity']) : "";
    $state = (isset($_POST['estate'])) ? mysqli_real_escape_string($connect, $_POST['estate']) : "";
    $zip = (isset($_POST['ezip'])) ? mysqli_real_escape_string($connect, $_POST['ezip']) : "";
    $telephone = (isset($_POST['etelephone'])) ? mysqli_real_escape_string($connect, $_POST['etelephone']) : "";
    $fax = (isset($_POST['efax'])) ? mysqli_real_escape_string($connect, $_POST['efax']) : "";

    $egeneralManager = (isset($_POST['egeneralManager'])) ? mysqli_real_escape_string($connect, $_POST['egeneralManager']) : "";
    $egeneralManagerContact = (isset($_POST['egeneralManagerContact'])) ? mysqli_real_escape_string($connect, $_POST['egeneralManagerContact']) : "";
    $eusedcarManager = (isset($_POST['eusedcarManager'])) ? mysqli_real_escape_string($connect, $_POST['eusedcarManager']) : "";
    $eusedcarManagerContact = (isset($_POST['eusedcarManagerContact'])) ? mysqli_real_escape_string($connect, $_POST['eusedcarManagerContact']) : "";


    $sql = "UPDATE `dealerships` SET 
    `brand`='$brand',`dealership`='$dealership',`address`='$address',
    `city`='$city',`state`='$state',`zip`='$zip',`telephone`='$telephone',
    `fax`='$fax',`gmanager`='$egeneralManager',`gmanager_contact`='$egeneralManagerContact',
    `usedcmanager`='$eusedcarManager',`usedcmanager_contact`='$eusedcarManagerContact' 
    WHERE id = '$dealershipId'";

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