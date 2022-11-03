<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if ($_GET['r'] == 'man') {
    // if ($salesConsultantID != $_SESSION['userRole']) {
    // if (hasAccess("sale", "Edit") === 'false' && hasAccess("sale", "Remove") === 'false') {
    if (hasAccess("sale", "View") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
        // echo "Dont Allow";
    }
    // }
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'edit') {
    if (hasAccess("sale", "Edit") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>edit</div>";
} // /else manage order


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
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1000px !important;
        }
    }

    body.theme-light .disabled-div {
        background-color: #eee !important;
        pointer-events: none;
    }

    body.theme-dark .disabled-div {
        background-color: #757575 !important;
        pointer-events: none;
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
</style>


<?php

if ($_GET['r'] == 'man') {

    if ($salesConsultantID != $_SESSION['userRole']) {
        echo '<input type="hidden" name="isConsultant" id="isConsultant" value="false" />';
    } else {
        echo '<input type="hidden" name="isConsultant" id="isConsultant" value="true" />';
    }
    $userRole = $_SESSION['userRole'];
    echo '<input type="hidden" name="loggedInUserRole" id="loggedInUserRole" value="' . $userRole . '" />';
    echo '<input type="hidden" name="currentUser" id="currentUser" value="' . $_SESSION['userName'] . '">';
    echo '<input type="hidden" name="currentUserId" id="currentUserId" value="' . $_SESSION['userId'] . '">';

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
                                    <div class="row d-flex justify-content-center flex-row p-0 m-0">
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
                                                        <span class="badge badge-lg p-1" id="AllCount"></span>
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
                                                    <input type="text" class="form-control" placeholder="Select Date" name="datefilter" value="" />
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
                                <table id="datatable-1" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sold Date</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Sales Consultant</th>
                                            <th>Stock #</th>
                                            <th>Vehicle</th>
                                            <th>Age</th>
                                            <th>Certified</th>
                                            <th>Lot</th>
                                            <th>Gross</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th>Balance</th>
                                            <th>Consultant Notes</th>
                                            <th>Salesperson Status</th>
                                            <th>Action</th>
                                            <th>Stock Type</th>
                                            <th>count</th>
                                            <th>ID</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="notDone d-none">
                                <table id="datatable-2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Sold Date</th>
                                            <th>Reconcile Date</th>
                                            <th>Customer Name</th>
                                            <th>Sales Consultant</th>
                                            <th>Stock #</th>
                                            <th>Vehicle</th>
                                            <th>Age</th>
                                            <th>Certified</th>
                                            <th>Lot</th>
                                            <th>Gross</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th>Balance</th>
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
    <div class="modal fade" id="showDetails">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-dialog-scrollable">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Sale Details</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="updateNotesForm" autocomplete="off" method="post" action="../php_action/updateSaleNotes.php" enctype="multipart/form-data">
                    <input type="hidden" name="sale_id" id="sale_id" />
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <div class="row">
                                        <label for="inputEmail4" class="col-sm-2 col-form-label text-md-center">Date:</label>
                                        <div class="col-sm-10">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="saleDate" placeholder="Select date" id="saleDate" disabled>
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
                                        <label for="inputEmail4" class="col-sm-4 col-form-label">Reconcile</label>
                                        <div class="col-sm-8">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="reconcileDate" placeholder="Select date" id="reconcileDate" disabled="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3 mb-3">
                                    <div class="row">
                                        <label for="inputPassword4" class="col-sm-5 col-form-label">Submitted By</label>
                                        <div class="col-sm-7 m-auto">
                                            <div class="form-group text-center">
                                                <input type="text" class="form-control" name="submittedBy" id="submittedBy" disabled="">
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="form-row">

                                <div class="col-md-6 mb-3">
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
                                            <label class="col-form-label" for="financeManager">Finance Manager:</label>
                                            <input type="text" class="form-control" id="financeManager" disabled>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="col-form-label" for="dealType">Deal Type:</label>
                                            <input type="text" class="form-control" id="dealType" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="dealNote">Deal Notes</label>
                                        <textarea class="form-control autosize" name="dealNote" id="dealNote" readonly placeholder="Deal Notes..."></textarea>
                                    </div>
                                </div>


                                <div class="col-md-6 mb-3">
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
                            <?php
                            if (hasAccess("sale", "Details") !== 'false') {
                            ?>
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
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="address1">Address 1</label>
                                        <input type="text" class="form-control" id="address1" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="address2">Address 2</label>
                                        <input type="text" class="form-control" id="address2" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="country">Country</label>
                                        <input type="text" class="form-control" id="country" disabled>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="zipCode">Zip Code</label>
                                        <input type="text" class="form-control" id="zipCode" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" class="form-control" id="mobile" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="altContact">Contact #</label>
                                        <input type="text" class="form-control" id="altContact" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" disabled>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <h4 class="h4">Co-Buyer Details</h4>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="cbAddress1">Address 1</label>
                                        <input type="text" class="form-control" id="cbAddress1" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cbAddress2">Address 2</label>
                                        <input type="text" class="form-control" id="cbAddress2" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cbCity">City</label>
                                        <input type="text" class="form-control" id="cbCity" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cbCountry">Country</label>
                                        <input type="text" class="form-control" id="cbCountry" disabled>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="cbZipCode">Zip Code</label>
                                        <input type="text" class="form-control" id="cbZipCode" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cbMobile">Mobile</label>
                                        <input type="text" class="form-control" id="cbMobile" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cbAltContact">Contact #</label>
                                        <input type="text" class="form-control" id="cbAltContact" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cbEmail">Email</label>
                                        <input type="text" class="form-control" id="cbEmail" disabled>
                                    </div>
                                </div>
                            <?php
                            }
                            if ($salesConsultantID == $_SESSION['userRole'] || $salesManagerID == $_SESSION['userRole'] || $generalManagerID == $_SESSION['userRole']) {
                            ?>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group <?php echo ($salesConsultantID == $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                            <label class="col-form-label" for="consultantNote">Consultant Notes</label>
                                            <textarea class="form-control autosize" name="consultantNote" id="consultantNote" placeholder="Consultant Notes..."></textarea>
                                        </div>
                                        <div class="custom-control custom-control-lg custom-checkbox mb-3 mt-3 <?php echo ($salesConsultantID != $_SESSION['userRole']) ?: "makeDisable"; ?>">
                                            <input type="checkbox" name="thankyouCard" class="custom-control-input" id="thankyouCard">
                                            <label class="custom-control-label" for="thankyouCard">Thank you card</label>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group col-md-4">
                                        <div class="<?php //echo ($salesConsultantID == $_SESSION['userRole']) ?: "makeDisable"; 
                                                    ?>">
                                            <label class="col-form-label" for="salePStatus">SalesPerson Status</label>
                                            <select class="selectpicker" onchange="chnageStyle(this)" id="salePStatus" name="salePStatus" data-style="btn-outline-danger">
                                                <option value="dealWritten">Deal Written</option>
                                                <option value="gmdSubmit">GMD Submit</option>
                                                <option value="contracted">Contracted</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="delivered">Delivered</option>
                                            </select>
                                        </div>
                                    </div> -->
                                </div>
                                <?php
                                if (hasAccess("todo", "Edit") !== 'false' && $salesConsultantID == $_SESSION['userRole']) {
                                ?>
                                    <div class="form-row">
                                        <label for="vincheck" class="col-md-1 col-form-label">Vin Check</label>
                                        <input type="hidden" name="soldTodoId" id="soldTodoId">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select onchange="chnageStyle(this)" title="&#160;" name="vincheck" id="vincheck" class="selectpicker" data-style="btn-outline-danger">
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
                                        <label for="insurance" class="col-md-1 col-form-label">Insurance</label>
                                        <div class="col-md-2">
                                            <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="insurance" name="insurance" data-style="btn-outline-danger">
                                                <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                <option value="need">Need</option>
                                                <option value="inHouse">In House</option>
                                                <option value="n/a">N/A</option>
                                            </select>
                                        </div>
                                        <label for="tradeTitle" class="col-md-1 col-form-label">Trade Title</label>
                                        <div class="col-md-2">
                                            <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="tradeTitle" name="tradeTitle" data-style="btn-outline-danger">
                                                <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                <option value="need">Need</option>
                                                <option value="payoff">Payoff</option>
                                                <option value="noTrade">No Trade</option>
                                                <option value="inHouse">In House</option>
                                            </select>
                                        </div>
                                        <label for="registration" class="col-md-1 col-form-label">Registration</label>
                                        <div class="col-md-2">
                                            <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="registration" name="registration" data-style="btn-outline-danger">
                                                <option value="" title="&#160;" data-hidden="true" selected> </option>
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
                                            <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="inspection" name="inspection" data-style="btn-outline-danger">
                                                <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                <option value="need">Need</option>
                                                <option value="notNeed">Doesn't Need</option>
                                                <option value="done">Done</option>
                                                <option value="n/a">N/A</option>
                                            </select>
                                        </div>
                                        <label for="salePStatus" class="col-md-1 col-form-label">Salesperson Status</label>
                                        <div class="col-md-3">
                                            <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="salePStatus" name="salePStatus" data-style="btn-outline-danger">
                                                <option value="" title="&#160;" data-hidden="true" selected> </option>

                                                <option value="dealWritten">Deal Written</option>
                                                <option value="gmdSubmit">GMD Submit</option>
                                                <option value="contracted">Contracted</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="delivered">Delivered</option>

                                            </select>
                                        </div>
                                        <label for="paid" class="col-md-1 col-form-label">Paid</label>
                                        <div class="col-md-3">
                                            <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="paid" name="paid" data-style="btn-outline-danger">
                                                <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>

                                    </div>
                                <?php
                                }
                                ?>

                            <?php
                            }

                            ?>


                        </div>
                    </div>
                    <div class="modal-footer modal-footer-bordered">
                        <?php
                        if ($salesConsultantID == $_SESSION['userRole'] || $salesManagerID == $_SESSION['userRole'] || $generalManagerID == $_SESSION['userRole']) {
                            echo '<button type="submit" class="btn btn-success mr-2">Update Sale</button>';
                            echo '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=edit&i=" class="btn btn-primary mr-2 d-none" id="editBtn">Edit</a>';
                        } else {
                            echo '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=edit&i=" class="btn btn-primary mr-2" id="editBtn">Edit</a>';
                        }
                        ?>
                        <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNewSchedule">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Schedule Appointment</h5>
                    <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="editScheduleForm" autocomplete="off" method="post" action="../php_action/editSchedule.php">
                    <input type="hidden" name="scheduleId" id="scheduleId">
                    <input type="hidden" name="ecallenderId" id="ecallenderId">
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">
                            <div class="w-100 appointment_div p-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row align-items-baseline">
                                            <label for="ecustomerName" class="col-sm-3 text-sm-center col-form-label">Customer Name</label>
                                            <div class="form-group col-sm-9">
                                                <input type="text" class="form-control" name="ecustomerName" id="ecustomerName" autocomplete="off" autofill="off" disabled />
                                            </div>
                                        </div>
                                        <div class="row align-items-baseline">
                                            <label for="esale_id" class="col-sm-3 text-sm-center col-form-label">Stock No - Vin</label>
                                            <div class="form-group col-sm-9">
                                                <input type="hidden" class="form-control" name="esale_id" id="esale_id" autocomplete="off" autofill="off" />
                                                <input type="hidden" class="form-control" name="estockno" id="estockno" />
                                                <input type="text" class="form-control" name="estocknoDisplay" id="estocknoDisplay" readonly />
                                                <!-- <select class="form-control selectpicker w-auto required" id="esale_id" onchange="echangeStockDetails(this)" name="esale_id" data-live-search="true" data-size="4">
                                                <option value="" selected disabled>Select</option>
                                                <optgroup class="stockno"></optgroup>
                                            </select> -->
                                            </div>
                                            <label for="evechicle" class="col-sm-3 text-sm-center col-form-label"> Vehicle</label>
                                            <div class="form-group col-sm-9">
                                                <input type="text" class="form-control" name="evechicle" id="evechicle" autocomplete="off" autofill="off" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="esubmittedBy" class="col-form-label">Submitted By</label>
                                            <input type="text" class="form-control text-center" name="esubmittedBy" id="esubmittedBy" readonly autocomplete="off" autofill="off" />
                                            <input type="hidden" class="form-control text-center" name="esubmittedByRole" id="esubmittedByRole" readonly autocomplete="off" autofill="off" />
                                        </div>

                                        <div class="form-group manager_override_div v-none" style="border-radius:5px;">
                                            <input type="hidden" name="ehas_appointment" id="ehas_appointment" value="null" />
                                            <div class="custom-control custom-control-lg custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="eoverrideBy" id="eoverrideBy">
                                                <label class="custom-control-label" for="eoverrideBy">Manager Override</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control text-center" name="eoverrideByName" id="eoverrideByName" readonly autocomplete="off" autofill="off" />
                                            <input type="hidden" class="form-control text-center" name="eoverrideById" id="eoverrideById" value="" readonly autocomplete="off" autofill="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <label for="escheduleDate" class="col-sm-3 text-sm-center col-form-label">Appointment Date</label>
                                            <div class="col-sm-4">
                                                <div class="form-group input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control scheduleDate handleDateTime" data-type="edit" name="escheduleDate" id="escheduleDate" />
                                                </div>
                                            </div>

                                            <label for="escheduleTime" class="col-sm-1 text-md-center col-form-label">Time</label>
                                            <div class="col-sm-4">
                                                <div class="form-group input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control scheduleTime handleDateTime" data-type="edit" name="escheduleTime" id="escheduleTime" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="row align-items-baseline">
                                            <label for="ecoordinator" class="col-sm-3 col-form-label">Coordinator</label>
                                            <div class="form-group col-sm-9">
                                                <select class="form-control selectpicker w-auto required" id="ecoordinator" name="ecoordinator" data-live-search="true" data-size="4">
                                                    <option value="" selected disabled>Select</option>
                                                    <optgroup class="ecoordinator" id="ecoordinatorList"></optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row d-flex flex-row align-items-center">
                                    <label for="edelivery" class="col-sm-2 text-sm-center col-form-label">Delivery</label>
                                    <div class="form-group col-sm-10">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="edelivery">
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="edelivery" value="spotDelivery" id="espotDelivery">
                                                Spot Delivery
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="edelivery" value="appointmentDelivery" id="eappointmentDelivery">
                                                Appointment Delivery
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="edelivery" value="outOfDealershipDelivery" id="eoutOfDealershipDelivery">
                                                Out of Dealership Delivery
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-baseline">
                                    <label for="eadditionalServices" class="col-sm-2 text-sm-center col-form-label">Additional Services</label>
                                    <div class="form-group col-sm-10">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="eadditionalServices">
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="eadditionalServices" value="vinCheck" id="evinCheck">
                                                Vin Check
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="eadditionalServices" value="maInspection" id="emaInspection">
                                                MA Inspection
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="eadditionalServices" value="riInspection" id="eriInspection">
                                                RI Inspection
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="eadditionalServices" value="paperworkSigned" id="epaperworkSigned">
                                                Get Paperwork Signed
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="eadditionalServices" value="other" id="eother">
                                                Other (See Notes)
                                            </label>
                                        </div>
                                    </div>

                                    <label for="escheduleNotes" class="col-sm-2 text-sm-center col-form-label">Notes</label>
                                    <div class="form-group col-sm-10">
                                        <textarea class="form-control autosize" name="escheduleNotes" id="escheduleNotes" placeholder="Notes..."></textarea>
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

                                <label for="econfirmed" class="col-sm-2 text-sm-right col-form-label">Confirmed</label>
                                <div class="col-md-4">
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="econfirmed">
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="econfirmed" value="ok" id="conok">
                                            Yes
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="econfirmed" value="showVerified" id="conshowVerified">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <label for="ecomplete" class="col-sm-2 text-sm-right col-form-label">Complete</label>
                                <div class="col-md-4">
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="ecomplete">
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="ecomplete" value="ok" id="comok">
                                            Yes
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="ecomplete" value="showVerified" id="comshowVerified">
                                            No
                                        </label>
                                    </div>
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


<?php
} else if ($_GET['r'] == 'edit') {
?>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Edit Sale</h3>
                            <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man" class="btn btn-secondary">
                                <i class="fa fa-arrow-right"></i>
                                Go Back</a>
                        </div>
                        <div class="portlet-body">
                            <!-- <form id="editSaleForm" autocomplete="off" method="post" onsubmit="return false;" action="#"> -->
                            <form id="editSaleForm" autocomplete="off" method="post" action="../php_action/editSale.php">

                                <div class="text-center">
                                    <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                                <div class="showResult">
                                    <input type="hidden" name="saleId" id="saleId" value="<?php echo $_GET["i"]; ?>">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="inputEmail4" class="col-sm-2 col-form-label text-md-center">Date:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-group input-group">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control" name="saleDate" onchange="changeRules()" placeholder="Select date" id="saleDate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="row">
                                                <label for="inputPassword4" class="col-sm-1 offset-sm-1 col-form-label text-md-right">Status</label>
                                                <div class="col-sm-8">
                                                    <div class="form-group col-sm-6 text-center">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">

                                                            <label class="btn btn-flat-primary d-flex align-items-center">
                                                                <input type="radio" name="status" value="pending" id="pending">
                                                                <i class="fa fa-clock pr-1"></i> Pending
                                                            </label>
                                                            <label class="btn btn-flat-success d-flex align-items-center">
                                                                <input type="radio" name="status" value="delivered" id="delivered">
                                                                <i class="fa fa-check pr-1"></i> Delivered
                                                            </label>

                                                            <label class="btn btn-flat-danger d-flex align-items-center">
                                                                <input type="radio" name="status" value="cancelled" id="cancelled">
                                                                <i class="fa fa-times pr-1"></i> Cancelled
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
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
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="row">
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
                                            <div class="row">
                                                <label class="col-md-3 col-form-label" for="salesConsultant">Sales Consultant:</label>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <select class="selectpicker" name="salesPerson" id="salesPerson" data-live-search="true" data-size="4">
                                                            <option value="0" selected disabled>Sales Consultant</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-baseline">
                                                <label class="col-md-3 col-form-label" for="financeManager">Finance Manager:</label>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <select class="selectpicker" name="financeManager" id="financeManager" data-live-search="true" data-size="4">
                                                            <option value="0" selected disabled>Finance Manager</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
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
                                            <div class="row">
                                                <label class="col-md-3 col-form-label" for="dealNote">Deal Notes</label>
                                                <div class="col-md-9 form-group">
                                                    <textarea class="form-control autosize" name="dealNote" id="dealNote" placeholder="Deal Notes..."></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6" id="detailsSection">
                                            <div class="form-group row">
                                                <label class="col-md-2 offset-md-1 col-form-label text-md-right" for="submittedBy">Submitted By</label>
                                                <div class="col-md-8 d-flex justify-content-around">
                                                    <input type="text" class="form-control text-center" id="submittedBy" placeholder="Submitte By" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 offset-md-1 col-form-label text-md-right" for="iscertified">Certified</label>
                                                <div class="col-md-8 d-flex justify-content-around">
                                                    <!-- <input type="text" class="form-control" id="iscertified" placeholder="yes" readonly> -->
                                                    <div class="custom-control custom-control-lg custom-radio">
                                                        <input type="radio" id="yes" value="on" name="iscertified" class="custom-control-input">
                                                        <label class="custom-control-label" for="yes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-control-lg custom-radio">
                                                        <input type="radio" id="no" value="off" name="iscertified" class="custom-control-input">
                                                        <label class="custom-control-label" for="no">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-10 offset-sm-1 saleDetailsDiv" id="saleDetailsDiv">
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
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                    <h5 class="my-4">Customer account</h5>
                                    <div class="form-row">

                                        <div class="col-md-10">
                                            <div class="form-group input-group d-flex flex-md-row flex-sm-column">
                                                <input type="text" name="fname" id="fname" class="form-control w-auto " placeholder="First name">
                                                <input type="text" name="mname" id="mname" class="form-control w-auto " placeholder="Middle name">
                                                <input type="text" name="lname" id="lname" class="form-control w-auto " placeholder="Last name">
                                                <select class="form-control selectpicker w-auto" onchange="changeSalesPersonTodo()" name="state" id="state" data-live-search="true" data-size="4">
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

                                        <a href="javascript:;" class="form-group col-md-2 text-center w-100 btn btn-outline-info" onclick="toggleInfo('customerDetailBody')">
                                            More Information <i class="fa fa-angle-down"></i>
                                        </a>

                                    </div>

                                    <div class="mt-3 customerDetailBody border rounded hidden" id="pbody" style="background-color: rgba(0,188,212,.1);">
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

                                    <h5 class="my-4">Co-Buyer</h5>
                                    <div class="form-row">
                                        <div class="col-md-10">
                                            <div class="form-group input-group d-flex flex-md-row flex-sm-column">
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

                                        <a href="javascript:;" class="form-group col-md-2 text-center w-100 btn btn-outline-info" onclick="toggleInfo('coBuyer')">
                                            Add Co-Buyer <i class="fa fa-angle-down"></i>
                                        </a>

                                    </div>
                                    <div class="mt-3 coBuyer border rounded hidden" id="pbody" style="background-color: rgba(0,188,212,.1);">
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

                                    <h5 class="my-4 pl-2 d-flex justify-content-between align-items-center border rounded">Incentives <a href="javascript:;" class="col-md-2 text-center w-100 btn btn-info ml-2 align-item-streach" onclick="toggleInfo('loadIncentives')">
                                            Load Incentives <i class="fa fa-angle-down"></i>
                                        </a>
                                    </h5>

                                    <div class="mt-3 loadIncentives border rounded hidden" id="pbody" style="background-color: rgba(0,188,212,.1);">
                                        <div class="form-row p-3">
                                            <label for="college" class="col-md-1 col-form-label">College
                                                <span class="badge-label-primary" id="college_v"></span>
                                            </label>
                                            <div class="col-md-2">
                                                <select class="selectpicker" data-live-search="true" id="college" name="college" data-size="5">
                                                    <optgroup>
                                                        <option>No</option>
                                                        <option>Yes</option>
                                                    </optgroup>
                                                    <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                    </optgroup>
                                                </select>
                                            </div>
                                            <label for="military" class="col-md-1 col-form-label">Military
                                                <span class="badge-label-primary" id="military_v"></span>
                                            </label>
                                            <div class="col-md-2">
                                                <select class="selectpicker" data-live-search="true" id="military" name="military" data-size="5">
                                                    <optgroup>
                                                        <option>No</option>
                                                        <option>Yes</option>
                                                    </optgroup>
                                                    <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                    </optgroup>
                                                </select>
                                            </div>
                                            <label for="loyalty" class="col-md-1 col-form-label">Loyalty
                                                <span class="badge-label-primary" id="loyalty_v"></span>
                                            </label>
                                            <div class="col-md-2">
                                                <select class="selectpicker" data-live-search="true" id="loyalty" name="loyalty" data-size="5">
                                                    <optgroup>
                                                        <option>No</option>
                                                        <option>Yes</option>
                                                    </optgroup>
                                                    <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                    </optgroup>
                                                </select>
                                            </div>
                                            <label for="conquest" class="col-md-1 col-form-label">Conquest
                                                <span class="badge-label-primary" id="conquest_v"></span>
                                            </label>
                                            <div class="col-md-2">
                                                <select class="selectpicker" data-live-search="true" id="conquest" name="conquest" data-size="5">
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
                                            <label for="leaseLoyalty" class="col-md-1 col-form-label">Lease Loyalty
                                                <span class="badge-label-primary" id="leaseLoyalty_v"></span>
                                            </label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" data-live-search="true" id="leaseLoyalty" name="leaseLoyalty" data-size="5">
                                                    <optgroup>
                                                        <option>No</option>
                                                        <option>Yes</option>
                                                    </optgroup>
                                                    <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                    </optgroup>
                                                </select>
                                            </div>
                                            <label for="misc1" class="col-md-1 col-form-label">Misc 1
                                                <span class="badge-label-primary" id="misc1_v"></span>
                                            </label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" data-live-search="true" id="misc1" name="misc1" data-size="5">
                                                    <optgroup>
                                                        <option>No</option>
                                                        <option>Yes</option>
                                                    </optgroup>
                                                    <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                    </optgroup>
                                                </select>
                                            </div>
                                            <label for="misc2" class="col-md-1 col-form-label">Misc 2
                                                <span class="badge-label-primary" id="misc2_v"></span>
                                            </label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" data-live-search="true" id="misc2" name="misc2" data-size="5">
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

                                    <h5 class="my-4 pl-2 d-flex justify-content-between align-items-center border rounded">Sales Person Todos <a href="javascript:;" class="col-md-2 text-center w-100 btn btn-info ml-2 align-item-streach" onclick="toggleInfo('loadSalesPersonTodo')">
                                            Sales Person Todo's <i class="fa fa-angle-down"></i>
                                        </a>
                                    </h5>

                                    <div class="mt-3 mb-3 loadSalesPersonTodo border rounded hidden" id="pbody" style="background-color: rgba(0,188,212,.1);">

                                        <div class="form-row p-3">

                                            <label for="vincheck" class="col-md-1 col-form-label">Vin Check</label>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select onchange="chnageStyle(this)" title="&#160;" name="vincheck" id="vincheck" class="selectpicker" data-style="btn-outline-danger">
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
                                            <label for="insurance" class="col-md-1 col-form-label">Insurance</label>
                                            <div class="col-md-2">
                                                <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="insurance" name="insurance" data-style="btn-outline-danger">
                                                    <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                    <option value="need">Need</option>
                                                    <option value="inHouse">In House</option>
                                                    <option value="n/a">N/A</option>
                                                </select>
                                            </div>
                                            <label for="tradeTitle" class="col-md-1 col-form-label">Trade Title</label>
                                            <div class="col-md-2">
                                                <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="tradeTitle" name="tradeTitle" data-style="btn-outline-danger">
                                                    <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                    <option value="need">Need</option>
                                                    <option value="payoff">Payoff</option>
                                                    <option value="noTrade">No Trade</option>
                                                    <option value="inHouse">In House</option>
                                                </select>
                                            </div>
                                            <label for="registration" class="col-md-1 col-form-label">Registration</label>
                                            <div class="col-md-2">
                                                <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="registration" name="registration" data-style="btn-outline-danger">
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

                                            <label for="inspection" class="col-md-1 col-form-label">Inspection</label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="inspection" name="inspection" data-style="btn-outline-danger">
                                                    <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                    <option value="need">Need</option>
                                                    <option value="notNeed">Doesn't Need</option>
                                                    <option value="done">Done</option>
                                                    <option value="n/a">N/A</option>
                                                </select>
                                            </div>
                                            <label for="salePStatus" class="col-md-1 col-form-label">Salesperson Status</label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="salePStatus" name="salePStatus" data-style="btn-outline-danger">
                                                    <option value="" title="&#160;" data-hidden="true" selected> </option>

                                                    <option value="dealWritten">Deal Written</option>
                                                    <option value="gmdSubmit">GMD Submit</option>
                                                    <option value="contracted">Contracted</option>
                                                    <option value="cancelled">Cancelled</option>
                                                    <option value="delivered">Delivered</option>

                                                </select>
                                            </div>
                                            <label for="paid" class="col-md-1 col-form-label">Paid</label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" onchange="chnageStyle(this)" title="&#160;" id="paid" name="paid" data-style="btn-outline-danger">
                                                    <option value="" title="&#160;" data-hidden="true" selected> </option>
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>

                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-success">Update Sale</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
}
?>



<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/soldLogs.js"></script>