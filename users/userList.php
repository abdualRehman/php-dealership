<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("user", "Edit") === 'false' && hasAccess("user", "Remove") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
?>

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
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
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
    <div class="modal-dialog">
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
                        <div class="form-group">
                            <label for="editusername">User Name</label>
                            <input type="text" class="form-control" id="editusername" name="editusername">
                            <!-- <small class="form-text text-muted">Please submit youremail</small> -->
                        </div>
                        <div class="form-group">
                            <label for="editemail">Email</label>
                            <input type="email" class="form-control" id="editemail" name="editemail">
                            <!-- <small class="form-text text-muted">Please submit youremail</small> -->
                        </div>
                        <div class="form-group">
                            <label for="editrole">Role</label>
                            <select id="editrole" name="editrole" class="form-control required">
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
                        <div class="form-group">
                            <label for="editpassword">Password</label>
                            <input type="password" class="form-control" id="editpassword" name="editpassword">
                            <!-- <small class="form-text text-muted">Please submit youremail</small> -->
                        </div>
                        <div class="form-group">
                            <label for="editconpassword">Confirm Password</label>
                            <input type="password" class="form-control" id="editconpassword" name="editconpassword">
                            <!-- <small class="form-text text-muted">Please submit youremail</small> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-primary mr-2">Save Changes</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Reset</button>
                </div>
            </form>

        </div>
    </div>
</div>



<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/userList.js"></script>