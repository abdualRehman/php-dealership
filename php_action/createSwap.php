<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');


if ($_POST) {

    $submitted_by = $_SESSION['userId'];

    $sales_consultant = mysqli_real_escape_string($connect, $_POST['salesPerson']);

    $fromDealer = mysqli_real_escape_string($connect, $_POST['fromDealer']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);

    $stockIn = mysqli_real_escape_string($connect, $_POST['stockIn']);  //stockIn
    $vehicleIn = mysqli_real_escape_string($connect, $_POST['vehicleIn']);
    $colorIn = mysqli_real_escape_string($connect, $_POST['colorIn']);

    $invReceived = isset($_POST['invReceived']) ? mysqli_real_escape_string($connect, $_POST['invReceived']) : "off";
    $transferredIn = isset($_POST['transferredIn']) ? mysqli_real_escape_string($connect, $_POST['transferredIn']) : "off";

    $vinIn = isset($_POST['vinIn']) ? mysqli_real_escape_string($connect, $_POST['vinIn']) : "";
    $invIn = isset($_POST['invIn']) ? mysqli_real_escape_string($connect, $_POST['invIn']) : "";
    $hbIn = isset($_POST['hbIn']) ? mysqli_real_escape_string($connect, $_POST['hbIn']) : "";
    $msrpIn = isset($_POST['msrpIn']) ? mysqli_real_escape_string($connect, $_POST['msrpIn']) : "";
    $hdagIn = isset($_POST['hdagIn']) ? mysqli_real_escape_string($connect, $_POST['hdagIn']) : "";
    $addsIn = isset($_POST['addsIn']) ? mysqli_real_escape_string($connect, $_POST['addsIn']) : "";
    $addsInNotes = isset($_POST['addsInNotes']) ? mysqli_real_escape_string($connect, $_POST['addsInNotes']) : "";
    $hbtIn = isset($_POST['hbtIn']) ? mysqli_real_escape_string($connect, $_POST['hbtIn']) : "";
    $netcostIn = isset($_POST['netcostIn']) ? mysqli_real_escape_string($connect, $_POST['netcostIn']) : "";

    $stockOut = mysqli_real_escape_string($connect, $_POST['stockOut']);  //stockIn
    $vehicleOut = mysqli_real_escape_string($connect, $_POST['vehicleOut']);
    $colorOut = mysqli_real_escape_string($connect, $_POST['colorOut']);

    $invSent = isset($_POST['invSent']) ? mysqli_real_escape_string($connect, $_POST['invSent']) : "off";
    $transferredOut = isset($_POST['transferredOut']) ? mysqli_real_escape_string($connect, $_POST['transferredOut']) : "off";

    $tagged = isset($_POST['tagged']) ? mysqli_real_escape_string($connect, $_POST['tagged']) : "off";

    $vinOut = isset($_POST['vinOut']) ? mysqli_real_escape_string($connect, $_POST['vinOut']) : "";
    $invOut = isset($_POST['invOut']) ? mysqli_real_escape_string($connect, $_POST['invOut']) : "";
    $hbOut = isset($_POST['hbOut']) ? mysqli_real_escape_string($connect, $_POST['hbOut']) : "";
    $msrpOut = isset($_POST['msrpOut']) ? mysqli_real_escape_string($connect, $_POST['msrpOut']) : "";
    $hdagOut = isset($_POST['hdagOut']) ? mysqli_real_escape_string($connect, $_POST['hdagOut']) : "";
    $addsOut = isset($_POST['addsOut']) ? mysqli_real_escape_string($connect, $_POST['addsOut']) : "";
    $addsOutNotes = isset($_POST['addsOutNotes']) ? mysqli_real_escape_string($connect, $_POST['addsOutNotes']) : "";
    $hbtOut = isset($_POST['hbtOut']) ? mysqli_real_escape_string($connect, $_POST['hbtOut']) : "";
    $netcostOut = isset($_POST['netcostOut']) ? mysqli_real_escape_string($connect, $_POST['netcostOut']) : "";

    $dealNote = isset($_POST['dealNote']) ? mysqli_real_escape_string($connect, $_POST['dealNote']) : "";


    $sql = "INSERT INTO `swaps`(
        `from_dealer`, `swap_status`, 
        `stock_in`, `vehicle_in`, `color_in`, `inv_received`, `transferred_in`, `vin_in`, `inv_in`, `hb_in`, `msrp_in`, `hdag_in`, `adds_in`, `adds_in_notes`, `hbt_in`, `net_cost_in`, 
        `stock_out`, `vehicle_out`, `color_out`, `inv_sent`, `transferred_out`, `vin_out`, `inv_out`, `hb_out`, `msrp_out`, `hdag_out`, `adds_out`, `adds_out_notes`, `hbt_out`, `net_cost_out`, `notes` , `sales_consultant` , `tagged` , `submitted_by`, `status`) 
    VALUES ('$fromDealer' , '$status' , 
        '$stockIn', '$vehicleIn' , '$colorIn' , '$invReceived' , '$transferredIn' , '$vinIn' , '$invIn' , '$hbIn' , '$msrpIn' , '$hdagIn' , '$addsIn' , '$addsInNotes' , '$hbtIn' , '$netcostIn',
        '$stockOut' , '$vehicleOut' , '$colorOut' , '$invSent' , '$transferredOut' , '$vinOut' , '$invOut' , '$hbOut' , '$msrpOut' , '$hdagOut' , '$addsOut' , '$addsOutNotes' , '$hbtOut' , '$netcostOut' , '$dealNote' , '$sales_consultant' , '$tagged' , '$submitted_by' , 1 )";

    if ($connect->query($sql) === true) {
        $valid['id'] = $connect->insert_id;
        $valid['success'] = true;
        $valid['messages'] = "Successfully Created";
    } else {
        $valid['success'] = false;
        $valid['id'] = null;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }



    // echo $fromDealer ."<br />";
    // echo $status ."<br />";
    // echo $stockIn ."<br />";
    // echo $vehicleIn ."<br />";
    // echo $colorIn ."<br />";
    // echo $invReceived ."<br />";
    // echo $transferredIn ."<br />";
    // echo "<hr />";

    // echo $vinIn ."<br />";
    // echo $invIn ."<br />";
    // echo $hbIn ."<br />";
    // echo $msrpIn ."<br />";
    // echo $hdagIn ."<br />";
    // echo $addsIn ."<br />";
    // echo $addsInNotes ."<br />";
    // echo $hbtIn ."<br />";
    // echo $netcostIn ."<br />";
    // echo "<hr />";

    // echo $stockOut ."<br />";
    // echo $vehicleOut ."<br />";
    // echo $colorOut ."<br />";
    // echo $invSent ."<br />";
    // echo $transferredOut ."<br />";
    // echo "<hr />";

    // echo $vinOut ."<br />";
    // echo $invOut ."<br />";
    // echo $hbOut ."<br />";
    // echo $msrpOut ."<br />";
    // echo $hdagOut ."<br />";
    // echo $addsOut ."<br />";
    // echo $addsOutNotes ."<br />";
    // echo $hbtOut ."<br />";
    // echo $netcostOut ."<br />";

    // echo $dealNote ."<br />";





    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);