<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("lotWizards", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}


if (hasAccess("lotWizards", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
    <!-- <link rel="stylesheet" href="../custom/css/jquery-ui.min.css"> -->
</head>


<style>
    label.btn-outline-primary,
    label.btn-outline-success,
    label.btn-outline-danger {
        /* padding: 10px; */
        width: 150px;
        margin: 5px;
        /* font-size: medium; */
    }

    label.btn-outline-success:hover,
    label.btn-outline-danger:hover {
        color: white;
    }

    .clear-selection {
        text-decoration: underline;
        cursor: pointer;
    }

    .dtsp-panes1 {
        display: none;
    }

    .btn-group-toggle label input {
        visibility: hidden;
        position: absolute;
    }

    /* .text-responsive {
        font-size: calc(100% + 4px - 0px);
    } */

    .dataTables_scrollHeadInner {
        width: 100% !important;
    }

    .dataTables_scroll {
        overflow: auto !important;
    }

    .dataTables_scrollHead {
        width: auto !important;
    }

    .dataTables_scrollBody {
        width: auto !important;
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

    /* shift search to left side */
    .inspectionTable .dataTables_wrapper .dataTables_filter {
        text-align: right !important;
    }

    /* red border to select2 */
    .has-error {
        border: 1px solid #a94442 !important;
        border-radius: 4px !important;
        border-color: rgb(185, 74, 72) !important;
    }

    span.error {
        outline: none;
        border: 1px solid #800000;
        box-shadow: 0 0 5px 1px #800000;
    }
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
                                        <label class="btn text-responsive active">
                                            <input type="radio" name="mod" value="notTouched" data-title="Not Touched"> Not Touched <br> <span></span>
                                        </label>
                                        <label class="btn text-responsive">
                                            <input type="radio" name="mod" value="holdForRecon" data-title="Hold For Recon"> Hold For Recon <br> <span></span>
                                        </label>
                                        <label class="btn text-responsive">
                                            <input type="radio" name="mod" value="sendToRecon" data-title="Send To Recon"> Send To Recon <br> <span></span>
                                        </label>
                                        <label class="btn text-responsive">
                                            <input type="radio" name="mod" value="LotNotes" data-title="Lot Notes"> Lot Notes <br> <span></span>
                                        </label>
                                        <label class="btn text-responsive">
                                            <input type="radio" name="mod" value="CarsToDealers" data-title="Cars to Dealers"> Cars to Dealers <br> <span></span>
                                        </label>
                                        <label class="btn text-responsive">
                                            <input type="radio" name="mod" value="windshield" data-title="Windshield"> Windshield <br> <span></span>
                                        </label>
                                        <label class="btn text-responsive">
                                            <input type="radio" name="mod" value="wheels" data-title="Wheels"> Wheels <br> <span></span>
                                        </label>

                                    </div>
                                </div>

                                <div class="row p-2">
                                    <div class="col-md-12">
                                        <div id="year">
                                            <!-- <div class="btn-group-toggle" data-toggle="buttons"> -->
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="toGo" data-title="To Go"> To Go <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="atBodyshop" data-title="At Bodyshop"> At Bodyshop <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="backFromBodyshop" data-title="Back From Bodyshop"> Back From Bodyshop <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="retailReady" data-title="Retail Ready"> Retail Ready <br> <span></span>
                                            </label>
                                            <label class="btn text-responsive">
                                                <input type="radio" name="mod" value="Gone" data-title="Gone"> Gone <br> <span></span>
                                            </label>
                                            <!-- </div> -->
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
                                <!-- <th style="min-width: 150px!important;">Lot Notes</th> -->
                                <thead>
                                    <tr>
                                        <!-- 0 -->
                                        <th>Action</th>
                                        <!-- 1 -->
                                        <th>Recon</th>
                                        <!-- 2 -->
                                        <th>Note Added By</th>
                                        <!-- 3 -->
                                        <th>Lot Notes</th>
                                        <!--- 4 -->
                                        <th>Bodyshop</th>
                                        <!-- 5 -->
                                        <th>Days Out</th>
                                        <!-- 6 -->
                                        <th>Windshield</th>
                                        <!-- 7 -->
                                        <th>wheels</th>
                                        <!-- 8 -->
                                        <th>Age</th>
                                        <!-- 9 -->
                                        <th>Stock no || Vin</th>
                                        <!-- 10 -->
                                        <th>Stock no</th>
                                        <!-- 11 -->
                                        <th>Year</th>
                                        <!-- 12 -->
                                        <th>Make</th>
                                        <!-- 13 -->
                                        <th>Model</th>
                                        <!-- 14 -->
                                        <th>Color</th>
                                        <!-- 15 -->
                                        <th>Mileage</th>
                                        <!-- 16 -->
                                        <th>Lot</th>
                                        <!-- 17 -->
                                        <th>Balance</th>
                                        <!-- 18 -->
                                        <th>Retail</th>
                                        <!-- 19 -->
                                        <th>Certified</th>
                                        <!-- 20 -->
                                        <th>Stock Type</th>
                                        <!-- 21 -->
                                        <th>Wholesale</th>
                                        <!-- 22 -->
                                        <th>ID</th>
                                        <!-- 23 -->
                                        <th>Repairs</th>
                                        <!-- 24 -->
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="DealerTable d-none">
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
                                        <th>Action</th>
                                        <th>Age</th>
                                        <th>Stock no || Vin</th>
                                        <th>Year Make Model</th>
                                        <th>Worked Needed</th>
                                        <th>Notes</th>
                                        <th>Date Sent</th>
                                        <th>Date Returned</th>
                                        <th>ID</th>
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

<div class="modal fade" id="modal8">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Inspections</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="updateInspectionForm" autocomplete="off" method="post" action="../php_action/updateInspections.php" enctype="multipart/form-data">
                <!-- <form id="updateInspectionForm" autocomplete="off" method="post" action="#" enctype="multipart/form-data"> -->
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
                                    <label for="lotNotes" class="col-sm-3 offset-sm-1 col-form-label">Lot Notes</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control" name="lotNotes" id="lotNotes" autocomplete="off" autofill="off" />
                                    </div>
                                    <label for="recon" class="col-sm-3 offset-sm-1 col-form-label">Recon</label>
                                    <div class="form-group col-sm-8" id="reconBtnGroup">
                                        <!-- <input type="text" class="form-control" name="recon" id="recon" placeholder="Recon" autocomplete="off" autofill="off" /> -->
                                        <div class="btn-group btn-group-toggle w-100" id="recon-btn-group" data-toggle="buttons">
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="recon" value="hold" id="hold">
                                                Hold Recon for service
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="recon" value="send" id="send">
                                                Send to recon
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="recon" value="sent" id="sent">
                                                Sent
                                            </label>
                                        </div>
                                    </div>
                                    <label for="repais" class="col-sm-3 offset-sm-1 col-form-label">Repairs</label>
                                    <div class="form-group col-sm-8">
                                        <!-- <input type="text" class="form-control" name="repais" id="repais" placeholder="Repais" autocomplete="off" autofill="off" /> -->
                                        <select class="form-control repairAndBodyshop" id="repais" name="repais[]" multiple="multiple">
                                            <option value="Front Bumper">Front Bumper</option>
                                            <option value="Front Bumper Top">Front Bumper Top</option>
                                            <option value="Front Bumper Lower">Front Bumper Lower</option>
                                            <option value="Attach Front Bumper">Attach Front Bumper</option>
                                            <option value="Rear Bumper">Rear Bumper</option>
                                            <option value="Attach Rear Bumper">Attach Rear Bumper</option>
                                            <option value="Both Bumpers">Both Bumpers</option>
                                            <option value="Rear Bumper Lower">Rear Bumper Lower</option>
                                            <option value="Grill">Grill</option>
                                            <option value="Hood">Hood</option>
                                            <option value="Roof">Roof</option>
                                            <option value="Spoiler">Spoiler</option>
                                            <option value="Tailgate / Hatch">Tailgate / Hatch</option>
                                            <option value="Trunk">Trunk</option>
                                            <option value="Driver Dogleg">Driver Dogleg</option>
                                            <option value="Driver Fender">Driver Fender</option>
                                            <option value="Driver Fog Light">Driver Fog Light</option>
                                            <option value="Driver Front Door">Driver Front Door</option>
                                            <option value="Driver Front Wheel Trim">Driver Front Wheel Trim</option>
                                            <option value="Driver Headlight">Driver Headlight</option>
                                            <option value="Driver Mirror">Driver Mirror</option>
                                            <option value="Driver Rear Door">Driver Rear Door</option>
                                            <option value="Driver Rear Quarter">Driver Rear Quarter</option>
                                            <option value="Driver Rear Wheel Trim">Driver Rear Wheel Trim</option>
                                            <option value="Driver Rocker">Driver Rocker</option>
                                            <option value="Driver Roof Rail">Driver Roof Rail</option>
                                            <option value="Driver Sill">Driver Sill</option>
                                            <option value="Driver Slider Door">Driver Slider Door</option>
                                            <option value="DriverTaillight">DriverTaillight</option>
                                            <option value="Passenger Dogleg">Passenger Dogleg</option>
                                            <option value="Passenger Fender">Passenger Fender</option>
                                            <option value="Passenger Fog Light">Passenger Fog Light</option>
                                            <option value="Passenger Front Door">Passenger Front Door</option>
                                            <option value="Passenger Front Wheel Trim">Passenger Front Wheel Trim</option>
                                            <option value="Passenger Headlight">Passenger Headlight</option>
                                            <option value="Passenger Mirror">Passenger Mirror</option>
                                            <option value="Passenger Rear Door">Passenger Rear Door</option>
                                            <option value="Passenger Rear Quarter">Passenger Rear Quarter</option>
                                            <option value="Passenger Rear Wheel Trim">Passenger Rear Wheel Trim</option>
                                            <option value="Passenger Rocker">Passenger Rocker</option>
                                            <option value="Passenger Roof Rail">Passenger Roof Rail</option>
                                            <option value="Passenger Sill">Passenger Sill</option>
                                            <option value="Passenger Slider Door">Passenger Slider Door</option>
                                            <option value="PassengerTaillight">PassengerTaillight</option>
                                            <option value="Hail">Hail</option>
                                            <option value="Pdr">Pdr</option>
                                            <option value="Wetsand">Wetsand</option>
                                            <option value="OTHER">OTHER</option>
                                            <option value="ESTIMATE ONLY">ESTIMATE ONLY</option>
                                        </select>
                                    </div>
                                    <label for="bodyshop" class="col-sm-3 offset-sm-1 col-form-label">Bodyshop</label>
                                    <div class="form-group col-sm-8">
                                        <!-- <input type="text" class="form-control" name="bodyshop" id="bodyshop" placeholder="Bodyshop" autocomplete="off" autofill="off" /> -->
                                        <select class="selectpicker repairAndBodyshop" name="bodyshop" id="bodyshop" data-live-search="true" required>
                                        </select>
                                    </div>
                                    <label for="bodyshopNotes" class="col-sm-3 offset-sm-1 col-form-label">Bodyshop Notes</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control" name="bodyshopNotes" id="bodyshopNotes" autocomplete="off" autofill="off" />
                                    </div>
                                    <label for="estimate" class="col-sm-3 offset-sm-1 col-form-label">Estimate</label>
                                    <div class="form-group col-sm-3">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" name="estimate" id="estimate" autocomplete="off" autofill="off" />
                                        </div>
                                    </div>
                                    <label for="repairPaid" class="col-sm-2 col-form-label">Repair Paid</label>
                                    <div class="form-group col-sm-3">

                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" name="repairPaid" id="repairPaid" autocomplete="off" autofill="off" />
                                        </div>
                                    </div>
                                    <label for="repairSent" class="col-sm-3 offset-sm-1 col-form-label">Repair Sent</label>
                                    <div class="form-group col-sm-3">
                                        <!-- <input type="text" class="form-control" name="repairSent" id="repairSent" placeholder="Repair Sent" autocomplete="off" autofill="off" /> -->
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="repairSent" placeholder="Select date" id="repairSent" disabled>
                                        </div>
                                    </div>
                                    <label for="repairReturn" class="col-sm-2 col-form-label">Repair Return</label>
                                    <div class="form-group col-sm-3">
                                        <!-- <input type="text" class="form-control" name="repairReturn" id="repairReturn" placeholder="Repair Return" autocomplete="off" autofill="off" /> -->
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="repairReturn" placeholder="Select date" id="repairReturn">
                                        </div>
                                    </div>
                                    <div class="form-group offset-sm-4 col-sm-8">
                                        <div class="custom-control custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="resend" id="resend" />
                                            <label class="custom-control-label" for="resend">Resend</label>
                                        </div>
                                    </div>
                                    <label for="windshield" class="col-sm-3 offset-sm-1 col-form-label">Windshield</label>
                                    <div class="form-group col-sm-6">
                                        <select class="selectpicker" id="windshield" name="windshield[]" multiple="multiple" data-actions-box="true">
                                            <option>Repair</option>
                                            <option>Replace</option>
                                            <option>Sent</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="windshield_done" id="windshield_done">
                                            <label class="custom-control-label" style="font-size:15px;" for="windshield_done">Done</label>
                                        </div>
                                    </div>
                                    <label for="wheels" class="col-sm-3 offset-sm-1 col-form-label">Wheels</label>
                                    <div class="form-group col-sm-6">
                                        <select class="selectpicker" name="wheels[]" id="wheels" multiple="multiple" data-actions-box="true">
                                            <option>Driver Front</option>
                                            <option>Passenger Front</option>
                                            <option>Driver Rear</option>
                                            <option>Passenger Rear</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="wheels_done" id="wheels_done">
                                            <label class="custom-control-label" style="font-size:15px;" for="wheels_done">Done</label>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-4">
                                <label for="submittedBy" class="col-form-label">Submitted By</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="submittedBy" id="submittedBy" readonly autocomplete="off" autofill="off" />
                                </div>
                                <label for="repairReturn" class="col-form-label">Vehicle</label>
                                <div class="saleDetailsDiv" id="saleDetailsDiv">
                                    <textarea class="form-control autosize" name="selectedDetails" style="border:none ;overflow-y: scroll!important;" id="selectedDetails" readonly placeholder="Type Something..."></textarea>
                                </div>
                                <div class="pt-3">
                                    <p>Max-Upload <strong id="maxLimit">10</strong> </p>
                                    <input type="file" class="form-control-file" id="images" name="images[]" multiple accept="image/*" />
                                </div>
                                <div class="carousel slick-2 mt-3" id="slickSlider" style="overflow: hidden">

                                </div>
                                <div class="resendDetailsDiv mt-3 d-none" id="resendDetailsDiv">
                                    <textarea class="form-control autosize" name="resendDetails" style="border:none ;overflow-y: scroll!important;" id="resendDetails" readonly placeholder="Type Something..."></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <label for="windshield" class="col-sm-1 col-form-label">Windshield</label>
                            <div class="form-group col-sm-10">
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-primary d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="repair" id="repair">
                                        Repair
                                    </label>
                                    <label class="btn btn-outline-primary d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="replace" id="replace">
                                        Replace
                                    </label>
                                    <label class="btn btn-outline-primary d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="sent" id="sent">
                                        Sent
                                    </label>
                                    <label class="btn btn-outline-success d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="done" id="done">
                                        Done
                                    </label>
                                </div>
                            </div>
                            <label for="windshield" class="col-sm-1 offset-sm-1 col-form-label">Wheels</label>
                            <div class="form-group col-sm-10">
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-primary d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="repair" id="repair">
                                        Driver Front
                                    </label>
                                    <label class="btn btn-outline-primary d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="replace" id="replace">
                                        Passenger Front
                                    </label>
                                    <label class="btn btn-outline-primary d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="sent" id="sent">
                                        Driver Rear
                                    </label>
                                    <label class="btn btn-outline-success d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="done" id="done">
                                        Passenger Rear
                                    </label>
                                    <label class="btn btn-outline-success d-flex align-items-center m-2 rounded">
                                        <input type="checkbox" name="windshield" value="done" id="done">
                                        Done
                                    </label>
                                </div>
                            </div>
                        </div> -->

                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" data-loading-text="Loading..." class="btn btn-primary mr-2" id="updateInspectBtn">Save Changes</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal9">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Cash To Dealers</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="updateCarsToDealersForm" autocomplete="off" method="post" action="../php_action/updateCarsToDealers.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow spinner-grow1 d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult1">
                        <input type="hidden" name="evehicleId" id="evehicleId">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row align-items-baseline">
                                    <label for="estockno" class="col-sm-3 offset-sm-1 col-form-label">Stock no. Vin</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control" name="estockno" id="estockno" readonly autocomplete="off" autofill="off" />
                                    </div>
                                    <label for="workNeeded" class="col-sm-3 offset-sm-1 col-form-label">Work Needed</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control" name="workNeeded" id="workNeeded" placeholder="Work Needed" autocomplete="off" autofill="off" />
                                    </div>
                                    <label for="recon" class="col-sm-3 offset-sm-1 col-form-label">Notes</label>
                                    <div class="form-group col-sm-8">
                                        <textarea class="form-control autosize" name="notes" id="notes" placeholder="Type Something..."></textarea>
                                    </div>

                                    <label for="dateSent" class="col-sm-3 offset-sm-1 col-form-label">Date Sent</label>
                                    <div class="form-group col-sm-3">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" disabled name="dateSent" placeholder="Select date" id="dateSent">
                                        </div>
                                    </div>
                                    <label for="dateReturn" class="col-sm-2 col-form-label">Date Returned</label>
                                    <div class="form-group col-sm-3">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" disabled name="dateReturn" placeholder="Select date" id="dateReturn">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="esubmittedBy" class="col-form-label">Submitted By</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="esubmittedBy" id="esubmittedBy" readonly autocomplete="off" autofill="off" />
                                </div>
                                <label for="eselectedDetails" class="col-form-label">Vehicle</label>
                                <div class="saleDetailsDiv" id="saleDetailsDiv">
                                    <textarea class="form-control autosize" name="eselectedDetails" style="border:none ;overflow-y: scroll!important;" id="eselectedDetails" readonly placeholder="Type Something..."></textarea>
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
<!-- <script type="text/javascript" src="../custom/js/jquery-ui.min.js"></script> -->
<script type="text/javascript" src="../custom/js/lotwidards.js"></script>