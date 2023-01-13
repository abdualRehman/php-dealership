<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("warranty", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("warranty", "Edit") === 'false') {
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

    .dropdown-header.optgroup-1 {
        padding: 0px !important;
    }

    .tt-is-under-cursor {
        background-color: #c9c8c8 !important;
        color: #FFFFFF !important;
    }

    .tt-cursor {
        background-color: #c9c8c8 !important;
        color: #FFFFFF !important;
    }

    .theme-light .custom-control-label::before {
        background: #f5f5f5;
        border-color: #8b8b8b;
    }

    .theme-dark .custom-control-label::before {
        background: #616161;
        border-color: #e0e0e0;
    }

    .not-valid {
        border-color: #f44336 !important;
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1000px;
        }
    }
    
    .table.table-bordered.table-striped.table-hover.dataTable.no-footer.dtr-inline.fixedHeader-floating {
        top: 5rem!important;
    }
</style>

<?php
if ($salesConsultantID != $_SESSION['userRole']) {
    echo '<input type="hidden" name="isConsultant" id="isConsultant" value="false" />';
} else {
    echo '<input type="hidden" name="isConsultant" id="isConsultant" value="true" />';
}
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Warranty Cancellation</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("warranty", "Add") !== 'false') {
                        ?>
                            <button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                                <i class="fa fa-plus ml-1 mr-2"></i> Add New
                            </button>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Warranty</th>
                                    <th>Date Cancelled</th>
                                    <th>Refund Destination</th>
                                    <th>Finance Manager</th>
                                    <th>Date Sold</th>
                                    <th>Paid</th>
                                    <th>Notes</th>
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
                <h5 class="modal-title">Edit Cancellation</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editWCForm" action="../php_action/editWarrentyCancellation.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="wcId" id="wcId">
                        <div class="form-row">
                            <label for="ecustomerName" class="col-sm-2 col-form-label text-md-center">Customer Name</label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="ecustomerName" name="ecustomerName" />
                                </div>
                            </div>
                            <label for="efinanceManager" class="col-sm-2 col-form-label text-md-center">Finance Manager:</label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select class="selectpicker" name="efinanceManager" id="efinanceManager" data-live-search="true" data-size="4">
                                        <optgroup class="financeManagerList">
                                            <option value="0" selected disabled>Finance Manager</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="ewarranty" class="col-sm-2 col-form-label text-md-center">Warranty</label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select class="form-control warranty tags" id="ewarranty" name="ewarranty[]" multiple="multiple" required="required">
                                        <option value="GAP">GAP</option>
                                        <option value="MILLENIUM">MILLENIUM</option>
                                        <option value="TIRE">TIRE</option>
                                        <option value="VSC">VSC</option>
                                        <option value="DENT">DENT</option>
                                        <option value="SIMONIZ">SIMONIZ</option>
                                        <option value="HONDACARE">HONDA CARE</option>
                                    </select>
                                </div>
                            </div>
                            <label for="erefundDes" class="col-sm-2 col-form-label text-md-center">Refund Destination:</label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="erefundDes" name="erefundDes" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label for="edateCancelled" class="col-sm-2 text-md-center col-form-label">Date Cancelled</label>
                            <div class="form-group col-sm-3">
                                <div class="input-group flex-nowrap">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="edateCancelled" id="edateCancelled" />
                                </div>
                            </div>
                            <label for="edateSold" class="col-sm-2  col-form-label text-md-center">Date Sold</label>
                            <div class="form-group col-sm-3">
                                <div class="input-group flex-nowrap">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="edateSold" id="edateSold">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="custom-control custom-control-lg custom-checkbox" style="font-size: initial;">
                                    <input type="checkbox" class="custom-control-input" name="epaid" id="epaid">
                                    <label class="custom-control-label" for="epaid">Paid</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="col-md-2 text-md-center col-form-label" for="enotes">Notes</label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <textarea class="form-control autosize" name="enotes" id="enotes" placeholder="Notes..."></textarea>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Cancellation</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewForm" autocomplete="off" method="post" action="../php_action/createWarrentyCancellation.php">
                <!-- <form id="addNewForm" autocomplete="off" method="post" action="#"> -->
                <input type="hidden" name="wattentyValue" id="wattentyValue" value="" />
                <div class="modal-body">
                    <div class="form-row">
                        <label for="customerName" class="col-sm-2 col-form-label text-md-center">Customer Name</label>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" id="customerName" name="customerName" />
                            </div>
                        </div>
                        <label for="financeManager" class="col-sm-2 col-form-label text-md-center">Finance Manager:</label>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select class="selectpicker" name="financeManager" id="financeManager" data-live-search="true" data-size="4">
                                    <optgroup class="financeManagerList">
                                        <option value="0" selected disabled>Finance Manager</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="warranty" class="col-sm-2 col-form-label text-md-center">Warranty</label>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select class="form-control warranty tags" id="warranty" name="warranty[]" multiple="multiple" required="required">
                                    <option value="GAP">GAP</option>
                                    <option value="MILLENIUM">MILLENIUM</option>
                                    <option value="TIRE">TIRE</option>
                                    <option value="VSC">VSC</option>
                                    <option value="DENT">DENT</option>
                                    <option value="SIMONIZ">SIMONIZ</option>
                                    <option value="HONDACARE">HONDA CARE</option>
                                </select>
                            </div>
                        </div>
                        <label for="refundDes" class="col-sm-2 col-form-label text-md-center">Refund Destination:</label>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="refundDes" name="refundDes" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label for="dateCancelled" class="col-sm-2 text-md-center col-form-label">Date Cancelled</label>
                        <div class="form-group col-sm-3">
                            <div class="input-group flex-nowrap">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control datepicker" name="dateCancelled" id="dateCancelled" />
                            </div>
                        </div>
                        <label for="dateSold" class="col-sm-2  col-form-label text-md-center">Date Sold</label>
                        <div class="form-group col-sm-3">
                            <div class="input-group flex-nowrap">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control datepicker" name="dateSold" id="dateSold">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="custom-control custom-control-lg custom-checkbox" style="font-size: initial;">
                                <input type="checkbox" class="custom-control-input" name="paid" id="paid">
                                <label class="custom-control-label" for="paid">Paid</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="col-md-2 text-md-center col-form-label" for="notes">Notes</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <textarea class="form-control autosize" name="notes" id="notes" placeholder="Notes..."></textarea>
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


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/warrantyCancellation.js"></script>