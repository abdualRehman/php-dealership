<?php
require_once 'php_action/db/db_connect.php';

session_start();


if (isset($_SESSION['userId'])) {

    header('location: dashboard.php');
}

$errors = array();

if ($_POST) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        if ($email == "") {
            $errors[] = "Email is required";
        }

        if ($password == "") {
            $errors[] = "Password is required";
        }
    } else {

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $connect->query($sql);

        if ($result->num_rows == 1) {
            $password = md5($password);
            // exists
            $mainSql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
            $mainResult = $connect->query($mainSql);

            if ($mainResult->num_rows == 1) {
                $value = $mainResult->fetch_assoc();
                $user_id = $value['id'];

                // set session
                $_SESSION['userId'] = $user_id;
                $_SESSION['userRole'] = $value['role'];

                header('location: dashboard.php');
            } else {
                $errors[] = "Incorrect email/password combination";
            } // /else
        } else {
            $errors[] = "Email doesnot exists";
        } // /else
    } // /else not empty email // password
} // /if $_POST


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&amp;family=Roboto+Mono&amp;display=swap" rel="stylesheet">
    <link href="assets/build/styles/ltr-core.css" rel="stylesheet">
    <link href="assets/build/styles/ltr-vendor.css" rel="stylesheet">
    <title>Login</title>
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
                            <div class="portlet">
                                <div class="portlet-body">
                                    <div class="text-center mb-4">
                                        <div class="avatar avatar-label-primary avatar-circle widget12">
                                            <div class="avatar-display"><i class="fa fa-user-alt"></i></div>
                                        </div>
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
                                    <form id="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                        <div class="form-group">
                                            <div class="float-label float-label-lg"><input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="Please insert your email"> <label for="email">Email</label></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="float-label float-label-lg"><input class="form-control form-control-lg" type="password" id="password" name="password" placeholder="Please insert your password"> <label for="password">Password</label></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="form-group mb-0">
                                                <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="remember" name="remember"> <label class="custom-control-label" for="remember">Remember me</label></div>
                                            </div><a href="#">Forgot password?</a>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="submit" class="btn btn-label-primary btn-lg btn-widest">Login</button>
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
</body>
<!-- Mirrored from dashboard1.panely-html.blueupcode.com/ltr/pages/login/login-1.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 12 Feb 2022 08:09:04 GMT -->

</html>