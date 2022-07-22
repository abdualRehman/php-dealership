<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '' , 'settingError' => array() );

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d') {
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux,$to_format);
}

if ($_POST) {


    $dealer = (isset($_POST['dealer'])) ? mysqli_real_escape_string($connect, $_POST['dealer']) : "";
    $other = (isset($_POST['other'])) ? mysqli_real_escape_string($connect, $_POST['other']) : "";
    $lease = (isset($_POST['lease'])) ? mysqli_real_escape_string($connect, $_POST['lease']) : "";

    $expireIn = (isset($_POST['expireIn'])) ? mysqli_real_escape_string($connect, $_POST['expireIn']) : "";

    $expireIn = ($expireIn === '') ? "" : reformatDate($expireIn);




    for ($x = 0; $x < count($_POST['model']); $x++) {
        $i = $x + 1;
        
        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        $exModelno = (isset($_POST['exModelno'.$i])) ? implode(" ",$_POST['exModelno'.$i]): "";
        $exModelno = ($exModelno ===  "") ? "" :   " ".$exModelno." " ;

          

        $checkSql = "SELECT * FROM `cash_incentive_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result && $result->num_rows > 0) {
            $valid['errorMessages'][] = $model . ' - ' . $year . ' - ' . $modelno . ", Already Exist";
        } else {

            $sql = "INSERT INTO `cash_incentive_rules`( `expire_in`, `model`, `year`, `modelno` , `ex_modelno`, `dealer`, `other` , `lease` , `status`) 
            VALUES (
                '$expireIn',
                '$model',
                '$year',
                '$modelno',
                '$exModelno',
                '$dealer',
                '$other',
                '$lease',
                1 )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'][] = "Successfully Added";

                $obj = updateAllCashInventives();
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