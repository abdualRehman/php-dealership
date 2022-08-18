<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("writedown", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

if (hasAccess("writedown", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>

<style>
    .custom-checkbox {
        display: inline-grid;
        margin: auto;
        align-items: center;
    }

    #datatable-1 tbody tr td {
        padding: 10px 6px;
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1000px;
        }
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Write Down Rules</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("dealership", "Edit") !== 'false') {
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Set New Rule
                        </button>';
                        }
                        ?>
                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Age Group</th>
                                    <th>Percentage of Balance</th>
                                    <th>Balance From</th>
                                    <th>Balance To</th>
                                    <th>Max Writedown</th>
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


<div class="modal fade" id="modal8">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Writedown Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editWritedownRule.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="ruleId" id="ruleId">
                        <div class="form-row justify-content-center">
                            <div class="col-md-6 text-center">
                                <h5 class="h5 text-center">End of Month Age</h5>
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" placeholder="From" name="eageFrom" id="eageFrom" />
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text"><i class="fa fa-ellipsis-h"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="To" name="eageTo" id="eageTo" />
                                </div>
                            </div>
                        </div>
                        <hr />
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width:25%;text-align:center">Percentage of Balance</th>
                                    <th style="width:25%;text-align:center">Balance From</th>
                                    <th style="width:25%;text-align:center">Balance To</th>
                                    <th style="width:25%;text-align:center">Max Writedown</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="epercntBalance" id="epercntBalance" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-percent"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="ebalanceFrom" id="ebalanceFrom" />
                                        </div>
                                    </td>
                                    <td class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="ebalanceTo" id="ebalanceTo" />
                                        </div>
                                    </td>
                                    <td class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="emaxWritedown" id="emaxWritedown" />
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Update Changes</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Rule</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createWriteDownRule.php">
                <!-- <form id="addNewRule" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <div class="form-row justify-content-center">
                        <div class="col-md-6 text-center">
                            <h5 class="h5 text-center">End of Month Age</h5>
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" placeholder="From" name="ageFrom" id="ageFrom" />
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text"><i class="fa fa-ellipsis-h"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="To" name="ageTo" id="ageTo" />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:25%;text-align:center">Percentage of Balance</th>
                                <th style="width:25%;text-align:center">Balance From</th>
                                <th style="width:20%;text-align:center">Balance To</th>
                                <th style="width:20%;text-align:center">Max Writedown</th>
                                <th style="width:10%;text-align:center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="percntBalance[]" id="percntBalance1" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-percent"></i>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-dollar-sign"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="balanceFrom[]" id="balanceFrom1" />
                                    </div>
                                </td>
                                <td class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-dollar-sign"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="balanceTo[]" id="balanceTo1" />
                                    </div>
                                </td>
                                <td class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-dollar-sign"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="maxWritedown[]" id="maxWritedown1">
                                    </div>
                                </td>
                                <td class="form-group text-center">
                                    <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <br>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/writeDownRules.js"></script>