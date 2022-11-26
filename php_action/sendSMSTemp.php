<?php
require_once './sendSMS.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array(), 'sms_status' => array());

$errors = array();

if ($_POST) {

    $number = $_POST['number'];

    $message = "
        1 DEALER SYSTEM: reply Yes to subscribe to mobile alerts. " ."\r\n"."
        You will receive text messages for registration problems, Today’s availability & Scheduling Delivery coordinator. Consent is not required. Reply STOP to cancel";

    if ($number != '') {
        // echo $number . '<br />';
        // $errors[] = sendSMS1("+92" . $number, $message);
        $errors[] = sendSMS1("+1" . $number, $message);
    }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>SEND SMS TEMP</title>
</head>

<body class="theme-light preload-active" id="fullscreen">
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
                                    <form id="login-form1" action="" method="post">
                                        <div class="form-group">
                                            <div class="float-label float-label-lg">
                                                <label for="number1">Send Initial Message</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <div class="float-label float-label-lg">
                                                <label for="number">Number</label>
                                                <input class="form-control form-control-lg" type="text" id="number" name="number" placeholder="Please insert your number">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="submit" class="btn btn-label-primary btn-lg btn-widest">SEND</button>
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
</body>

</html>