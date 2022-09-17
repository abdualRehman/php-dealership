<?php
require_once 'db/core.php';
require_once './updateRoles.php';

$valid['success'] = array('success' => false, 'messages' => array());
$id = $_POST['id'];

if ($id) {

    $sql = "UPDATE user_location SET status = 2 WHERE id = '$id'";

    if ($connect->query($sql) === TRUE) {

        removeDefaulRoles($id);


        $valid['success'] = true;
        $valid['messages'] = "Successfully Removed";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }

    $connect->close();

    echo json_encode($valid);
} // /if $_POST