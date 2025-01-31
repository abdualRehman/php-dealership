<?php

require_once './db/core.php';

$connect->begin_transaction();

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '');
function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {


    $college = "N/A";
    $collegeE = "";
    if (isset($_POST['college'])) {
        $college = mysqli_real_escape_string($connect, $_POST['collegeV']);

        $collegeE = mysqli_real_escape_string($connect, $_POST['collegeE']);
        $collegeE = reformatDate($collegeE);
    }
    $military = "N/A";
    $militaryE = "";
    if (isset($_POST['military'])) {
        $military = mysqli_real_escape_string($connect, $_POST['militaryV']);
        $militaryE = mysqli_real_escape_string($connect, $_POST['militaryE']);
        $militaryE = reformatDate($militaryE);
    }
    $loyalty = "N/A";
    $loyaltyE = "";
    if (isset($_POST['loyalty'])) {
        $loyalty = mysqli_real_escape_string($connect, $_POST['loyaltyV']);

        $loyaltyE = mysqli_real_escape_string($connect, $_POST['loyaltyE']);
        $loyaltyE = reformatDate($loyaltyE);
    }
    $conquest = "N/A";
    $conquestE = "";
    if (isset($_POST['conquest'])) {
        $conquest = mysqli_real_escape_string($connect, $_POST['conquestV']);

        $conquestE = mysqli_real_escape_string($connect, $_POST['conquestE']);
        $conquestE = reformatDate($conquestE);
    }

    $leaseLoyalty = "N/A";
    $leaseLoyaltyE = "";
    if (isset($_POST['leaseLoyalty'])) {
        $leaseLoyalty = mysqli_real_escape_string($connect, $_POST['leaseLoyaltyV']);

        $leaseLoyaltyE = mysqli_real_escape_string($connect, $_POST['leaseLoyaltyE']);
        $leaseLoyaltyE = reformatDate($leaseLoyaltyE);
    }

    $misc1 = "N/A";
    $misc1E = "";
    if (isset($_POST['misc1'])) {
        $misc1 = mysqli_real_escape_string($connect, $_POST['misc1V']);

        $misc1E = mysqli_real_escape_string($connect, $_POST['misc1E']);
        $misc1E = reformatDate($misc1E);
    }
    $misc2 = "N/A";
    $misc2E = "";
    if (isset($_POST['misc2'])) {
        $misc2 = mysqli_real_escape_string($connect, $_POST['misc2V']);

        $misc2E = mysqli_real_escape_string($connect, $_POST['misc2E']);
        $misc2E = reformatDate($misc2E);
    }




    for ($x = 0; $x < count($_POST['model']); $x++) {

        $i = $x + 1;

        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        $modelType = mysqli_real_escape_string($connect, $_POST['modelType'][$x]);

        $exModelno = (isset($_POST['exModelno' . $i])) ? implode("_", $_POST['exModelno' . $i]) : "";
        $exModelno = ($exModelno ===  "") ? "" :   "_" . $exModelno . "_";

        $state = (isset($_POST['state' . $i])) ? implode("_", $_POST['state' . $i]) : "";
        $state = ($state ===  "") ? "" :   "_" . $state . "_";



        $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

        $isExist = false;

        // echo $exModelno ."<br />";
        if (isset($_POST['state' . $i])) {
            foreach ($_POST['state' . $i] as $stateValue) {
                $checkSql = "SELECT * FROM `incentive_rules` 
                WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND state LIKE '%_" . $stateValue . "_%' AND type='$modelType' AND status = 1 AND location = '$location'";
                $result = $connect->query($checkSql);
                if ($result->num_rows > 0) {
                    $isExist = true;
                    $valid['errorMessages'][] = $model . ' - ' . $year . ' - ' . $modelno . ' - ' . $modelType  . ' - ' . $stateValue  . ", Already Exist";
                    $connect->rollback();
                }
            }
        }

        if (empty($valid['errorMessages'])) {
            $sql = "INSERT INTO `incentive_rules`( `model`, `year`, `modelno`, `ex_modelno` , `state`, `type`, `college`, `college_e`, `military`, `military_e`, `loyalty`, `loyalty_e`, `conquest`, `conquest_e`, `misc1`, `misc1_e`, `misc2`, `misc2_e`, `lease_loyalty`, `lease_loyalty_e`, `status` , `location`) 
            VALUES (
                '$model',
                '$year',
                '$modelno',
                '$exModelno',
                '$state',
                '$modelType',
                '$college',
                '$collegeE',
                '$military',
                '$militaryE',
                '$loyalty',
                '$loyaltyE',
                '$conquest',
                '$conquestE',
                '$misc1',
                '$misc1E',
                '$misc2',
                '$misc2E',
                '$leaseLoyalty',
                '$leaseLoyaltyE' , 1  , '$location' )";

            if ($connect->query($sql) === true) {
                $valid['success'] = false;
                $valid['messages'][] = "";
            } else {
                $valid['success'] = false;
                $valid['messages'][] = $connect->error;
            }
        }
    }

    if (empty($valid['errorMessages'])) {
        $valid['success'] = true;
        $valid['messages'] = ["Successfully Added"];
    } else {
        $valid['success'] = false;
        $valid['messages'] = ["Please remove error first!"];
    }


    $connect->commit();
    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);