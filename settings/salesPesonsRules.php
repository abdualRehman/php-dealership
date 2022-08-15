<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("sptr", "Add") === 'false' && hasAccess("sptr", "Edit") === 'false' && hasAccess("sptr", "Remove") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("sptr", "Edit") === 'false') {
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

    /* #datatable-1 tbody tr td {
        padding: 10px 6px;
    } */

    /* .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    } */

    /* #datatable-1 tbody tr td {
        padding: 10px;
    } */

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
                        <h3 class="portlet-title">Sales Person's Todo Rule List</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("sptr", "Add") !== 'false') {
                            echo ' <button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
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
                                    <th>State</th>
                                    <th>Model Type</th>
                                    <th>Ex Model No</th>
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
                        <!-- <div class="form-group row">
                            <label for="dateRange" class="col-sm-2 offset-sm-1 col-form-label text-right">Select Date:</label>
                            <div class="col-sm-8">
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="editfromDate" id="editfromDate" placeholder="From">
                                    <div class="input-group-prepend input-group-append"><span class="input-group-text"><i class="fa fa-ellipsis-h"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="edittoDate" id="edittoDate" placeholder="To">
                                </div>
                            </div>
                        </div> -->

                        <table class="table" id="productTable1">
                            <thead>
                                <tr>
                                    <th style="width:20%;text-align:center">Model</th>
                                    <th style="width:15%;text-align:center">Year</th>
                                    <th style="width:15%;text-align:center">Model No.</th>
                                    <th style="width:15%;text-align:center">Model Type</th>
                                    <th style="width:15%;text-align:center">State</th>
                                    <th style="width:20%;text-align:center">Exclude Model No</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModel" name="editModel" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>Select Model</option>
                                            <option value="All">All</option>
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
                                            <option value="ALL" selected>ALL</option>
                                            <option value="NEW">NEW</option>
                                            <option value="USED">USED</option>
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" name="editState" id="editState" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>State</option>
                                            <option value="MA">MA</option>
                                            <option value="RI">RI</option>
                                            <option value="CT">CT</option>
                                            <option value="NH">NH</option>
                                            <option value="AL">AL</option>
                                            <option value="AK">AK</option>
                                            <option value="AZ">AZ</option>
                                            <option value="AR">AR</option>
                                            <option value="CA">CA</option>
                                            <option value="CO">CO</option>
                                            <option value="DC">DC</option>
                                            <option value="DE">DE</option>
                                            <option value="FL">FL</option>
                                            <option value="GA">GA</option>
                                            <option value="HI">HI</option>
                                            <option value="ID">ID</option>
                                            <option value="IL">IL</option>
                                            <option value="IN">IN</option>
                                            <option value="IA">IA</option>
                                            <option value="KS">KS</option>
                                            <option value="KY">KY</option>
                                            <option value="LA">LA</option>
                                            <option value="ME">ME</option>
                                            <option value="MD">MD</option>
                                            <option value="MI">MI</option>
                                            <option value="MN">MN</option>
                                            <option value="MS">MS</option>
                                            <option value="MO">MO</option>
                                            <option value="MT">MT</option>
                                            <option value="NE">NE</option>
                                            <option value="NV">NV</option>
                                            <option value="NJ">NJ</option>
                                            <option value="NM">NM</option>
                                            <option value="NY">NY</option>
                                            <option value="NC">NC</option>
                                            <option value="ND">ND</option>
                                            <option value="OH">OH</option>
                                            <option value="OK">OK</option>
                                            <option value="OR">OR</option>
                                            <option value="PA">PA</option>
                                            <option value="SC">SC</option>
                                            <option value="SD">SD</option>
                                            <option value="TN">TN</option>
                                            <option value="TX">TX</option>
                                            <option value="UT">UT</option>
                                            <option value="VT">VT</option>
                                            <option value="VA">VA</option>
                                            <option value="WA">WA</option>
                                            <option value="WV">WV</option>
                                            <option value="WI">WI</option>
                                            <option value="WY">WY</option>
                                            <option value="N/A">N/A</option>
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
                                        <tr>
                                            <th class="text-center">
                                                <select onchange="chnageStyle(this)" name="editVinCheck" id="editVinCheck" class="selectpicker">
                                                    <option value="N/A" selected>Disabled</option>
                                                    <option value="checkTitle">Check Title</option>
                                                    <option value="need">Need</option>
                                                    <option value="notNeed">Doesn't Need</option>
                                                    <option value="n/a">N/A</option>
                                                    <option value="onHold">On Hold</option>
                                                    <option value="done">Done</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select class="selectpicker" onchange="chnageStyle(this)" id="editInsurance" name="editInsurance">
                                                    <option value="N/A" selected>Disabled</option>
                                                    <option value="need">Need</option>
                                                    <option value="inHouse">In House</option>
                                                    <option value="n/a">N/A</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select class="selectpicker" onchange="chnageStyle(this)" id="editTradeTitle" name="editTradeTitle">
                                                    <option value="N/A" selected>Disabled</option>
                                                    <option value="need">Need</option>
                                                    <option value="payoff">Payoff</option>
                                                    <option value="noTrade">No Trade</option>
                                                    <option value="inHouse">In House</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select class="selectpicker" onchange="chnageStyle(this)" id="editRegistration" name="editRegistration">
                                                    <option value="N/A" selected>Disabled</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="done">Done</option>
                                                    <option value="customerHas">Customer Has</option>
                                                    <option value="mailed">Mailed</option>
                                                    <option value="n/a">N/A</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select class="selectpicker" onchange="chnageStyle(this)" id="editInspection" name="editInspection">
                                                    <option value="N/A" selected>Disabled</option>
                                                    <option value="need">Need</option>
                                                    <option value="notNeed">Doesn't Need</option>
                                                    <option value="done">Done</option>
                                                    <option value="n/a">N/A</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select class="selectpicker" onchange="chnageStyle(this)" id="editSalespersonStatus" name="editSalespersonStatus">
                                                    <option value="N/A" selected>Disabled</option>
                                                    <option value="dealWritten">Deal Written</option>
                                                    <option value="gmdSubmit">GMD Submit</option>
                                                    <option value="contracted">Contracted</option>
                                                    <option value="cancelled">Cancelled</option>
                                                    <option value="delivered">Delivered</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select class="selectpicker" onchange="chnageStyle(this)" id="editPaid" name="editPaid">
                                                    <option value="N/A" selected>Disabled</option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
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
                    <!-- <br>
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
                    <br><br> -->

                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:20%;text-align:center">Model</th>
                                <th style="width:15%;text-align:center">Year</th>
                                <th style="width:15%;text-align:center">Model No.</th>
                                <th style="width:10%;text-align:center">Model Type</th>
                                <th style="width:10%;text-align:center">State</th>
                                <th style="width:20%;text-align:center">Exclude Model No</th>
                                <th style="width:10%;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="model1" name="model[]" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select Model</option>
                                        <option value="All">All</option>
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
                                        <option value="ALL" selected>All</option>
                                        <option value="NEW">NEW</option>
                                        <option value="USED">USED</option>
                                    </select>
                                </td>
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" name="state[]" id="state1" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>State</option>
                                        <option value="MA">MA</option>
                                        <option value="RI">RI</option>
                                        <option value="CT">CT</option>
                                        <option value="NH">NH</option>
                                        <option value="AL">AL</option>
                                        <option value="AK">AK</option>
                                        <option value="AZ">AZ</option>
                                        <option value="AR">AR</option>
                                        <option value="CA">CA</option>
                                        <option value="CO">CO</option>
                                        <option value="DC">DC</option>
                                        <option value="DE">DE</option>
                                        <option value="FL">FL</option>
                                        <option value="GA">GA</option>
                                        <option value="HI">HI</option>
                                        <option value="ID">ID</option>
                                        <option value="IL">IL</option>
                                        <option value="IN">IN</option>
                                        <option value="IA">IA</option>
                                        <option value="KS">KS</option>
                                        <option value="KY">KY</option>
                                        <option value="LA">LA</option>
                                        <option value="ME">ME</option>
                                        <option value="MD">MD</option>
                                        <option value="MI">MI</option>
                                        <option value="MN">MN</option>
                                        <option value="MS">MS</option>
                                        <option value="MO">MO</option>
                                        <option value="MT">MT</option>
                                        <option value="NE">NE</option>
                                        <option value="NV">NV</option>
                                        <option value="NJ">NJ</option>
                                        <option value="NM">NM</option>
                                        <option value="NY">NY</option>
                                        <option value="NC">NC</option>
                                        <option value="ND">ND</option>
                                        <option value="OH">OH</option>
                                        <option value="OK">OK</option>
                                        <option value="OR">OR</option>
                                        <option value="PA">PA</option>
                                        <option value="SC">SC</option>
                                        <option value="SD">SD</option>
                                        <option value="TN">TN</option>
                                        <option value="TX">TX</option>
                                        <option value="UT">UT</option>
                                        <option value="VT">VT</option>
                                        <option value="VA">VA</option>
                                        <option value="WA">WA</option>
                                        <option value="WV">WV</option>
                                        <option value="WI">WI</option>
                                        <option value="WY">WY</option>
                                        <option value="N/A">N/A</option>
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

                    <br><br>
                    <div class="form-row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <!-- <th>All</th> -->
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
                                    <tr>
                                        <th class="text-center">
                                            <select onchange="chnageStyle(this)" name="vinCheck" id="vinCheck" class="selectpicker">
                                                <option value="N/A" selected>Disabled</option>
                                                <option value="checkTitle">Check Title</option>
                                                <option value="need">Need</option>
                                                <option value="notNeed">Doesn't Need</option>
                                                <option value="n/a">N/A</option>
                                                <option value="onHold">On Hold</option>
                                                <option value="done">Done</option>
                                            </select>
                                        </th>
                                        <th class="text-center">
                                            <select class="selectpicker" onchange="chnageStyle(this)" id="insurance" name="insurance">
                                                <option value="N/A" selected>Disabled</option>
                                                <option value="need">Need</option>
                                                <option value="inHouse">In House</option>
                                                <option value="n/a">N/A</option>
                                            </select>
                                        </th>
                                        <th class="text-center">
                                            <select class="selectpicker" onchange="chnageStyle(this)" id="tradeTitle" name="tradeTitle">
                                                <option value="N/A" selected>Disabled</option>
                                                <option value="need">Need</option>
                                                <option value="payoff">Payoff</option>
                                                <option value="noTrade">No Trade</option>
                                                <option value="inHouse">In House</option>
                                            </select>
                                        </th>
                                        <th class="text-center">
                                            <select class="selectpicker" onchange="chnageStyle(this)" id="registration" name="registration">
                                                <option value="N/A" selected>Disabled</option>
                                                <option value="pending">Pending</option>
                                                <option value="done">Done</option>
                                                <option value="customerHas">Customer Has</option>
                                                <option value="mailed">Mailed</option>
                                                <option value="n/a">N/A</option>
                                            </select>
                                        </th>
                                        <th class="text-center">
                                            <select class="selectpicker" onchange="chnageStyle(this)" id="inspection" name="inspection">
                                                <option value="N/A" selected>Disabled</option>
                                                <option value="need">Need</option>
                                                <option value="notNeed">Doesn't Need</option>
                                                <option value="done">Done</option>
                                                <option value="n/a">N/A</option>
                                            </select>
                                        </th>
                                        <th class="text-center">
                                            <select class="selectpicker" onchange="chnageStyle(this)" id="salePStatus" name="salePStatus">
                                                <option value="N/A" selected>Disabled</option>
                                                <option value="dealWritten">Deal Written</option>
                                                <option value="gmdSubmit">GMD Submit</option>
                                                <option value="contracted">Contracted</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="delivered">Delivered</option>
                                            </select>
                                        </th>
                                        <th class="text-center">
                                            <select class="selectpicker" onchange="chnageStyle(this)" id="paid" name="paid">
                                                <option value="N/A" selected>Disabled</option>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
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