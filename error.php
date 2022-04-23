<?php include('php_action/db/core.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&amp;family=Roboto+Mono&amp;display=swap" rel="stylesheet">
    <link href="<?php echo $GLOBALS['siteurl']; ?>/assets/build/styles/ltr-core.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['siteurl']; ?>/assets/build/styles/ltr-vendor.css" rel="stylesheet">
    <title>404 - Not Found</title>
</head>

<body>
    <div class="holder">
        <div class="wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="row no-gutters align-items-center justify-content-center h-100">
                        <div class="col-md-8 col-lg-6 col-xl-4 text-center">
                            <h1 class="widget20">404</h1>
                            <h2 class="mb-3">Page Not Found!</h2>
                            <p class="mb-4">Sorry we can't seem to find the page you're looking for. There may be
                                amisspelling in the URL entered, or the page you are looking for may no longer exist.
                            </p><a href="index.php" class="btn btn-label-primary btn-lg btn-widest">Back to
                                home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

    <?php include('includes/footer.php') ?>