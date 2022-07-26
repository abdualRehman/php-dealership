<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $id = $_POST['transId'];

    $stockId = (isset($_POST['estockId'])) ? mysqli_real_escape_string($connect, $_POST['estockId']) : "";
    $status = (isset($_POST['estatus'])) ? mysqli_real_escape_string($connect, $_POST['estatus']) : "";
    $locNum = (isset($_POST['elocNum'])) ? mysqli_real_escape_string($connect, $_POST['elocNum']) : "";
    $damageType = (isset($_POST['edamageType'])) ? mysqli_real_escape_string($connect, $_POST['edamageType']) : "";
    $damageSeverity = (isset($_POST['edamageSeverity'])) ? mysqli_real_escape_string($connect, $_POST['edamageSeverity']) : "";
    $damageGrid = (isset($_POST['edamageGrid'])) ? mysqli_real_escape_string($connect, $_POST['edamageGrid']) : "";


    $sql = "UPDATE `transportation` SET `stock_id` = '$stockId',
    `loc_num`='$locNum',`damage_type`='$damageType',
    `damage_severity`='$damageSeverity',`damage_grid`='$damageGrid',
    `transport_status`='$status',`status`= '1' WHERE `id`='$id' ";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'][] = $connect->error;
    }



    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);