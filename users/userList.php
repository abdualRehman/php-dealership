<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

// if (hasAccess("user", "Edit") === 'false' && hasAccess("user", "Remove") === 'false') {
//     echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
// }
if (hasAccess("user", "Edit") === 'false' && hasAccess("user", "Remove") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
?>

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

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1200px !important;
        }
    }

    .ui-timepicker-wrapper {
        width: 8.5em !important;
    }

    .jQWCP-wWidget {
        min-width: max-content !important;
        height: initial !important;
        z-index: 9999;
    }

    .dropdown-header.optgroup-1 {
        padding: 0px;
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">User List</h3>
                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Cell</th>
                                    <th>Extention</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal7">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove User</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Do you really want to remove ?</p>
            </div>
            <div class="modal-footer removeUserFooter">
                <button class="btn btn-primary mr-2" id="removeUserBtn">Confirm</button>
                <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal8">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit User</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editUserForm" action="../php_action/editUser.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="editUser-result">

                        <div class="row">
                            <div class="col-md-10 m-auto">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="editusername" class="col-form-label">User Name</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="editusername" id="editusername" autocomplete="off" autofill="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="location" class="col-form-label">Location</label>
                                        <div class="form-group">
                                            <?php
                                            if ($_SESSION['userRole'] == 'Admin') {
                                            ?>
                                                <select id="location" name="location" data-roleId="" class="selectpicker form-control required" onchange="fetchUserRolesByLocation()">
                                                    <option value="0" selected disabled>Select</option>
                                                    <?php
                                                    $sql = "SELECT `id`, `name` FROM `user_location` WHERE status = 1";
                                                    $result = $connect->query($sql);
                                                    while ($itemData = $result->fetch_assoc()) {
                                                        echo '<option value="' . $itemData['id'] . '">' . $itemData['name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            <?php
                                            } else {
                                                echo '<input type="hidden" class="form-control" name="location" value="' . $_SESSION['userLoc'] . '" id="location" autocomplete="off" autofill="off" />';
                                                echo '<input type="text" class="form-control" name="locationName" value="' . $_SESSION['userLocName'] . '" id="locationName" autocomplete="off" autofill="off" readonly />';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="editrole" class="col-form-label">Role</label>
                                        <div class="form-group">
                                            <select id="editrole" name="editrole" class="selectpicker form-control required">
                                                <option value="0">Select</option>
                                                <optgroup id="roleList">
                                                    <?php
                                                    $location = $_SESSION['userLoc'] != '' ? $_SESSION['userLoc'] : '1';
                                                    $sql = "SELECT `role_id`, `role_name` FROM `role` WHERE role_status != 2 AND location_id = '$location'";
                                                    $result = $connect->query($sql);
                                                    while ($itemData = $result->fetch_assoc()) {
                                                        echo '<option value="' . $itemData['role_id'] . '">' . $itemData['role_name'] . '</option>';
                                                    }
                                                    ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="editemail" class="col-form-label">Email</label>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="editemail" id="editemail" autocomplete="off">
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
                                            <input type="text" class="form-control" name="mobile" id="mobile" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="mobile" class="col-form-label">Color</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" data-wheelcolorpicker="" data-wcp-preview="true" id="color" name="color">
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
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                    <button type="reset" class="btn btn-outline-danger" data-dismiss="modal">Reset</button>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="modal9">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit User</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editPasswordForm" action="../php_action/editUserPasswords.php" method="post">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="editUserPassword-result">

                        <div class="row">
                            <div class="col-md-10 m-auto">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="editpassword" class="col-form-label">New Password</label>
                                        <div class="form-group"><input type="password" class="form-control" name="editpassword" id="editpassword" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="editconpassword" class="col-form-label">Confirm Password</label>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="editconpassword" id="editconpassword" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                    <button type="reset" class="btn btn-outline-danger" data-dismiss="modal">Reset</button>
                </div>
            </form>

        </div>
    </div>
</div>



<?php require_once('../includes/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-wheelcolorpicker@3.0.9/jquery.wheelcolorpicker.min.js"></script>
<script type="text/javascript" src="../custom/js/userList.js"></script>