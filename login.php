<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&amp;family=Roboto+Mono&amp;display=swap" rel="stylesheet">
    <link href="./assets/build/styles/ltr-core.css" rel="stylesheet">
    <link href="./assets/build/styles/ltr-vendor.css" rel="stylesheet">
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
                                    <form id="login-form">
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

    <?php include('includes/footer.php') ?>
    <script type="text/javascript" src="./assets/app/pages/login.js"></script>