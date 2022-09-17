<?php

require_once './db/core.php';


$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $ruleId = $_POST['ruleId'];


    $eageFrom = (isset($_POST['eageFrom'])) ? mysqli_real_escape_string($connect, $_POST['eageFrom']) : "";
    $eageTo = (isset($_POST['eageTo'])) ? mysqli_real_escape_string($connect, $_POST['eageTo']) : "";
    $epercntBalance = mysqli_real_escape_string($connect, $_POST['epercntBalance']);
    $ebalanceFrom = mysqli_real_escape_string($connect, $_POST['ebalanceFrom']);
    $ebalanceFrom = str_replace(',', '', $ebalanceFrom);
    $ebalanceTo = mysqli_real_escape_string($connect, $_POST['ebalanceTo']);
    $ebalanceTo = str_replace(',', '', $ebalanceTo);
    $emaxWritedown = mysqli_real_escape_string($connect, $_POST['emaxWritedown']);
    $emaxWritedown = str_replace(',', '', $emaxWritedown);
    


    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    $checkSql = "SELECT * FROM `writedown_rules` WHERE age_from = '$eageFrom' AND age_to = '$eageTo' AND balance_from = '$ebalanceFrom' AND balance_to = '$ebalanceTo'  AND status = 1 AND location = '$location' AND id != '$ruleId'";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        $valid['success'] = false;
        $valid['messages'] = "Rule Already Exist";
    } else {

        $sql = "UPDATE `writedown_rules` SET 
        `age_from`='$eageFrom',`age_to`='$eageTo',
        `pencent_balance`='$epercntBalance',`balance_from`='$ebalanceFrom',`balance_to`='$ebalanceTo',
        `max_writedown`='$emaxWritedown' WHERE id = '$ruleId'";

        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
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