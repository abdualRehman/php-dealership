<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

loadDefaultRoles();

if ($_GET['r'] == 'man') {
    $userRole = $_SESSION['userRole'];
    if (hasAccess("sale", "View") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>man</div>";

    if (hasAccess("sale", "Edit") === 'false') {
        echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
    } else {
        echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
    }

    if ($userRole != 'Admin' && $userRole != $branchAdmin && $userRole != $generalManagerID && $userRole != $salesManagerID) {
        echo '<input type="hidden" name="vgb" id="vgb" value="false">';
    } else {
        echo '<input type="hidden" name="vgb" id="vgb" value="true">';
    }

    if ($salesConsultantID != $_SESSION['userRole']) {
        echo '<input type="hidden" name="isConsultant" id="isConsultant" value="false" />';
    } else {
        echo '<input type="hidden" name="isConsultant" id="isConsultant" value="true" />';
    }

    $_editTodo = (hasAccess("todo", "Edit") !== 'false') ?: "disabled";
    $_editIncentives = (hasAccess("incentives", "Edit") !== 'false') ?: "disabled";

    echo '<input type="hidden" name="loggedInUserRole" id="loggedInUserRole" value="' . $userRole . '" />';
    echo '<input type="hidden" name="currentUser" id="currentUser" value="' . $_SESSION['userName'] . '">';
    echo '<input type="hidden" name="currentUserId" id="currentUserId" value="' . $_SESSION['userId'] . '">';
}

?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>
<style>
    .makeDisable {
        pointer-events: none;
        opacity: 0.8;
    }

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

    body.theme-dark #datatable-1 tbody td.dublicate_left {
        border: 3px solid red;
        border-right: 0.1px solid #757575;
    }

    body.theme-dark #datatable-1 tbody td.dublicate_right {
        border: 3px solid red;
        border-left: 0.1px solid #757575;
    }

    body.theme-light #datatable-1 tbody td.dublicate_left {
        border: 3px solid red;
        border-right: 0.1px solid #f5f5f5;
    }

    body.theme-light #datatable-1 tbody td.dublicate_right {
        border: 3px solid red;
        border-left: 0.1px solid #f5f5f5;
    }


    #datatable-1 tbody tr td {
        padding: .55rem;
        text-align: center;
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1200px !important;
        }
    }

    body.theme-light .disabled-div {
        background-color: #eee !important;
        pointer-events: none;
        border-radius: 5px;
    }

    body.theme-dark .disabled-div {
        background-color: #757575 !important;
        pointer-events: none;
        border-radius: 5px;
    }

    .font-size-initial {
        font-weight: 900 !important;
        font-size: large;
    }

    body.theme-light .disabled-div,
    body.theme-light .disabled-div .btn-group-toggle>.btn:not(.active),
    body.theme-light .disabled-div .bootstrap-select .bs-btn-default {
        background-color: #eee !important;
        pointer-events: none;
    }

    body.theme-dark .disabled-div,
    body.theme-dark .disabled-div .btn-group-toggle>.btn:not(.active),
    body.theme-dark .disabled-div .bootstrap-select .bs-btn-default {
        background-color: #757575 !important;
        pointer-events: none;
    }

    .calendar-time {
        display: none !important;
    }

    .loading {
        position: relative;
        cursor: not-allowed !important;
        pointer-events: none !important;
        opacity: 0.5;
    }

    .loading:before {
        content: "";
        display: block;
        position: absolute;
        top: 20%;
        left: 30%;
        width: 20px;
        height: 20px;
        border: 2px solid #fff;
        border-top-color: #999;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>


<?php

if ($_GET['r'] == 'man') {
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <div class="row w-100 p-0 m-0">
                                <div class="col-md-3 d-flex align-items-center p-0 mb-2">
                                    <h3 class="portlet-title">Sold Logs</h3>
                                </div>

                                <div class="col-md-6 d-flex justify-content-center align-items-center p-0 mb-2">
                                    <div class="row d-flex justify-content-center flex-row p-0 m-0 font-sm-mobile">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-flat-primary">
                                                        <input type="radio" name="radio-date" id="notDoneBtn" value="notDone">
                                                        Thank You Card
                                                        <span class="badge badge-lg p-1" id="notDoneCount"></span>
                                                    </label>
                                                    <label class="btn btn-flat-primary">
                                                        <input type="radio" name="radio-date" id="todayBtn" value="today">
                                                        Today
                                                        <span class="badge badge-lg p-1" id="todayCount"></span>
                                                    </label>
                                                    <label class="btn btn-flat-primary">
                                                        <input type="radio" name="radio-date" value="yesterday">
                                                        Yesterday
                                                        <span class="badge badge-lg p-1" id="yesterdayCount"></span>
                                                    </label>
                                                    <label class="btn btn-flat-primary">
                                                        <input type="radio" name="radio-date" id="currentMonth" value="currentMonth">
                                                        Current Month
                                                        <span class="badge badge-lg p-1" id="currentMonthCount"></span>
                                                    </label>
                                                    <label class="btn btn-flat-primary">
                                                        <input type="radio" name="radio-date" value="all" id="modAll">
                                                        Show All
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3 p-1 d-flex justify-content-end align-items-center">
                                    <div class="justify-content-right align-items-center">
                                        <div class="row d-flex justify-content-center flex-row p-0 mb-2 w-100">
                                            <div class="row w-100">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Select Date" name="datefilter" value="" autocomplete="off" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-inline-flex">
                                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="soldLogs">
                                <!-- <button type="button" class="btn btn-link p-0 d-none" id="codp_warn" style="width: fit-content;position: absolute;top: 10%;right: 15%;" data-toggle="popover" data-trigger="focus" title="Cars To Dealer – Pending" data-content="This Stock is also visible on Cars To Dealer – Pending">
                                    <div class="widget19-icon text-danger bg-transparent">
                                        <i data-feather="alert-circle"></i>
                                    </div>
                                </button> -->

                                <div class="form-row m-2 customFilters1 d-none">
                                    <div class="col-md-12 p-2 d-flex justify-content-between">
                                        <div class="dtsp-title">Filters Active</div>
                                        <button type="button" id="filterDataTable" class="btn btn-flat-primary btn-wider">Filter Data</button>
                                    </div>
                                    <div class="col-12 row">
                                        <div class="col p-1">
                                            <select class="form-control filterTags" id="salesConsultantFilter" multiple="multiple">
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
                                                <optgroup label="Vehicle">
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col p-1">
                                            <select class="form-control filterTags" id="typeFilter" multiple="multiple">
                                                <optgroup label="Stock Type">
                                                    <option value="NEW" selected>NEW</option>
                                                    <option value="USED" selected>USED</option>
                                                    <option value="OTHER">OTHER</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <table id="datatable-1" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; padding: 10px;min-width: 70px;">Sold Date</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Sales Consultant</th>
                                            <th>Stock #</th>
                                            <th>Vehicle</th>
                                            <th style="text-align: center; padding: 10px;">Age</th>
                                            <th style="text-align: center; padding: 10px;">Certified</th>
                                            <th style="text-align: center; padding: 10px;">Lot</th>
                                            <th style="text-align: center; padding: 10px;">Gross</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th style="text-align: center; padding: 10px;">Balance</th>
                                            <th style="text-align: center; padding: 10px;">Finance Manager</th>
                                            <th style="text-align: center; padding: 10px;">Salesperson Status</th>
                                            <th>Action</th>
                                            <th>Stock Type</th>
                                            <th>count</th>
                                            <th>ID</th>
                                            <th>Vin</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="notDone d-none">
                                <table id="datatable-2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th style="min-width: 70px;">Sold Date</th>
                                            <th>Reconcile Date</th>
                                            <th>Customer Name</th>
                                            <th>Sales Consultant</th>
                                            <th>Stock #</th>
                                            <th>Vehicle</th>
                                            <th style="text-align: center; padding: 10px;">Age</th>
                                            <th style="text-align: center; padding: 10px;">Certified</th>
                                            <th style="text-align: center; padding: 10px;">Lot</th>
                                            <th style="text-align: center; padding: 10px;">Gross</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th style="text-align: center; padding: 10px;">Balance</th>
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


    <div class="modal fade" id="addNewScheduleModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Schedule Appointment</h5>
                    <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="addNewScheduleForm" autocomplete="off" method="post" action="../php_action/createSchedule.php">
                    <!-- <input type="hidden" name="scheduleId" id="scheduleId">
                    <input type="hidden" name="ecallenderId" id="ecallenderId"> -->
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">
                            <div class="w-100 appointment_div p-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row align-items-baseline">
                                            <label for="customerName" class="col-sm-3 text-sm-center col-form-label">Customer Name</label>
                                            <div class="form-group col-sm-9">
                                                <input type="text" class="form-control" name="customerName" id="customerName" autocomplete="off" autofill="off" readonly />
                                            </div>
                                        </div>
                                        <div class="row align-items-baseline">
                                            <label for="sale_id" class="col-sm-3 text-sm-center col-form-label">Stock No - Vin</label>
                                            <div class="form-group col-sm-9">
                                                <input type="hidden" class="form-control" name="sale_id" id="sale_id" autocomplete="off" autofill="off" />
                                                <input type="hidden" class="form-control" name="allready_created" id="allready_created" />
                                                <input type="hidden" class="form-control" name="stockno" id="stockno" />
                                                <input type="text" class="form-control" name="stocknoDisplay" id="stocknoDisplay" readonly />
                                                <!-- <select class="form-control selectpicker w-auto required" id="esale_id" onchange="echangeStockDetails(this)" name="esale_id" data-live-search="true" data-size="4">
                                                <option value="" selected disabled>Select</option>
                                                <optgroup class="stockno"></optgroup>
                                            </select> -->
                                            </div>
                                            <label for="vechicle" class="col-sm-3 text-sm-center col-form-label"> Vehicle</label>
                                            <div class="form-group col-sm-9">
                                                <input type="text" class="form-control" name="vechicle" id="vechicle" autocomplete="off" autofill="off" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="submittedBy" class="col-form-label">Submitted By</label>
                                            <input type="text" class="form-control text-center" name="submittedBy" id="submittedBy" readonly autocomplete="off" autofill="off" />
                                            <input type="hidden" class="form-control text-center" name="submittedByRole" id="submittedByRole" readonly autocomplete="off" autofill="off" />
                                            <input type="hidden" class="form-control text-center" name="submittedById" id="submittedById" readonly autocomplete="off" autofill="off" />
                                        </div>

                                        <div class="form-group manager_override_div v-none" style="border-radius:5px;">
                                            <input type="hidden" name="has_appointment" id="has_appointment" value="null" />
                                            <div class="custom-control custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="overrideBy" id="overrideBy">
                                                <label class="custom-control-label" for="overrideBy">Manager Override</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control text-center" name="overrideByName" id="overrideByName" readonly autocomplete="off" autofill="off" />
                                            <input type="hidden" class="form-control text-center" name="overrideById" id="overrideById" value="" readonly autocomplete="off" autofill="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <label for="scheduleDate" class="col-sm-3 text-sm-center col-form-label">Appointment Date</label>
                                            <div class="col-sm-4">
                                                <div class="form-group input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control scheduleDate handleDateTime" data-type="add" name="scheduleDate" id="scheduleDate" />
                                                </div>
                                            </div>

                                            <label for="scheduleTime" class="col-sm-1 text-md-center col-form-label">Time</label>
                                            <div class="col-sm-4">
                                                <div class="form-group input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control scheduleTime handleDateTime" data-type="add" name="scheduleTime" id="scheduleTime" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="row align-items-baseline">
                                            <label for="coordinator" class="col-sm-3 col-form-label">Coordinator</label>
                                            <div class="form-group col-sm-9">
                                                <select class="form-control selectpicker w-auto required" id="coordinator" name="coordinator" data-live-search="true" data-size="4">
                                                    <option value="" selected disabled>Select</option>
                                                    <optgroup class="coordinator" id="coordinatorList"></optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row d-flex flex-row align-items-center">
                                    <label for="delivery" class="col-sm-2 text-sm-center col-form-label">Delivery</label>
                                    <div class="form-group col-sm-10">
                                        <input type="hidden" name="prev_delivery" id="prev_delivery" value="">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="delivery">
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="delivery" value="spotDelivery" id="spotDelivery">
                                                Spot Delivery
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="delivery" value="appointmentDelivery" id="appointmentDelivery">
                                                Appointment Delivery
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="delivery" value="outOfDealershipDelivery" id="outOfDealershipDelivery">
                                                Out of Dealership Delivery
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-baseline">
                                    <label for="additionalServices" class="col-sm-2 text-sm-center col-form-label">Additional Services</label>
                                    <div class="form-group col-sm-10">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="additionalServices">
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="checkbox" name="additionalServices[]" value="vinCheck" id="vinCheck">
                                                Vin Check
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="checkbox" name="additionalServices[]" value="maInspection" id="maInspection">
                                                MA Inspection
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="checkbox" name="additionalServices[]" value="riInspection" id="riInspection">
                                                RI Inspection
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="checkbox" name="additionalServices[]" value="paperworkSigned" id="paperworkSigned">
                                                Get Paperwork Signed
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="checkbox" name="additionalServices[]" value="other" id="other">
                                                Other (See Notes)
                                            </label>
                                        </div>
                                    </div>

                                    <label for="scheduleNotes" class="col-sm-2 text-sm-center col-form-label">Notes</label>
                                    <div class="form-group col-sm-10">
                                        <textarea class="form-control autosize" name="scheduleNotes" id="scheduleNotes" placeholder="Notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row delivery_coordinator align-items-baseline" id="delivery_coordinator">

                                <div class="col-md-12 mb-3 mt-3">
                                    <p class="h5 text-center">
                                        Coordinator
                                    </p>
                                </div>

                                <label for="confirmed" class="col-sm-2 text-sm-right col-form-label">Confirmed</label>
                                <div class="col-md-4">
                                    <div class="btn-group btn-group-toggle clear-selection-btn-group w-100" data-targetElement="complete" data-toggle="buttons" id="confirmed">
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="confirmed" value="ok" id="conok">
                                            Yes
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="confirmed" value="showVerified" id="conshowVerified">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <label for="complete" class="col-sm-2 text-sm-right col-form-label">Complete</label>
                                <div class="col-md-4">
                                    <div class="btn-group btn-group-toggle disabled-div clear-selection-btn-group w-100" data-toggle="buttons" id="complete">
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="complete" value="ok" id="comok">
                                            Yes
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="complete" value="showVerified" id="comshowVerified">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-bordered">
                        <button type="submit" id="SubmitBtn" class="btn btn-primary mr-2">Submit</button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editSaleModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-dialog-scrollable">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Edit Sale</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="editSaleForm" autocomplete="off" method="post" action="../php_action/editSale.php" enctype="multipart/form-data">
                    <input type="hidden" name="saleId" id="saleId" />
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="eshowResult d-none">
                            <div class="form-row">
                                <div class="col-md-8">
                                    <div class="row flex-xs-column-reverse">
                                        <label for="inputEmail4" class="col-md-2 col-form-label text-md-center">Date:</label>
                                        <div class="col-md-3 <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="saleDate" onchange="changeRules()" placeholder="Select date" id="saleDate">
                                            </div>
                                        </div>
                                        <label for="inputPassword4" class="col-md-1 col-form-label text-md-right">Status</label>
                                        <div class="col-md-6 <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                            <div class="form-group text-center">
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-flat-primary d-flex align-items-center responsive-content">
                                                        <input type="radio" name="status" value="pending" id="pending">
                                                        <i class="fa fa-clock pr-1"></i> Pending
                                                    </label>
                                                    <label class="btn btn-flat-success d-flex align-items-center responsive-content">
                                                        <input type="radio" name="status" value="delivered" id="delivered">
                                                        <i class="fa fa-check pr-1"></i> Delivered
                                                    </label>

                                                    <label class="btn btn-flat-danger d-flex align-items-center responsive-content">
                                                        <input type="radio" name="status" value="cancelled" id="cancelled">
                                                        <i class="fa fa-times pr-1"></i> Cancelled
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <div class="col-sm-3">
                                            <div class="custom-control custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" onchange="changeReconcile()" id="reconcileCheckbox">
                                                <!-- <label class="custom-control-label" for="reconcileCheckbox"></label> -->
                                                <label for="reconcileCheckbox" class="custom-control-label text-md-right">Reconcile</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="reconcileDate" placeholder="Select date" id="reconcileDate" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row flex-xs-column-reverse">
                                <div class="col-md-6">
                                    <div class="row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <label class="col-md-3 col-form-label" for="stockId">Stock No.</label>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="hidden" name="selectedStockType" id="selectedStockType" />
                                                <select class="selectpicker required" onchange="changeStockDetails(this)" name="stockId" id="stockId" data-live-search="true" data-size="4">
                                                    <option value="0" selected disabled>Stock No:</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <label class="col-md-3 col-form-label" for="salesConsultant">Sales Consultant:</label>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <select class="selectpicker" name="salesPerson" id="salesPerson" data-live-search="true" data-size="4">
                                                    <option value="0" selected disabled>Sales Consultant</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-baseline <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <label class="col-md-3 col-form-label" for="financeManager">Finance Manager:</label>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <select class="selectpicker" name="financeManager" id="financeManager" data-live-search="true" data-size="4">
                                                    <option value="0" selected disabled>Finance Manager</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <label class="col-md-3 col-form-label" for="dealType">Deal Type:</label>
                                        <div class="col-md-9">
                                            <div class="form-group d-flex justify-content-around">

                                                <div class="custom-control custom-control-lg custom-radio">
                                                    <input type="radio" id="cash" name="dealType" value="cash" class="custom-control-input">
                                                    <label class="custom-control-label" for="cash">Cash</label>
                                                </div>

                                                <div class="custom-control custom-control-lg custom-radio">
                                                    <input type="radio" id="lease" name="dealType" value="lease" class="custom-control-input">
                                                    <label class="custom-control-label" for="lease">Lease</label>
                                                </div>

                                                <div class="custom-control custom-control-lg custom-radio">
                                                    <input type="radio" id="finance" name="dealType" value="finance" class="custom-control-input">
                                                    <label class="custom-control-label" for="finance">Finance</label>
                                                </div>

                                                <div class="custom-control custom-control-lg custom-radio">
                                                    <input type="radio" id="other" name="dealType" value="other" class="custom-control-input">
                                                    <label class="custom-control-label" for="other">Other</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <label class="col-md-3 col-form-label" for="dealNote">Deal Notes</label>
                                        <div class="col-md-9 form-group">
                                            <textarea class="form-control autosize" name="dealNote" id="dealNote" placeholder="Deal Notes..."></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6" id="detailsSection">

                                    <div class="form-group row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <label class="col-md-2 offset-md-1 col-form-label text-md-right" for="submittedBy">Submitted By</label>
                                        <div class="col-md-8 d-flex justify-content-around">
                                            <input type="text" class="form-control text-center" id="submittedByName" placeholder="Submitte By" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <div class="col-md-10 offset-md-1 saleDetailsDiv" id="saleDetailsDiv">
                                            <button type="button" class="btn btn-link p-0 d-none" id="codp_warn" style="width: fit-content;position: absolute;top: 10%;right: 15%;" data-toggle="popover" data-trigger="focus" title="Cars To Dealer – Pending" data-content="This Stock is also visible on Cars To Dealer – Pending">
                                                <div class="widget19-icon text-danger bg-transparent">
                                                    <i data-feather="alert-circle"></i>
                                                </div>
                                            </button>
                                            <textarea class="form-control autosize" style="border: none;" name="selectedDetails" id="selectedDetails" readonly placeholder="Type Something..."></textarea>
                                            <div class="form-group row" id="grossDiv">
                                                <label class="col-md-2 offset-md-3 col-form-label text-md-right" for="profit">Gross</label>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">$</div>
                                                        </div>
                                                        <input type="text" class="form-control" name="profit" id="profit" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-link p-0 d-none" id="lwbn_warn" style="width: fit-content;position: absolute;bottom: 10%;right: 15%;" data-toggle="popover" data-trigger="focus" title="Lot Wizards Bills - NOT PAID" data-content="This Stock is also visible on Lot Wizards Bills - NOT PAID">
                                                <div class="widget19-icon text-danger bg-transparent">
                                                    <i data-feather="dollar-sign"></i>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                        <label class="col-md-2 offset-md-1 col-form-label text-md-right" for="iscertified">Certified</label>
                                        <div class="col-md-8 d-flex justify-content-around">
                                            <!-- <input type="text" class="form-control" id="iscertified" placeholder="yes" readonly> -->
                                            <div class="custom-control custom-control-lg custom-radio">
                                                <input type="radio" id="yes" value="on" name="iscertified" class="custom-control-input">
                                                <label class="custom-control-label" for="yes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-control-lg custom-radio">
                                                <input type="radio" id="yesOther" value="yesOther" name="iscertified" class="custom-control-input">
                                                <label class="custom-control-label" for="yesOther">Yes/Other</label>
                                            </div>
                                            <div class="custom-control custom-control-lg custom-radio">
                                                <input type="radio" id="no" value="off" name="iscertified" class="custom-control-input">
                                                <label class="custom-control-label" for="no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-10 offset-md-1">
                                            <div class="form-group <?php echo ($salesConsultantID == $_SESSION['userRole'] || 'Admin' == $_SESSION['userRole'] || $branchAdmin == $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                                <label class="col-form-label" for="consultantNote">Consultant Notes</label>
                                                <textarea class="form-control autosize" name="consultantNote" id="consultantNote" placeholder="Consultant Notes..."></textarea>
                                            </div>
                                            <div class="custom-control custom-control-lg custom-checkbox mb-3 mt-3 <?php echo ($salesManagerID == $_SESSION['userRole'] || $generalManagerID == $_SESSION['userRole'] || 'Admin' == $_SESSION['userRole'] || $branchAdmin == $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                                <input type="checkbox" name="thankyouCard" class="custom-control-input" id="thankyouCard">
                                                <label class="custom-control-label" for="thankyouCard">Thank you card</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <h5 class="my-3">Customer account</h5>
                            <div class="form-row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                <div class=<?php echo hasAccess("sale", "Details") !== 'false' ? "col-md-10" : "col-md-12" ?>>
                                    <div class="form-group input-group d-flex flex-md-row flex-sm-column input-group-mobile">
                                        <input type="text" name="fname" id="fname" class="form-control w-auto " placeholder="First name">
                                        <input type="text" name="mname" id="mname" class="form-control w-auto " placeholder="Middle name">
                                        <input type="text" name="lname" id="lname" class="form-control w-auto " placeholder="Last name">
                                        <!-- <select class="form-control selectpicker w-auto" onchange="changeSalesPersonTodo()" name="state" id="state" data-live-search="true" data-size="4"> -->
                                        <select class="form-control selectpicker w-auto" onchange="changeRules(true)" name="state" id="state" data-live-search="true" data-size="4">
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
                                    </div>
                                </div>
                                <?php
                                if (hasAccess("sale", "Details") !== 'false') {
                                ?>
                                    <a href="javascript:;" class="form-group col-md-2 text-center w-100 btn btn-outline-info input-group-button-mobile" onclick="toggleInfo('customerDetailBody')">
                                        More Information <i class="fa fa-angle-down"></i>
                                    </a>
                                <?php
                                }
                                ?>

                            </div>

                            <div class="mt-2 customerDetailBody border rounded hidden <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>" id="pbody" style="background-color: rgba(0,188,212,.1);">
                                <div class="form-row p-3">
                                    <label for="address1" class="col-md-1 col-form-label text-center">Address 1*</label>
                                    <div class="form-group col-md-5">
                                        <div class="input-group-icon">
                                            <input type="text" class="form-control" name="address1" id="address1" placeholder="Your address here">
                                            <div class="input-group-append"><i class="fa fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="address2" class="col-md-1 col-form-label text-center">Address 2</label>
                                    <div class="form-group col-md-5">
                                        <div class="input-group-icon">
                                            <input type="text" class="form-control" name="address2" id="address2" placeholder="Your address here">
                                            <div class="input-group-append"><i class="fa fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row pb-0 p-3">

                                    <div class="col-md-4 form-group">
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City*">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input type="text" class="form-control" id="country" name="country" placeholder="Country*">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="Zip Code*">
                                    </div>
                                </div>
                                <div class="form-row p-3 pt-0">

                                    <div class="col-md-4 form-group">
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile*">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="text" class="form-control" id="altContact" name="altContact" placeholder="Alternate Contact">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email*">
                                    </div>

                                </div>


                            </div>


                            <h5 class="my-3">Co-Buyer</h5>
                            <div class="form-row <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                <div class="<?php echo hasAccess("sale", "Details") !== 'false' ? "col-md-10" : "col-md-12" ?>">
                                    <div class="form-group input-group d-flex flex-md-row flex-sm-column input-group-mobile">
                                        <input type="text" name="cbfname" id="cbfname" class="form-control w-auto " placeholder="First name">
                                        <input type="text" name="cbmname" id="cbmname" class="form-control w-auto " placeholder="Middle name">
                                        <input type="text" name="cblname" id="cblname" class="form-control w-auto " placeholder="Last name">
                                        <select class="form-control selectpicker w-auto" onchange="changeSalesPersonTodo()" name="cbstate" id="cbstate" data-live-search="true" data-size="4">
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
                                    </div>
                                </div>

                                <a href="javascript:;" class="form-group col-md-2 text-center w-100 btn btn-outline-info input-group-button-mobile <?php echo hasAccess("sale", "Details") !== 'false' ? "d-block" : "d-none" ?>" onclick="toggleInfo('coBuyer')">
                                    Add Co-Buyer <i class="fa fa-angle-down"></i>
                                </a>

                            </div>
                            <div class="mt-2 coBuyer border rounded hidden <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>" id="pbody" style="background-color: rgba(0,188,212,.1);">
                                <div class=<?php echo hasAccess("sale", "Details") !== 'false' ? "d-block" : "d-none" ?>>
                                    <div class="form-row p-3">
                                        <label for="cbAddress1" class="col-md-1 col-form-label text-center">Address 1*</label>
                                        <div class="form-group col-md-5">
                                            <div class="input-group-icon">
                                                <input type="text" class="form-control" name="cbAddress1" id="cbAddress1" placeholder="Your address here">
                                                <div class="input-group-append"><i class="fa fa-map-marker-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="cbAddress2" class="col-md-1 col-form-label text-center">Address 2</label>
                                        <div class="form-group col-md-5">
                                            <div class="input-group-icon">
                                                <input type="text" class="form-control" name="cbAddress2" id="cbAddress2" placeholder="Your address here">
                                                <div class="input-group-append"><i class="fa fa-map-marker-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row pb-0 p-3">

                                        <div class="col-md-4 form-group">
                                            <input type="text" class="form-control" id="cbCity" name="cbCity" placeholder="City*">
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <input type="text" class="form-control" id="cbCountry" name="cbCountry" placeholder="Country*">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <input type="text" class="form-control" id="cbZipCode" name="cbZipCode" placeholder="Zip Code*">
                                        </div>
                                    </div>
                                    <div class="form-row p-3 pt-0">

                                        <div class="col-md-4 form-group">
                                            <input type="text" class="form-control" id="cbMobile" name="cbMobile" placeholder="Mobile*">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <input type="text" class="form-control" id="cbAltContact" name="cbAltContact" placeholder="Alternate Contact">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <input type="text" class="form-control" id="cbEmail" name="cbEmail" placeholder="Email*">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <h5 class="my-4 pl-2 <?php echo ($salesConsultantID != $_SESSION['userRole']) ? "d-flex" : "d-none"; ?> justify-content-between align-items-center border rounded text-responsive">Incentives
                                <a href="javascript:;" class="col-md-2 text-center w-100 btn btn-info ml-2 align-item-streach" onclick="toggleInfo('loadIncentives')">
                                    Load Incentives <i class="fa fa-angle-down"></i>
                                </a>
                            </h5>

                            <!-- <div class="mt-3 loadIncentives border rounded <?php echo ($salesConsultantID != $_SESSION['userRole']) ? "hidden" : ""; ?>" id="loadIncentivesDiv" style="background-color: rgba(0,188,212,.1);"> -->
                            <div class="mt-3 loadIncentives border rounded hidden" id="loadIncentivesDiv" style="background-color: rgba(0,188,212,.1);">
                                <div class="form-row p-3">
                                    <label for="college" class="col-md-1 col-form-label text-md-center">College
                                        <span class="badge-label-primary" id="college_v"></span>
                                    </label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" data-live-search="true" id="college" name="college" data-size="5" <?php echo $_editIncentives; ?>>
                                            <optgroup>
                                                <option>No</option>
                                                <option>Yes</option>
                                            </optgroup>
                                            <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                            </optgroup>
                                        </select>
                                    </div>
                                    <label for="military" class="col-md-1 col-form-label text-md-center">Military
                                        <span class="badge-label-primary" id="military_v"></span>
                                    </label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" data-live-search="true" id="military" name="military" data-size="5" <?php echo $_editIncentives; ?>>
                                            <optgroup>
                                                <option>No</option>
                                                <option>Yes</option>
                                            </optgroup>
                                            <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                            </optgroup>
                                        </select>
                                    </div>
                                    <label for="loyalty" class="col-md-1 col-form-label text-md-center">Loyalty
                                        <span class="badge-label-primary" id="loyalty_v"></span>
                                    </label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" data-live-search="true" id="loyalty" name="loyalty" data-size="5" <?php echo $_editIncentives; ?>>
                                            <optgroup>
                                                <option>No</option>
                                                <option>Yes</option>
                                            </optgroup>
                                            <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                            </optgroup>
                                        </select>
                                    </div>
                                    <label for="conquest" class="col-md-1 col-form-label text-md-center">Conquest
                                        <span class="badge-label-primary" id="conquest_v"></span>
                                    </label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" data-live-search="true" id="conquest" name="conquest" data-size="5" <?php echo $_editIncentives; ?>>
                                            <optgroup>
                                                <option>No</option>
                                                <option>Yes</option>
                                            </optgroup>
                                            <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row pb-0 p-3">
                                    <label for="leaseLoyalty" class="col-md-1 col-form-label text-md-center">Lease Loyalty
                                        <span class="badge-label-primary" id="leaseLoyalty_v"></span>
                                    </label>
                                    <div class="col-md-3">
                                        <select class="selectpicker" data-live-search="true" id="leaseLoyalty" name="leaseLoyalty" data-size="5" <?php echo $_editIncentives; ?>>
                                            <optgroup>
                                                <option>No</option>
                                                <option>Yes</option>
                                            </optgroup>
                                            <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                            </optgroup>
                                        </select>
                                    </div>
                                    <label for="misc1" class="col-md-1 col-form-label text-md-center">Right to Repair
                                        <span class="badge-label-primary" id="misc1_v"></span>
                                    </label>
                                    <div class="col-md-3">
                                        <select class="selectpicker" data-live-search="true" id="misc1" name="misc1" data-size="5" <?php echo $_editIncentives; ?>>
                                            <optgroup>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </optgroup>
                                            <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                            </optgroup>
                                        </select>
                                    </div>
                                    <label for="misc2" class="col-md-1 col-form-label text-md-center">Closing Cash
                                        <span class="badge-label-primary" id="misc2_v"></span>
                                    </label>
                                    <div class="col-md-3">
                                        <select class="selectpicker" data-live-search="true" id="misc2" name="misc2" data-size="5" <?php echo $_editIncentives; ?>>
                                            <optgroup>
                                                <option>No</option>
                                                <option>Yes</option>
                                            </optgroup>
                                            <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                            </optgroup>
                                        </select>
                                    </div>

                                </div>

                            </div>

                            <h5 class="my-4 pl-2 d-flex justify-content-between align-items-center border rounded text-responsive">Sales Person Todos <a href="javascript:;" class="col-md-2 text-center w-100 btn btn-info ml-2 align-item-streach" onclick="toggleInfo('loadSalesPersonTodo')">
                                    Sales Person Todo's <i class="fa fa-angle-down"></i>
                                </a>
                            </h5>

                            <div class="mt-3 mb-3 loadSalesPersonTodo border rounded <?php echo ($salesConsultantID != $_SESSION['userRole']) ? "hidden" : ""; ?> " id="pbody" style="background-color: rgba(0,188,212,.1);">

                                <div class="form-row p-3">

                                    <label for="vincheck" class="col-md-1 col-form-label text-md-center">Vin Check</label>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select onchange="chnageStyle(this)" title="&#160;" name="vincheck" id="vincheck" class="selectpicker" data-style="btn-outline-success" <?php echo $_editTodo; ?>>
                                                <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                <option value="checkTitle">Check Title</option>
                                                <option value="need">Need</option>
                                                <option value="notNeed">Doesn't Need</option>
                                                <option value="n/a">N/A</option>
                                                <option value="onHold">On Hold</option>
                                                <option value="done">Done</option>
                                            </select>
                                        </div>
                                    </div>
                                    <label for="insurance" class="col-md-1 col-form-label text-md-center">Insurance</label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="insurance" name="insurance" data-style="btn-outline-success" <?php echo $_editTodo; ?>>
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="need">Need</option>
                                            <option value="inHouse">In House</option>
                                            <option value="n/a">N/A</option>
                                        </select>
                                    </div>
                                    <label for="tradeTitle" class="col-md-1 col-form-label text-md-center">Trade Title</label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="tradeTitle" name="tradeTitle" data-style="btn-outline-success" <?php echo $_editTodo; ?>>
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="need">Need</option>
                                            <option value="payoff">Payoff</option>
                                            <option value="noTrade">No Trade</option>
                                            <option value="inHouse">In House</option>
                                        </select>
                                    </div>
                                    <label for="registration" class="col-md-1 col-form-label text-md-center">Registration</label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="registration" name="registration" data-style="btn-outline-success" <?php echo $_editTodo; ?>>
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="pending">Pending</option>
                                            <option value="done">Done</option>
                                            <option value="customerHas">Customer Has</option>
                                            <option value="mailed">Mailed</option>
                                            <option value="n/a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row pb-0 p-3">

                                    <label for="inspection" class="col-md-1 col-form-label text-md-center">Inspection</label>
                                    <div class="col-md-3">
                                        <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="inspection" name="inspection" data-style="btn-outline-success" <?php echo $_editTodo; ?>>
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="need">Need</option>
                                            <option value="notNeed">Doesn't Need</option>
                                            <option value="done">Done</option>
                                            <option value="n/a">N/A</option>
                                        </select>
                                    </div>
                                    <label for="salePStatus" class="col-md-1 col-form-label text-md-center">Salesperson Status</label>
                                    <div class="col-md-3">
                                        <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="salePStatus" name="salePStatus" data-style="btn-outline-success" <?php echo $_editTodo; ?>>
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>

                                            <option value="dealWritten">Deal Written</option>
                                            <option value="gmdSubmit">GMD Submit</option>
                                            <option value="contracted">Contracted</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="delivered">Delivered</option>

                                        </select>
                                    </div>
                                    <label for="paid" class="col-md-1 col-form-label text-md-center">Paid</label>
                                    <div class="col-md-3">
                                        <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="paid" name="paid" data-style="btn-outline-success" <?php echo $_editTodo; ?>>
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer modal-footer-bordered">
                                <button type="submit" class="btn btn-success mr-2">Update Sale</button>
                                <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php
}
?>

<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/soldLogs.js"></script>