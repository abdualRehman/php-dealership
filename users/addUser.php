<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("user", "Add") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

?>

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
                            <div class="row"><label for="username" class="col-sm-3 offset-sm-1 col-form-label text-right">User Name</label>
                                <div class="form-group col-sm-6">
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Please insert user name" autocomplete="false" autofill="off" />
                                </div>
                            </div>
                            <div class="row">
                                <label for="email" class="col-sm-3 offset-sm-1 col-form-label text-right">Email</label>
                                <div class="form-group col-sm-6">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Please insert your email">
                                </div>
                            </div>

                            <div class="row">
                                <label for="role" class="col-sm-3 offset-sm-1 col-form-label text-right">Role</label>
                                <div class="form-group col-sm-6">

                                    <select id="role" name="role" class="form-control required">
                                        <option value="0">Select</option>
                                        <?php
                                        $sql = "SELECT `role_id`, `role_name` FROM `role` WHERE role_status = 1";
                                        $result = $connect->query($sql);
                                        while ($itemData = $result->fetch_assoc()) {
                                            echo '<option value="' . $itemData['role_id'] . '">' . $itemData['role_name'] . '</option>';
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="row"><label for="password" class="col-sm-3 offset-sm-1 col-form-label text-right">Password</label>
                                <div class="form-group col-sm-6"><input type="password" class="form-control" name="password" id="password" placeholder="Please provide your password">
                                </div>
                            </div>
                            <div class="row"><label for="conpassword" class="col-sm-3 offset-sm-1 col-form-label text-right">Confirm Password</label>
                                <div class="form-group col-sm-6"><input type="password" class="form-control" name="conpassword" id="conpassword" placeholder="Please reenter your password">
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
<script type="text/javascript" src="../custom/js/addUser.js"></script>