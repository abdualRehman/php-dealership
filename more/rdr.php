<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("rdr", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

if (hasAccess("rdr", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}




if ($salesConsultantID != $_SESSION['userRole']) {
    echo '<input type="hidden" name="isConsultant" id="isConsultant" value="false" />';
} else {
    echo '<input type="hidden" name="isConsultant" id="isConsultant" value="true" />';
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
                        <h3 class="portlet-title">RETAIL DELIVERY REGISTRATION</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                    </div>
                    <div class="portlet-body">
                        <table id="datatable-1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Sale Date</th>
                                    <th>Reconcile Date</th>
                                    <th>First Name</th>
                                    <th>Last name</th>
                                    <th>Stock no</th>
                                    <th>Vin</th>
                                    <th>Certified</th>
                                    <th>Delivered</th>
                                    <th>Entered</th>
                                    <th>Approved</th>
                                    <th>RDR Notes</th>
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
                <h5 class="modal-title">Edit RDR</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>

            <form id="editForm" autocomplete="off" method="post" action="../php_action/editRdr.php">
                <div class="modal-body w-100" style="display: inline-table;">
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult">
                        <input type="hidden" name="sale_id" id="sale_id">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <div class="row">
                                    <label for="inputEmail4" class="col-sm-3 col-form-label text-md-center">Date:</label>
                                    <div class="col-sm-9">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="saleDate" placeholder="Select date" id="saleDate" disabled>
                                        </div>
                                    </div>
                                    <label for="inputEmail4" class="col-sm-3 col-form-label text-md-center">Reconcile</label>
                                    <div class="col-sm-9">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="reconcile" id="reconcile" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="row">
                                    <label for="inputPassword4" class="col-sm-1 offset-sm-1 col-form-label text-md-right">Status</label>
                                    <div class="col-sm-6 m-auto">
                                        <div class="form-group text-center">
                                            <div class="btn-group btn-group-toggle" id="statusDiv" data-toggle="buttons">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="row">
                                    <label for="submittedBy" class="col-sm-4 col-form-label text-md-center">Submitted By</label>
                                    <div class="col-sm-8 m-auto">
                                        <input type="text" class="form-control" name="submittedBy" placeholder="Submitted By" id="submittedBy" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-8 mb-3">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="col-form-label" for="stockId">Stock #</label>
                                        <input type="text" class="form-control" id="stockId" disabled>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="col-form-label" for="salesPerson">Sales Consultant:</label>
                                        <input type="text" class="form-control" id="salesPerson" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="col-form-label" for="deliveryDate">Delivery Date</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="deliveryDate" placeholder="Select date" id="deliveryDate" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="col-form-label" for="enteredDate">Entered Date</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="enteredDate" placeholder="Select date" id="enteredDate" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="col-form-label" for="approvedDate">Approved Date</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="approvedDate" placeholder="Select date" id="approvedDate" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="col-form-label" for="rdrNotes">RDR Notes</label>
                                        <textarea class="form-control autosize" name="rdrNotes" id="rdrNotes"></textarea>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-form-label" for="rdrNotes">RDR Notes</label>
                                    <textarea class="form-control autosize" name="rdrNotes" id="rdrNotes"></textarea>
                                </div> -->
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="col-form-label text-md-right" for="iscertified">Certified</label>
                                    <input type="text" class="form-control" id="iscertified" disabled>
                                </div>
                                <div class="form-group">
                                    <div class="saleDetailsDiv" id="saleDetailsDiv">
                                        <textarea class="form-control autosize" name="selectedDetails" style="border:none ;overflow-y: scroll!important;" id="selectedDetails" readonly placeholder="Type Something..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group input-group d-flex flex-md-row flex-sm-column">
                                    <input type="text" name="fname" id="fname" class="form-control w-auto" disabled placeholder="First name">
                                    <input type="text" name="mname" id="mname" class="form-control w-auto" disabled placeholder="Middle name">
                                    <input type="text" name="lname" id="lname" class="form-control w-auto" disabled placeholder="Last name">
                                    <input type="text" name="state" id="state" class="form-control w-auto" disabled placeholder="State">
                                </div>
                            </div>
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
<script type="text/javascript" src="../custom/js/rdr.js"></script>