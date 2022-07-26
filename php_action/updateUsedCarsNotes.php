<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $submittedBy = $_SESSION['userId'];

    $vehicleId = (isset($_POST['vehicleId'])) ? mysqli_real_escape_string($connect, $_POST['vehicleId']) : "";


    $titleNotes = (isset($_POST['titleNotes'])) ? mysqli_real_escape_string($connect, $_POST['titleNotes']) : "";
    $onlineDescription = (isset($_POST['onlineDescription'])) ? mysqli_real_escape_string($connect, $_POST['onlineDescription']) : "";
    $roNotes = (isset($_POST['roNotes'])) ? mysqli_real_escape_string($connect, $_POST['roNotes']) : "";

    $titlePriority = (isset($_POST['titlePriority'])) ? mysqli_real_escape_string($connect, $_POST['titlePriority']) : "";
    $salesConsultant = (isset($_POST['salesConsultant'])) ? mysqli_real_escape_string($connect, $_POST['salesConsultant']) : "";
    $customerName = (isset($_POST['customerName'])) ? mysqli_real_escape_string($connect, $_POST['customerName']) : "";


    // echo $salesConsultant;


    $checkSql = "SELECT * FROM `used_cars` WHERE inv_id = '$vehicleId' AND status = 1";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        // update Inv data if this stock number already exist with deleted id with sale 
        $updatekSql = "UPDATE `used_cars` SET `title_notes`='$titleNotes', `online_description`='$onlineDescription' ,
        `ro_online_notes`='$roNotes' , `title_priority`='$titlePriority' , `sales_consultant`='$salesConsultant', `customer`='$customerName' ,`submitted_by`='$submittedBy' WHERE inv_id = '$vehicleId'";

        if ($connect->query($updatekSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $sql = "INSERT INTO `used_cars`(
            `inv_id`, `title_notes` ,`online_description`, 
            `ro_online_notes` , `title_priority` , `sales_consultant` , `customer` , `submitted_by`, `status`) VALUES (
                '$vehicleId', '$titleNotes' , '$onlineDescription',
                '$roNotes' , '$titlePriority' , '$salesConsultant' , '$customerName' , '$submittedBy' , 1 )";

        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }




    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);