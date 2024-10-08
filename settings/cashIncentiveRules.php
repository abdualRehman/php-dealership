<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("cashincrule", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("cashincrule", "Edit") === 'false') {
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
            max-width: 1000PX;
        }

        .modal-dialog table.detialsTable {
            width: inherit;
        }
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Dealer Cash Incentive Rule List</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("cashincrule", "Add") !== 'false') {
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
                                    <th>Model</th>
                                    <th>Year</th>
                                    <th>Model no.</th>
                                    <th>Exclude Model No</th>
                                    <th>Expire In.</th>
                                    <th>Dealer</th>
                                    <th>Other</th>
                                    <th>Lease</th>
                                    <th>Action</th>
                                    <th>ID</th>
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
                <h5 class="modal-title">Edit Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editCashIncentiveRule.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="ruleId" id="ruleId">
                        <div class="form-group row">
                            <label for="dateRange" class="col-sm-2 offset-sm-1 col-form-label text-center">Select Date:</label>
                            <div class="col-sm-3">
                                <td class="form-group">
                                    <input type="text" class="form-control" id="editexpireIn" name="editexpireIn" placeholder="Expire Date">
                                </td>
                            </div>
                        </div>
                        <br>
                        <h3 class="h4">Stock Details:</h3>
                        <table class="table" id="productTable1">
                            <thead>
                                <tr>
                                    <th style="width:25%;text-align:center">Model</th>
                                    <th style="width:20%;text-align:center">Year</th>
                                    <th style="width:20%;text-align:center">Model No.</th>
                                    <th style="width:20%;text-align:center">Exclude Model No.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModel" name="editModel" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>Select Model</option>
                                            <option value="All">All</option>
                                            <?php
                                            $sql = "SELECT model FROM `manufature_price` WHERE status = 1 GROUP BY model";
                                            $result = $connect->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_array()) {
                                                    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                                }
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editYear" name="editYear" placeholder="Year">
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editModelno" name="editModelno" placeholder="Model No.">
                                    </td>
                                    <td class="form-group">
                                        <!-- <input type="text" class="form-control" id="editExModelno" name="editExModelno" placeholder="Exclude Model No."> -->
                                        <select class="form-control select21 tags" id="editExModelno" name="editExModelno[]" multiple="multiple" title="Exclude Model No.">
                                            <optgroup label="Press Enter to add">

                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <h3 class="h4">Edit Rule Details:</h3>
                        <div class="form-row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 33%;">Dealer</th>
                                            <th style="width: 33%;">Other</th>
                                            <th>Lease</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-warning">
                                        <tr>
                                            <th class="text-center form-group">
                                                <input type="text" class="form-control" id="edealer" name="edealer" placeholder="Dealer">
                                            </th>
                                            <th class="text-center form-group">
                                                <input type="text" class="form-control" id="eother" name="eother" placeholder="Other">
                                            </th>
                                            <th class="text-center form-group">
                                                <input type="text" class="form-control" id="elease" name="elease" placeholder="Lease">
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-primary mr-2">Update Changes</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Rule</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createCashIncentiveRule.php">
                <!-- <form id="addNewRule" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <br>
                    <div class="form-group row">
                        <label for="dateRange" class="col-sm-2 col-form-label text-center">Select Date:</label>
                        <div class="col-sm-3">
                            <td class="form-group">
                                <input type="text" class="form-control" id="expireIn" name="expireIn" placeholder="Expire Date">
                            </td>
                        </div>
                    </div>
                    <br><br>
                    <h3 class="h4">Stock Details:</h3>
                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:25%;text-align:center">Model</th>
                                <th style="width:20%;text-align:center">Year</th>
                                <th style="width:20%;text-align:center">Model No.</th>
                                <th style="width:20%;text-align:center">Exclude Model No.</th>
                                <th style="width:15%;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="model1" name="model[]" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select Model</option>
                                        <option value="All">All</option>
                                        <?php
                                        $sql = "SELECT model FROM `manufature_price` WHERE status = 1 GROUP BY model";
                                        $result = $connect->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_array()) {
                                                echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                        }
                                        ?>

                                    </select>
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="year1" name="year[]" placeholder="Year">
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="modelno1" name="modelno[]" placeholder="Model No.">
                                </td>
                                <td class="form-group">
                                    <!-- <input type="text" class="form-control select2 select21" id="exModelno1" name="exModelno[]" placeholder="Exclude Model No."> -->
                                    <select class="form-control select21 tags" id="exModelno1" name="exModelno1[]" multiple="multiple" title="Exclude Model No.">
                                        <optgroup label="Press Enter to add">
                                    </select>
                                </td>

                                <td class="form-group text-center">
                                    <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <br>
                    <h3 class="h4">Rule Details:</h3>
                    <div class="form-row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 33%;">Dealer</th>
                                        <th style="width: 33%;">Other</th>
                                        <th>Lease</th>
                                    </tr>
                                </thead>
                                <tbody class="table-warning">
                                    <tr>
                                        <th class="text-center form-group">
                                            <input type="text" class="form-control" id="dealer" name="dealer" placeholder="Dealer">
                                        </th>
                                        <th class="text-center form-group">
                                            <input type="text" class="form-control" id="other" name="other" placeholder="Other">
                                        </th>
                                        <th class="text-center form-group">
                                            <input type="text" class="form-control" id="lease" name="lease" placeholder="Lease">
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br><br>

                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/cashIncentiveRules.js"></script>