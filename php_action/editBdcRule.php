<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '' , 'settingError' => array());

if ($_POST) {

    $ruleId = $_POST['ruleId'];


    $model = mysqli_real_escape_string($connect, $_POST['editModel']);
    $year = mysqli_real_escape_string($connect, $_POST['editYear']);
    $modelno = mysqli_real_escape_string($connect, $_POST['editModelno']);
    
    $editExModelno = ( isset($_POST['editExModelno']) ) ? implode(" ", $_POST['editExModelno']) : "";
    $editExModelno =  ($editExModelno ===  "") ? "" :  " " . $editExModelno . " ";
    


    $editCalcFrom = mysqli_real_escape_string($connect, $_POST['editCalcFrom']);
    $editCalculation = mysqli_real_escape_string($connect, $_POST['editCalculation']);
    $editNumToCalc = mysqli_real_escape_string($connect, $_POST['editNumToCalc']);



    $checkSql = "SELECT * FROM `bdc_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1 AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule Already Exist";
    } else {

        $sql = "UPDATE `bdc_rules` SET 
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `calcfrom`='$editCalcFrom',
        `calculation`='$editCalculation',
        `num_to_calc`='$editNumToCalc'
        WHERE id = '$ruleId' ";

        if ($connect->query($sql) === true) {
            
            // update All Matrix Becaause we don't know about weather used delete exclude model no or not
            $obj = updateAllBdc(); 
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