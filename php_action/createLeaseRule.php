<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '' , 'settingError' => array() );


if ($_POST) {


    $p12_24_33 = (isset($_POST['12_24_33'])) ? mysqli_real_escape_string($connect, $_POST['12_24_33']) : "";
    $p12_36_48 = (isset($_POST['12_36_48'])) ? mysqli_real_escape_string($connect, $_POST['12_36_48']) : "";
    $p10_24_33 = (isset($_POST['10_24_33'])) ? mysqli_real_escape_string($connect, $_POST['10_24_33']) : "";
    $p10_36_48 = (isset($_POST['10_36_48'])) ? mysqli_real_escape_string($connect, $_POST['10_36_48']) : "";
    
    $v24 = (isset($_POST['24'])) ? mysqli_real_escape_string($connect, $_POST['24']) : "";
    $v27 = (isset($_POST['27'])) ? mysqli_real_escape_string($connect, $_POST['27']) : "";
    $v30 = (isset($_POST['30'])) ? mysqli_real_escape_string($connect, $_POST['30']) : "";
    $v33 = (isset($_POST['33'])) ? mysqli_real_escape_string($connect, $_POST['33']) : "";
    $v36 = (isset($_POST['36'])) ? mysqli_real_escape_string($connect, $_POST['36']) : "";
    $v39 = (isset($_POST['39'])) ? mysqli_real_escape_string($connect, $_POST['39']) : "";
    $v42 = (isset($_POST['42'])) ? mysqli_real_escape_string($connect, $_POST['42']) : "";
    $v45 = (isset($_POST['45'])) ? mysqli_real_escape_string($connect, $_POST['45']) : "";
    $v48 = (isset($_POST['48'])) ? mysqli_real_escape_string($connect, $_POST['48']) : "";
    $v51 = (isset($_POST['51'])) ? mysqli_real_escape_string($connect, $_POST['51']) : "";
    $v54 = (isset($_POST['54'])) ? mysqli_real_escape_string($connect, $_POST['54']) : "";
    $v57 = (isset($_POST['57'])) ? mysqli_real_escape_string($connect, $_POST['57']) : "";
    $v60 = (isset($_POST['60'])) ? mysqli_real_escape_string($connect, $_POST['60']) : "";




    for ($x = 0; $x < count($_POST['model']); $x++) {
        $i = $x + 1;
        
        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        $exModelno = (isset($_POST['exModelno'.$i])) ? implode(" ",$_POST['exModelno'.$i]): "";
        $exModelno = ($exModelno ===  "") ? "" :   " ".$exModelno." " ;

          

        $checkSql = "SELECT * FROM `lease_rule` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result && $result->num_rows > 0) {
            $valid['errorMessages'][] = $model . ' - ' . $year . ' - ' . $modelno . " is Already Exist";
        } else {

            $sql = "INSERT INTO `lease_rule`( `model`, `year`, `modelno` , `ex_modelno`, `24`, `27`, `30`, `33`, `36`, `39`, `42`, `45`, `48`, `51`, `54`, `57`, `60`, `12_24_33`, `12_36_48`, `10_24_33`, `10_36_48`, `status`) 
            VALUES (
                '$model',
                '$year',
                '$modelno',
                '$exModelno',
                '$v24',
                '$v27',
                '$v30',
                '$v33',
                '$v36',
                '$v39',
                '$v42',
                '$v45',
                '$v48',
                '$v51',
                '$v54',
                '$v57',
                '$v60',
                '$p12_24_33',
                '$p12_36_48',
                '$p10_24_33',
                '$p10_36_48',
                1 )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'][] = "Successfully Added";

                $obj = updateAllLeaseRules();
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