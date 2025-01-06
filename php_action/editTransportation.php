<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    // Start transaction
    $connect->begin_transaction();

    $id = $_POST['transId'];

    try {
        $stockId = (isset($_POST['estockId'])) ? mysqli_real_escape_string($connect, $_POST['estockId']) : "";
        $status = (isset($_POST['estatus'])) ? mysqli_real_escape_string($connect, $_POST['estatus']) : "";
        $notes = (isset($_POST['enotes'])) ? mysqli_real_escape_string($connect, $_POST['enotes']) : "";

        $deletedRows = (isset($_POST['deletedRows'])) ? $_POST['deletedRows'] : "";


        $sql = "UPDATE `transportation` SET `stock_id` = '$stockId', 
            `notes`='$notes', `transport_status`='$status', `status`= '1' 
            WHERE `id`='$id' ";

        if (!$connect->query($sql)) {
            throw new Exception("Error inserting into transportation: " . $connect->error);
        }


        for ($x = 0; $x < count($_POST['edamageType']); $x++) {
            $erowId = (isset($_POST['erowId'][$x])) ? mysqli_real_escape_string($connect, $_POST['erowId'][$x]) : "";

            $locNum = (isset($_POST['elocNum'][$x])) ? mysqli_real_escape_string($connect, $_POST['elocNum'][$x]) : "";
            $damageType = (isset($_POST['edamageType'][$x])) ? mysqli_real_escape_string($connect, $_POST['edamageType'][$x]) : "";
            $damageSeverity = (isset($_POST['edamageSeverity'][$x])) ? mysqli_real_escape_string($connect, $_POST['edamageSeverity'][$x]) : "";
            $damageGrid = (isset($_POST['edamageGrid'][$x])) ? mysqli_real_escape_string($connect, $_POST['edamageGrid'][$x]) : "";

            if (!empty($erowId)) {
                // Update if erowId exists
                $sql2 = "UPDATE `transportation_damages` 
                 SET `loc_num` = '$locNum', 
                     `damage_type` = '$damageType', 
                     `damage_severity` = '$damageSeverity', 
                     `damage_grid` = '$damageGrid', 
                     `status` = 1 
                 WHERE `id` = '$erowId'";
            } else {
                // Insert if erowId does not exist
                $sql2 = "INSERT INTO `transportation_damages`(`transportation_id`, `loc_num`, `damage_type`, `damage_severity`, `damage_grid`, `status`) 
                 VALUES ('$id', '$locNum', '$damageType', '$damageSeverity', '$damageGrid', 1)";
            }

            if (!$connect->query($sql2)) {
                throw new Exception("Error inserting into transportation_damages: " . $connect->error);
            }
        }


        if (isset($_POST['deletedRows']) && !empty($_POST['deletedRows'])) {
            $deletedRowsRaw = $_POST['deletedRows'];

            // Normalize the input
            if (is_string($deletedRowsRaw)) {
                // Remove unwanted characters (e.g., brackets and quotes)
                $cleanedString = trim($deletedRowsRaw, '[]');
                $cleanedString = str_replace('"', '', $cleanedString);
                $deletedRowIds = explode(',', $cleanedString);
            } elseif (is_array($deletedRowsRaw)) {
                $deletedRowIds = $deletedRowsRaw; // Use directly if it's already an array
            } else {
                $deletedRowIds = []; // Fallback to an empty array
            }


            // Ensure all IDs are valid integers
            $deletedRowIds = array_filter($deletedRowIds, function ($id) {
                return is_numeric($id) && intval($id) > 0;
            });

            $deletedRowIds = array_map('intval', $deletedRowIds);

            // Proceed if we have valid IDs
            if (!empty($deletedRowIds)) {
                $deletedRowIdsList = implode("','", $deletedRowIds);
                // Update query to mark these rows as deleted
                $sqlDelete = "UPDATE transportation_damages 
                      SET status = 2 
                      WHERE `id` IN ('$deletedRowIdsList')";

                if (!$connect->query($sqlDelete)) {
                    throw new Exception("Error updating rows in transportation_damages: " . $connect->error);
                }
            }
        }


        // Commit transaction if all queries are successful
        $connect->commit();
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Updated";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $connect->rollback();
        $valid['success'] = false;
        $valid['messages'][] = $e->getMessage();
    }


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);