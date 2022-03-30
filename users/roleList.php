<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if ($_GET['r'] == 'add') {
    echo "<div class='div-request d-none'>add</div>";
} else if ($_GET['r'] == 'man') {
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'edit') {
    echo "<div class='div-request d-none'>edit</div>";
} // /else manage order
?>

<?php
if ($_GET['r'] == 'add') {
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">

                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Create A New Role</h3>
                            <a class="btn btn-info border" href="<?php echo $GLOBALS['siteurl']; ?>/users/roleList.php?r=man">View Roles</a>
                        </div>
                        <div class="portlet-body">
                            <div id="add-messages"></div>
                            <form class="form-horizontal" id="createRoleForm" action="../php_action/createRole.php" method="post">
                                <div class="row"><label for="roleName" class="col-sm-2 col-form-label">Role Name</label>
                                    <div class="form-group col-md-6 col-sm-10"><input type="text" class="form-control" id="roleName" name="roleName" placeholder="Please insert your role name"></div>
                                </div>
                                <div class="form-group row"><label for="roleDes" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-md-6 col-sm-10">
                                        <textarea class="form-control" id="roleDes" name="roleDes" rows="3"></textarea>
                                    </div>
                                </div>

                                <br>
                                <div class="alert alert-outline-primary fade show">
                                    <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                    <div class="alert-content"><strong>Permission can be granted by simply dragging a Functions block into the Permissions table.</strong>
                                    </div>
                                    <button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Modules</th>
                                            <th>Functions</th>
                                            <th>Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle text-center">
                                                <h3 class="h3">Users</h3>
                                            </td>

                                            <td>
                                                <div class="sortable rich-list rich-list-bordered rich-list-action bg-secondary p-2 rounded-lg" id="users-left">
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item border bg-primary">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">Add</span>
                                                                <input type="hidden" name="userAdd" value="false">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item bg-secondary">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">Edit</span>
                                                                <input type="hidden" name="userEdit" value="false">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item bg-danger">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">Remove</span>
                                                                <input type="hidden" name="userRemove" value="false">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </td>
                                            <td>
                                                <div class="sortable rich-list rich-list-bordered rich-list-action bg-secondary p-2 rounded-lg" id="users-right">
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item bg-success">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">View</span>
                                                                <input type="hidden" name="userView" value="true">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle text-center">
                                                <h3 class="h3">Inventory</h3>
                                            </td>
                                            <td>
                                                <div class="sortable rich-list rich-list-bordered rich-list-action bg-secondary p-2 rounded-lg" id="inv-left">
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item border bg-primary">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">Add</span>
                                                                <input type="hidden" name="invAdd" value="false">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item bg-secondary">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">Edit</span>
                                                                <input type="hidden" name="invEdit" value="false">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item bg-danger">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">Remove</span>
                                                                <input type="hidden" name="invRemove" value="false">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="sortable rich-list rich-list-bordered rich-list-action bg-secondary p-2 rounded-lg" id="inv-right">
                                                    <div class="sortable-item">
                                                        <div class="rich-list-item bg-success">
                                                            <div class="rich-list-content">
                                                                <span class="rich-list-subtitle text-light">View</span>
                                                                <input type="hidden" name="invView" value="false">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>


                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button class="btn btn-secondary">Cancel</button>
                            </form>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else if ($_GET['r'] == 'man') {
?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">

                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Role List</h3>
                            <a class="btn btn-info border" href="<?php echo $GLOBALS['siteurl']; ?>/users/roleList.php?r=add">Add New Role</a>

                        </div>
                        <div class="portlet-body">
                            <div class="remove-messages"></div>
                            <table id="datatable-1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Description</th>
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
<?php
} else if ($_GET['r'] == 'edit') {
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">

                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Edit Role</h3>
                            <a class="btn btn-info border" href="<?php echo $GLOBALS['siteurl']; ?>/users/roleList.php?r=man">View Roles</a>
                        </div>
                        <div class="portlet-body">

                            <div id="add-messages"></div>
                            <form class="form-horizontal" id="eidtRoleForm" action="../php_action/editRole.php" method="post">

                                <?php
                                $roleId = $_GET['i'];

                                $sql = "SELECT `role_name`, `role_des` FROM `role` WHERE `role_id` = '$roleId'";

                                $result = $connect->query($sql);
                                $data = $result->fetch_row();

                                ?>



                                <div class="row"><label for="editRoleName" class="col-sm-2 col-form-label">Role Name</label>
                                    <div class="form-group col-md-6 col-sm-10"><input type="text" class="form-control" id="editRoleName" name="editRoleName" value="<?php echo $data[0] ?>" placeholder="Please insert your role name"></div>
                                </div>
                                <div class="form-group row"><label for="editRoleDes" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-md-6 col-sm-10">
                                        <textarea class="form-control" id="editRoleDes" name="editRoleDes" rows="3"><?php echo $data[1] ?></textarea>
                                    </div>
                                </div>

                                <br>
                                <div class="alert alert-outline-primary fade show">
                                    <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                    <div class="alert-content"><strong>Permission can be granted by simply dragging a Functions block into the Permissions table.</strong>
                                    </div>
                                    <button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Modules</th>
                                            <th>Functions</th>
                                            <th>Permissions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php

                                        function serPermissions($row, $funName, $class, $hidenValue , $bool)
                                        {
                                            $html = "";
                                            if ($row['functions'] == $funName) {
                                                $html .= '<div class="sortable-item">
                                                            <div class="rich-list-item border ' . $class . '">
                                                                <div class="rich-list-content">
                                                                    <span class="rich-list-subtitle text-light">' . $funName . '</span>
                                                                    <input type="hidden" name="' . $hidenValue . '" value="'.$bool.'" >
                                                                </div>
                                                            </div>
                                                        </div>';
                                            }
                                            return $html;
                                        }

                                        // $itemSql = "SELECT `role_mod_id`, `modules`, `functions`, `permission` FROM `role_mod` WHERE role_id = {$roleId} ORDER BY  role_mod_id";
                                        $itemSql = "SELECT modules FROM `role_mod` WHERE role_id = {$roleId} GROUP BY modules ORDER by role_mod_id;";
                                        $resultItem = $connect->query($itemSql);

                                        while ($itemData = $resultItem->fetch_assoc()) {
                                            $module = $itemData['modules'];
                                            $Name = "";
                                            // setting module Name in HTML
                                            if ($module == 'users') {
                                                $Name = 'Users';
                                            } elseif ($module == 'inv') {
                                                $Name = "Inventory";
                                            }
                                        ?>
                                            <tr>
                                                <td>
                                                    <h3 class="h3"><?php echo $Name ?></h3>
                                                </td>
                                                <?php

                                                $itemPSql = "SELECT `role_mod_id`, `functions`, `permission` FROM `role_mod` WHERE role_id = '$roleId' and modules = '$module' ORDER BY role_mod_id";

                                                $resultPItem = $connect->query($itemPSql);


                                                echo '<td>
                                                    <div class="sortable rich-list rich-list-bordered rich-list-action bg-secondary p-2 rounded-lg" id="' . $module . '-left">
                                                ';

                                                
                                                foreach ($resultPItem as $itemPData) {
                                                    if ($itemPData['permission'] === 'false') {
                                                        if ($module === 'users') {
                                                            // check for Add
                                                            echo serPermissions($itemPData, 'Add', 'bg-primary', 'userAdd' , 'false');
                                                            echo serPermissions($itemPData, 'Edit', 'bg-secondary', 'userEdit' , 'false');
                                                            echo serPermissions($itemPData, 'Remove', 'bg-danger', 'userRemove' , 'false');
                                                            echo serPermissions($itemPData, 'View', 'bg-success', 'userView' , 'false');
                                                        } else if ($module === 'inv') {
                                                            // check for Inventory
                                                            echo serPermissions($itemPData, 'Add', 'bg-primary', 'invAdd' , 'false');
                                                            echo serPermissions($itemPData, 'Edit', 'bg-secondary', 'invEdit' , 'false');
                                                            echo serPermissions($itemPData, 'Remove', 'bg-danger', 'invRemove' , 'false');
                                                            echo serPermissions($itemPData, 'View', 'bg-success', 'invView' , 'false');
                                                        }
                                                    }
                                                    
                                                }
                                                echo '</div>
                                                    </td>
                                                    ';

                                                echo '<td>
                                                    <div class="sortable rich-list rich-list-bordered rich-list-action bg-secondary p-2 rounded-lg" id="'.$module. '-right">
                                                    ';

                                                
                                                foreach ($resultPItem as $itemPData) {

                                                    if ($itemPData['permission'] === 'true') {
                                                        if ($module === 'users') {
                                                            // check for Add
                                                            echo serPermissions($itemPData, 'Add', 'bg-primary', 'userAdd' , 'true');
                                                            echo serPermissions($itemPData, 'Edit', 'bg-secondary', 'userEdit' , 'true');
                                                            echo serPermissions($itemPData, 'Remove', 'bg-danger', 'userRemove' , 'true');
                                                            echo serPermissions($itemPData, 'View', 'bg-success', 'userView' , 'true');
                                                        } else if ($module === 'inv') {
                                                            // check for Inventory
                                                            echo serPermissions($itemPData, 'Add', 'bg-primary', 'invAdd' , 'true');
                                                            echo serPermissions($itemPData, 'Edit', 'bg-secondary', 'invEdit' , 'true');
                                                            echo serPermissions($itemPData, 'Remove', 'bg-danger', 'invRemove' , 'true');
                                                            echo serPermissions($itemPData, 'View', 'bg-success', 'invView' , 'true');
                                                        }
                                                    }

                                                    
                                                }
                                                echo '</div>
                                                    </td>
                                                    ';

                                                ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>



                                    </tbody>
                                </table>


                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="<?php echo $GLOBALS['siteurl']; ?>/users/roleList.php?r=man" class="btn btn-secondary">Go Back</a>
                                <input type="hidden" name="roleId" id="roleId" value="<?php echo $_GET['i']; ?>" />
                            </form>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
}
?>



<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/roleList.js"></script>