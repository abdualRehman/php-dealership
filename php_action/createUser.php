<?php

require_once './db/core.php';
require_once './sendEmail.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {

    $uid = "";
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $color = $_POST['color'];
    // $password = $_POST['password'];
    // $conpassword = $_POST['conpassword'];


    $location = $_POST['location'];
    $extention = $_POST['extention'];
    $mobile = $_POST['mobile'];



    $monStart = $_POST['monStart'];
    $monEnd = ($monStart != "") ? $_POST['monEnd'] : "";
    $tueStart = $_POST['tueStart'];
    $tueEnd = ($tueStart != "") ? $_POST['tueEnd'] : "";
    $wedStart = $_POST['wedStart'];
    $wedEnd = ($wedStart != "") ? $_POST['wedEnd'] : "";
    $thuStart = $_POST['thuStart'];
    $thuEnd = ($thuStart != "") ? $_POST['thuEnd'] : "";
    $friStart = $_POST['friStart'];
    $friEnd = ($friStart != "") ? $_POST['friEnd'] : "";
    $satStart = $_POST['satStart'];
    $satEnd = ($satStart != "") ? $_POST['satEnd'] : "";
    $sunStart = $_POST['sunStart'];
    $sunEnd = ($sunStart != "") ? $_POST['sunEnd'] : "";




    $_password = randomPassword();
    $password = md5($_password);

    $checkSql = "SELECT users.* FROM users WHERE users.email = '$email' AND users.status = 1";
    $checkResult = $connect->query($checkSql);

    if ($checkResult->num_rows <= 0) {

        $sql = "INSERT INTO `users`( `username`, `email`, `password`, `role`, `status`, `permissions`, `location`, `extention`, `mobile` , `color`) 
                VALUES ('$username' , '$email' , '$password' , '$role' , 1 , '0' , '$location' , '$extention' , '$mobile' , '$color' )";

        if ($connect->query($sql) === true) {
            $uid = $connect->insert_id;

            $mailed_user = sendEmail($email, $username, 'register', $_password);

            $sql1 = "INSERT INTO `schedule`(`user_id`, `mon_start`, `mon_end`, `tue_start`, `tue_end`, `wed_start`, `wed_end`, `thu_start`, `thu_end`, `fri_start`, `fri_end`, `sat_start`, `sat_end`, `sun_start`, `sun_end`) 
                VALUES ('$uid' , '$monStart' , '$monEnd' , '$tueStart' , '$tueEnd' , '$wedStart' , '$wedEnd' , '$thuStart' , '$thuEnd' , '$friStart' , '$friEnd' , '$satStart' , '$satEnd' , '$sunStart' , '$sunEnd')";

            if ($connect->query($sql1) === true) {
                if ($mailed_user == 'true') {
                    $valid['success'] = true;
                    $valid['messages'] = "Successfully Added";
                } else {
                    $valid['success'] = true;
                    $valid['messages'] = "Successfully Added, Mail Failed to Send";
                }
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Email Already Exist";
    }





    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);

function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
