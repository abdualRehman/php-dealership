<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $id = $_POST['swapId'];


    $sales_consultant = isset($_POST['esalesPerson']) ? mysqli_real_escape_string($connect, $_POST['esalesPerson']) : '0';
    $fromDealer =  isset($_POST['efromDealer']) ?  mysqli_real_escape_string($connect, $_POST['efromDealer']) : '';
    $status = mysqli_real_escape_string($connect, $_POST['estatus']);

    $stockIn = mysqli_real_escape_string($connect, $_POST['estockIn']);  //stockIn
    $vehicleIn = mysqli_real_escape_string($connect, $_POST['evehicleIn']);
    $colorIn = mysqli_real_escape_string($connect, $_POST['ecolorIn']);

    $invReceived = isset($_POST['einvReceived']) ? mysqli_real_escape_string($connect, $_POST['einvReceived']) : "off";
    $transferredIn = isset($_POST['etransferredIn']) ? mysqli_real_escape_string($connect, $_POST['etransferredIn']) : "off";

    $vinIn = isset($_POST['evinIn']) ? mysqli_real_escape_string($connect, $_POST['evinIn']) : "";
    $invIn = isset($_POST['einvIn']) ? mysqli_real_escape_string($connect, $_POST['einvIn']) : "";
    $hbIn = isset($_POST['ehbIn']) ? mysqli_real_escape_string($connect, $_POST['ehbIn']) : "";
    $msrpIn = isset($_POST['emsrpIn']) ? mysqli_real_escape_string($connect, $_POST['emsrpIn']) : "";
    $hdagIn = isset($_POST['ehdagIn']) ? mysqli_real_escape_string($connect, $_POST['ehdagIn']) : "";
    $addsIn = isset($_POST['eaddsIn']) ? mysqli_real_escape_string($connect, $_POST['eaddsIn']) : "";
    $addsInNotes = isset($_POST['eaddsInNotes']) ? mysqli_real_escape_string($connect, $_POST['eaddsInNotes']) : "";
    $hbtIn = isset($_POST['ehbtIn']) ? mysqli_real_escape_string($connect, $_POST['ehbtIn']) : "";
    $netcostIn = isset($_POST['enetcostIn']) ? mysqli_real_escape_string($connect, $_POST['enetcostIn']) : "";

    $stockOut = mysqli_real_escape_string($connect, $_POST['estockOut']);  //stockIn
    $vehicleOut = mysqli_real_escape_string($connect, $_POST['evehicleOut']);
    $colorOut = mysqli_real_escape_string($connect, $_POST['ecolorOut']);

    $invSent = isset($_POST['einvSent']) ? mysqli_real_escape_string($connect, $_POST['einvSent']) : "off";
    $transferredOut = isset($_POST['etransferredOut']) ? mysqli_real_escape_string($connect, $_POST['etransferredOut']) : "off";
    $tagged = isset($_POST['etagged']) ? mysqli_real_escape_string($connect, $_POST['etagged']) : "off";

    $vinOut = isset($_POST['evinOut']) ? mysqli_real_escape_string($connect, $_POST['evinOut']) : "";
    $invOut = isset($_POST['einvOut']) ? mysqli_real_escape_string($connect, $_POST['einvOut']) : "";
    $hbOut = isset($_POST['ehbOut']) ? mysqli_real_escape_string($connect, $_POST['ehbOut']) : "";
    $msrpOut = isset($_POST['emsrpOut']) ? mysqli_real_escape_string($connect, $_POST['emsrpOut']) : "";
    $hdagOut = isset($_POST['ehdagOut']) ? mysqli_real_escape_string($connect, $_POST['ehdagOut']) : "";
    $addsOut = isset($_POST['eaddsOut']) ? mysqli_real_escape_string($connect, $_POST['eaddsOut']) : "";
    $addsOutNotes = isset($_POST['eaddsOutNotes']) ? mysqli_real_escape_string($connect, $_POST['eaddsOutNotes']) : "";
    $hbtOut = isset($_POST['ehbtOut']) ? mysqli_real_escape_string($connect, $_POST['ehbtOut']) : "";
    $netcostOut = isset($_POST['enetcostOut']) ? mysqli_real_escape_string($connect, $_POST['enetcostOut']) : "";

    $dealNote = isset($_POST['edealNote']) ? mysqli_real_escape_string($connect, $_POST['edealNote']) : "";



    $sql = "UPDATE `swaps` SET 
    `from_dealer`='$fromDealer', `swap_status`='$status',
    `stock_in`='$stockIn', `vehicle_in`='$vehicleIn', `color_in`='$colorIn',
    `inv_received`='$invReceived', `transferred_in`='$transferredIn',
    `vin_in`='$vinIn', `inv_in`='$invIn',`hb_in`='$hbIn',`msrp_in`='$msrpIn',`hdag_in`='$hdagIn',`adds_in`='$addsIn',`adds_in_notes`='$addsInNotes',`hbt_in`='$hbtIn',`net_cost_in`='$netcostIn',
    `stock_out`='$stockOut',`vehicle_out`='$vehicleOut',`color_out`='$colorOut',
    `inv_sent`='$invSent',`transferred_out`='$transferredOut',
    `vin_out`='$vinOut',`inv_out`='$invOut',`hb_out`='$hbOut',`msrp_out`='$msrpOut',`hdag_out`='$hdagOut',`adds_out`='$addsOut',`adds_out_notes`='$addsOutNotes',`hbt_out`='$hbtOut',`net_cost_out`='$netcostOut',
    `notes`='$dealNote' , `sales_consultant` = '$sales_consultant', `tagged` = '$tagged' WHERE id = '$id'";

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