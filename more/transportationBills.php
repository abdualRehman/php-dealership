<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

// if (hasAccess("todo", "Edit") === 'false') {
//     echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
// }

?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>
<style>
    .customerDetailBody,
    .loadIncentives,
    .loadSalesPersonTodo {
        transition: all 0.2s ease-in;
        /* transition: opacity 0.1s linear; */
        opacity: 1;
        display: block;
    }

    .hidden {
        transition: all 0.2s ease-in;
        opacity: 0;
        height: 0px;
        overflow: hidden;
    }

    .dropdown-header.optgroup-1 {
        padding: 0px;
    }


    .saleDetailsDiv.is-invalid {
        border-color: #f44336;
    }

    .saleDetailsDiv .v-none {
        visibility: hidden;
        height: 0px;
        opacity: 0;
    }

    .saleDetailsDiv {
        border: 1px solid #bdbdbd;
        border-radius: 5px;
    }

    .theme-light .saleDetailsDiv {
        background: #eee;
    }

    .theme-dark .saleDetailsDiv {
        background: #757575;
    }

    .saleDetailsDiv .v-none {
        visibility: hidden;
        height: 0px;
        opacity: 0;
    }

    .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    }

    #datatable-1 tbody tr td {
        padding: 7px;
    }

    .calendar-time {
        display: none !important;
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 900px !important;
        }
    }
</style>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Transportation Bills</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                    </div>
                    <div class="portlet-body">
                        <table id="datatable-1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date Stock In</th>
                                    <th>Stock #</th>
                                    <th>Year</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Vin</th>
                                    <th>Purchase From</th>
                                    <th>Date In Paid</th>
                                    <th>Date Sent</th>
                                    <th>Date Out Paid</th>
                                    <th>Notes</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editDetails">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-dialog-scrollable">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Transportation Bill</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>

            <form id="editTransportationForm" autocomplete="off" method="post" action="../php_action/editTransportationBills.php">
                <div class="modal-body w-100" style="display: inline-table;">
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult">
                        <input type="hidden" name="usedCarId" id="usedCarId" />
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="dateIn" class="col-form-label">Date Stock In</label>
                                <div class="form-group input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" readonly name="dateIn" id="dateIn">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="stockno" class="col-form-label">Stock # - Vin</label>
                                <input type="text" class="form-control" id="stockno" readonly name="stockno" />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="vehicle" class="col-form-label">Vehicle</label>
                                <input type="text" class="form-control" id="vehicle" readonly name="vehicle" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="purchaseFrom" class="col-form-label">Purchase From</label>
                                <input type="text" class="form-control" id="purchaseFrom" readonly name="purchaseFrom">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="date_in_paid" class="col-form-label">Date In Paid</label>
                                <!-- <input type="text" class="form-control" id="vehicle" readonly name="vehicle" placeholder="Vehicle"> -->
                                <div class="form-group input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" name="date_in_paid" id="date_in_paid" />
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="dateSent" class="col-form-label">Date Sent</label>
                                <input type="text" class="form-control" id="dateSent" readonly name="dateSent" />
                            </div>
                            <div class="form-group col-md-3">
                                <label for="date_out_paid" class="col-form-label">Date Out Paid</label>
                                <div class="form-group input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" name="date_out_paid" id="date_out_paid" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="notes" class="col-form-label">Notes</label>
                            <textarea class="form-control autosize" name="notes" id="notes" placeholder="Notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2" id="updateBtn">Save Changes</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>





<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/transportationBills.js"></script>