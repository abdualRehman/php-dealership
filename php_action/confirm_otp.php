<?php

require_once 'db/db_connect.php';
session_start();
$valid['success'] = array('success' => false, 'messages' => array());


if ($_POST) {

    if (isset($_SESSION['otp_auth'])) {

        $text = $_POST['text'];
        $user_id = $_SESSION['otp_auth'];

        $checkSql = "SELECT users.* , role.role_name , user_location.name as locName FROM users LEFT JOIN role ON users.role = role.role_id LEFT JOIN user_location ON users.location = user_location.id WHERE users.otp_code = '$text' AND users.id='$user_id' AND users.status = 1";
        $result = $connect->query($checkSql);
        if ($result->num_rows > 0) {

            $_SESSION['otp_auth'] = "";

            $value = $result->fetch_assoc();

            $user_id = $value['id'];
            $role_id = $value['role'];
            // set session
            $_SESSION['userId'] = $user_id;
            $_SESSION['userName'] = $value['username'];
            $_SESSION['userEmail'] = $value['email'];
            $_SESSION['userProfile'] = $value['profile'];
            $_SESSION['userRoleName'] = $value['role_name'];
            $_SESSION['userRole'] = $role_id;
            $_SESSION['userLoc'] = $value['location'] != '' ? $value['location'] : 1;
            $_SESSION['userLocName'] = $value['locName'];

            $checkSql = "SELECT modules , functions , permission FROM `role_mod` WHERE role_id = '$role_id'";
            $result = $connect->query($checkSql);
            $output = array();
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($output, $row);
                }
            }
            $_SESSION['permissionsArray'] = $output;
            $valid['success'] = true;
            $valid['messages'] = "Success";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "OTP Expire";
        }


        $connect->close();
        echo json_encode($valid);
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Something Wrong! Try again later";
    }
} // /if $_POST