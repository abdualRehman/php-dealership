<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if ($_GET['r'] == 'man') {
    if ($salesConsultantID != $_SESSION['userRole']) {
        if (hasAccess("sale", "Edit") === 'false' && hasAccess("sale", "Remove") === 'false') {
            // echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
            echo "Dont Allow";
        }
    }
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
                            <h3 class="portlet-title">Sold Logs</h3>
                            <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                                <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                            </button>
                        </div>
                        <div class="portlet-body">
                            <table id="datatable-1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Sold Date</th>
                                        <th>Stock #</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Sales Consultant</th>
                                        <th>Vehicle</th>
                                        <th>Certified</th>
                                        <th>Lot</th>
                                        <th>Gross</th>
                                        <th>Status</th>
                                        <th style="max-width: 5%;">Notes</th>
                                        <th>Balance</th>
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
    <div class="modal fade" id="showDetails">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-dialog-scrollable">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Sale Details</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
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
                            <div class="col-md-6 mb-3">
                                <div class="row">
                                    <label for="inputPassword4" class="col-sm-1 offset-sm-1 col-form-label text-md-right">Status</label>
                                    <div class="col-sm-6 m-auto">
                                        <div class="form-group col-sm-6 text-center">
                                            <div class="btn-group btn-group-toggle" id="statusDiv" data-toggle="buttons">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=edit&i=" class="btn btn-primary mr-2" id="editBtn">Edit</a>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
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
                                        <div class="col-md-6">
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

                                        <div class="form-group col-md-6">
                                            <div class="row">
                                                <label for="inputPassword4" class="col-sm-1 offset-sm-1 col-form-label text-md-right">Status</label>
                                                <div class="col-sm-6">
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
                                    </div>
                                    <hr>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <label class="col-md-2 col-form-label" for="stockId">Stock No.</label>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <input type="hidden" name="selectedStockType" id="selectedStockType" />
                                                        <select class="selectpicker required" onchange="changeStockDetails(this)" name="stockId" id="stockId" data-live-search="true" data-size="4">
                                                            <option value="0" selected disabled>Stock No:</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-2 col-form-label" for="salesConsultant">Sales Consultant:</label>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <select class="selectpicker" name="salesPerson" id="salesPerson" data-live-search="true" data-size="4">
                                                            <option value="0" selected disabled>Sales Consultant</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-2 col-form-label" for="dealNote">Deal Notes</label>
                                                <div class="col-md-10 form-group">
                                                    <textarea class="form-control autosize" name="dealNote" id="dealNote" placeholder="Deal Notes..."></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6" id="detailsSection">
                                            <div class="form-group row">
                                                <label class="col-md-2 offset-md-1 col-form-label text-md-right" for="iscertified">Certified</label>
                                                <div class="col-md-8 d-flex justify-content-around">
                                                    <!-- <input type="text" class="form-control" id="iscertified" placeholder="yes" readonly> -->
                                                    <div class="custom-control custom-control-lg custom-radio">
                                                        <input type="radio" id="yes" name="iscertified" class="custom-control-input">
                                                        <label class="custom-control-label" for="yes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-control-lg custom-radio">
                                                        <input type="radio" id="no" name="iscertified" class="custom-control-input">
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
                                            <label for="address1" class="col-md-1 col-form-label">Address 1*</label>
                                            <div class="form-group col-md-5">
                                                <div class="input-group-icon">
                                                    <input type="text" class="form-control" name="address1" id="address1" placeholder="Your address here">
                                                    <div class="input-group-append"><i class="fa fa-map-marker-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="address2" class="col-md-1 col-form-label">Address 2</label>
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
                                    <h5 class="my-4 pl-2 d-flex justify-content-between align-items-center border rounded">Incentives <a href="javascript:;" class="col-md-2 text-center w-100 btn btn-info ml-2 align-item-streach" onclick="toggleInfo('loadIncentives')">
                                            Load Incentives <i class="fa fa-angle-down"></i>
                                        </a>
                                    </h5>

                                    <div class="mt-3 loadIncentives border rounded hidden" id="pbody" style="background-color: rgba(0,188,212,.1);">
                                        <div class="form-row p-3">
                                            <label for="college" class="col-md-1 col-form-label">College</label>
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
                                            <label for="military" class="col-md-1 col-form-label">Military</label>
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
                                            <label for="loyalty" class="col-md-1 col-form-label">Loyalty</label>
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
                                            <label for="conquest" class="col-md-1 col-form-label">Conquest</label>
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

                                            <label for="misc1" class="col-md-1 col-form-label">Misc 1</label>
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
                                            <label for="misc2" class="col-md-1 col-form-label">Misc 2</label>
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
                                            <label for="misc3" class="col-md-1 col-form-label">Misc 3</label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" data-live-search="true" id="misc3" name="misc3" data-size="5">
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
                                        <div class="form-row pb-0 p-3">

                                            <label for="inspection" class="col-md-1 col-form-label">Inspection</label>
                                            <div class="col-md-3">
                                                <select class="selectpicker" onchange="chnageStyle(this)" id="inspection" name="inspection" data-style="btn-outline-danger">
                                                    <option value="need">Need</option>
                                                    <option value="notNeed">Doesn't Need</option>
                                                    <option value="done">Done</option>
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