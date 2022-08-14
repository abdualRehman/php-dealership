<?php

require_once './db/core.php';


$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {


    $ageFrom = (isset($_POST['ageFrom'])) ? mysqli_real_escape_string($connect, $_POST['ageFrom']) : "";
    $ageTo = (isset($_POST['ageTo'])) ? mysqli_real_escape_string($connect, $_POST['ageTo']) : "";


    for ($x = 0; $x < count($_POST['percntBalance']); $x++) {
        $i = $x + 1;

        $percntBalance = mysqli_real_escape_string($connect, $_POST['percntBalance'][$x]);
        $balanceFrom = mysqli_real_escape_string($connect, $_POST['balanceFrom'][$x]);
        $balanceFrom = str_replace(',', '', $balanceFrom);
        $balanceTo = mysqli_real_escape_string($connect, $_POST['balanceTo'][$x]);
        $balanceTo = str_replace(',', '', $balanceTo);
        $maxWritedown = mysqli_real_escape_string($connect, $_POST['maxWritedown'][$x]);
        $maxWritedown = str_replace(',', '', $maxWritedown);



        $checkSql = "SELECT * FROM `writedown_rules` WHERE age_from = '$ageFrom' AND age_to = '$ageTo' AND balance_from = '$balanceFrom' AND balance_to = '$balanceTo' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result && $result->num_rows > 0) {
            $valid['errorMessages'][] = $ageFrom . ' - ' . $ageTo . ' - ' . $balanceFrom . ' - ' . $balanceTo . ", Already Exist";
        } else {

            $sql = "INSERT INTO `writedown_rules`(`age_from`, `age_to`, `pencent_balance`, `balance_from`, `balance_to`, `max_writedown`, `status`) 
            VALUES ('$ageFrom','$ageTo','$percntBalance','$balanceFrom','$balanceTo','$maxWritedown',1)";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Added";
            } else {
                $valid['success'] = false;
                $valid['messages'] = $connect->error;
            }
        }
    }


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);