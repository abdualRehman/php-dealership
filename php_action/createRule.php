<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array() , 'errorMessages' => array() , 'id' => '');
function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {

    $fromDate = mysqli_real_escape_string($connect, $_POST['fromDate']);
    $fromDate = reformatDate($fromDate);

    $toDate = mysqli_real_escape_string($connect, $_POST['toDate']);
    $toDate = reformatDate($toDate);


    // echo $fromDate . '<br />';
    // echo $toDate . '<br />';



    $college = "N/A";
    if (isset($_POST['college'])) {
        $college = mysqli_real_escape_string($connect, $_POST['college']);
    }
    $military = "N/A";
    if (isset($_POST['military'])) {
        $military = mysqli_real_escape_string($connect, $_POST['military']);
    }
    $loyalty = "N/A";
    if (isset($_POST['loyalty'])) {
        $loyalty = mysqli_real_escape_string($connect, $_POST['loyalty']);
    }
    $conquest = "N/A";
    if (isset($_POST['conquest'])) {
        $conquest = mysqli_real_escape_string($connect, $_POST['conquest']);
    }
    $misc1 = "N/A";
    if (isset($_POST['misc1'])) {
        $misc1 = mysqli_real_escape_string($connect, $_POST['misc1']);
    }
    $misc2 = "N/A";
    if (isset($_POST['misc2'])) {
        $misc2 = mysqli_real_escape_string($connect, $_POST['misc2']);
    }
    $misc3 = "N/A";
    if (isset($_POST['misc3'])) {
        $misc3 = mysqli_real_escape_string($connect, $_POST['misc3']);
    }

    // echo $misc1 . '<br />';
    // echo $misc2 . '<br />';
    // echo $misc3 . '<br />';



    for ($x = 0; $x < count($_POST['model']); $x++) {

        $model = mysqli_real_escape_string($connect, $_POST['model'][$x]);
        $year = mysqli_real_escape_string($connect, $_POST['year'][$x]);
        $modelno = mysqli_real_escape_string($connect, $_POST['modelno'][$x]);
        $modelType = mysqli_real_escape_string($connect, $_POST['modelType'][$x]);
       

        $checkSql = "SELECT * FROM `incentive_rules` WHERE model = '$model' AND year = '$year' AND modelno = '$modelno' AND type='$modelType' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result->num_rows > 0) {
            
            $valid['errorMessages'][] = $model .' - '. $year . ' - ' . $modelno. ' - ' . $modelType  ." is Already Exist";
        
        } else {

            $sql = "INSERT INTO `incentive_rules`(`from_date`, `to_date`, `model`, `year`, `modelno` , `type`, `college`, `military`, `loyalty`, `conquest`, `misc1`, `misc2`, `misc3`, `status`) 
            VALUES (
                '$fromDate',
                '$toDate',
                '$model',
                '$year',
                '$modelno',
                '$modelType',
                '$college',
                '$military',
                '$loyalty',
                '$conquest',
                '$misc1',
                '$misc2',
                '$misc3', 1 )";

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