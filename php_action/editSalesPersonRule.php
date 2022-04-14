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
    $editState = mysqli_real_escape_string($connect, $_POST['editState']);

    $editExModelno = (isset($_POST['editExModelno'])) ? implode("_", $_POST['editExModelno']): "";
    $editExModelno = ($editExModelno ===  "") ? "" :   "_".$editExModelno."_" ;

    // echo $editExModelno;

    $vinCheck = "N/A";
    if (isset($_POST['editVinCheck'])) {
        $vinCheck = mysqli_real_escape_string($connect, $_POST['editVinCheck']);
    }
    $insurance = "N/A";
    if (isset($_POST['editInsurance'])) {
        $insurance = mysqli_real_escape_string($connect, $_POST['editInsurance']);
    }
    $tradeTitle = "N/A";
    if (isset($_POST['editTradeTitle'])) {
        $tradeTitle = mysqli_real_escape_string($connect, $_POST['editTradeTitle']);
    }
    $registration = "N/A";
    if (isset($_POST['editRegistration'])) {
        $registration = mysqli_real_escape_string($connect, $_POST['editRegistration']);
    }
    $inspection = "N/A";
    if (isset($_POST['editInspection'])) {
        $inspection = mysqli_real_escape_string($connect, $_POST['editInspection']);
    }
    $salespersonStatus = "N/A";
    if (isset($_POST['editSalespersonStatus'])) {
        $salespersonStatus = mysqli_real_escape_string($connect, $_POST['editSalespersonStatus']);
    }
    $paid = "N/A";
    if (isset($_POST['editPaid'])) {
        $paid = mysqli_real_escape_string($connect, $_POST['editPaid']);
    }


    $checkSql = "SELECT * FROM `salesperson_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND type = '$editModelType' AND `state` = '$editState' AND status = 1 AND id != '$ruleId'";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        $valid['success'] = false;
        $valid['messages'] = "Rule is Already Exist";

    } else {

        $sql = "UPDATE `salesperson_rules` SET 
        `from_date`='$fromDate',
        `to_date`='$toDate',
        `model`='$model',
        `year`='$year',
        `modelno`='$modelno',
        `ex_modelno`='$editExModelno',
        `type`='$editModelType',
        `state`='$editState',
        `vin_check`='$vinCheck',
        `insurance`='$insurance',
        `trade_title`='$tradeTitle',
        `registration`='$registration',
        `inspection`='$inspection',
        `salesperson_status`='$salespersonStatus',
        `paid`='$paid' WHERE id = '$ruleId' ";

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