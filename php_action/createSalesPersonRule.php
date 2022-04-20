<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array() , 'errorMessages' => array() , 'id' => '');

if ($_POST) {




    $vinCheck = "N/A";
    if (isset($_POST['vinCheck'])) {
        $vinCheck = mysqli_real_escape_string($connect, $_POST['vinCheck']);
    }
    $insurance = "N/A";
    if (isset($_POST['insurance'])) {
        $insurance = mysqli_real_escape_string($connect, $_POST['insurance']);
    }
    $tradeTitle = "N/A";
    if (isset($_POST['tradeTitle'])) {
        $tradeTitle = mysqli_real_escape_string($connect, $_POST['tradeTitle']);
    }
    $registration = "N/A";
    if (isset($_POST['registration'])) {
        $registration = mysqli_real_escape_string($connect, $_POST['registration']);
    }
    $inspection = "N/A";
    if (isset($_POST['inspection'])) {
        $inspection = mysqli_real_escape_string($connect, $_POST['inspection']);
    }
    $salespersonStatus = "N/A";
    if (isset($_POST['salePStatus'])) {
        $salespersonStatus = mysqli_real_escape_string($connect, $_POST['salePStatus']);
    }
    $paid = "N/A";
    if (isset($_POST['paid'])) {
        $paid = mysqli_real_escape_string($connect, $_POST['paid']);
    }

    // echo $vinCheck . '<br />';
    // echo $insurance . '<br />';
    // echo $tradeTitle . '<br />';
    // echo $registration . '<br />';
    // echo $inspection . '<br />';
    // echo $salespersonStatus . '<br />';
    // echo $paid . '<br />';



    for ($x = 0; $x < count($_POST['model']); $x++) {
        $i = $x + 1;
        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $state = mysqli_real_escape_string($connect, $_POST['state'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        $modelType = mysqli_real_escape_string($connect, $_POST['modelType'][$x]);

        $exModelno = (isset($_POST['exModelno' . $i] )) ? implode("_", $_POST['exModelno' . $i] ) : "";
        $exModelno = ($exModelno ===  "") ? "" :   "_" . $exModelno . "_";
       

        $checkSql = "SELECT * FROM `salesperson_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND type='$modelType' AND `state`='$state' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result->num_rows > 0) {
            
            $valid['errorMessages'][] = $model .' - '. $year . ' - ' . $modelno. ' - ' . $state . ' - ' . $modelType . " is Already Exist";
        
        } else {

            $sql = "INSERT INTO `salesperson_rules`(`model`, `year`, `modelno` , `ex_modelno` , `type`, `state` , `vin_check`, `insurance`, `trade_title`, `registration`, `inspection`, `salesperson_status`, `paid`, `status`) 
            VALUES (
                '$model',
                '$year',
                '$modelno',
                '$exModelno',
                '$modelType',
                '$state',
                '$vinCheck',
                '$insurance',
                '$tradeTitle',
                '$registration',
                '$inspection',
                '$salespersonStatus',
                '$paid', 1 )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'][] = "Successfully Added";
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