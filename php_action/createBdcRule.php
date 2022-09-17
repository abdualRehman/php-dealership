<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '' , 'settingError' => array() );


if ($_POST) {


    $calcFrom = (isset($_POST['calcFrom'])) ? mysqli_real_escape_string($connect, $_POST['calcFrom']) : "";
    $calculation = (isset($_POST['calculation'])) ? mysqli_real_escape_string($connect, $_POST['calculation']) : "";
    $numToCalc = (isset($_POST['numToCalc'])) ? mysqli_real_escape_string($connect, $_POST['numToCalc']) : "";



    for ($x = 0; $x < count($_POST['model']); $x++) {
        $i = $x + 1;
        
        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        $exModelno = (isset($_POST['exModelno'.$i])) ? implode(" ",$_POST['exModelno'.$i]): "";
        $exModelno = ($exModelno ===  "") ? "" :   " ".$exModelno." " ;

        $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
          

        $checkSql = "SELECT * FROM `bdc_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND status = 1 AND location = '$location'";
        $result = $connect->query($checkSql);
        if ($result && $result->num_rows > 0) {
            $valid['errorMessages'][] = $model . ' - ' . $year . ' - ' . $modelno . ",  Already Exist";
        } else {

            $sql = "INSERT INTO `bdc_rules`( `model`, `year`, `modelno` , `ex_modelno`, `calcfrom`, `calculation` , `num_to_calc` , `status` , `location`) 
            VALUES (
                '$model',
                '$year',
                '$modelno',
                '$exModelno',
                '$calcFrom',
                '$calculation',
                '$numToCalc',
                1 , '$location' )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'][] = "Successfully Added";

                $obj = updateAllBdc();
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