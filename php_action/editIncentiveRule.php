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

    $fromDate = mysqli_real_escape_string($connect, $_POST['editfromDate']);
    $fromDate = reformatDate($fromDate);

    $toDate = mysqli_real_escape_string($connect, $_POST['edittoDate']);
    $toDate = reformatDate($toDate);

    $model = mysqli_real_escape_string($connect, $_POST['editModel']);
    $year = mysqli_real_escape_string($connect, $_POST['editYear']);
    $modelno = mysqli_real_escape_string($connect, $_POST['editModelno']);
    $editModelType = mysqli_real_escape_string($connect, $_POST['editModelType']);
    
    $editExModelno = (isset($_POST['editExModelno'])) ? implode("_",$_POST['editExModelno']): "";
    // echo implode(" ",(array)$_POST['editExModelno']);
    $editExModelno = ($editExModelno ===  "") ? "" :   "_".$editExModelno."_" ;

    // echo $editExModelno;

    $college = "N/A";
    if (isset($_POST['editCollege'])) {
        $college = mysqli_real_escape_string($connect, $_POST['editCollege']);
    }
    $military = "N/A";
    if (isset($_POST['editMilitary'])) {
        $military = mysqli_real_escape_string($connect, $_POST['editMilitary']);
    }
    $loyalty = "N/A";
    if (isset($_POST['editLoyalty'])) {
        $loyalty = mysqli_real_escape_string($connect, $_POST['editLoyalty']);
    }
    $conquest = "N/A";
    if (isset($_POST['editConquest'])) {
        $conquest = mysqli_real_escape_string($connect, $_POST['editConquest']);
    }
    $misc1 = "N/A";
    if (isset($_POST['editMisc1'])) {
        $misc1 = mysqli_real_escape_string($connect, $_POST['editMisc1']);
    }
    $misc2 = "N/A";
    if (isset($_POST['editMisc2'])) {
        $misc2 = mysqli_real_escape_string($connect, $_POST['editMisc2']);
    }
    $misc3 = "N/A";
    if (isset($_POST['editMisc3'])) {
        $misc3 = mysqli_real_escape_string($connect, $_POST['editMisc3']);
    }


    $checkSql = "SELECT * FROM `incentive_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND type = '$editModelType' AND status = 1 AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule is Already Exist";

    } else {

        $sql = "UPDATE `incentive_rules` SET 
        `from_date`='$fromDate',
        `to_date`='$toDate',
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `type`='$editModelType',
        `college`='$college',
        `military`='$military',
        `loyalty`='$loyalty',
        `conquest`='$conquest',
        `misc1`='$misc1',
        `misc2`='$misc2',
        `misc3`='$misc3' WHERE id = '$ruleId' ";

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