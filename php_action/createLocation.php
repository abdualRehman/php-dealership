<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {

    $dealerno = mysqli_real_escape_string($connect, $_POST['dealerno']);
    $dealership = mysqli_real_escape_string($connect, $_POST['dealership']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $city = mysqli_real_escape_string($connect, $_POST['city']);
    $state = mysqli_real_escape_string($connect, $_POST['state']);
    $zip = mysqli_real_escape_string($connect, $_POST['zip']);
    $miles = mysqli_real_escape_string($connect, $_POST['miles']);
    $travelTime = mysqli_real_escape_string($connect, $_POST['travelTime']);
    $roundTrip = mysqli_real_escape_string($connect, $_POST['roundTrip']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $fax = mysqli_real_escape_string($connect, $_POST['fax']);
    $mcontact = mysqli_real_escape_string($connect, $_POST['mcontact']);
    $cell = mysqli_real_escape_string($connect, $_POST['cell']);

    $call = "";
    if (isset($_POST['call'])) {
        $call = "Call";
    }
    $text = "";
    if (isset($_POST['text'])) {
        $text = "Text";
    }

    if($call === 'Call' && $text === 'Text'){
        $preffer = "Both";
    }else{
        $preffer = $call . $text;
    }

    // echo $dealerno . '<br />';
    // echo $dealership . '<br />';
    // echo $address . '<br />';
    // echo $city . '<br />';
    // echo $state . '<br />';
    // echo $zip . '<br />';
    // echo $miles . '<br />';
    // echo $travelTime . '<br />';
    // echo $roundTrip . '<br />';
    // echo $phone . '<br />';
    // echo $fax . '<br />';
    // echo $mcontact . '<br />';
    // echo $cell . '<br />';
    // echo $preffer . '<br />';


    $sql = "INSERT INTO `locations`(`dealer_no`, `dealership`, `address`, `city`, `state`, `zip`, `miles`, `travel_time`, `round_trip`, `phone`, `fax`, `main_contact`, `cell`, `preffer`, `status`)
    VALUES (
        '$dealerno',
        '$dealership',
        '$address',
        '$city',
        '$state',
        '$zip',
        '$miles',
        '$travelTime',
        '$roundTrip',
        '$phone',
        '$fax',
        '$mcontact',
        '$cell',
        '$preffer',
        1 )";

  

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }




    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);