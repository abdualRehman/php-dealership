<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '' , 'settingError' => array());

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d') {
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux,$to_format);
}

if ($_POST) {

    $ruleId = $_POST['ruleId'];

    $editexpireIn = (isset($_POST['editexpireIn'])) ? mysqli_real_escape_string($connect, $_POST['editexpireIn']) : "";
    $editexpireIn = ($editexpireIn === '') ? "" : reformatDate($editexpireIn);


    $model = mysqli_real_escape_string($connect, $_POST['editModel']);
    $year = mysqli_real_escape_string($connect, $_POST['editYear']);
    $modelno = mysqli_real_escape_string($connect, $_POST['editModelno']);
    
    $editExModelno = ( isset($_POST['editExModelno']) ) ? implode(" ", $_POST['editExModelno']) : "";
    $editExModelno =  ($editExModelno ===  "") ? "" :  " " . $editExModelno . " ";
    


    $edealer = mysqli_real_escape_string($connect, $_POST['edealer']);
    $eother = mysqli_real_escape_string($connect, $_POST['eother']);
    $elease = mysqli_real_escape_string($connect, $_POST['elease']);



    $checkSql = "SELECT * FROM `cash_incentive_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1 AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule Already Exist";
    } else {

        $sql = "UPDATE `cash_incentive_rules` SET 
        `expire_in`='$editexpireIn',
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `dealer`='$edealer',
        `other`='$eother',
        `lease`='$elease'
        WHERE id = '$ruleId' ";

        if ($connect->query($sql) === true) {
            
            // update All Matrix Becaause we don't know about weather used delete exclude model no or not
            $obj = updateAllCashInventives(); 
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