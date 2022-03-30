<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');

if ($_POST) {

    $soldTodoId = $_POST['soldTodoId'];

    // sales person Todo
    $vincheck = mysqli_real_escape_string($connect, $_POST['vincheck']);
    $insurance = mysqli_real_escape_string($connect, $_POST['insurance']);
    $tradeTitle = mysqli_real_escape_string($connect, $_POST['tradeTitle']);
    $registration = mysqli_real_escape_string($connect, $_POST['registration']);
    $inspection = mysqli_real_escape_string($connect, $_POST['inspection']);
    $salePStatus = mysqli_real_escape_string($connect, $_POST['salePStatus']);
    $paid = mysqli_real_escape_string($connect, $_POST['paid']);



    if ($soldTodoId) {


        $saleTodoSql = "UPDATE `sale_todo` SET 
        `vin_check`='$vincheck',
        `insurance`='$insurance',
        `trade_title`='$tradeTitle',
        `registration`='$registration',
        `inspection`='$inspection',
        `salesperson_status`='$salePStatus',
        `paid`='$paid' 
        WHERE sale_todo_id = '$soldTodoId' ";


        if ($connect->query($saleTodoSql) === true ) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
        
    }









    $connect->close();
    echo json_encode($valid);
} // /if $_POST
