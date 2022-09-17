<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("usedCars", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

if (hasAccess("usedCars", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
if ($_SESSION['userRole'] == $onlineManagerID || hasAccess("usedCars", "Edit") === 'false') {
    echo '<input type="hidden" name="isRoleAllowed" id="isRoleAllowed" value="false" />';
} else if ($_SESSION['userRole'] != $onlineManagerID && hasAccess("usedCars", "Edit") !== 'false') {
    echo '<input type="hidden" name="isRoleAllowed" id="isRoleAllowed" value="true" />';
}

$allowedForOffice = false;
if ($_SESSION['userRole'] == $officeID) {
    echo '<input type="hidden" name="allowedForOffice" id="allowedForOffice" value="false" />';
} else {
    echo '<input type="hidden" name="allowedForOffice" id="allowedForOffice" value="true" />';
    $allowedForOffice = true;
}

?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>


<style>
    .calendar-time {
        display: none !important;
    }

    label.btn-outline-primary,
    label.btn-outline-success,
    label.btn-outline-danger,
    label.btn-outline-info {
        /* padding: 10px; */
        width: 150px;
        margin: 5px;
        /* font-size: medium; */
    }

    label.btn-outline-success:hover,
    label.btn-outline-danger:hover,
    label.btn-outline-infor:hover {
        color: white;
    }

    .btn-group-toggle label input {
        visibility: hidden;
        position: absolute;
    }

    .dataTables_scrollHeadInner {
        width: 100% !important;
    }

    .dataTables_scroll {
        overflow: auto !important;
    }

    .row {
        align-items: baseline;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 800px !important;
            margin: 1.75rem auto;
        }
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1000px !important;
        }
    }

    /* ----------------- Slick ----------------------  */
    .slick-next,
    .slick-prev {
        bottom: 0px;
    }

    .carousel-item {
        position: relative;
        text-align: center;
        /* width: -webkit-fill-available !important; */
        color: white;
        max-height: 300px;
        overflow-y: hidden;
    }

    .carousel-item img {
        width: 90%;
    }

    .carousel-item .card {
        position: absolute;
        top: 1px;
        right: 13px;
        /* background: transparent; */
        background: black;
        padding: 0px 3px;
        cursor: pointer;
    }

    .slick-slide {
        height: 80px;
        /* width: fit-content!important; */
    }

    .slick-track {
        display: flex !important;
        min-width: max-content !important;
    }

    .slick-lightbox-slick-img {
        height: auto !important;
    }

    .slick-slide img {
        height: 80px;
        width: 100%;
        object-fit: cover;
        /* min-width: fit-content!important; */
    }

    /* datatable 2 hide 3rd column  */
    .DealerTable .dtsp-searchPane:last-child {
        display: none !important;
    }

    .clear-selection {
        text-decoration: underline;
        cursor: pointer;
    }

    body.theme-dark .selectGroup {
        color: #f5f5f5;
        background: #535353;
    }

    body.theme-light .selectGroup {
        color: #424242;
        background: #fafafa;
    }

    #mods label {
        cursor: pointer;
    }

    /* .w-inherit {
        width: "-webkit-fill-available"!important;
    } */
</style>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <div class="text-center m-auto justify-content-center">
                            <div class="btn-group-toggle" id="mods" data-toggle="buttons">
                                <div class="row p-2">
                                    <div class="col-md-12">
                                        <?php
                                        if ($allowedForOffice) {
                                        ?>
                                            <label class="btn text-responsive active">
                                                <input type="radio" name="mod" value="addToSheet" id="searchAddToSheet" data-title="Add To Sheet"> Add To Sheet <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="missingDate" data-title="Missing Date"> Missing Date <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="titleIssue" id="searchTitleIssue" data-title="Title Issues"> Title Issues <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="readyToShip" data-title="W/S Ready To Ship"> W/S Ready To Ship <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="keysPulled" data-title="W/S Keys Pulled"> W/S Keys Pulled <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="atAuction" data-title="At Auction"> At Auction <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="soldAtAuction" data-title="Sold At Auction"> Sold At Auction <br> <span></span>
                                            </label>
                                        <?php
                                        } else {
                                            // title issue only
                                            echo '<label class="btn text-responsive">
                                            <input type="radio" name="mod" value="titleIssue" id="searchTitleIssue" data-title="Title Issues"> Title Issues <br> <span></span>
                                        </label>';
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row p-2 <?php echo ($allowedForOffice) ? "d-flex" : "d-none"; ?>">
                                    <div class="col-md-12">
                                        <div id="year">
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="retail" data-title="Retail"> Retail <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="sold" data-title="Sold"> Sold <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="fixAge" data-title="Fix Age"> Fix CDK AGE <br> <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="inspectionTable">
                            <div class="form-row text-right">
                                <div class="col-md-12 p-1 pr-2">
                                    <button class="btn btn-primary p-2" onclick="toggleFilterClass2()">
                                        <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                                    </button>
                                </div>
                            </div>
                            <table id="datatable-1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Age</th>
                                        <th>Stock no || Vin</th>
                                        <th>Inventory Date</th>
                                        <th>Keys</th>
                                        <th>Year</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Mileage</th>
                                        <th>Lot</th>
                                        <th>Title</th>
                                        <th>
                                            <select class="form-control" name="statusPriority" onchange="filterDatatable()" id="statusPriority">
                                                <option value="" selected>Title Priority</option>
                                                <option value="New">New</option>
                                                <option value="Low">Low</option>
                                                <option value="Medium">Medium</option>
                                                <option value="High">High</option>
                                                <option value="Problem">Problem</option>
                                                <option value="Done">Done</option>
                                            </select>
                                        </th>
                                        <th>Customer</th>
                                        <th>Sales Consultant</th>
                                        <th>Title Notes</th>
                                        <th>Balance</th>
                                        <th>Retail</th>
                                        <th>Certified</th>
                                        <th>Stock Type</th>
                                        <th>Wholesale</th>
                                        <th>Retail Status</th>
                                        <th>Date Sent</th>
                                        <th>Wholesale Notes</th>
                                        <th>Sold Price</th>
                                        <th>Profit</th>
                                        <th>UCI</th>
                                        <th>Purchase From</th>
                                        <th>Date Sold</th>
                                        <th>Action</th>
                                        <th>UCI RO</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="FixSDKtable d-none">
                            <div class="form-row text-right">
                                <div class="col-md-12 p-1 pr-2">
                                    <button class="btn btn-primary p-2" onclick="toggleFilterClass2()">
                                        <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                                    </button>
                                </div>
                            </div>
                            <table id="datatable-2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Inventory Date</th>
                                        <th>AGE</th>
                                        <th>CDK AGE</th>
                                        <th>Action</th>
                                        <th>Stock no || Vin</th>
                                        <th>Year</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Mileage</th>
                                        <th>Lot</th>
                                        <th>Balance</th>
                                        <th>Retail</th>
                                        <th>Certified</th>
                                        <th>Stock Type</th>
                                        <th>Wholesale</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

if ($_SESSION['userRole'] != $onlineManagerID && $_SESSION['userRole'] != $officeID) {

?>

    <div class="modal fade" id="modal8">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Used Cars</h5>
                    <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="updateUsedCarsForm" autocomplete="off" method="post" action="../php_action/updateUsedCars.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">
                            <input type="hidden" name="vehicleId" id="vehicleId">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row align-items-baseline">
                                        <label for="stockno" class="col-sm-3 offset-sm-1 col-form-label">Stock no. Vin</label>
                                        <div class="form-group col-sm-8">
                                            <input type="text" class="form-control" name="stockno" id="stockno" readonly autocomplete="off" autofill="off" />
                                        </div>

                                        <label for="lotNotes" class="col-sm-3 offset-sm-1 col-form-label">Inventory Date</label>
                                        <div class="form-group col-sm-8">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="invDate" placeholder="Inventory date" id="invDate">
                                            </div>
                                        </div>
                                        <label for="retailStatus" class="col-sm-3 offset-sm-1 col-form-label">Retail Status</label>
                                        <div class="form-group col-sm-8">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="retailStatus">
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="retailStatus" value="retail" id="retail">
                                                    Retail
                                                </label>
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="retailStatus" value="wholesale" id="wholesale">
                                                    Wholesale
                                                </label>
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="retailStatus" value="sold" id="sold">
                                                    Sold
                                                </label>
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="retailStatus" value="rintercompany" id="rintercompany">
                                                    Intercompany
                                                </label>
                                            </div>
                                        </div>
                                        <label for="purchaseFrom" class="col-sm-3 offset-sm-1 col-form-label">Purchase From</label>
                                        <div class="form-group col-sm-8">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="purchaseFrom">
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="purchaseFrom" value="auction" id="auction">
                                                    Auction
                                                </label>
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="purchaseFrom" value="customer" id="customer">
                                                    Customer
                                                </label>
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="purchaseFrom" value="honda" id="honda">
                                                    Honda
                                                </label>
                                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                    <input type="radio" name="purchaseFrom" value="pintercompany" id="pintercompany">
                                                    Intercompany
                                                </label>
                                            </div>
                                            <span class="badge-text-primary pl-2 clear-selection" id="clear-selection" data-id="purchaseFrom">Clear Selection</span>
                                        </div>
                                        <div class="form-group offset-sm-4 col-sm-4">
                                            <div class="custom-control custom-control-lg custom-checkbox" style="font-size: initial;">
                                                <input type="checkbox" class="custom-control-input" name="certified" id="certified">
                                                <label class="custom-control-label" for="certified">Certified</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="custom-control custom-control-lg custom-checkbox" style="font-size: initial;">
                                                <input type="checkbox" class="custom-control-input" name="title" id="title">
                                                <label class="custom-control-label" for="title">Title</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <label for="submittedBy" class="col-form-label">Submitted By</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="submittedBy" id="submittedBy" readonly autocomplete="off" autofill="off" />
                                    </div>
                                    <label for="selectedDetails" class="col-form-label">Vehicle</label>
                                    <div class="saleDetailsDiv" id="saleDetailsDiv">
                                        <textarea class="form-control autosize" name="selectedDetails" style="border:none ;overflow-y: scroll!important;" id="selectedDetails" readonly placeholder="Type Something..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row align-items-baseline">
                                        <label for="titlePriority" class="col-sm-3 offset-sm-1 col-form-label">Title Priority</label>
                                        <div class="form-group col-sm-8">
                                            <!-- <input type="text" class="form-control" name="titlePriority" id="titlePriority" autocomplete="off" autofill="off" /> -->
                                            <select class="selectpicker" name="titlePriority" id="titlePriority">
                                                <!-- <option selected title="..." value="">Select...</option> -->
                                                <option value="New">New</option>
                                                <option value="Low">Low</option>
                                                <option value="Medium">Medium</option>
                                                <option value="High">High</option>
                                                <option value="Problem">Problem</option>
                                                <option value="Done">Done</option>
                                            </select>
                                            <div class="p-1 pb-0">
                                                <span class="badge-text-primary clear-selection" id="clear-selection" data-id="titlePriority">Clear Selection</span>
                                            </div>
                                        </div>
                                        <label for="salesConsultant" class="col-sm-3 offset-sm-1 col-form-label">Sales Consultant</label>
                                        <div class="form-group col-sm-8">
                                            <select class="selectpicker" name="salesConsultant" id="salesConsultant" data-live-search="true" data-size="4">
                                                <option value="0" selected disabled>Sales Consultant</option>
                                            </select>
                                            <div class="p-1 pb-0">
                                                <span class="badge-text-primary clear-selection" id="clear-selection1" data-id="salesConsultant">Clear Selection</span>
                                            </div>
                                        </div>
                                        <label for="customerName" class="col-sm-3 offset-sm-1 col-form-label">Customer</label>
                                        <div class="form-group col-sm-8">
                                            <input type="text" class="form-control" name="customerName" id="customerName" autocomplete="off" autofill="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="titleNotes" class="col-form-label">Title Notes</label>
                                    <div class="form-group">
                                        <textarea class="form-control autosize" name="titleNotes" id="titleNotes" placeholder="Title Notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2 offset-sm-1">
                                    <div class="custom-control custom-control-lg custom-checkbox" style="font-size: initial;">
                                        <input type="checkbox" class="custom-control-input" name="keys" id="keys">
                                        <label class="custom-control-label" for="keys">Keys</label>
                                    </div>
                                </div>
                                <label for="dateSent" class="col-sm-1 col-form-label">Date Sent</label>
                                <div class="form-group col-sm-2">
                                    <div class="form-group input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="dateSent" id="dateSent">
                                    </div>
                                </div>
                                <label for="dateSold" class="col-sm-1 col-form-label text-center">Date Sold</label>
                                <div class="form-group col-sm-2">
                                    <div class="form-group input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="dateSold" id="dateSold">
                                    </div>
                                </div>

                                <label for="soldPrice" class="col-sm-1 col-form-label">Sold Price</label>
                                <div class="form-group col-sm-2">
                                    <div class="form-group input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-dollar-sign"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="soldPrice" id="soldPrice">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center m-auto">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="onlineDescription" class="col-form-label text-center">Online Description</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="onlineDescription" id="onlineDescription" autocomplete="off" autofill="off" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="roNotes" class="col-form-label text-center">RO Online Notes</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="roNotes" id="roNotes" autocomplete="off" autofill="off" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-form-label text-center"></label>
                                            <div class="form-group mt-1">
                                                <input type="button" class="form-control-btn btn btn-outline-info w-100" onclick="toggleInfo('serviceDiv')" value="Add Service" />

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="d-none" id="serviceDiv">
                                <hr>
                                <div class="row justify-content-center m-auto">
                                    <div class="col-md-12">
                                        <div class="row align-items-end">
                                            <div class="col-md-3 p-2">
                                                <label for="uci" class="col-form-label">UCI</label>
                                                <div class="form-group">
                                                    <select class="selectpicker" name="uci" id="uci">
                                                        <option disabled selected value="0">Select</option>
                                                        <option value="need">Need</option>
                                                        <option value="opened">Opened</option>
                                                        <option value="closed">Closed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 p-2">
                                                <label for="uciRo" class="col-form-label">UCI RO #</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="uciRo" id="uciRo" autocomplete="off" autofill="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-2 p-2">
                                                <label for="uciClosed" class="col-form-label">UCI Closed</label>
                                                <div class="form-group">
                                                    <div class="form-group input-group">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-dollar-sign"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control" name="uciClosed" id="uciClosed">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 p-2">
                                                <label for="uciApproved" class="col-form-label">UCI Approved</label>
                                                <div class="form-group">
                                                    <div class="form-group input-group">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-dollar-sign"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control" name="uciApproved" id="uciApproved">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2 text-center p-2">
                                                <div class="custom-control custom-control-lg custom-checkbox" style="font-size:initial;">
                                                    <input type="checkbox" class="custom-control-input" name="oci" id="oci">
                                                    <label class="custom-control-label" for="oci">UCI OK</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<?php
} else if ($_SESSION['userRole'] == $onlineManagerID) {

?>
    <div class="modal fade" id="modal8">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Used Cars</h5>
                    <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="updateUsedCarsForm" autocomplete="off" method="post" action="../php_action/updateUsedCarsNotes.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">
                            <input type="hidden" name="vehicleId" id="vehicleId" />
                            <input type="hidden" name="titlePriority" id="titlePriority">
                            <input type="hidden" name="customerName" id="customerName">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row align-items-baseline">
                                        <label for="stockno" class="col-sm-3 offset-sm-1 col-form-label">Stock no. Vin</label>
                                        <div class="form-group col-sm-8">
                                            <input type="text" class="form-control" name="stockno" id="stockno" readonly autocomplete="off" autofill="off" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="onlineDescription" class="col-form-label text-center">Online Description</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="onlineDescription" id="onlineDescription" autocomplete="off" autofill="off" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="roNotes" class="col-form-label text-center">RO Online Notes</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="roNotes" id="roNotes" autocomplete="off" autofill="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 d-none">
                                        <select class="selectpicker d-none" name="salesConsultant" id="salesConsultant" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>Sales Consultant</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="titleNotes" class="col-form-label">Title Notes</label>
                                            <div class="form-group">
                                                <textarea class="form-control autosize" name="titleNotes" id="titleNotes" placeholder="Title Notes..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <label for="submittedBy" class="col-form-label">Submitted By</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="submittedBy" id="submittedBy" readonly autocomplete="off" autofill="off" />
                                    </div>
                                    <label for="selectedDetails" class="col-form-label">Vehicle</label>
                                    <div class="saleDetailsDiv" id="saleDetailsDiv">
                                        <textarea class="form-control autosize" name="selectedDetails" style="border:none ;overflow-y: scroll!important;" id="selectedDetails" readonly placeholder="Type Something..."></textarea>
                                    </div>
                                </div>
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

<?php
} else if ($_SESSION['userRole'] == $officeID) {
?>
    <div class="modal fade" id="modal8">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Used Cars</h5>
                    <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="updateUsedCarsForm" autocomplete="off" method="post" action="../php_action/updateUsedCarsNotes.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">
                            <input type="hidden" name="vehicleId" id="vehicleId" />
                            <input type="hidden" name="onlineDescription" id="onlineDescription" />
                            <input type="hidden" name="roNotes" id="roNotes" />
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row align-items-baseline">
                                        <label for="stockno" class="col-sm-3 offset-sm-1 col-form-label">Stock no. Vin</label>
                                        <div class="form-group col-sm-8">
                                            <input type="text" class="form-control" name="stockno" id="stockno" readonly autocomplete="off" autofill="off" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label for="titlePriority" class="col-sm-3 offset-sm-1 col-form-label">Title Priority</label>
                                        <div class="form-group col-sm-8">
                                            <select class="selectpicker" name="titlePriority" id="titlePriority">
                                                <option value="New">New</option>
                                                <option value="Low">Low</option>
                                                <option value="Medium">Medium</option>
                                                <option value="High">High</option>
                                                <option value="Problem">Problem</option>
                                                <option value="Done">Done</option>
                                            </select>
                                            <div class="p-1 pb-0">
                                                <span class="badge-text-primary clear-selection" id="clear-selection" data-id="titlePriority">Clear Selection</span>
                                            </div>
                                        </div>
                                        <label for="salesConsultant" class="col-sm-3 offset-sm-1 col-form-label">Sales Consultant</label>
                                        <div class="form-group col-sm-8">
                                            <select class="selectpicker" name="salesConsultant" id="salesConsultant" data-live-search="true" data-size="4">
                                                <option value="0" selected disabled>Sales Consultant</option>
                                            </select>
                                            <div class="p-1 pb-0">
                                                <span class="badge-text-primary clear-selection" id="clear-selection" data-id="salesConsultant">Clear Selection</span>
                                            </div>
                                        </div>
                                        <label for="customerName" class="col-sm-3 offset-sm-1 col-form-label">Customer</label>
                                        <div class="form-group col-sm-8">
                                            <input type="text" class="form-control" name="customerName" id="customerName" autocomplete="off" autofill="off" />
                                        </div>
                                        <div class="col-md-11 offset-sm-1">
                                            <label for="titleNotes" class="col-form-label">Title Notes</label>
                                            <div class="form-group">
                                                <textarea class="form-control autosize" name="titleNotes" id="titleNotes" placeholder="Title Notes..."></textarea>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <label for="submittedBy" class="col-form-label">Submitted By</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="submittedBy" id="submittedBy" readonly autocomplete="off" autofill="off" />
                                    </div>
                                    <label for="selectedDetails" class="col-form-label">Vehicle</label>
                                    <div class="saleDetailsDiv" id="saleDetailsDiv">
                                        <textarea class="form-control autosize" name="selectedDetails" style="border:none ;overflow-y: scroll!important;" id="selectedDetails" readonly placeholder="Type Something..."></textarea>
                                    </div>
                                </div>
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
<?php
}

?>






<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/usedCars.js"></script>