<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("tansptDmg", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("tansptDmg", "Edit") !== 'false') {
    echo '<input type="hidden" name="isAllowed" id="isAllowed" value="true" />';
} else {
    echo '<input type="hidden" name="isAllowed" id="isAllowed" value="false" />';
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

    .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    }

    #datatable-1 tbody tr td {
        padding: 10px;
    }


    .theme-dark .table-hover tbody tr.odd:hover td:nth-child(-n+3) {
        background: #616161;
    }

    .theme-dark .table-hover tbody tr.even:hover td:nth-child(-n+3) {
        background: #424242;
    }

    .theme-light .table-hover tbody tr.odd:hover td:nth-child(-n+3) {
        background-color: #f5f5f5;
    }

    .theme-light .table-hover tbody tr.even:hover td:nth-child(-n+3) {
        background-color: #ffffff;
    }

    .align-content-center {
        align-content: center !important;
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
            max-width: 1200PX;
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
                        <h3 class="portlet-title">Transportation Damage</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("tansptDmg", "Add") !== 'false') {
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Add Vehicle
                        </button>';
                        }
                        ?>


                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Stock no – Vin</th>
                                    <th>Model</th>
                                    <th>Notes</th>
                                    <th>Damage</th>
                                    <!-- <th>Status</th> -->
                                    <th>
                                        <select class="form-control" name="statusPriority" onchange="filterDatatable()" id="statusPriority">
                                            <option value="" selected>Status</option>
                                            <option value="pendingInspection">Pending Inspection</option>
                                            <option value="partsNeeded">Parts Needed</option>
                                            <option value="partsRequested">Parts Requested</option>
                                            <option value="partsArrivedPendingService">Parts Arrived Pending Service</option>
                                            <option value="bodyshopNeeded">Bodyshop Needed</option>
                                            <option value="atBodyshop">At Bodyshop</option>
                                            <option value="bodyshopCompleted">Bodyshop Completed</option>
                                            <option value="completedAwaitingPayment">Completed Awaiting Payment</option>
                                            <option value="repairNotRequired">Repair Not Require</option>
                                            <option value="done">Done</option>
                                        </select>
                                    </th>
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
                <h5 class="modal-title">Edit Transportation Damage</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editForm" action="../php_action/editTransportation.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="eshowResult d-none">
                        <input type="hidden" name="transId" id="transId" />
                        <h3 class="h4">Stock Details:</h3>
                        <div class="form-row">
                            <div class="col-md-3">
                                <label class="col-form-label" for="estockId">Stock No. - Vin</label>
                                <div class="form-group">
                                    <select class="selectpicker required" onchange="echangeStockDetails(this)" name="estockId" id="estockId" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Stock No:</option>
                                        <optgroup class="stockId">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="estockDetails">Details</label>
                                <div class="form-group">
                                    <input type="text" class="form-control text-center" id="estockDetails" name="estockDetails" disabled />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label" for="estatus">Status</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="estatus" id="estatus" autocomplete="off" autocomplete="off">
                                        <option value="pendingInspection" selected>Pending Inspection</option>
                                        <option value="partsNeeded">Parts Needed</option>
                                        <option value="partsRequested">Parts Requested</option>
                                        <option value="partsArrivedPendingService">Parts Arrived Pending Service</option>
                                        <option value="bodyshopNeeded">Bodyshop Needed</option>
                                        <option value="atBodyshop">At Bodyshop</option>
                                        <option value="bodyshopCompleted">Bodyshop Completed</option>
                                        <option value="completedAwaitingPayment">Completed Awaiting Payment</option>
                                        <!-- <option value="notRequired">Repair not require</option> -->
                                        <option value="repairNotRequired">Repair Not Require</option>
                                        <option value="done">Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-2 col-form-label d-flex justify-content-md-end justify-content-sm-center" for="enotes">Notes</label>
                            <div class="col-md-10 form-group">
                                <textarea class="form-control autosize rounded" name="enotes" id="enotes" placeholder="Notes..."></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="deletedRows" id="deletedRows">
                        <br><br>

                        <div class="overflow-auto">
                            <table class="table" id="eproductTable" style="table-layout: fixed;fixed;min-width:800px;">
                                <thead>
                                    <tr>
                                        <th style="width:20%;text-align:center">Location Number</th>
                                        <th style="width:20%;text-align:center">Damage Type</th>
                                        <th style="width:20%;text-align:center">Damager Severity</th>
                                        <th style="width:20%;text-align:center">Damager Grid Location</th>
                                        <th style="width:10%;text-align:center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="erow1" class="0">
                                        <td class="form-group">
                                            <input type="hidden" name="erowId[]" id="erowId1">
                                            <select class="selectpicker required" name="elocNum[]" id="elocNum1" data-live-search="true" data-size="4" autocomplete="off">
                                                <option value="0" selected disabled>Select</option>
                                                <optgroup class="locNum edefaultOptions1">
                                                </optgroup>
                                            </select>

                                        </td>
                                        <td class="form-group">
                                            <select class="selectpicker required" name="edamageType[]" id="edamageType1" data-live-search="true" data-size="4" autocomplete="off">
                                                <option value="0" selected disabled>Select</option>
                                                <optgroup class="damageType edefaultOptions1">
                                                </optgroup>
                                            </select>
                                        </td>
                                        <td class="form-group">
                                            <select class="selectpicker required" name="edamageSeverity[]" id="edamageSeverity1" data-live-search="true" data-size="4" autocomplete="off">
                                                <option value="0" selected disabled>Select</option>
                                                <optgroup class="damageSeverity edefaultOptions1">
                                                </optgroup>
                                            </select>
                                        </td>
                                        <td class="form-group">
                                            <select class="selectpicker required" name="edamageGrid[]" id="edamageGrid1" data-live-search="true" data-size="4" autocomplete="off">
                                                <option value="0" selected disabled>Select</option>
                                                <optgroup class="damageGrid edefaultOptions1">
                                                </optgroup>
                                            </select>
                                        </td>
                                        <td class="form-group text-center">
                                            <button type="button" id="eaddRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow('e')">Add New</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br><br>
                        <!-- <div class="form-row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="elocNum">Location Number</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="elocNum" id="elocNum" data-live-search="true" data-size="4" autocomplete="off">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="locNum defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="edamageType">Damage Type</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="edamageType" id="edamageType" data-live-search="true" data-size="4" autocomplete="off">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="damageType defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="edamageSeverity">Damager Severity</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="edamageSeverity" id="edamageSeverity" data-live-search="true" data-size="4" autocomplete="off">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="damageSeverity defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="edamageGrid">Damager Grid Location</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="edamageGrid" id="edamageGrid" data-live-search="true" data-size="4" autocomplete="off">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="damageGrid defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div> -->



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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Rule</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewFrom" autocomplete="off" method="post" action="../php_action/createTransportation.php">
                <!-- <form id="addNewFrom" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">

                    <h3 class="h4">Stock Details:</h3>
                    <div class="form-row">
                        <div class="col-md-3">
                            <label class="col-form-label" for="stockId">Stock No. - Vin</label>
                            <div class="form-group">
                                <select class="selectpicker required" onchange="changeStockDetails(this)" name="stockId" id="stockId" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>Stock No:</option>
                                    <optgroup class="stockId">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="stockDetails">Details</label>
                            <div class="form-group">
                                <input type="text" class="form-control text-center" id="stockDetails" name="stockDetails" disabled />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="col-form-label" for="status">Status</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="status" id="status" autocomplete="off">
                                    <option value="pendingInspection" selected>Pending Inspection</option>
                                    <option value="partsNeeded">Parts Needed</option>
                                    <option value="partsRequested">Parts Requested</option>
                                    <option value="partsArrivedPendingService">Parts Arrived Pending Service</option>
                                    <option value="bodyshopNeeded">Bodyshop Needed</option>
                                    <option value="atBodyshop">At Bodyshop</option>
                                    <option value="bodyshopCompleted">Bodyshop Completed</option>
                                    <option value="completedAwaitingPayment">Completed Awaiting Payment</option>
                                    <!-- <option value="notRequired">Repair not require</option> -->
                                    <option value="repairNotRequired">Repair Not Require</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br><br>

                    <div class="row">
                        <label class="col-md-2 col-form-label d-flex justify-content-md-end justify-content-sm-center" for="notes">Notes</label>
                        <div class="col-md-10 form-group">
                            <textarea class="form-control autosize rounded" name="notes" id="notes" placeholder="Notes..."></textarea>
                        </div>
                    </div>
                    <br><br>
                    <div class="overflow-auto">
                        <table class="table" id="productTable" style="table-layout: fixed;min-width:800px">
                            <thead>
                                <tr>
                                    <th style="width:20%;text-align:center">Location Number</th>
                                    <th style="width:20%;text-align:center">Damage Type</th>
                                    <th style="width:20%;text-align:center">Damager Severity</th>
                                    <th style="width:20%;text-align:center">Damager Grid Location</th>
                                    <th style="width:10%;text-align:center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <select class="selectpicker required" name="locNum[]" id="locNum1" data-live-search="true" data-size="4" autocomplete="off">
                                            <option value="0" selected disabled>Select</option>
                                            <optgroup class="locNum defaultOptions1">
                                            </optgroup>
                                        </select>

                                    </td>
                                    <td class="form-group">
                                        <select class="selectpicker required" name="damageType[]" id="damageType1" data-live-search="true" data-size="4" autocomplete="off">
                                            <option value="0" selected disabled>Select</option>
                                            <optgroup class="damageType defaultOptions1">
                                            </optgroup>
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <select class="selectpicker required" name="damageSeverity[]" id="damageSeverity1" data-live-search="true" data-size="4" autocomplete="off">
                                            <option value="0" selected disabled>Select</option>
                                            <optgroup class="damageSeverity defaultOptions1">
                                            </optgroup>
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <select class="selectpicker required" name="damageGrid[]" id="damageGrid1" data-live-search="true" data-size="4" autocomplete="off">
                                            <option value="0" selected disabled>Select</option>
                                            <optgroup class="damageGrid defaultOptions1">
                                            </optgroup>
                                        </select>
                                    </td>
                                    <td class="form-group text-center">
                                        <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br><br>


                    <!-- <div class="form-row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="locNum">Location Number</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="locNum" id="locNum" data-live-search="true" data-size="4" autocomplete="off">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="locNum defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="damageType">Damage Type</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="damageType" id="damageType" data-live-search="true" data-size="4" autocomplete="off">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="damageType defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="damageSeverity">Damager Severity</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="damageSeverity" id="damageSeverity" data-live-search="true" data-size="4" autocomplete="off">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="damageSeverity defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="damageGrid">Damager Grid Location</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="damageGrid" id="damageGrid" data-live-search="true" data-size="4" autocomplete="off">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="damageGrid defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div> -->


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
<script type="text/javascript" src="../assets/app/dataTables.rowsGroup.js"></script>
<script type="text/javascript" src="../custom/js/transportation.js"></script>