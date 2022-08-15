<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';


if (hasAccess("rdr", "Add") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

if (hasAccess("rdr", "Edit") === 'false') {
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
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">RDR (RETAIL DELIVERY REGISTRATION) Rule List</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        // if (hasAccess("bdcrule", "Add") !== 'false') {
                        //     echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                        //     <i class="fa fa-plus ml-1 mr-2"></i> Set New Rule
                        // </button>';
                        // }
                        ?>
                        <button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Set New Rule
                        </button>

                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Model Type</th>
                                    <th>Certified</th>
                                    <th>RDR Type</th>
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
                <h5 class="modal-title">Edit RDR Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editRdrRule.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="ruleId" id="ruleId">
                        <br>
                        <h3 class="h4">Stock Details:</h3>
                        <table class="table" id="productTable">
                            <thead>
                                <tr>
                                    <th style="width:15%;text-align:center">Year</th>
                                    <th style="width:15%;text-align:center">Make</th>
                                    <th style="width:15%;text-align:center">Model</th>
                                    <th style="width:20%;text-align:center">Model Type.</th>
                                    <th style="width:20%;text-align:center">Certified</th>
                                    <th style="width:15%;text-align:center">RDR Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="eyear" name="eyear" placeholder="Year">
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="emake" name="emake" placeholder="Make">
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="emodel" name="emodel" placeholder="Model">
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="emodelType" name="emodelType">
                                            <option value="All" selected>All</option>
                                            <option value="New">New</option>
                                            <option value="Used">Used</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </td>
                                    <td class="form-group text-center">
                                        <select class="form-control selectpicker w-auto" id="ecertified" name="ecertified">
                                            <option value="Yes" selected>Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </td>
                                    <td class="form-group text-center">
                                        <input type="text" class="form-control" id="erdrType" name="erdrType" placeholder="RDR Type">
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <br>
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
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createRdrRule.php">
                <!-- <form id="addNewRule" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <br>
                    <h3 class="h4">Stock Details:</h3>
                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:15%;text-align:center">Year</th>
                                <th style="width:15%;text-align:center">Make</th>
                                <th style="width:15%;text-align:center">Model</th>
                                <th style="width:20%;text-align:center">Model Type.</th>
                                <th style="width:20%;text-align:center">Certified</th>
                                <th style="width:15%;text-align:center">RDR Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="year" name="year" placeholder="Year">
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="make" name="make" placeholder="Make">
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="model" name="model" placeholder="Model">
                                </td>
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="modelType" name="modelType">
                                        <option value="All" selected>All</option>
                                        <option value="New">New</option>
                                        <option value="Used">Used</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </td>
                                <td class="form-group text-center">
                                    <select class="form-control selectpicker w-auto" id="certified" name="certified">
                                        <option value="Yes" selected>Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>
                                <td class="form-group text-center">
                                    <input type="text" class="form-control" id="rdrType" name="rdrType" placeholder="RDR Type">
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
<script type="text/javascript" src="../custom/js/rdrRules.js"></script>