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
    .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    }

    #datatable-1 tbody tr td {
        padding: 10px;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 600px;
            margin: 1.75rem auto;
        }

        .modal-dialog table.detialsTable {
            width: max-content;
        }
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
                        <h3 class="portlet-title">Incentives Rule List</h3>
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
                                    <th>Ex Model No</th>
                                    <th>Expire In.</th>
                                    <th>College</th>
                                    <th>Military</th>
                                    <th>Loyalty</th>
                                    <th>Conquest</th>
                                    <th>Misc 1</th>
                                    <th>Misc 2</th>
                                    <th>Misc 3</th>
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
                <h5 class="modal-title">Edit Incentive Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editIncentiveRule.php" method="post">
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

                        <!-- <div class="form-row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="model">Model</label>
                                    
                                    <select class="form-control selectpicker w-auto" id="editModel" name="editModel" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select Model</option>
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
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    
                                    <input type="text" class="form-control typeahead" id="editYear" name="editYear" placeholder="Year">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="modelno">Model No.</label>
                                   
                                    <input type="text" class="form-control typeahead" id="editModelno" name="editModelno" placeholder="Model No.">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="modelno">Model Type</label>
                                    <select class="form-control selectpicker w-auto" id="editModelType" name="editModel" data-live-search="true" data-size="4">
                                        <option value="NEW" selected>NEW</option>
                                        <option value="USED">USED</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->

                        <table class="table" id="productTable1">
                            <thead>
                                <tr>
                                    <!-- <th style="width:25%;text-align:center">Model</th>
                                    <th style="width:20%;text-align:center">Year</th>
                                    <th style="width:20%;text-align:center">Model No.</th>
                                    <th style="width:20%;text-align:center">Model Type</th> -->
                                    <th style="width:20%;text-align:center">Model</th>
                                    <th style="width:20%;text-align:center">Year</th>
                                    <th style="width:20%;text-align:center">Model No.</th>
                                    <th style="width:20%;text-align:center">Model Type</th>
                                    <th style="width:20%;text-align:center">Exclude Model No</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModel" name="editModel" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>Select Model</option>
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
                                        <input type="text" class="form-control typeahead typeahead1" id="editModelno" name="editModelno" placeholder="Model No.">
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModelType" name="editModelType">
                                            <option value="BOTH" selected>BOTH</option>
                                            <option value="NEW">NEW</option>
                                            <option value="USED">USED</option>
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control select21" id="editExModelno" name="editExModelno[]" multiple="multiple" title="Exclude Model No.">
                                            <optgroup label="Press Enter to add">
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
                                            <th>College</th>
                                            <th>Military</th>
                                            <th>Loyalty</th>
                                            <th>Conquest</th>
                                            <th>Misc 1</th>
                                            <th>Misc 2</th>
                                            <th>Misc 3</th>
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
                                                    <input type="checkbox" class="custom-control-input check" name="editCollege" id="editCollege">
                                                    <label class="custom-control-label" for="editCollege">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editMilitary" id="editMilitary">
                                                    <label class="custom-control-label" for="editMilitary">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editLoyalty" id="editLoyalty">
                                                    <label class="custom-control-label" for="editLoyalty">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editConquest" id="editConquest">
                                                    <label class="custom-control-label" for="editConquest">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editMisc1" id="editMisc1">
                                                    <label class="custom-control-label" for="editMisc1">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editMisc2" id="editMisc2">
                                                    <label class="custom-control-label" for="editMisc2">
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="custom-control-lg custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input check" name="editMisc3" id="editMisc3">
                                                    <label class="custom-control-label" for="editMisc3">
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
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createRule.php">
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
                                <th style="width:20%;text-align:center">Model</th>
                                <th style="width:15%;text-align:center">Year</th>
                                <th style="width:20%;text-align:center">Model No.</th>
                                <th style="width:15%;text-align:center">Model Type</th>
                                <th style="width:20%;text-align:center">Exclude Model No</th>
                                <th style="width:10%;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="model1" name="model[]" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select Model</option>
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
                                    <input type="text" class="form-control typeahead typeahead1" id="modelno1" name="modelno[]" placeholder="Model No.">
                                </td>
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="modelType1" name="modelType[]">
                                        <option value="BOTH" selected>BOTH</option>
                                        <option value="NEW">NEW</option>
                                        <option value="USED">USED</option>
                                    </select>
                                </td>
                                <td class="form-group">
                                    <select class="form-control select21" id="exModelno1" name="exModelno1[]" multiple="multiple" title="Exclude Model No.">
                                        <optgroup label="Press Enter to add">
                                    </select>
                                </td>
                                <td class="form-group text-center">
                                    <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>




                    <!-- <div class="form-row">
                        <div class="col-md-3">

                            <div class="form-group">
                                <label for="model">Model</label>
                                <select class="form-control selectpicker w-auto" id="model" name="model" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>Select Model</option>
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
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year">Year</label>
                                <input type="text" class="form-control typeahead" id="year" name="year" placeholder="Year">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modelno">Model No.</label>
                                <input type="text" class="form-control typeahead" id="modelno" name="modelno" placeholder="Model No.">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="modelno">Model Type</label>
                                    <select class="form-control selectpicker w-auto" id="modelType" name="modelType">
                                        <option value="NEW" selected>NEW</option>
                                        <option value="USED">USED</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-1 align-content-center align-self-end">
                                    <button class="btn btn-info" onclick="addRow()">Add New</button>
                                </div>
                            </div>
                        </div>

                    </div> -->



                    <br><br>
                    <div class="form-row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Select All</th>
                                        <th>College</th>
                                        <th>Military</th>
                                        <th>Loyalty</th>
                                        <th>Conquest</th>
                                        <th>Misc 1</th>
                                        <th>Misc 2</th>
                                        <th>Misc 3</th>
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
                                                <input type="checkbox" class="custom-control-input check" name="college" id="college">
                                                <label class="custom-control-label" for="college">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="military" id="military">
                                                <label class="custom-control-label" for="military">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="loyalty" id="loyalty">
                                                <label class="custom-control-label" for="loyalty">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="conquest" id="conquest">
                                                <label class="custom-control-label" for="conquest">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="misc1" id="misc1">
                                                <label class="custom-control-label" for="misc1">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="misc2" id="misc2">
                                                <label class="custom-control-label" for="misc2">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center">
                                            <div class="custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check" name="misc3" id="misc3">
                                                <label class="custom-control-label" for="misc3">
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
<script type="text/javascript" src="../custom/js/incentiveRules.js"></script>