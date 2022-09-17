<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $submittedBy = $_SESSION['userId'];

    $vehicleId = (isset($_POST['evehicleId'])) ? mysqli_real_escape_string($connect, $_POST['evehicleId']) : "";
    $workNeeded = (isset($_POST['workNeeded'])) ? mysqli_real_escape_string($connect, $_POST['workNeeded']) : "";
    $notes = (isset($_POST['notes'])) ? mysqli_real_escape_string($connect, $_POST['notes']) : "";
    $dateSent = (isset($_POST['dateSent'])) ? mysqli_real_escape_string($connect, $_POST['dateSent']) : "";
    $dateReturn = (isset($_POST['dateReturn'])) ? mysqli_real_escape_string($connect, $_POST['dateReturn']) : "";

    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    $checkSql = "SELECT * FROM `car_to_dealers` WHERE inv_id = '$vehicleId' AND status = 1 AND location = '$location'";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        // update Inv data if this stock number already exist with deleted id with sale 
        $updatekSql = "UPDATE `car_to_dealers` 
        SET `work_needed`='$workNeeded', `notes`='$notes', `date_sent`='$dateSent', `date_returned`='$dateReturn', `submitted_by`='$submittedBy' WHERE inv_id = '$vehicleId'";

        if ($connect->query($updatekSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $sql = "INSERT INTO `car_to_dealers`( `inv_id`, `work_needed`, `notes`, `date_sent`, `date_returned`, `submitted_by`, `status` , `location`)
        VALUES (
            '$vehicleId',
            '$workNeeded',
            '$notes',
            '$dateSent',
            '$dateReturn',
            '$submittedBy', 1 , '$location' )";

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