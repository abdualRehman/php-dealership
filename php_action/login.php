<?php

// require_once './db/core.php';
require_once './db/db_connect.php';
$valid = array('success' => false, 'messages' => array(), 'send_otp' => false);

session_start();
if (isset($_SESSION['userId'])) {
    if (isset($_GET['redirect'])) {
        header('location:' . $_GET['redirect']);
    } else {
        header('location: ../dashboard.php');
    }
}


function handleUserData($value)
{
    global $connect, $valid;

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
    // $output = array('data' => array());
    $output = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // $output['data'][] = array($row);
            array_push($output, $row);
        }
    }
    $_SESSION['permissionsArray'] = $output;

    $valid['success'] = true;
    $valid['messages'] = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];


    if (empty($email) || empty($password)) {
        if ($email == "") {
            $valid['success'] = false;
            $valid['messages'] = "Email is required";
        }

        if ($password == "") {
            $valid['success'] = false;
            $valid['messages'] = "Password is required";
        }
    } else {

        $sql = "SELECT * FROM users WHERE email = '$email' AND status = 1";
        $result = $connect->query($sql);

        if ($result->num_rows == 1) {
            $fRow = $result->fetch_assoc();

            $password = md5($password);
            // echo $password;
            // exists
            $mainSql = "SELECT users.* , role.role_name , user_location.name as locName FROM users LEFT JOIN role ON users.role = role.role_id LEFT JOIN user_location ON users.location = user_location.id WHERE users.email = '$email' AND users.password = '$password' AND users.status = 1 " . ($fRow['role'] == 'Admin' ? "" : " AND user_location.status = 1") . "";
            $mainResult = $connect->query($mainSql);

            if ($mainResult->num_rows == 1) {
                $value = $mainResult->fetch_assoc();

                $user_id = $value['id'];

                $otp_status = $value['otp_status'];

                handleUserData($value);
                // if ($otp_status == 0) {
                //     handleUserData($value);
                // } else {
                //     $digits = 6;
                //     $otp = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                //     $sql4 = "UPDATE `users` SET `otp_code` = '$otp' WHERE `users`.`id` = '$user_id'";
                //     $connect->query($sql4);
                //     $_SESSION['otp_auth'] = $user_id;
                //     $valid['send_otp'] = true;
                //     $valid['success'] = false;
                //     $valid['messages'] = "";
                // }
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Incorrect email/password combination";
            } // /else
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Email doesnot exists";
        } // /else
    } // /else not empty email // password



    $connect->close();
    echo json_encode($valid);
} else {
    header('location: ../dashboard.php');
}
