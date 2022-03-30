<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $conpassword = $_POST['conpassword'];

    if ($password == $conpassword) {
        $password = md5($password);

        $sql = "INSERT INTO `users`( `username`, `email`, `password`, `role`, `status`, `permissions`) 
            VALUES ('$username' , '$email' , '$password' , '$role' , 1 , '0' )";

        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        }else{
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }



    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);