<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '' , 'settingError' => array() );

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d') {
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux,$to_format);
}

if ($_POST) {


    $f_24_36 = (isset($_POST['f_24_36'])) ? mysqli_real_escape_string($connect, $_POST['f_24_36']) : "";
    $f_37_48 = (isset($_POST['f_37_48'])) ? mysqli_real_escape_string($connect, $_POST['f_37_48']) : "";
    $f_49_60 = (isset($_POST['f_49_60'])) ? mysqli_real_escape_string($connect, $_POST['f_49_60']) : "";
    $f_61_72 = (isset($_POST['f_61_72'])) ? mysqli_real_escape_string($connect, $_POST['f_61_72']) : "";

    $f_659_610_24_36 = (isset($_POST['f_659_610_24_36'])) ? mysqli_real_escape_string($connect, $_POST['f_659_610_24_36']) : "";
    $f_659_610_37_60 = (isset($_POST['f_659_610_37_60'])) ? mysqli_real_escape_string($connect, $_POST['f_659_610_37_60']) : "";
    $f_659_610_61_72 = (isset($_POST['f_659_610_61_72'])) ? mysqli_real_escape_string($connect, $_POST['f_659_610_61_72']) : "";
    $f_expire = (isset($_POST['f_expire'])) ? mysqli_real_escape_string($connect, $_POST['f_expire']) : "";

    $f_expire = ($f_expire === '') ? "" : reformatDate($f_expire);

    $lease_660 = (isset($_POST['lease_660'])) ? mysqli_real_escape_string($connect, $_POST['lease_660']) : "";
    $lease_659_610 = (isset($_POST['lease_659_610'])) ? mysqli_real_escape_string($connect, $_POST['lease_659_610']) : "";
    $lease_one_pay_660 = (isset($_POST['lease_one_pay_660'])) ? mysqli_real_escape_string($connect, $_POST['lease_one_pay_660']) : "";
    $lease_one_pay_659_610 = (isset($_POST['lease_one_pay_659_610'])) ? mysqli_real_escape_string($connect, $_POST['lease_one_pay_659_610']) : "";
    $lease_expire = (isset($_POST['lease_expire'])) ? mysqli_real_escape_string($connect, $_POST['lease_expire']) : "";
    $lease_expire =  ($lease_expire === '') ? "" : reformatDate($lease_expire);


    for ($x = 0; $x < count($_POST['model']); $x++) {
        $i = $x + 1;
        
        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        $exModelno = (isset($_POST['exModelno'.$i])) ? implode(" ",$_POST['exModelno'.$i]): "";
        $exModelno = ($exModelno ===  "") ? "" :   " ".$exModelno." " ;

          

        $checkSql = "SELECT * FROM `rate_rule` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result && $result->num_rows > 0) {
            $valid['errorMessages'][] = $model . ' - ' . $year . ' - ' . $modelno . ", Already Exist";
        } else {

            $sql = "INSERT INTO `rate_rule`( 
                `model`, `year`, `modelno`, `ex_modelno`, 
                `f_24-36`, `f_37-48`, `f_49-60`, `f_61-72`, 
                `f_659_610_24-36`, `f_659_610_37-60`, `f_659_610_61-72`, `f_expire`, 
                `lease_660`, `lease_659_610`, `lease_one_pay_660`, `lease_one_pay_659_610`, `lease_expire` , `status`) 
            VALUES (
                '$model',
                '$year',
                '$modelno',
                '$exModelno',
                '$f_24_36',
                '$f_37_48',
                '$f_49_60',
                '$f_61_72',
                '$f_659_610_24_36',
                '$f_659_610_37_60',
                '$f_659_610_61_72',
                '$f_expire',
                '$lease_660',
                '$lease_659_610',
                '$lease_one_pay_660',
                '$lease_one_pay_659_610',
                '$lease_expire',1
            )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'][] = "Successfully Added";

                $obj = updateALLRates();
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