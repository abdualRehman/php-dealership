<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');

if ($_POST) {

    $soldTodoId = $_POST['soldTodoId'];

    // sales person Todo
    $vincheck = isset($_POST['vincheck']) ? mysqli_real_escape_string($connect, $_POST['vincheck']) : "";
    $insurance =  isset($_POST['insurance']) ? mysqli_real_escape_string($connect, $_POST['insurance']) : "";
    $tradeTitle = isset($_POST['tradeTitle']) ? mysqli_real_escape_string($connect, $_POST['tradeTitle']) : "";
    $registration = isset($_POST['registration']) ? mysqli_real_escape_string($connect, $_POST['registration']) : "";
    $inspection = isset($_POST['inspection']) ? mysqli_real_escape_string($connect, $_POST['inspection']) : "";
    $salePStatus = isset($_POST['salePStatus']) ? mysqli_real_escape_string($connect, $_POST['salePStatus']) : "";
    $paid = isset($_POST['paid']) ? mysqli_real_escape_string($connect, $_POST['paid']) : "";
    $consultantNote = isset($_POST['consultantNote']) ? mysqli_real_escape_string($connect, $_POST['consultantNote']) : "";



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


        if (isset($_POST['consultantNote'])) {
            $saleTodoSql2 = "UPDATE sales INNER JOIN sale_todo ON sales.sale_id = sale_todo.sale_id 
            SET sales.consultant_notes = '$consultantNote'
            WHERE sale_todo.sale_todo_id = '$soldTodoId'";
            $connect->query($saleTodoSql2) === true;
        }


        if ($connect->query($saleTodoSql) === true) {
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
