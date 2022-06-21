<?php

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {


    $editpassword = $_POST['editpassword'];
    $editconpassword = $_POST['editconpassword'];
    $userId = $_POST['userpasswordId'];



    if ($editpassword == $editconpassword) {
        $editpassword = md5($editpassword);
        $sql = "UPDATE users SET  `password` = '$editpassword' WHERE `id` = '$userId'";

        if ($connect->query($sql) === TRUE) {
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