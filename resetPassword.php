<head>
    <script type="text/javascript" src="assets/build/scripts/mandatory.js"></script>
    <script type="text/javascript" src="assets/build/scripts/core.js"></script>
    <script type="text/javascript" src="assets/app/pages/login.js"></script>
    <script type="text/javascript" src="assets/build/scripts/vendor.js"></script>
</head>
<?php
require_once 'php_action/db/db_connect.php';
session_start();

$url = '';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";
$url .= $_SERVER['HTTP_HOST'];

if ($url == 'http://localhost') {
    $url .= "/carshop";
}




$exp = $_GET['exp'];
$exp = base64_decode(urldecode($exp));
$currentTime = strtotime(date("Y-m-d h:i:s"));
if ($currentTime > $exp) {
    echo "<script>location.href='" . $url . "/error.php';</script>";
}


$actype = $_GET['actype'];
$actype = base64_decode(urldecode($actype));


$errors = array();
if ($_POST) {

    $email = $_GET['id'];
    $email = base64_decode(urldecode($email));

    $newpassword = $_POST['newpassword'];
    $conpassword = $_POST['conpassword'];
    $oldpassword = '';
    if ($actype == 'reset') {
        $oldpassword = $_POST['oldpassword'];
        if ($oldpassword == "" || empty($oldpassword)) {
            $errors[] = "Old Password is required";
        }
    }


    if (empty($newpassword) || empty($conpassword)) {
        if ($newpassword == "") {
            $errors[] = "Password is required";
        }

        if ($conpassword == "") {
            $errors[] = "Please Re-enter Password";
        }
    } else if ($newpassword != $conpassword) {
        $errors[] = "Passwords Should be Match";
    } else {

        if ($actype == 'reset') {
            $oldpassword = md5($oldpassword);
            $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$oldpassword'";
        } else {
            $sql = "SELECT * FROM users WHERE email = '$email'";
        }
        $result = $connect->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $newpassword = md5($newpassword);
            // exists
            $mainSql = "UPDATE `users` SET `password` = '$newpassword' WHERE users.id = '$id'";
            if ($connect->query($mainSql) == TRUE) {

                echo "<script>
                setTimeout(function () {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Password Successfully Changed',
                        showConfirmButton: true,
                        confirmButtonText: 'Go to Login Page!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href='" . $url . "/index.php';
                        }
                    })
                }, 2000);
                </script>";
            } else {
                echo "<script>
                setTimeout(function () {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Something Went Wrong',
                        showConfirmButton: true,
                        confirmButtonText: 'Try Again',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.history.back()
                        }
                    });
                }, 2000);
                </script>";
            }

            // remove all session variables
            session_unset();
            // destroy the session 
            session_destroy();


            // header('location: dashboard.php');
        } else {
            $errors[] = "User doesnot exists";
        } // /else

    } // /else not empty email // password
} // /if $_POST

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="assets/build/styles/ltr-core.css" rel="stylesheet">
    <link href="assets/build/styles/ltr-vendor.css" rel="stylesheet">
    <title>Forgot Password</title>
</head>

<body class="theme-light preload-active" id="fullscreen">
    <div class="preload">
        <div class="preload-dialog">
            <div class="spinner-border text-primary preload-spinner"></div>
        </div>
    </div>
    <div class="holder">
        <div class="wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="row no-gutters align-items-center justify-content-center h-100">
                        <div class="col-sm-8 col-md-6 col-lg-4 col-xl-3">
                            <div class="portlet" style="border-color:#d7d4d4;">
                                <div class="portlet-body">
                                    <div class="text-center mb-4">
                                        <h3 class="h3 portlet-title text-bold">Change Password</h3>
                                    </div>
                                    <?php if ($errors) {
                                        foreach ($errors as $key => $value) {
                                            echo '<div class="alert alert-danger show d-flex w-100 justify-content-between">
                                                    <div class="widget18">
                                                    <div class="widget18-icon"><i data-feather="alert-circle"></i></div>
                                                    <span class="widget18-text">' . $value . '</span>
                                                    </div>
                                                    <button type="button" class="btn btn-text-light btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                            </div>';
                                        }
                                    } ?>
                                    <form id="password-form" action="" method="post">
                                        <?php
                                        if ($actype == 'reset') {
                                        ?>
                                            <div class="form-group">
                                                <label class="form-label form-label-lg" for="oldpassword">Old Password</label>
                                                <input class="form-control form-control-lg" type="password" id="oldpassword" name="oldpassword" autocomplete="off">
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div class="form-group">
                                            <label class="form-label form-label-lg" for="newpassword">New Password</label>
                                            <input class="form-control form-control-lg" type="password" id="newpassword" name="newpassword" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label form-label-lg" for="conpassword">Confirm Password</label>
                                            <input class="form-control form-control-lg" type="password" id="conpassword" name="conpassword" autocomplete="off">
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="submit" class="btn btn-label-primary btn-lg btn-widest">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- <script type="text/javascript" src="./assets/app/pages/login.js"></script> -->
    <div class="float-btn float-btn-right"><button class="btn btn-flat-primary btn-icon mb-2" id="theme-toggle" data-toggle="tooltip" data-placement="right" title="Change theme"><i class="fa fa-moon"></i></button>
    </div>
    <script type="text/javascript" src="assets/build/scripts/mandatory.js"></script>
    <script type="text/javascript" src="assets/build/scripts/core.js"></script>
    <script type="text/javascript" src="assets/app/pages/login.js"></script>
    <script type="text/javascript" src="assets/build/scripts/vendor.js"></script>


    <script>
        $("#password-form").validate({
            rules: {
                oldpassword: {
                    required: !0,
                },
                newpassword: {
                    required: !0,
                    minlength: 6
                },
                conpassword: {
                    required: !0,
                    minlength: 6,
                    equalTo: "#newpassword"
                },
            },
            messages: {
                newpassword: {
                    required: "Please provide your password",
                    minlength: $.validator.format("Please enter at least {0} characters")
                },
                conpassword: {
                    required: "Please reenter your password",
                    minlength: $.validator.format("Please enter at least {0} characters"),
                    equalTo: $.validator.format("Password is not Match"),
                },
            },
            submitHandler: function(form, e) {
                return true;
            }
        })
    </script>







</body>


</html>