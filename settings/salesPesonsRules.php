<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';


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
                        <h3 class="portlet-title">Sales Person's Todo Rule List</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Set New Rule
                        </button>
                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Year</th>
                                    <th>Model no.</th>
                                    <th>Model Type</th>
                                    <th>Expire In.</th>
                                    <th>Vin Check</th>
                                    <th>Insurance</th>
                                    <th>Trade Title</th>
                                    <th>Registration</th>
                                    <th>Inspection</th>
                                    <th>Salesperson Status</th>
                                    <th>Paid</th>
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
                <h5 class="modal-title">Edit Sales Person's Todo Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editSalesPersonRule.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="ruleId" id="ruleId">
                        <div class="form-group row">
                            <label for="dateRange" class="col-sm-2 offset-sm-1 col-form-label text-right">Select Date:</label>
                            <div class="col-sm-8">
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="editfromDate" id="editfromDate" placeholder="From">
                                    <div class="input-group-prepend input-group-append"><span class="input-group-text"><i class="fa fa-ellipsis-h"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="edittoDate" id="edittoDate" placeholder="To">
                                </div>
                            </div>
                        </div>

                        <table class="table" id="productTable1">
                            <thead>
                                <tr>
                                    <th style="width:25%;text-align:center">Modal</th>
                                    <th style="width:20%;text-align:center">Year</th>
                                    <th style="width:20%;text-align:center">Model No.</th>
                                    <th style="width:20%;text-align:center">Model Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModel" name="editModel" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>Select Modal</option>
                                            <option value="ACCORD">ACCORD</option>
                                            <option value="ACCORD HYBRID">ACCORD HYBRID</option>
                                            <option value="CIVIC">CIVIC</option>
                                            <option value="CR-V">CR-V</option>
                                            <option value="CR-V HYBRID">CR-V HYBRID</option>
                                            <option value="HR-V">HR-V</option>
                                            <option value="INSIGHT">INSIGHT</option>
                                            <option value="ODYSSEY">ODYSSEY</option>
                                            <option value="PASSPORT">PASSPORT</option>
                                            <option value="PILOT">PILOT</option>
                                            <option value="RIDGELINE">RIDGELINE</option>
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editYear" name="editYear" placeholder="Year">
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editModelno" name="editModelno" placeholder="Modal No.">
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModelType" name="editModelType">
                                            <option value="NEW" selected>NEW</option>
                                            <option value="USED">USED</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <div class="form-row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Select All</th>
                                            <th>Vin Check</th>
                                            <th>Insurance</th>
                                            <th>Trade Title</th>
                                            <th>Registration</th>
                                            <th>Inspection</th>
                                            <th>Salesperson Status</th>
                                            <th>Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-warning">
                                        <tr id="checkBoxRow">
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="editSelectAll">
                                                    <label class="custom-control-label" for="editSelectAll">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editVinCheck" id="editVinCheck">
                                                    <label class="custom-control-label" for="editVinCheck">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editInsurance" id="editInsurance">
                                                    <label class="custom-control-label" for="editInsurance">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editTradeTitle" id="editTradeTitle">
                                                    <label class="custom-control-label" for="editTradeTitle">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editRegistration" id="editRegistration">
                                                    <label class="custom-control-label" for="editRegistration">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editInspection" id="editInspection">
                                                    <label class="custom-control-label" for="editInspection">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editSalespersonStatus" id="editSalespersonStatus">
                                                    <label class="custom-control-label" for="editSalespersonStatus">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editPaid" id="editPaid">
                                                    <label class="custom-control-label" for="editPaid">
                                                    </label>
                                                </div>
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
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createSalesPersonRule.php">
                <!-- <form id="addNewRule" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <br>
                    <div class="form-group row">
                        <label for="dateRange" class="col-sm-2 offset-sm-1 col-form-label text-right">Select Date:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" name="fromDate" id="fromDate" placeholder="From">
                                <div class="input-group-prepend input-group-append"><span class="input-group-text"><i class="fa fa-ellipsis-h"></i></span>
                                </div>
                                <input type="text" class="form-control" name="toDate" id="toDate" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <br><br>

                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:25%;text-align:center">Modal</th>
                                <th style="width:20%;text-align:center">Year</th>
                                <th style="width:20%;text-align:center">Model No.</th>
                                <th style="width:20%;text-align:center">Model Type</th>
                                <th style="width:15%;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="model1" name="model[]" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select Modal</option>
                                        <option value="ACCORD">ACCORD</option>
                                        <option value="ACCORD HYBRID">ACCORD HYBRID</option>
                                        <option value="CIVIC">CIVIC</option>
                                        <option value="CR-V">CR-V</option>
                                        <option value="CR-V HYBRID">CR-V HYBRID</option>
                                        <option value="HR-V">HR-V</option>
                                        <option value="INSIGHT">INSIGHT</option>
                                        <option value="ODYSSEY">ODYSSEY</option>
                                        <option value="PASSPORT">PASSPORT</option>
                                        <option value="PILOT">PILOT</option>
                                        <option value="RIDGELINE">RIDGELINE</option>
                                    </select>
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="year1" name="year[]" placeholder="Year">
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="modelno1" name="modelno[]" placeholder="Modal No.">
                                </td>
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="modelType1" name="modelType[]">
                                        <option value="NEW" selected>NEW</option>
                                        <option value="USED">USED</option>
                                    </select>
                                </td>
                                <td class="form-group text-center">
                                    <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <br><br>
                    <div class="form-row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Select All</th>
                                        <th>Vin Check</th>
                                        <th>Insurance</th>
                                        <th>Trade Title</th>
                                        <th>Registration</th>
                                        <th>Inspection</th>
                                        <th>Salesperson Status</th>
                                        <th>Paid</th>
                                    </tr>
                                </thead>
                                <tbody class="table-warning">
                                    <tr id="checkBoxRow">
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="vinCheck" id="vinCheck">
                                                <label class="custom-control-label" for="vinCheck">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="insurance" id="insurance">
                                                <label class="custom-control-label" for="insurance">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="tradeTitle" id="tradeTitle">
                                                <label class="custom-control-label" for="tradeTitle">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="registration" id="registration">
                                                <label class="custom-control-label" for="registration">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="inspection" id="inspection">
                                                <label class="custom-control-label" for="inspection">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="salespersonStatus" id="salespersonStatus">
                                                <label class="custom-control-label" for="salespersonStatus">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="paid" id="paid">
                                                <label class="custom-control-label" for="paid">
                                                </label>
                                            </div>
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
<script type="text/javascript" src="../custom/js/salesPersonRules.js"></script>