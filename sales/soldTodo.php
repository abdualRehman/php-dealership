<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("todo", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("todo", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
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

    #datatable-1 thead tr th,
    #datatable-1 tbody tr td {
        text-align: center !important;
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 900px !important;
        }
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
                        <h3 class="portlet-title">Sale Consultant To Do list</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                    </div>
                    <div class="portlet-body">
                        <div class="form-row m-2 customFilters1 d-none">
                            <div class="col-md-12 p-2 d-flex justify-content-between">
                                <div class="dtsp-title">Filters Active</div>
                                <button type="button" id="filterDataTable" class="btn btn-flat-primary btn-wider">Filter Data</button>
                            </div>
                            <div class="col-12 row">
                                <div class="col p-1">
                                    <select class="form-control filterTags" id="consultantFilter" multiple="multiple">
                                        <optgroup label="Sales Consultant">
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col p-1">
                                    <select class="form-control filterTags" id="stockFilter" multiple="multiple">
                                        <optgroup label="Stock #">
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col p-1">
                                    <select class="form-control filterTags" id="vehicleFilter" multiple="multiple">
                                        <optgroup label="Vechicle">
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col p-1">
                                    <select class="form-control filterTags" id="stateFilter" multiple="multiple">
                                        <optgroup label="State">
                                        </optgroup>
                                    </select>
                                </div>


                            </div>
                        </div>
                        <table id="datatable-1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sold Date</th>
                                    <th>Customer</th>
                                    <th>Stock #</th>
                                    <th>Vehicle</th>
                                    <th>State</th>
                                    <th>Vin Check</th>
                                    <th>Insurance</th>
                                    <th>Trade Title</th>
                                    <th>Registration</th>
                                    <th>Inspection</th>
                                    <th>Salesperson Status</th>
                                    <th>Paid</th>
                                    <th>Action</th>
                                    <th>Sales Consultant</th>
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
                <h5 class="modal-title">Edit Todo</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>

            <form id="editSoldTodoForm" autocomplete="off" method="post" action="../php_action/editSoldTodo.php">
                <div class="modal-body w-100" style="display: inline-table;">
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="saleDate" class="col-form-label">Date</label>
                                <div class="form-group input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" readonly name="saleDate" placeholder="Select date" id="saleDate">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="customerName" class="col-form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName" readonly name="customerName" placeholder="Customer Name">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="state" class="col-form-label">State</label>
                                <input type="text" class="form-control" id="state" readonly name="state" placeholder="State">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="stockNo" class="col-form-label">Stock #</label>
                                <input type="text" class="form-control" id="stockNo" readonly name="stockNo" placeholder="Stock No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="vehicle" class="col-form-label">Vehicle</label>
                                <input type="text" class="form-control" id="vehicle" readonly name="vehicle" placeholder="Vehicle">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="vinTodo" class="col-form-label">Vin</label>
                                <input type="text" class="form-control" id="vinTodo" readonly name="vin" placeholder="Vin">
                            </div>
                        </div>
                        <h5 class="my-4">Sales Consultant Todo</h5>
                        <div class="form-row">
                            <label for="vincheck" class="col-md-1 col-form-label">Vin Check</label>
                            <input type="hidden" name="soldTodoId" id="soldTodoId">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select onchange="chnageStyle(this)" name="vincheck" id="vincheck" class="selectpicker" data-style="btn-outline-danger">
                                        <option value="checkTitle">Check Title</option>
                                        <option value="need">Need</option>
                                        <option value="notNeed">Doesn't Need</option>
                                        <option value="n/a">N/A</option>
                                        <option value="onHold">On Hold</option>
                                        <option value="done">Done</option>
                                    </select>
                                </div>
                            </div>
                            <label for="insurance" class="col-md-1 col-form-label">Insurance</label>
                            <div class="col-md-2">
                                <select class="selectpicker" onchange="chnageStyle(this)" id="insurance" name="insurance" data-style="btn-outline-danger">
                                    <option value="need">Need</option>
                                    <option value="inHouse">In House</option>
                                    <option value="n/a">N/A</option>
                                </select>
                            </div>
                            <label for="tradeTitle" class="col-md-1 col-form-label">Trade Title</label>
                            <div class="col-md-2">
                                <select class="selectpicker" onchange="chnageStyle(this)" id="tradeTitle" name="tradeTitle" data-style="btn-outline-danger">
                                    <option value="need">Need</option>
                                    <option value="payoff">Payoff</option>
                                    <option value="noTrade">No Trade</option>
                                    <option value="inHouse">In House</option>
                                </select>
                            </div>
                            <label for="registration" class="col-md-1 col-form-label">Registration</label>
                            <div class="col-md-2">
                                <select class="selectpicker" onchange="chnageStyle(this)" id="registration" name="registration" data-style="btn-outline-danger">
                                    <option value="pending">Pending</option>
                                    <option value="done">Done</option>
                                    <option value="customerHas">Customer Has</option>
                                    <option value="mailed">Mailed</option>
                                    <option value="n/a">N/A</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">

                            <label for="inspection" class="col-md-1 col-form-label">Inspection</label>
                            <div class="col-md-3">
                                <select class="selectpicker" onchange="chnageStyle(this)" id="inspection" name="inspection" data-style="btn-outline-danger">
                                    <option value="need">Need</option>
                                    <option value="notNeed">Doesn't Need</option>
                                    <option value="done">Done</option>
                                    <option value="donebycustomer">Done by Customer</option>
                                    <option value="n/a">N/A</option>
                                </select>
                            </div>
                            <label for="salePStatus" class="col-md-1 col-form-label">Salesperson Status</label>
                            <div class="col-md-3">
                                <select class="selectpicker" onchange="chnageStyle(this)" id="salePStatus" name="salePStatus" data-style="btn-outline-danger">

                                    <option value="dealWritten">Deal Written</option>
                                    <option value="gmdSubmit">GMD Submit</option>
                                    <option value="contracted">Contracted</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="delivered">Delivered</option>

                                </select>
                            </div>
                            <label for="paid" class="col-md-1 col-form-label">Paid</label>
                            <div class="col-md-3">
                                <select class="selectpicker" onchange="chnageStyle(this)" id="paid" name="paid" data-style="btn-outline-danger">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>

                        </div>

                        <div class="form-group <?php echo ($salesConsultantID == $_SESSION['userRole'] || 'Admin' == $_SESSION['userRole'] || $branchAdmin == $_SESSION['userRole']) ?: "makeDisable"; ?>">
                            <label class="col-form-label" for="consultantNote">Consultant Notes</label>
                            <textarea class="form-control autosize" name="consultantNote" id="consultantNote" placeholder="Consultant Notes..."></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2" id="updateBtn">Save Changes</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>





<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/soldTodo.js"></script>