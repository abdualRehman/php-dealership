<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("tansptDmg", "Add") === 'false' && hasAccess("tansptDmg", "Edit") === 'false' && hasAccess("tansptDmg", "Remove") === 'false') {
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
                                    <th>Damage</th>
                                    <th>Status</th>
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
                    <div class="showResult d-none">
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
                                    <select class="selectpicker required" name="estatus" id="estatus">
                                        <option value="pending" selected>Pending</option>
                                        <option value="notRequired">Repair not require</option>
                                        <option value="done">Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="elocNum">Location Number</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="elocNum" id="elocNum" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="locNum defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="edamageType">Damage Type</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="edamageType" id="edamageType" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="damageType defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="edamageSeverity">Damager Severity</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="edamageSeverity" id="edamageSeverity" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="damageSeverity defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="edamageGrid">Damager Grid Location</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="edamageGrid" id="edamageGrid" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select</option>
                                        <optgroup class="damageGrid defaultOptions">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
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
                                <select class="selectpicker required" name="status" id="status">
                                    <option value="pending" selected>Pending</option>
                                    <option value="notRequired">Repair not require</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="locNum">Location Number</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="locNum" id="locNum" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="locNum defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="damageType">Damage Type</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="damageType" id="damageType" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="damageType defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="damageSeverity">Damager Severity</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="damageSeverity" id="damageSeverity" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="damageSeverity defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="damageGrid">Damager Grid Location</label>
                            <div class="form-group">
                                <select class="selectpicker required" name="damageGrid" id="damageGrid" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>Select</option>
                                    <optgroup class="damageGrid defaultOptions">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="importNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Import File</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="ImportRule" autocomplete="off" method="post" action="../php_action/createTransportation.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <h3 class="h4">Import RESIDUALS.CSV File:</h3>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="excelFile">File</label>
                                <input type="file" class="form-control-file" id="excelFile" name="excelFile" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expireIni">Expire In.</label>
                                <input type="text" class="form-control" id="expireIni" name="expireIni" placeholder="Expire In.">
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-md-12">
                            <div class="alert alert-outline-info fade show mb-0">
                                <div class="alert-icon"><i class="fa fa-info"></i></div>
                                <div class="alert-content">
                                    <h4 class="alert-heading">Please Note!</h4>
                                    <p>A record that already exists in the database will be updated by this file.</p>
                                </div>
                                <button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="form-row mt-4 mb-4">
                        <div class="col-md-6">
                            <h3 class="h6 mb-3">12,000 Miles Per Year </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="12_24_33i" value="1" name="12_24_33i" class="form-control" placeholder=" ">
                                        <label for="12_24_33i">24-33 -%</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="12_36_48i" value="2" name="12_36_48i" class="form-control" placeholder=" ">
                                        <label for="12_36_48i">36-48 -%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6 mb-3">10,000 Miles Per Year </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="10_24_33i" value="2" name="10_24_33i" class="form-control" placeholder=" ">
                                        <label for="10_24_33i">24-33 -%</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="10_36_48i" value="3" name="10_36_48i" class="form-control" placeholder=" ">
                                        <label for="10_36_48i">36-48 -%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" id="importSubmitBtn" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/transportation.js"></script>