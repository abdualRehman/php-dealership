<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '' , 'settingError' => array());

if ($_POST) {

    $ruleId = $_POST['ruleId'];


    $model = mysqli_real_escape_string($connect, $_POST['editModel']);
    $year = mysqli_real_escape_string($connect, $_POST['editYear']);
    $modelno = mysqli_real_escape_string($connect, $_POST['editModelno']);
    // $editExModelno = mysqli_real_escape_string($connect, $_POST['editExModelno']);
    $editExModelno = ( isset($_POST['editExModelno']) ) ? implode(" ", $_POST['editExModelno']) : "";
    $editExModelno =  ($editExModelno ===  "") ? "" :  " " . $editExModelno . " ";
    // echo $editExModelno;


    $editDestination = mysqli_real_escape_string($connect, $_POST['editDestination']);
    $editHb = mysqli_real_escape_string($connect, $_POST['editHb']);



    $checkSql = "SELECT * FROM `matrix_rule` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1 AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule is Already Exist";
    } else {

        $sql = "UPDATE `matrix_rule` SET 
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `destination`='$editDestination',
        `hb`='$editHb'
        WHERE id = '$ruleId' ";

        if ($connect->query($sql) === true) {
            
            // update All Matrix Becaause we don't know about weather used delete exclude model no or not
            $obj = updateAllMaxtix(); 
            $obj = json_decode($obj);
            
            if ($obj->success === 'false') {
                $valid['settingError'][] = $obj->messages;
            }

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