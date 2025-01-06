<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {
    // Start transaction
    $connect->begin_transaction();

    try {
        $stockId = (isset($_POST['stockId'])) ? mysqli_real_escape_string($connect, $_POST['stockId']) : "";
        $status = (isset($_POST['status'])) ? mysqli_real_escape_string($connect, $_POST['status']) : "";
        $notes = (isset($_POST['notes'])) ? mysqli_real_escape_string($connect, $_POST['notes']) : "";
        $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

        // Insert into `transportation`
        $sql = "INSERT INTO `transportation`(`stock_id`, `notes` , `transport_status`, `status`, `location`) 
        VALUES ('$stockId', '$notes' ,'$status', 1, '$location')";

        if (!$connect->query($sql)) {
            throw new Exception("Error inserting into transportation: " . $connect->error);
        }

        // Get the last inserted transportation_id
        $transportation_id = $connect->insert_id;

        // Insert into `transportation_damages`
        for ($x = 0; $x < count($_POST['damageType']); $x++) {

            $locNum = (isset($_POST['locNum'][$x])) ? mysqli_real_escape_string($connect, $_POST['locNum'][$x]) : "";
            $damageType = (isset($_POST['damageType'][$x])) ? mysqli_real_escape_string($connect, $_POST['damageType'][$x]) : "";
            $damageSeverity = (isset($_POST['damageSeverity'][$x])) ? mysqli_real_escape_string($connect, $_POST['damageSeverity'][$x]) : "";
            $damageGrid = (isset($_POST['damageGrid'][$x])) ? mysqli_real_escape_string($connect, $_POST['damageGrid'][$x]) : "";

            $sql2 = "INSERT INTO `transportation_damages`(`transportation_id`, `loc_num`, `damage_type`, `damage_severity`, `damage_grid`, `status`) 
            VALUES ('$transportation_id', '$locNum', '$damageType', '$damageSeverity', '$damageGrid', 1)";

            if (!$connect->query($sql2)) {
                throw new Exception("Error inserting into transportation_damages: " . $connect->error);
            }
        }

        // Commit transaction if all queries are successful
        $connect->commit();
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Added";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $connect->rollback();
        $valid['success'] = false;
        $valid['messages'][] = $e->getMessage();
    }

    $connect->close();

    echo json_encode($valid);
}
