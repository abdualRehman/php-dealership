<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d') {
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux,$to_format);
}

if ($_POST) {

    $ruleId = $_POST['ruleId'];


    $model = mysqli_real_escape_string($connect, $_POST['editModel']);
    $year = mysqli_real_escape_string($connect, $_POST['editYear']);
    $modelno = mysqli_real_escape_string($connect, $_POST['editModelno']);

    $editExModelno = (isset($_POST['editExModelno'])) ? implode(" ", $_POST['editExModelno']) : "";
    $editExModelno =  ($editExModelno ===  "") ? "" :  " " . $editExModelno . " ";

    $editExpireIn = (isset($_POST['editExpireIn'])) ? mysqli_real_escape_string($connect, $_POST['editExpireIn']) : "";
    $editExpireIn = ($editExpireIn === '') ? "" : reformatDate($editExpireIn);


    $p12_24_33 = (isset($_POST['e12_24_33'])) ? mysqli_real_escape_string($connect, $_POST['e12_24_33']) : "";
    $p12_36_48 = (isset($_POST['e12_36_48'])) ? mysqli_real_escape_string($connect, $_POST['e12_36_48']) : "";
    $p10_24_33 = (isset($_POST['e10_24_33'])) ? mysqli_real_escape_string($connect, $_POST['e10_24_33']) : "";
    $p10_36_48 = (isset($_POST['e10_36_48'])) ? mysqli_real_escape_string($connect, $_POST['e10_36_48']) : "";

    $v24 = (isset($_POST['e24'])) ? mysqli_real_escape_string($connect, $_POST['e24']) : "";
    $v27 = (isset($_POST['e27'])) ? mysqli_real_escape_string($connect, $_POST['e27']) : "";
    $v30 = (isset($_POST['e30'])) ? mysqli_real_escape_string($connect, $_POST['e30']) : "";
    $v33 = (isset($_POST['e33'])) ? mysqli_real_escape_string($connect, $_POST['e33']) : "";
    $v36 = (isset($_POST['e36'])) ? mysqli_real_escape_string($connect, $_POST['e36']) : "";
    $v39 = (isset($_POST['e39'])) ? mysqli_real_escape_string($connect, $_POST['e39']) : "";
    $v42 = (isset($_POST['e42'])) ? mysqli_real_escape_string($connect, $_POST['e42']) : "";
    $v45 = (isset($_POST['e45'])) ? mysqli_real_escape_string($connect, $_POST['e45']) : "";
    $v48 = (isset($_POST['e48'])) ? mysqli_real_escape_string($connect, $_POST['e48']) : "";
    $v51 = (isset($_POST['e51'])) ? mysqli_real_escape_string($connect, $_POST['e51']) : "";
    $v54 = (isset($_POST['e54'])) ? mysqli_real_escape_string($connect, $_POST['e54']) : "";
    $v57 = (isset($_POST['e57'])) ? mysqli_real_escape_string($connect, $_POST['e57']) : "";
    $v60 = (isset($_POST['e60'])) ? mysqli_real_escape_string($connect, $_POST['e60']) : "";



    $checkSql = "SELECT * FROM `lease_rule` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1 AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule Already Exist";
    } else {

        $sql = "UPDATE `lease_rule` SET 
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `expire_in`='$editExpireIn',
        `24`='$v24',
        `27`='$v27',
        `30`='$v30',
        `33`='$v33',
        `36`='$v36',
        `39`='$v39',
        `42`='$v42',
        `45`='$v45',
        `48`='$v48',
        `51`='$v51',
        `54`='$v54',
        `57`='$v57',
        `60`='$v60',
        `12_24_33`='$p12_24_33',
        `12_36_48`='$p12_36_48',
        `10_24_33`='$p10_24_33',
        `10_36_48`='$p10_36_48' 
        WHERE id = '$ruleId' ";

        if ($connect->query($sql) === true) {

            // update All Matrix Becaause we don't know about weather used delete exclude model no or not
            $obj = updateAllLeaseRules();
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