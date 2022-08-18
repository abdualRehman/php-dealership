<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("appointment", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}

$userRole = $_SESSION['userRole'];
echo '<input type="hidden" name="loggedInUserRole" id="loggedInUserRole" value="' . $userRole . '" />';
echo '<input type="hidden" name="currentUser" id="currentUser" value="' . $_SESSION['userName'] . '">';
echo '<input type="hidden" name="currentUserId" id="currentUserId" value="' . $_SESSION['userId'] . '">';
?>



<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
    <link rel="stylesheet" href="../custom/dist/tui-calendar.css">
    <!-- <link rel="stylesheet" href="../custom/css/default.css"> -->
    <link rel="stylesheet" href="../custom/css/icons.css">
    <style>
        .tui-full-calendar-timegrid-timezone {
            background-color: transparent !important;
        }

        .tui-full-calendar-vlayout-area.tui-full-calendar-vlayout-container div[data-panel-index="0"] {
            min-height: 200px !important;
        }

        body.theme-dark .tui-full-calendar-layout *:not(.tui-full-calendar-timegrid-hourmarker-line-today) {
            color: #f5f5f5 !important;
            border-color: #757575 !important;
        }

        body.theme-dark .tui-full-calendar-layout,
        body.theme-dark .tui-full-calendar-month-more {
            background-color: #424242 !important;
        }

        body.theme-light .tui-full-calendar-layout *:not(.tui-full-calendar-timegrid-hourmarker-line-today) {
            color: #424242;
            border-color: #eee !important;
        }

        body.theme-light .tui-full-calendar-time-schedule-content.tui-full-calendar-time-schedule-content-time {
            color: #eee !important;
        }


        body.theme-light .tui-full-calendar-layout,
        body.theme-light .tui-full-calendar-month-more {
            background-color: #ffffff !important;
        }

        .dropdown-header {
            padding: 0px !important;
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

        .tui-full-calendar-weekday-schedule-bullet {
            display: none;
        }

        .tui-full-calendar-weekday-schedule-title {
            color: inherit !important;
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
</head>

<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Appointment Calendar</h3>
                        <?php
                        if (hasAccess("appointment", "Add") !== 'false') {
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Add New Schedules
                        </button>';
                        }
                        ?>
                    </div>
                    <div class="portlet-body">
                        <div>
                            <div id="menu" class="d-flex flex-row justify-content-between align-items-center p-3">
                                <div>
                                    <span id="lnb-calendars" class="lnb-calendars">
                                        <button class="btn btn-outline-primary" id="dropdownMenu-lnb-calendars" aria-expanded="false" data-toggle="dropdown">
                                            <i class="calendar-icon fa fa-filter" style="margin-right: 4px;"></i>
                                            <span>Filter</span>&nbsp;
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-left dropdown-menu-animated" id="calendarList" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                                        </div>
                                    </span>

                                    <span class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" id="dropdownMenu-calendarType" data-toggle="dropdown" aria-expanded="false">
                                            <i id="calendarTypeIcon" class="calendar-icon fa fa-list-alt" style="margin-right: 4px;"></i>
                                            <span id="calendarTypeName">View</span>&nbsp;
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-left dropdown-menu-animated" role="menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                                            <li role="presentation">
                                                <a class="dropdown-item" role="menuitem" data-action="toggle-daily">
                                                    <div class="dropdown-icon"><i class="calendar-icon fa fa-align-justify"></i></div>
                                                    <span class="dropdown-content">Daily</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" role="menuitem" data-action="toggle-weekly">
                                                    <div class="dropdown-icon"><i class="calendar-icon fa fa-list"></i></div>
                                                    <span class="dropdown-content">Weekly</span>
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a class="dropdown-item" role="menuitem" data-action="toggle-monthly">
                                                    <div class="dropdown-icon"><i class="fa fa-table"></i>
                                                    </div><span class="dropdown-content">Monthly</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </span>
                                </div>
                                <span id="menu-navi">
                                    <button type="button" class="btn btn-outline-primary" data-action="move-today">Today</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm move-day" data-action="move-prev">
                                        <!-- <i class="calendar-icon ic-arrow-line-left" data-action="move-prev"></i> -->
                                        <i class="fa fa-arrow-left" data-action="move-prev"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm move-day" data-action="move-next">
                                        <!-- <i class="calendar-icon ic-arrow-line-right" data-action="move-next"></i> -->
                                        <i class="fa fa-arrow-right" data-action="move-next"></i>

                                    </button>
                                </span>

                                <span id="renderRange" class="render-range"></span>
                            </div>
                        </div>



                        <div id="calendar"></div>

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
                <h5 class="modal-title">Add Schedule</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewSchedule" autocomplete="off" method="post" action="../php_action/createSchedule.php">

                <div class="modal-body">
                    <div class="w-100 appointment_div p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row align-items-baseline">
                                    <label for="leadDate" class="col-sm-3 text-sm-center col-form-label">Customer Name</label>
                                    <div class="form-group col-sm-9">
                                        <input type="text" class="form-control" name="customerName" id="customerName" autocomplete="off" autofill="off" disabled />
                                    </div>
                                </div>
                                <div class="row align-items-baseline">
                                    <label for="leadDate" class="col-sm-3 text-sm-center col-form-label">Stock No - Vin</label>
                                    <div class="form-group col-sm-9">
                                        <input type="hidden" class="form-control" name="stockno" id="stockno" />
                                        <select class="form-control selectpicker w-auto required" id="sale_id" onchange="changeStockDetails(this)" name="sale_id" data-live-search="true" data-size="4">
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
                                        <input type="radio" name="additionalServices" value="vinCheck" id="vinCheck">
                                        Vin Check
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="additionalServices" value="maInspection" id="maInspection">
                                        MA Inspection
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="additionalServices" value="riInspection" id="riInspection">
                                        RI Inspection
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="additionalServices" value="paperworkSigned" id="paperworkSigned">
                                        Get Paperwork Signed
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="additionalServices" value="other" id="other">
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
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="confirmed">
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
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="complete">
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
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
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
                <h5 class="modal-title">Edit Schedule</h5>
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
                                                <optgroup class="coordinator" id="ecoordinatorList"></optgroup>
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


<?php require_once('../includes/footer.php') ?>
<script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/v2.0.3/tui-time-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/v4.0.3/tui-date-picker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chance/1.0.13/chance.min.js"></script>

<script type="text/javascript" src="../custom/dist/tui-calendar.js"></script>
<script type="text/javascript" src="../custom/dist/calendars.js"></script>
<script type="text/javascript" src="../custom/dist/schedules.js"></script>


<!-- working js -->
<script type="text/javascript" src="../custom/js/appointments.js"></script>
<!-- working js -->



<script type="text/javascript" src="../custom/dist/default.js"></script>

<script>
    $('.tui-full-calendar-timegrid-hourmarker-time').bind("DOMSubtreeModified", function(e) {
        // console.log(e)
        // console.log(e.target.innerHTML)
    });
</script>