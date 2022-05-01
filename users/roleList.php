<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if ($_GET['r'] == 'add') {
    if (hasAccess("role", "Add") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>add</div>";
} else if ($_GET['r'] == 'man') {
    if (hasAccess("role", "Add") === 'false' && hasAccess("role", "Edit") === 'false' && hasAccess("role", "Remove") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'edit') {
    if (hasAccess("role", "Edit") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>edit</div>";
} // /else manage order
?>
<style>
    .custom-control {
        margin: auto 40px;
    }

    .tableDiv {
        overflow-x: auto;
    }
</style>

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

                                <hr>
                                <h4 class="h4">Set Permissions</h4>
                                <div class="tableDiv">
                                    <table class="table table-bordered" style="max-width:80%;margin:auto; font-size:large">
                                        <thead>
                                            <tr>
                                                <th>Modules</th>
                                                <th>Permissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">Administer</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="checkALL" name="checkALL">
                                                            <label class="custom-control-label h5" for="checkALL">Check All</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">MATRIX</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="matrixView" name="matrixView">
                                                            <label class="custom-control-label h5" for="matrixView">View</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">SWAPS</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="swapAdd" name="swapAdd">
                                                            <label class="custom-control-label h5" for="swapAdd">ADD</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="swapEdit" name="swapEdit">
                                                            <label class="custom-control-label h5" for="swapEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="swapRemove" name="swapRemove">
                                                            <label class="custom-control-label h5" for="swapRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">INCENTIVES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="incentivesEdit" name="incentivesEdit">
                                                            <label class="custom-control-label h5" for="incentivesEdit">EDIT</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">INVENTORY</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox" style="margin-right: -18px;">
                                                            <input type="checkbox" class="custom-control-input" id="invAdd" name="invAdd">
                                                            <label class="custom-control-label h5" for="invAdd">Add/Import</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="invEdit" name="invEdit">
                                                            <label class="custom-control-label h5" for="invEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="invRemove" name="invRemove">
                                                            <label class="custom-control-label h5" for="invRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">SALE</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="saleAdd" name="saleAdd">
                                                            <label class="custom-control-label h5" for="saleAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="saleEdit" name="saleEdit">
                                                            <label class="custom-control-label h5" for="saleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="saleRemove" name="saleRemove">
                                                            <label class="custom-control-label h5" for="saleRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">TO DO'S</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="todoEdit" name="todoEdit">
                                                            <label class="custom-control-label h5" for="todoEdit">EDIT</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">REGISTRATION PROBLEMS</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="regpAdd" name="regpAdd">
                                                            <label class="custom-control-label h5" for="regpAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="regpEdit" name="regpEdit">
                                                            <label class="custom-control-label h5" for="regpEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="regpRemove" name="regpRemove">
                                                            <label class="custom-control-label h5" for="regpRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">USERS</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="userAdd" name="userAdd">
                                                            <label class="custom-control-label h5" for="userAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="userEdit" name="userEdit">
                                                            <label class="custom-control-label h5" for="userEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="userRemove" name="userRemove">
                                                            <label class="custom-control-label h5" for="userRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">ROLES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="roleAdd" name="roleAdd">
                                                            <label class="custom-control-label h5" for="roleAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="roleEdit" name="roleEdit">
                                                            <label class="custom-control-label h5" for="roleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="roleRemove" name="roleRemove">
                                                            <label class="custom-control-label h5" for="roleRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">INCENTIVE RULES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="incrAdd" name="incrAdd">
                                                            <label class="custom-control-label h5" for="incrAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="incrEdit" name="incrEdit">
                                                            <label class="custom-control-label h5" for="incrEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="incrRemove" name="incrRemove">
                                                            <label class="custom-control-label h5" for="incrRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">SALES PERSONS'S TODO RULES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="sptrAdd" name="sptrAdd">
                                                            <label class="custom-control-label h5" for="sptrAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="sptrEdit" name="sptrEdit">
                                                            <label class="custom-control-label h5" for="sptrEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="sptrRemove" name="sptrRemove">
                                                            <label class="custom-control-label h5" for="sptrRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">SWAP LOCATION</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="swplocAdd" name="swplocAdd">
                                                            <label class="custom-control-label h5" for="swplocAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="swplocEdit" name="swplocEdit">
                                                            <label class="custom-control-label h5" for="swplocEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="swplocRemove" name="swplocRemove">
                                                            <label class="custom-control-label h5" for="swplocRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">NEW CAR PRICE BY MANUFACTURE</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="manpriceAdd" name="manpriceAdd">
                                                            <label class="custom-control-label h5" for="manpriceAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="manpriceEdit" name="manpriceEdit">
                                                            <label class="custom-control-label h5" for="manpriceEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="manpriceRemove" name="manpriceRemove">
                                                            <label class="custom-control-label h5" for="manpriceRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">MATRIX RULES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="matrixruleAdd" name="matrixruleAdd">
                                                            <label class="custom-control-label h5" for="matrixruleAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="matrixruleEdit" name="matrixruleEdit">
                                                            <label class="custom-control-label h5" for="matrixruleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="matrixruleRemove" name="matrixruleRemove">
                                                            <label class="custom-control-label h5" for="matrixruleRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">BDC RULES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="bdcruleAdd" name="bdcruleAdd">
                                                            <label class="custom-control-label h5" for="bdcruleAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="bdcruleEdit" name="bdcruleEdit">
                                                            <label class="custom-control-label h5" for="bdcruleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="bdcruleRemove" name="bdcruleRemove">
                                                            <label class="custom-control-label h5" for="bdcruleRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">RATE RULES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="rateruleAdd" name="rateruleAdd">
                                                            <label class="custom-control-label h5" for="rateruleAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="rateruleEdit" name="rateruleEdit">
                                                            <label class="custom-control-label h5" for="rateruleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="rateruleRemove" name="rateruleRemove">
                                                            <label class="custom-control-label h5" for="rateruleRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">LEASE RULES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="leaseruleAdd" name="leaseruleAdd">
                                                            <label class="custom-control-label h5" for="leaseruleAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="leaseruleEdit" name="leaseruleEdit">
                                                            <label class="custom-control-label h5" for="leaseruleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="leaseruleRemove" name="leaseruleRemove">
                                                            <label class="custom-control-label h5" for="leaseruleRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">DEALER CASH INCENTIVE RULES</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="cashincruleAdd" name="cashincruleAdd">
                                                            <label class="custom-control-label h5" for="cashincruleAdd">Add</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="cashincruleEdit" name="cashincruleEdit">
                                                            <label class="custom-control-label h5" for="cashincruleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="cashincruleRemove" name="cashincruleRemove">
                                                            <label class="custom-control-label h5" for="cashincruleRemove">REMOVE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">Lot Wazards</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="lotWizardsAdd" name="lotWizardsAdd">
                                                            <label class="custom-control-label h5" for="lotWizardsAdd">Add</label>
                                                        </div>
                                                        <!-- <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="cashincruleEdit" name="cashincruleEdit">
                                                            <label class="custom-control-label h5" for="cashincruleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="cashincruleRemove" name="cashincruleRemove">
                                                            <label class="custom-control-label h5" for="cashincruleRemove">REMOVE</label>
                                                        </div> -->
                                                    </div>
                                                </td>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">Used Cars</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="usedCarsAdd" name="usedCarsAdd">
                                                            <label class="custom-control-label h5" for="usedCarsAdd">Add</label>
                                                        </div>
                                                        <!-- <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="cashincruleEdit" name="cashincruleEdit">
                                                            <label class="custom-control-label h5" for="cashincruleEdit">EDIT</label>
                                                        </div>
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="cashincruleRemove" name="cashincruleRemove">
                                                            <label class="custom-control-label h5" for="cashincruleRemove">REMOVE</label>
                                                        </div> -->
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <hr class="my-5">

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
                            <?php
                            if (hasAccess("role", "Add") !== 'false') {
                            ?>
                                <a class="btn btn-info border" href="<?php echo $GLOBALS['siteurl']; ?>/users/roleList.php?r=add">Add New Role</a>
                            <?php
                            }
                            ?>

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

                                <hr>

                                <h4 class="h4">Set Permissions</h4>
                                <div class="tableDiv">
                                    <table class="table table-bordered" style="max-width:80%;margin:auto; font-size:large">
                                        <thead>
                                            <tr>
                                                <th>Modules</th>
                                                <th>Permissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <h3 class="h5">Administer</h3>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="checkALL" name="checkALL">
                                                            <label class="custom-control-label h5" for="checkALL">Check All</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php

                                            function serPermissions($row, $fullName, $itemId)
                                            {
                                                $html = "";
                                                if ($row['functions'] == $fullName) {
                                                    $checked = ($row['permission'] == "true") ? 'checked' : "";
                                                    $html .= '<div class="custom-control custom-control-lg custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="' . $itemId . '" name="' . $itemId . '" ' . $checked . ' >
                                                            <label class="custom-control-label h5" for="' . $itemId . '">' . $fullName . '</label>
                                                        </div>';
                                                }
                                                return $html;
                                            }

                                            // $itemSql = "SELECT `role_mod_id`, `modules`, `functions`, `permission` FROM `role_mod` WHERE role_id = {$roleId} ORDER BY  role_mod_id";
                                            $itemSql = "SELECT * FROM `role_mod` WHERE role_id = '$roleId' GROUP BY modules ORDER BY role_mod_id;";
                                            $resultItem = $connect->query($itemSql);

                                            while ($itemData = $resultItem->fetch_assoc()) {
                                                $module = $itemData['modules'];
                                                // $Name = $module;
                                                $Name = "";
                                                // setting module Name in HTML
                                                if ($module == 'matrix') {
                                                    $Name = 'MATRIX';
                                                } elseif ($module == 'swap') {
                                                    $Name = "SWAP";
                                                } elseif ($module == 'incentives') {
                                                    $Name = "INCENTIVES";
                                                } elseif ($module == 'inventory') {
                                                    $Name = "INVENTORY";
                                                } else if ($module === 'sale') {
                                                    $Name = "SALE";
                                                } else if ($module === 'todo') {
                                                    $Name = "TO DO'S";
                                                } else if ($module === 'regp') {
                                                    $Name = "REGISTRATION PROBLEMS";
                                                } else if ($module === 'user') {
                                                    $Name = "USERS";
                                                } else if ($module === 'role') {
                                                    $Name = "ROLES";
                                                } else if ($module === 'incr') {
                                                    $Name = "INCENTIVE RULES";
                                                } else if ($module === 'sptr') {
                                                    $Name = "SALES PERSONS'S TODO RULES";
                                                } else if ($module === 'swploc') {
                                                    $Name = "SWAP LOCATION";
                                                } else if ($module === 'manprice') {
                                                    $Name = "NEW CAR PRICE BY MANUFACTURE";
                                                } else if ($module === 'matrixrule') {
                                                    $Name = "MATRIX RULES";
                                                } else if ($module === 'bdcrule') {
                                                    $Name = "BDC RULES";
                                                } else if ($module === 'raterule') {
                                                    $Name = "RATE RULE";
                                                } else if ($module === 'leaserule') {
                                                    $Name = "LEASE RULE";
                                                } else if ($module === 'cashincrule') {
                                                    $Name = "DEALER CASH INCENTIVE RULES";
                                                } else if ($module === 'lotWizards') {
                                                    $Name = "Lot Wizards";
                                                } else if ($module === 'usedCars') {
                                                    $Name = "Used Cars";
                                                }
                                            ?>
                                                <tr>
                                                    <td class="align-middle text-center">
                                                        <h3 class="h5"><?php echo $Name ?></h3>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-start">
                                                            <?php

                                                            $itemPSql = "SELECT `role_mod_id`, `functions`, `permission` FROM `role_mod` WHERE role_id = '$roleId' and modules = '$module' ORDER BY role_mod_id";

                                                            $resultPItem = $connect->query($itemPSql);




                                                            foreach ($resultPItem as $itemPData) {

                                                                if ($module === 'matrix') {
                                                                    echo serPermissions($itemPData, 'View', 'matrixView');
                                                                } else if ($module === 'swap') {
                                                                    echo serPermissions($itemPData, 'Add', 'swapAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'swapEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'swapRemove');
                                                                } else if ($module === 'incentives') {
                                                                    echo serPermissions($itemPData, 'Edit', 'incentivesEdit');
                                                                } else if ($module === 'inventory') {
                                                                    echo serPermissions($itemPData, 'Add', 'invAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'invEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'invRemove');
                                                                } else if ($module === 'sale') {
                                                                    echo serPermissions($itemPData, 'Add', 'saleAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'saleEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'saleRemove');
                                                                } else if ($module === 'todo') {
                                                                    echo serPermissions($itemPData, 'Edit', 'todoEdit');
                                                                } else if ($module === 'regp') {
                                                                    echo serPermissions($itemPData, 'Add', 'regpAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'regpEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'regpRemove');
                                                                } else if ($module === 'user') {
                                                                    echo serPermissions($itemPData, 'Add', 'userAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'userEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'userRemove');
                                                                } else if ($module === 'role') {
                                                                    echo serPermissions($itemPData, 'Add', 'roleAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'roleEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'roleRemove');
                                                                } else if ($module === 'incr') {
                                                                    echo serPermissions($itemPData, 'Add', 'incrAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'incrEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'incrRemove');
                                                                } else if ($module === 'sptr') {

                                                                    echo serPermissions($itemPData, 'Add', 'sptrAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'sptrEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'sptrRemove');
                                                                } else if ($module === 'swploc') {
                                                                    echo serPermissions($itemPData, 'Add', 'swplocAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'swplocEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'swplocRemove');
                                                                } else if ($module === 'manprice') {
                                                                    echo serPermissions($itemPData, 'Add', 'manpriceAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'manpriceEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'manpriceRemove');
                                                                } else if ($module === 'matrixrule') {
                                                                    echo serPermissions($itemPData, 'Add', 'matrixruleAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'matrixruleEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'matrixruleRemove');
                                                                } else if ($module === 'bdcrule') {
                                                                    echo serPermissions($itemPData, 'Add', 'bdcruleAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'bdcruleEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'bdcruleRemove');
                                                                } else if ($module === 'raterule') {
                                                                    echo serPermissions($itemPData, 'Add', 'rateruleAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'rateruleEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'rateruleRemove');
                                                                } else if ($module === 'leaserule') {
                                                                    echo serPermissions($itemPData, 'Add', 'leaseruleAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'leaseruleEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'leaseruleRemove');
                                                                } else if ($module === 'cashincrule') {
                                                                    echo serPermissions($itemPData, 'Add', 'cashincruleAdd');
                                                                    echo serPermissions($itemPData, 'Edit', 'cashincruleEdit');
                                                                    echo serPermissions($itemPData, 'Remove', 'cashincruleRemove');
                                                                } else if ($module === 'lotWizards') {
                                                                    echo serPermissions($itemPData, 'Add', 'lotWizardsAdd');
                                                                } else if ($module === 'usedCars') {
                                                                    echo serPermissions($itemPData, 'Add', 'usedCarsAdd');
                                                                }
                                                            }

                                                            ?>

                                                            <!-- <div class="custom-control custom-control-lg custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="matrixView" name="matrixView">
                                                                <label class="custom-control-label h5" for="matrixView">View</label>
                                                            </div> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                        </tbody>

                                    </table>
                                </div>


                                <hr class="my-5">

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