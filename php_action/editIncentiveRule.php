<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {

    $ruleId = $_POST['ruleId'];

    $model = mysqli_real_escape_string($connect, $_POST['editModel']);
    $year = mysqli_real_escape_string($connect, $_POST['editYear']);
    $modelno = mysqli_real_escape_string($connect, $_POST['editModelno']);
    $editModelType = mysqli_real_escape_string($connect, $_POST['editModelType']);
    
    $editExModelno = (isset($_POST['editExModelno'])) ? implode("_",$_POST['editExModelno']): "";
    // echo implode(" ",(array)$_POST['editExModelno']);
    $editExModelno = ($editExModelno ===  "") ? "" :   "_".$editExModelno."_" ;

    // echo $editExModelno;

    $college = "N/A";
    $collegeE = "";
    if (isset($_POST['editCollege'])) {
        $college = mysqli_real_escape_string($connect, $_POST['ecollegeV']);
        $collegeE = mysqli_real_escape_string($connect, $_POST['ecollegeE']);
        $collegeE = reformatDate($collegeE);
    }
    $military = "N/A";
    $militaryE = "";
    if (isset($_POST['editMilitary'])) {
        $military = mysqli_real_escape_string($connect, $_POST['emilitaryV']);
        $militaryE = mysqli_real_escape_string($connect, $_POST['emilitaryE']);
        $militaryE = reformatDate($militaryE);
    }
    $loyalty = "N/A";
    $loyaltyE = "";
    if (isset($_POST['editLoyalty'])) {
        $loyalty = mysqli_real_escape_string($connect, $_POST['eloyaltyV']);
        $loyaltyE = mysqli_real_escape_string($connect, $_POST['eloyaltyE']);
        $loyaltyE = reformatDate($loyaltyE);
    }
    $conquest = "N/A";
    $conquestE = "";
    if (isset($_POST['editConquest'])) {
        $conquest = mysqli_real_escape_string($connect, $_POST['econquestV']);
        $conquestE = mysqli_real_escape_string($connect, $_POST['econquestE']);
        $conquestE = reformatDate($conquestE);
    }

    $leaseLoyalty = "N/A";
    $leaseLoyaltyE = "";
    if (isset($_POST['editLeaseLoyalty'])) {
        $leaseLoyalty = mysqli_real_escape_string($connect, $_POST['eleaseLoyaltyV']);

        $leaseLoyaltyE = mysqli_real_escape_string($connect, $_POST['eleaseLoyaltyE']);
        $leaseLoyaltyE = reformatDate($leaseLoyaltyE);
    }

    $misc1 = "N/A";
    $misc1E = "";
    if (isset($_POST['editMisc1'])) {
        $misc1 = mysqli_real_escape_string($connect, $_POST['emisc1V']);
        $misc1E = mysqli_real_escape_string($connect, $_POST['emisc1E']);
        $misc1E = reformatDate($misc1E);
    }
    $misc2 = "N/A";
    $misc2E = "";
    if (isset($_POST['editMisc2'])) {
        $misc2 = mysqli_real_escape_string($connect, $_POST['emisc2V']);
        $misc2E = mysqli_real_escape_string($connect, $_POST['emisc2E']);
        $misc2E = reformatDate($misc2E);
    }
    

    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

    $checkSql = "SELECT * FROM `incentive_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND type = '$editModelType' AND status = 1 AND location = '$location' AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule Already Exist";

    } else {

        $sql = "UPDATE `incentive_rules` SET 
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `type`='$editModelType',
        `college`='$college',
        `college_e`='$collegeE',
        `military`='$military',
        `military_e`='$militaryE',
        `loyalty`='$loyalty',
        `loyalty_e`='$loyaltyE',
        `conquest`='$conquest',
        `conquest_e`='$conquestE',
        `misc1`='$misc1',
        `misc1_e`='$misc1E',
        `misc2`='$misc2',
        `misc2_e`='$misc2E',
        `lease_loyalty`='$leaseLoyalty',
        `lease_loyalty_e`='$leaseLoyaltyE' WHERE id = '$ruleId' ";

        if ($connect->query($sql) === true) {
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