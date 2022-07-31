<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $leadId = $_POST['leadId'];


    $leadDate = (isset($_POST['eleadDate'])) ? mysqli_real_escape_string($connect, $_POST['eleadDate']) : "";
    $entityId = (isset($_POST['eentityId'])) ? mysqli_real_escape_string($connect, $_POST['eentityId']) : "";
    $fname = (isset($_POST['efname'])) ? mysqli_real_escape_string($connect, $_POST['efname']) : "";
    $lname = (isset($_POST['elname'])) ? mysqli_real_escape_string($connect, $_POST['elname']) : "";
    $salesConsultant = (isset($_POST['esalesConsultant'])) ? mysqli_real_escape_string($connect, $_POST['esalesConsultant']) : "";
    $vehicle = (isset($_POST['evehicle'])) ? mysqli_real_escape_string($connect, $_POST['evehicle']) : "";
    $leadType = (isset($_POST['eleadType'])) ? mysqli_real_escape_string($connect, $_POST['eleadType']) : "";
    $leadStatus = (isset($_POST['eleadStatus'])) ? mysqli_real_escape_string($connect, $_POST['eleadStatus']) : "";
    $source = (isset($_POST['esource'])) ? mysqli_real_escape_string($connect, $_POST['esource']) : "";
    $leadNotes = (isset($_POST['eleadNotes'])) ? mysqli_real_escape_string($connect, $_POST['eleadNotes']) : "";
    $varifiedStatus = (isset($_POST['evarifiedStatus'])) ? mysqli_real_escape_string($connect, $_POST['evarifiedStatus']) : "";
    $approvedBy = (isset($_POST['eapprovedBy'])) ? mysqli_real_escape_string($connect, $_POST['eapprovedBy']) : "";





    $sql = "UPDATE `bdc_lead` SET 
    `date`='$leadDate',`lname`='$lname',`fname`='$fname',
    `entity`='$entityId',`vehicle`='$vehicle',`sales_consultant`='$salesConsultant',
    `lead_status`='$leadStatus',`lead_type`='$leadType',`source`='$source',`notes`='$leadNotes',
    `verified`='$varifiedStatus',`verified_by`='$approvedBy' WHERE id = '$leadId'";

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