<?php

require_once 'db/db_connect.php';
require_once './sendEmail.php';

$valid['success'] = array('success' => false, 'messages' => array());

$email = $_POST['email'];

if ($email) {


    $checkSql = "SELECT * FROM `users` WHERE email = '$email' AND status = 1";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $forgot_request = $row['forgot_request'];
        $uid = $row['id'];
        $currentTime = strtotime(date("Y-m-d h:i:s")); //current timestamp

        if ($forgot_request != '' && $currentTime < $forgot_request) {
            $valid['success'] = false;
            $valid['messages'] = "Request Already Sent, Try Again after 5 minutes";
        } else {
            $mailed_user = sendEmail($row['email'], $row['username'], 'forgot');

            if ($mailed_user == "true") {
                $validFor = strtotime('+5 minutes', $currentTime);
                $sql = "UPDATE users SET forgot_request = '$validFor' WHERE id = '$uid'";

                if ($connect->query($sql) === TRUE) {
                    $valid['success'] = true;
                    $valid['messages'] = "Verification Email Sent";
                } else {
                    $valid['success'] = false;
                    $valid['messages'] = $connect->error;
                    $valid['messages'] = mysqli_error($connect);
                }
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Something Went Wrong, Try again later";
            }
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Email doesnot exists";
    }

    $connect->close();
    echo json_encode($valid);
} // /if $_POST