<?php

require_once './db/core.php';
$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {


    // $submittedBy = $_SESSION['userId'];
    $submittedBy = (isset($_POST['submittedById'])) ? mysqli_real_escape_string($connect, $_POST['submittedById']) : "";
    $leadDate = (isset($_POST['leadDate'])) ? mysqli_real_escape_string($connect, $_POST['leadDate']) : "";
    $entityId = (isset($_POST['entityId'])) ? mysqli_real_escape_string($connect, $_POST['entityId']) : "";
    $fname = (isset($_POST['fname'])) ? mysqli_real_escape_string($connect, $_POST['fname']) : "";
    $lname = (isset($_POST['lname'])) ? mysqli_real_escape_string($connect, $_POST['lname']) : "";
    $salesConsultant = (isset($_POST['salesConsultant'])) ? mysqli_real_escape_string($connect, $_POST['salesConsultant']) : "";
    $vehicle = (isset($_POST['vehicle'])) ? mysqli_real_escape_string($connect, $_POST['vehicle']) : "";
    $leadType = (isset($_POST['leadType'])) ? mysqli_real_escape_string($connect, $_POST['leadType']) : "";
    $leadStatus = (isset($_POST['leadStatus'])) ? mysqli_real_escape_string($connect, $_POST['leadStatus']) : "";
    $source = (isset($_POST['source'])) ? mysqli_real_escape_string($connect, $_POST['source']) : "";
    $leadNotes = (isset($_POST['leadNotes'])) ? mysqli_real_escape_string($connect, $_POST['leadNotes']) : "";
    $varifiedStatus = (isset($_POST['varifiedStatus'])) ? mysqli_real_escape_string($connect, $_POST['varifiedStatus']) : "";
    $approvedBy = (isset($_POST['approvedBy'])) ? mysqli_real_escape_string($connect, $_POST['approvedBy']) : "";    

    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    
    $sql = "INSERT INTO `bdc_lead`(
        `date`, `ccs`, `lname`, `fname`, 
        `entity`, `vehicle`, `sales_consultant`, `lead_status`, 
        `lead_type`, `source`, `notes`, `verified`, `verified_by` , `status` , `location` ) 
    VALUES (
        '$leadDate' , '$submittedBy',  '$lname' , '$fname' , 
        '$entityId' , '$vehicle' , '$salesConsultant' , '$leadStatus',
        '$leadType' , '$source' , '$leadNotes' , '$varifiedStatus' , '$approvedBy' , 1 , '$location'
    )";

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