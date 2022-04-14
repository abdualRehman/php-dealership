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


    $model = mysqli_real_escape_string($connect, $_POST['editModel']);
    $year = mysqli_real_escape_string($connect, $_POST['editYear']);
    $modelno = mysqli_real_escape_string($connect, $_POST['editModelno']);
    
    $editExModelno = ( isset($_POST['editExModelno']) ) ? implode(" ", $_POST['editExModelno']) : "";
    $editExModelno =  ($editExModelno ===  "") ? "" :  " " . $editExModelno . " ";
    


    $f_24_36 = (isset($_POST['ef_24_36'])) ? mysqli_real_escape_string($connect, $_POST['ef_24_36']) : "";
    $f_37_48 = (isset($_POST['ef_37_48'])) ? mysqli_real_escape_string($connect, $_POST['ef_37_48']) : "";
    $f_49_60 = (isset($_POST['ef_49_60'])) ? mysqli_real_escape_string($connect, $_POST['ef_49_60']) : "";
    $f_61_72 = (isset($_POST['ef_61_72'])) ? mysqli_real_escape_string($connect, $_POST['ef_61_72']) : "";

    $f_659_610_24_36 = (isset($_POST['ef_659_610_24_36'])) ? mysqli_real_escape_string($connect, $_POST['ef_659_610_24_36']) : "";
    $f_659_610_37_60 = (isset($_POST['ef_659_610_37_60'])) ? mysqli_real_escape_string($connect, $_POST['ef_659_610_37_60']) : "";
    $f_659_610_61_72 = (isset($_POST['ef_659_610_61_72'])) ? mysqli_real_escape_string($connect, $_POST['ef_659_610_61_72']) : "";
    $f_expire = (isset($_POST['ef_expire'])) ? mysqli_real_escape_string($connect, $_POST['ef_expire']) : "";

    $f_expire = ($f_expire === '') ? "" : reformatDate($f_expire);

    $lease_660 = (isset($_POST['elease_660'])) ? mysqli_real_escape_string($connect, $_POST['elease_660']) : "";
    $lease_659_610 = (isset($_POST['elease_659_610'])) ? mysqli_real_escape_string($connect, $_POST['elease_659_610']) : "";
    $lease_one_pay_660 = (isset($_POST['elease_one_pay_660'])) ? mysqli_real_escape_string($connect, $_POST['elease_one_pay_660']) : "";
    $lease_one_pay_659_610 = (isset($_POST['elease_one_pay_659_610'])) ? mysqli_real_escape_string($connect, $_POST['elease_one_pay_659_610']) : "";
    $lease_expire = (isset($_POST['elease_expire'])) ? mysqli_real_escape_string($connect, $_POST['elease_expire']) : "";
    $lease_expire =  ($lease_expire === '') ? "" : reformatDate($lease_expire);



    $checkSql = "SELECT * FROM `rate_rule` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1 AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule is Already Exist";
    } else {

        $sql = "UPDATE `rate_rule` SET 
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `f_24-36`='$f_24_36',
        `f_37-48`='$f_37_48',
        `f_49-60`='$f_49_60',
        `f_61-72`='$f_61_72',
        `f_659_610_24-36`='$f_659_610_24_36',
        `f_659_610_37-60`='$f_659_610_37_60',
        `f_659_610_61-72`='$f_659_610_61_72',
        `f_expire`='$f_expire',
        `lease_660`='$lease_660',
        `lease_659_610`='$lease_659_610',
        `lease_one_pay_660`='$lease_one_pay_660',
        `lease_one_pay_659_610`='$lease_one_pay_659_610',
        `lease_expire`='$lease_expire'
        WHERE id = '$ruleId' ";

        if ($connect->query($sql) === true) {
            
            // update All Matrix Becaause we don't know about weather used delete exclude model no or not
            $obj = updateALLRates();
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