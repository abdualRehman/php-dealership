<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("sale", "Add") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

$userRole = $_SESSION['userRole'];
if ($userRole != 'Admin' && $userRole != $branchAdmin && $userRole != $generalManagerID && $userRole != $salesManagerID) {
    echo '<input type="hidden" name="vgb" id="vgb" value="false">';
} else {
    echo '<input type="hidden" name="vgb" id="vgb" value="true">';
}

?>
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
</style>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Add Sale</h3>
                    </div>
                    <div class="portlet-body">
                        <!-- <form id="addSaleForm" autocomplete="off" method="post" onsubmit="return false;" action="#"> -->
                        <form id="addSaleForm" autocomplete="off" method="post" action="../php_action/createSale.php">

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
                                                <input type="text" class="form-control" onchange="changeRules()" name="saleDate" placeholder="Select date" id="saleDate">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="row w-100">
                                        <label for="inputPassword4" class="col-sm-4 col-form-label text-md-right">Status</label>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-flat-primary active d-flex align-items-center">
                                                        <input type="radio" name="status" value="pending" id="pending" checked="checked">
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
                            <hr class="mt-0 mb-5">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-md-3 col-form-label" for="stockId">Stock No.</label>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="hidden" name="selectedStockType" id="selectedStockType" />
                                                <select class="selectpicker required" data-focus-on="true" tabIndex="1" name="stockId" id="stockId" data-live-search="true" data-size="4" autofocus='autofocus'>
                                                    <option value="0" selected disabled>Stock No:</option>
                                                    <optgroup class="stockno"></optgroup>

                                                </select>
                                            </div>
                                            <p>Not in Stock? <a href="javascript:;" data-toggle="modal" data-target="#showDetails" class="a"> Click to Add</a> </p>

                                        </div>
                                    </div>
                                    <div class="row align-items-baseline">
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
                                                    <option value="0" selected>Select</option>
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
                                <div class="col-md-6 d-none" id="detailsSection">
                                    <div class="form-group row">
                                        <label class="col-md-2 offset-md-1 col-form-label text-md-right" for="iscertified">Certified</label>
                                        <div class="col-md-8 d-flex justify-content-around">
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

                                    <div class="form-group row">
                                        <div class="col-md-10 offset-sm-1 saleDetailsDiv" id="saleDetailsDiv">
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
                                    <!-- <div class="form-group row">
                                        <label class="col-md-2 offset-md-1 col-form-label text-md-right" for="profit">Gross</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">$</div>
                                                </div>
                                                <input type="text" class="form-control" name="profit" id="profit" placeholder="">
                                            </div>
                                        </div>
                                    </div> -->

                                </div>
                            </div>


                            <h5 class="my-4">Customer Account</h5>
                            <div class="form-row">

                                <div class="col-md-10">
                                    <div class="form-group input-group d-flex flex-md-row flex-sm-column">
                                        <input type="text" name="fname" id="fname" class="form-control w-auto " placeholder="First name">
                                        <input type="text" name="mname" id="mname" class="form-control w-auto " placeholder="Middle name">
                                        <input type="text" name="lname" id="lname" class="form-control w-auto " placeholder="Last name">
                                        <select class="form-control selectpicker w-auto" onchange="changeRules()" name="state" id="state" data-live-search="true" data-size="4">
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
                                        <select class="form-control selectpicker w-auto" name="cbstate" id="cbstate" data-live-search="true" data-size="4">
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
                                <a href="javascript:;" class="form-group col-md-2 text-center w-100 btn btn-outline-info align-item-streach" onclick="toggleInfo('coBuyer')">
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

                            <div class="mt-3 loadIncentives border rounded hidden" id="loadIncentivesDiv" style="background-color: rgba(0,188,212,.1);">
                                <div class="form-row p-3">
                                    <label for="college" class="col-md-1 col-form-label text-md-center">College
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
                                    <label for="military" class="col-md-1 col-form-label text-md-center">Military
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
                                    <label for="loyalty" class="col-md-1 col-form-label text-md-center">Loyalty
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
                                    <label for="conquest" class="col-md-1 col-form-label text-md-center">Conquest
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
                                    <label for="leaseLoyalty" class="col-md-1 col-form-label text-md-center">Lease Loyalty
                                        <span class="badge-label-primary" id="leaseLoyalty_v"></span>
                                    </label>
                                    <div class="col-md-3">
                                        <!-- <select class="selectpicker" data-live-search="true" id="misc3" name="misc3" data-size="5"> -->
                                        <select class="selectpicker" data-live-search="true" id="leaseLoyalty" name="leaseLoyalty" data-size="5">
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
                                        <select class="selectpicker" data-live-search="true" id="misc1" name="misc1" data-size="5">
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

                                    <label for="vincheck" class="col-md-1 col-form-label text-md-center">Vin Check</label>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select onchange="chnageStyle(this)" name="vincheck" id="vincheck" title="&#160;" class="selectpicker" data-style="btn-outline-success">
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
                                        <select class="selectpicker" onchange="chnageStyle(this)" id="insurance" title="&#160;" name="insurance" data-style="btn-outline-success">
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="need">Need</option>
                                            <option value="inHouse">In House</option>
                                            <option value="n/a">N/A</option>
                                        </select>
                                    </div>
                                    <label for="tradeTitle" class="col-md-1 col-form-label text-md-center">Trade Title</label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" onchange="chnageStyle(this)" id="tradeTitle" title="&#160;" name="tradeTitle" data-style="btn-outline-success">
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="need">Need</option>
                                            <option value="payoff">Payoff</option>
                                            <option value="noTrade">No Trade</option>
                                            <option value="inHouse">In House</option>
                                        </select>
                                    </div>
                                    <label for="registration" class="col-md-1 col-form-label text-md-center">Registration</label>
                                    <div class="col-md-2">
                                        <select class="selectpicker" onchange="chnageStyle(this)" id="registration" title="&#160;" name="registration" data-style="btn-outline-success">
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
                                        <select class="selectpicker" onchange="chnageStyle(this)" id="inspection" title="&#160;" name="inspection" data-style="btn-outline-success">
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="need">Need</option>
                                            <option value="notNeed">Doesn't Need</option>
                                            <option value="done">Done</option>
                                            <option value="n/a">N/A</option>
                                        </select>
                                    </div>
                                    <label for="salePStatus" class="col-md-1 col-form-label text-md-center">Salesperson Status</label>
                                    <div class="col-md-3">
                                        <select class="selectpicker" onchange="chnageStyle(this)" id="salePStatus" title="&#160;" name="salePStatus" data-style="btn-outline-success">
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
                                        <select class="selectpicker" onchange="chnageStyle(this)" id="paid" title="&#160;" name="paid" data-style="btn-outline-success">
                                            <option value="" title="&#160;" data-hidden="true" selected> </option>
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>

                                </div>

                            </div>

                            <button type="submit" class="btn btn-success float-right">Create Sale</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="showDetails">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Vehicle</h5><button type="button" class="btn btn-outline-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addInvForm" autocomplete="off" method="post" action="../php_action/createInv.php">
                <!-- <form id="addInvForm" autocomplete="off" method="post" > -->
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div id="success-message"></div>
                    <div class="showResult">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="stockType">Stock Type</label>
                                <select class="custom-select w-100" id="stockType" name="stockType">
                                    <option value="0" selected="selected" disabled="disabled">Choose...
                                    </option>
                                    <option value="NEW">NEW</option>
                                    <option value="USED">USED</option>
                                    <option value="OTHER">OTHER</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-1 d-flex justify-content-around align-items-end p-2">
                                <div class="custom-control custom-checkbox mr-5">
                                    <input type="checkbox" class="custom-control-input" id="certified" name="certified">
                                    <label class="custom-control-label" for="certified">Certified</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="wholesale" name="wholesale">
                                    <label class="custom-control-label" for="wholesale">Wholesale</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="stockno">Stock No.</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="stockno" name="stockno">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="year">Year</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="year" name="year">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="make">Make</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="make" name="make">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="model">Model</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="model" name="model">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="modelno">Model No</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="modelno" name="modelno">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="color">Color</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="color" name="color">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lot">Lot</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="lot" name="lot">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="vin">Vin</label>
                                <div class="form-group">

                                    <input type="text" class="form-control" id="vin" name="vin">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="mileage">Mileage</label>
                                <div class="form-group">

                                    <input type="text" class="form-control" id="mileage" name="mileage">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="age">Age</label>
                                <div class="form-group">

                                    <input type="text" class="form-control" id="age" name="age">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="balance">Balance</label>
                                <div class="form-group">

                                    <input type="text" class="form-control" id="balance" name="balance">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="retail">Retail</label>
                                <div class="form-group">

                                    <input type="text" class="form-control" id="retail" name="retail">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-2" id="createBtn">Create</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/addSale.js"></script>