<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {


    $destination = (isset($_POST['destination'])) ? mysqli_real_escape_string($connect, $_POST['destination']) : "";
    $hb = (isset($_POST['hb'])) ? mysqli_real_escape_string($connect, $_POST['hb']) : "";



    for ($x = 0; $x < count($_POST['model']); $x++) {
        $i = $x + 1;

        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        // $exModelno = mysqli_real_escape_string($connect, $_POST['exModelno'.$i]);
        $exModelno = (isset($_POST['exModelno' . $i])) ? implode(" ", $_POST['exModelno' . $i]) : "";
        $exModelno = ($exModelno ===  "") ? "" :   " " . $exModelno . " ";

        // echo json_encode($model);
        // echo json_encode($exModelno);



        $checkSql = "SELECT * FROM `matrix_rule` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result && $result->num_rows > 0) {
            $valid['errorMessages'][] = $model . ' - ' . $year . ' - ' . $modelno . ", Already Exist";
        } else {

            $sql = "INSERT INTO `matrix_rule`( `model`, `year`, `modelno` , `ex_modelno`, `destination`, `hb`, `status`) 
            VALUES (
                '$model',
                '$year',
                '$modelno',
                '$exModelno',
                '$destination',
                '$hb',
                1 )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'][] = "Successfully Added";

                $obj = updateAllMaxtix();
                $obj = json_decode($obj);
                if ($obj->success === 'false') {
                    $valid['settingError'][] = $obj->messages;
                }
            } else {
                $valid['success'] = false;
                $valid['messages'][] = $connect->error;
                // $valid['messages'] = mysqli_error($connect);
            }
        }
    }


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);