<?php

require_once './db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '' , 'settingError' => array());
// print_r($valid);
if ($_POST) {

    $year = mysqli_real_escape_string($connect, $_POST['year']);
    $model = mysqli_real_escape_string($connect, $_POST['model']);
    $modelCode = mysqli_real_escape_string($connect, $_POST['modelCode']);
    $msrp = mysqli_real_escape_string($connect, $_POST['msrp']);
    $dlrInv = mysqli_real_escape_string($connect, $_POST['dlrInv']);
    $modelDescription = mysqli_real_escape_string($connect, $_POST['modelDescription']);

    $year = trim($year);
    $model = ucwords(trim($model));
    $modelCode = trim($modelCode);
    $location = $_SESSION['userLoc'];


    $sql = "INSERT INTO `manufature_price`(`year`, `model`, `model_code`, `msrp`, `dlr_inv`, `model_des`, `trim`, `status` , `location`) 
            VALUES (
                '$year',
                '$model',
                '$modelCode',
                '$msrp',
                '$dlrInv',
                '$modelDescription',
                '$modelDescription', 
                1,
                '$location'
            )";


    if ($connect->query($sql) === true) {
        
        $obj = updateManufacturePriceByRule($model, $year, $modelCode);
        $obj = json_decode($obj);
        if ($obj->success === 'false') {
            $valid['settingError'][] = $obj->messages;
        }

        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }




    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);