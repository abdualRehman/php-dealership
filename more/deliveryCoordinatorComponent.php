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

    body.theme-light .disabled-div,
    body.theme-light .disabled-div .btn-group-toggle>.btn:not(.active),
    body.theme-light .disabled-div .bootstrap-select .bs-btn-default {
        background-color: #eee !important;
        pointer-events: none;
        border-radius: 5px;
    }

    body.theme-dark .disabled-div,
    body.theme-dark .disabled-div .btn-group-toggle>.btn:not(.active),
    body.theme-dark .disabled-div .bootstrap-select .bs-btn-default {
        background-color: #757575 !important;
        pointer-events: none;
        border-radius: 5px;
    }

    .font-size-initial {
        font-weight: 900 !important;
        font-size: large;
    }

    .clear-selection {
        cursor: pointer;
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

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Delivery Coordinator</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("appointment", "Add") !== 'false') {
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Add Appointment
                        </button>';
                        }
                        ?>

                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <div class="form-row m-2 customFilters1 d-none">
                            <div class="col-md-12 p-2 d-flex justify-content-between">
                                <div class="dtsp-title">Filters Active</div>
                                <button type="button" id="filterDataTable" class="btn btn-flat-primary btn-wider">Filter Data</button>
                            </div>
                            <div class="col-12 row">
                                <div class="col p-1">
                                    <select class="form-control filterTags" id="coordinatorFilter" multiple="multiple">
                                        <optgroup label="Coordinator">
                                        </optgroup>
                                    </select>
                                </div>
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
                                    <select class="form-control filterTags" id="additionalServiceFilter" multiple="multiple">
                                        <optgroup label="Additional Services">
                                            <option value="vinCheck">Vin Check</option>
                                            <option value="maInspection">MA Inspection</option>
                                            <option value="riInspection">RI Inspection</option>
                                            <option value="paperworkSigned">Get Paperwork Signed</option>
                                            <option value="other">Other (See Notes)</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Confirmed</th>
                                    <th>Complete</th>
                                    <th>Customer Name</th>
                                    <th>Appointment Date</th>
                                    <th>Time</th>
                                    <th>Coordinator</th>
                                    <th>Stock No.</th>
                                    <th>Vehicle</th>
                                    <th>Vin</th>
                                    <th>Sales Consultant</th>
                                    <th>Delivery</th>
                                    <th>Approvals</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                    <th>Additional Services</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Add Appointment</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewSchedule" autocomplete="off" method="post" action="<?php echo $siteurl ?>/php_action/createSchedule.php">

                <div class="modal-body">
                    <div class="w-100 appointment_div p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row align-items-baseline">
                                    <label for="leadDate" class="col-sm-3 text-sm-center col-form-label">Customer Name</label>
                                    <div class="form-group col-sm-9">
                                        <input type="text" class="form-control" name="customerName" id="customerName" autocomplete="off" autofill="off" readonly />
                                    </div>
                                </div>
                                <div class="row align-items-baseline">
                                    <label for="leadDate" class="col-sm-3 text-sm-center col-form-label">Stock No - Vin</label>
                                    <div class="form-group col-sm-9">
                                        <input type="hidden" class="form-control" name="stockno" id="stockno" />
                                        <select class="form-control selectpicker w-auto" id="sale_id" onchange="changeStockDetails(this)" name="sale_id" data-live-search="true" data-size="4">
                                            <option value="" selected disabled>Select</option>
                                            <optgroup class="stockno"></optgroup>
                                        </select>
                                    </div>
                                    <label for="leadDate" class="col-sm-3 text-sm-center col-form-label"> Vehicle</label>
                                    <div class="form-group col-sm-9">
                                        <input type="text" class="form-control" name="vechicle" id="vechicle" autocomplete="off" autofill="off" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="leadDate" class="col-form-label">submitted By</label>
                                    <input type="text" class="form-control text-center" name="submittedBy" id="submittedBy" value="<?php echo $_SESSION['userName']; ?>" readonly autocomplete="off" autofill="off" />
                                </div>

                                <div class="form-group manager_override_div" style="border-radius:5px;">
                                    <input type="hidden" name="has_appointment" id="has_appointment" value="null" />
                                    <div class="custom-control custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="overrideBy" id="overrideBy">
                                        <label class="custom-control-label" for="overrideBy">Manager Override</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control text-center" name="overrideByName" id="overrideByName" value="" readonly autocomplete="off" autofill="off" />
                                    <input type="hidden" class="form-control text-center" name="overrideById" id="overrideById" value="" readonly autocomplete="off" autofill="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <label for="fname" class="col-sm-3 text-sm-center col-form-label">Appointment Date</label>
                                    <div class="col-sm-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control scheduleDate handleDateTime" data-type="add" name="scheduleDate" id="scheduleDate" />
                                        </div>
                                    </div>

                                    <label for="lname" class="col-sm-1 text-md-center col-form-label">Time</label>
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
                                        <select class="form-control selectpicker w-auto" id="coordinator" name="coordinator" data-live-search="true" data-size="4">
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
                                    <input type="radio" name="confirmed" value="ok" id="ok">
                                    Yes
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="confirmed" value="showVerified" id="showVerified">
                                    No
                                </label>
                            </div>
                        </div>
                        <label for="complete" class="col-sm-2 text-sm-right col-form-label">Complete</label>
                        <div class="col-md-4">
                            <div class="btn-group btn-group-toggle disabled-div clear-selection-btn-group w-100" data-toggle="buttons" id="complete">
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="complete" value="ok" id="ok">
                                    Yes
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="complete" value="showVerified" id="showVerified">
                                    No
                                </label>
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



<div class="modal fade" id="editScheduleModel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Appointment</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="editScheduleForm" autocomplete="off" method="post" action="<?php echo $siteurl ?>/php_action/editSchedule.php">
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
                                            <input type="text" class="form-control" name="ecustomerName" id="ecustomerName" autocomplete="off" autofill="off" readonly />
                                        </div>
                                    </div>
                                    <div class="row align-items-baseline">
                                        <label for="esale_id" class="col-sm-3 text-sm-center col-form-label">Stock No - Vin</label>
                                        <div class="form-group col-sm-9">
                                            <input type="hidden" class="form-control" name="estockno" id="estockno" />
                                            <select class="form-control selectpicker w-auto required" id="esale_id" onchange="echangeStockDetails(this)" name="esale_id" data-live-search="true" data-size="4">
                                                <option value="" selected disabled>Select</option>
                                                <optgroup class="stockno"></optgroup>
                                            </select>
                                        </div>
                                        <label for="evechicle" class="col-sm-3 text-sm-center col-form-label"> Vehicle</label>
                                        <div class="form-group col-sm-9">
                                            <input type="text" class="form-control" name="evechicle" id="evechicle" autocomplete="off" autofill="off" disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="esubmittedBy" class="col-form-label">submitted By</label>
                                        <input type="text" class="form-control text-center" name="esubmittedBy" id="esubmittedBy" readonly autocomplete="off" autofill="off" />
                                        <input type="hidden" class="form-control text-center" name="esubmittedByRole" id="esubmittedByRole" readonly autocomplete="off" autofill="off" />
                                        <input type="hidden" class="form-control text-center" name="esubmittedById" id="esubmittedById" readonly autocomplete="off" autofill="off" />
                                    </div>

                                    <div class="form-group manager_override_div" style="border-radius:5px;">
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
                                    <input type="hidden" name="prev_delivery" id="prev_delivery" value="">
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
                                            <input type="checkbox" name="eadditionalServices[]" value="vinCheck" id="evinCheck">
                                            Vin Check
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="checkbox" name="eadditionalServices[]" value="maInspection" id="emaInspection">
                                            MA Inspection
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="checkbox" name="eadditionalServices[]" value="riInspection" id="eriInspection">
                                            RI Inspection
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="checkbox" name="eadditionalServices[]" value="paperworkSigned" id="epaperworkSigned">
                                            Get Paperwork Signed
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="checkbox" name="eadditionalServices[]" value="other" id="eother">
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
                            <div class="col-md-4 disabled-div1">
                                <div class="btn-group btn-group-toggle clear-selection-btn-group w-100" data-targetElement="ecomplete" data-toggle="buttons" id="econfirmed">
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="econfirmed" value="ok" id="conok">
                                        Yes
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="econfirmed" value="showVerified" id="conshowVerified">
                                        No
                                    </label>
                                </div>
                                <!-- <span class="badge-text-primary clear-selection" data-id="econfirmed">Clear Selection</span> -->
                            </div>
                            <label for="ecomplete" class="col-sm-2 text-sm-right col-form-label">Complete</label>
                            <div class="col-md-4">
                                <div class="btn-group btn-group-toggle disabled-div clear-selection-btn-group w-100" data-toggle="buttons" id="ecomplete">
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="ecomplete" value="ok" id="comok">
                                        Yes
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="ecomplete" value="showVerified" id="comshowVerified">
                                        No
                                    </label>
                                </div>
                                <!-- <span class="badge-text-primary clear-selection" data-id="ecomplete">Clear Selection</span> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" id="eSubmitBtn" data-loading-text="Loading..." class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>