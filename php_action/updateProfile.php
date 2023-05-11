<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array());


if ($_POST) {

    $userId = $_SESSION['userId'];
    $formType = (isset($_POST['formType'])) ? mysqli_real_escape_string($connect, $_POST['formType']) : "";

    if ($formType == 'updateDetails') {
        $username = (isset($_POST['username'])) ? mysqli_real_escape_string($connect, $_POST['username']) : "";
        $email = (isset($_POST['email'])) ? mysqli_real_escape_string($connect, $_POST['email']) : "";

        $sql = "UPDATE `users` SET `username`='$username',`email`='$email' WHERE id = '$userId'";
        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } elseif ($formType == 'updatePasswords') {

        $password = (isset($_POST['password'])) ? mysqli_real_escape_string($connect, $_POST['password']) : "";
        $npassword = (isset($_POST['npassword'])) ? mysqli_real_escape_string($connect, $_POST['npassword']) : "";
        $conpassword = (isset($_POST['conpassword'])) ? mysqli_real_escape_string($connect, $_POST['conpassword']) : "";

        $currentPassword = md5($password);
        $newPassword = md5($npassword);
        $conformPassword = md5($conpassword);

        $sql = "SELECT * FROM users WHERE id = {$userId}";
        $query = $connect->query($sql);
        $result = $query->fetch_assoc();

        if ($currentPassword == $result['password']) {

            if ($newPassword == $conformPassword) {

                $updateSql = "UPDATE users SET password = '$newPassword' WHERE id = {$userId}";
                if ($connect->query($updateSql) === TRUE) {
                    $valid['success'] = true;
                    $valid['messages'] = "Successfully Updated";
                } else {
                    $valid['success'] = false;
                    $valid['messages'] = "Error while updating the password";
                }
            } else {
                $valid['success'] = false;
                $valid['messages'] = "New password does not match with Conform password";
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Current password is incorrect";
        }
    } elseif ($formType == 'updateProfile') {

        $fileName = $_FILES['wizard-picture']['name'];

        if (isset($fileName)) {
            if (empty($fileName)) {

                $uploadedImg = (isset($_POST['uploadedImg'])) ? mysqli_real_escape_string($connect, $_POST['uploadedImg']) : "";
                $sql = "UPDATE users SET profile='$uploadedImg' WHERE id = '$userId' ";

                $_SESSION['userProfile'] = $uploadedImg;
                if ($connect->query($sql) === true) {
                    $valid['success'] = true;
                    $valid['messages'] =  "Successfully Updated";
                } else {
                    $valid['success'] = true;
                    $valid['erorStock'] = $connect->error;
                }
            } else {

                $targetPath = "../assets/profiles/" . $_FILES['wizard-picture']['name'];
                $targetPath = mysqli_real_escape_string($connect, $targetPath);
                move_uploaded_file($_FILES['wizard-picture']['tmp_name'], $targetPath);

                $sql = "UPDATE users SET profile='$fileName' WHERE id = '$userId' ";

                $_SESSION['userProfile'] = $fileName;
                if ($connect->query($sql) === true) {
                    $valid['success'] = true;
                    $valid['messages'] =  "Successfully Updated";
                } else {
                    $valid['success'] = true;
                    $valid['erorStock'] = $connect->error;
                }
            }
        }
    } elseif ($formType == 'changeOTP') {
        $mobile = (isset($_POST['mobile'])) ? mysqli_real_escape_string($connect, $_POST['mobile']) : "";
        $otp_status = (isset($_POST['otp_status'])) ? mysqli_real_escape_string($connect, $_POST['otp_status']) : "";
        $otp_status = $otp_status == 'true' ? 1 : 0;

        if ($otp_status == 0 || ($otp_status == 1 && $mobile != '')) {
            $sql = "UPDATE `users` SET `mobile`='$mobile',`otp_status`='$otp_status' WHERE id = '$userId'";
            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Updated";
            } else {
                $valid['success'] = false;
                $valid['messages'] = $connect->error;
                $valid['messages'] = mysqli_error($connect);
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error: Number is Required";
        }
    }






    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);