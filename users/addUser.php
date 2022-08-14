<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("user", "Add") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/jquery-wheelcolorpicker@3.0.9/css/wheelcolorpicker.css" rel="stylesheet" />
    <link rel="stylesheet" href="../custom/css/customDatatable.css">

    <style>
        body.theme-light #scheduleTable .was-validated .form-control:valid {
            color: #424242;
            background: #fff;
            border-color: #e0e0e0;
            padding: 5px !important;
            background-image: none !important;
        }

        body.theme-dark #scheduleTable .was-validated .form-control:valid {
            color: #f5f5f5;
            background: #424242;
            border-color: #9e9e9e;
            padding: 5px !important;
            background-image: none !important;
        }

        .ui-timepicker-wrapper {
            width: 8.5em !important;
        }

        .jQWCP-wWidget {
            min-width: max-content!important;
            height: initial!important;
        }
    </style>


</head>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Add User</h3>
                    </div>
                    <div class="portlet-body">
                        <form id="addUserForm" autocomplete="off" method="post" action="../php_action/createUser.php">
                            <div class="row justify-content-center">
                                <div class="form-group form-group col-sm-10" id="add-messages">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 m-auto">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="username" class="col-form-label">User Name</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Please insert user name" autocomplete="off" autofill="off" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="email" class="col-form-label">Email</label>
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Please insert your email" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="role" class="col-form-label">Role</label>
                                            <div class="form-group">
                                                <select id="role" name="role" class="form-control required">
                                                    <option value="0">Select</option>
                                                    <?php
                                                    $sql = "SELECT `role_id`, `role_name` FROM `role` WHERE role_status != 2";
                                                    $result = $connect->query($sql);
                                                    while ($itemData = $result->fetch_assoc()) {
                                                        echo '<option value="' . $itemData['role_id'] . '">' . $itemData['role_name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="location" class="col-form-label">Location</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="location" id="location" autocomplete="off" autofill="off" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="extention" class="col-form-label">Extention</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="extention" id="extention" autocomplete="off" autofill="off" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="mobile" class="col-form-label">Mobile</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Please insert your mobile number" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="mobile" class="col-form-label">Color</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" data-wheelcolorpicker="" data-wcp-preview="true" id="color" name="color" >
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="password" class="col-form-label">Password</label>
                                            <div class="form-group"><input type="password" class="form-control" name="password" id="password" autocomplete="off" placeholder="Please provide your password">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="conpassword" class="col-form-label">Confirm Password</label>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="conpassword" id="conpassword" autocomplete="off" placeholder="Please reenter your password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5 mb-5" style="overflow:auto;">
                                <div class="col-md-12 m-auto">
                                    <table id="scheduleTable" class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th colspan="7">
                                                    <h2>Schedule</h2>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Monday</th>
                                                <th>Tuesday</th>
                                                <th>Wednesday</th>
                                                <th>Thursday</th>
                                                <th>Friday</th>
                                                <th>Saturday</th>
                                                <th>Sunday</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="monStart" name="monStart">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="monEnd" name="monEnd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="tueStart" name="tueStart">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="tueEnd" name="tueEnd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="wedStart" name="wedStart">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="wedEnd" name="wedEnd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="thuStart" name="thuStart">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="thuEnd" name="thuEnd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="friStart" name="friStart">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="friEnd" name="friEnd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="satStart" name="satStart">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="satEnd" name="satEnd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="sunStart" name="sunStart">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group was-validated">
                                                                <input type="text" class="form-control timeInterval" id="sunEnd" name="sunEnd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group text-center mb-0">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-wheelcolorpicker@3.0.9/jquery.wheelcolorpicker.min.js"></script>
<script type="text/javascript" src="../custom/js/addUser.js"></script>