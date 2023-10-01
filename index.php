<?php
require_once 'php_action/db/db_connect.php';

session_start();

if (isset($_SESSION['userId'])) {
    if (isset($_GET['redirect'])) {
        header('location:' . $_GET['redirect']);
    } else {
        header('location: dashboard.php');
    }
}

$errors = array();

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";
$url .= $_SERVER['HTTP_HOST'];

if ($url == 'http://localhost') {
    $url .= "/carshop";
}
// echo $url;
$_SESSION['siteurl'] = $url;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="assets/build/styles/ltr-core.css" rel="stylesheet">
    <link href="assets/build/styles/ltr-vendor.css" rel="stylesheet">
    <title>Login</title>
    <script>
        let domain = (new URL(window.location));
        domain = domain.origin;
        if (domain == 'http://localhost') {
            domain += '/carshop';
        }
        console.log(domain);
        var siteURL = domain;
        // var siteURL = 'http://localhost/carshop';
        // var siteURL = 'http://onedealersystem.com';
        // var siteURL = 'https://www.laughingalbattani5c25df.binfarooqtextile.com';
        localStorage.setItem('siteURL', siteURL);
    </script>
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
                                    <?php
                                    // if ($errors) {
                                    //     foreach ($errors as $key => $value) {
                                    //         echo '<div class="alert alert-danger show d-flex w-100 justify-content-between">
                                    //                 <div class="widget18">
                                    //                 <div class="widget18-icon"><i data-feather="alert-circle"></i></div>
                                    //                 <span class="widget18-text">' . $value . '</span>
                                    //                 </div>
                                    //                 <button type="button" class="btn btn-text-light btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                    //         </div>';
                                    //     }
                                    // } 
                                    ?>

                                    <div id="login-error-message"></div>
                                    <form id="login-form" action="php_action/login.php" method="post">
                                        <div class="form-group">
                                            <div class="float-label float-label-lg"><input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="Please insert your email" autocomplete="username"> <label for="email">Email</label></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="float-label float-label-lg"><input class="form-control form-control-lg" type="password" id="password" name="password" placeholder="Please insert your password" autocomplete="current-password"> <label for="password">Password</label></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="form-group mb-0">
                                                <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="remember" name="remember"> <label class="custom-control-label" for="remember">Remember me</label></div>
                                            </div>
                                            <a href="#" id="forgot-password">Forgot password?</a>
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

    <script>
        $('#forgot-password').on('click', function() {
            event.preventDefault();

            Swal.fire({
                title: 'Please Submit your Email Address',
                input: 'email',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Send Varification Link?',
                showLoaderOnConfirm: true,
                preConfirm: (email) => {
                    return $.ajax({
                        url: 'php_action/forgotPassword.php',
                        type: "POST",
                        dataType: 'json',
                        data: {
                            email: email
                        },
                        success: function(response) {
                            return JSON.stringify(response);
                        },
                        error: function(error) {
                            console.log(error);
                            Swal.showValidationMessage(
                                `Request failed: ${error.statusText}`
                            )
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: `${result.value.success == 'true' ? 'Success' : 'Failed'}`,
                        title: `${result.value.messages}`,
                    })
                }
            })
        })
    </script>

    <script>
        $("#login-form").validate({
            rules: {
                email: {
                    required: !0,
                },
                password: {
                    required: !0,
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                var form = $('#login-form');
                $.ajax({
                    url: 'php_action/login.php',
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (!response.send_otp) {
                            if (response.success == false) {
                                $('#login-error-message').html(`<div class="alert alert-danger show d-flex w-100 justify-content-between">
                                    <div class="widget18">
                                    <div class="widget18-icon"><i data-feather="alert-circle"></i></div>
                                    <span class="widget18-text">${response.messages}</span>
                                    </div>
                                    <button type="button" class="btn btn-text-light btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                </div>`);
                            } else {
                                window.location.replace("dashboard.php");
                            }
                        } else {
                            Swal.fire({
                                title: 'Please Enter your OTP',
                                input: 'text',
                                inputAttributes: {
                                    autocapitalize: 'off'
                                },
                                showCancelButton: true,
                                showCloseButton: false,
                                confirmButtonText: 'Submit',
                                cancelButtonText: 'Cancel and Resend',
                                showLoaderOnConfirm: true,
                                preConfirm: (text) => {
                                    if (text != "") {
                                        // console.log(text);
                                        return $.ajax({
                                            url: 'php_action/confirm_otp.php',
                                            type: "POST",
                                            dataType: 'json',
                                            data: {
                                                text: text
                                            },
                                            success: function(response) {
                                                console.log("response", response);
                                                if (response.success == false) {
                                                    Swal.showValidationMessage(`Wrong OTP`);
                                                } else {
                                                    return JSON.stringify(response);

                                                }
                                            },
                                            error: function(error) {
                                                console.log(error);
                                                Swal.showValidationMessage(
                                                    `Request failed: ${error.statusText}`
                                                )
                                            }
                                        });
                                    } else {
                                        Swal.showValidationMessage(`Field is required`);
                                        !Swal.isLoading();
                                    }
                                },
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then((result) => {
                                console.log(result);
                                if (result.value?.success == true) {
                                    window.location.replace("dashboard.php");
                                } else if (result.value?.success == false) {
                                    Swal.showValidationMessage(`Wrong OTP`);
                                } else {
                                    if (result.isConfirmed) {
                                        Swal.fire({
                                            title: `${result.value.success == 'true' ? 'Success' : 'Failed'}`,
                                            title: `${result.value.messages}`,
                                        })
                                    }

                                }
                            })

                        }
                    },
                    error: function(error) {
                        console.log("error", error);
                    }
                });
                return false;




            }
        });
    </script>



</body>


</html>