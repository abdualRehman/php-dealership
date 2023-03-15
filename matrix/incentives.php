<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("incentives", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
$isEditAllowed = false;
if (hasAccess("incentives", "Edit") === 'false') {
    $isEditAllowed = false;
} else {
    $isEditAllowed = true;
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

    .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    }

    .DTFC_RightWrapper {
        right: 0px !important;
    }

    #datatable-1 tbody tr td {
        text-align: center;
        padding: 10px;
    }

    .slick-lightbox-slick-item-inner>img {
        height: 400px !important;
    }

    div.dataTables_scrollHeadInner {
        width: inherit !important;
    }

    @media (max-width: 1025px) {
        .slick-lightbox-slick-item-inner>img {
            height: 400px !important;
        }
    }


    @media (max-width: 576px) {
        .slick-lightbox-slick-item-inner>img {
            height: 200px !important;
        }
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 900px;
            margin: 1.75rem auto;
        }
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1100px !important;
        }
    }

    .border-red {
        border: 1px solid red;
    }

    #datatable-1 thead tr {
        text-align: center;
    }


    /* ----------------- Slick ----------------------  */
    .slick-next,
    .slick-prev {
        bottom: 0px;
    }

    .carousel-item {
        position: relative;
        text-align: center;
        width: -webkit-fill-available !important;
        color: white;
        max-height: 300px;
        overflow-y: hidden;
    }

    .carousel-item img {
        width: 95%;
    }

    .carousel-item .card {
        position: absolute;
        top: 1px;
        right: 3px;
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

    .slick-slide img {
        height: 80px;
        width: 100%;
        object-fit: cover;
        /* min-width: fit-content!important; */
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
                        <h3 class="portlet-title">Incentives list</h3>
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
                                    <input type="text" class="form-control" placeholder="Sold Date" id="soldFilter" />
                                </div>
                                <div class="col p-1">
                                    <select class="form-control filterTags" id="customerFilter" multiple="multiple">
                                        <optgroup label="Customer">
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
                            </div>
                        </div>
                        <table id="datatable-1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sold Date</th>
                                    <th>Customer</th>
                                    <th>Stock #</th>
                                    <th>Vehicle</th>
                                    <!-- <th>State</th> -->
                                    <th>College</th>
                                    <th>Military</th>
                                    <th>Loyalty</th>
                                    <th>Conquest</th>
                                    <th>Lease Loyalty</th>
                                    <th>Right to Repair</th>
                                    <th>Misc 2</th>
                                    <th>ColD</th>
                                    <th>MilD</th>
                                    <th>LoyD</th>
                                    <th>ConD</th>
                                    <th>M1D</th>
                                    <th>M2D</th>
                                    <th>M3D</th>
                                    <th>Images</th>
                                    <th>Action</th>
                                    <th>Sale Status</th>
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
                <h5 class="modal-title">Edit Incentive</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>

            <form id="editSoldTodoForm" autocomplete="off" method="post" action="../php_action/editIncentive.php" enctype="multipart/form-data">
                <input type="hidden" name="incentiveId" id="incentiveId">
                <div class="modal-body w-100" style="display: inline-table;">
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult">
                        <div class="form-row flex-column-reverse flex-md-row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="sale_status" class="col-form-label text-md-right">Status</label>
                                        <div class="<?php echo ($salesConsultantID != $_SESSION['userRole'] && hasAccess("sale", "Edit") !== 'false') ?: "makeDisable"; ?>">
                                            <div class="form-group">
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-flat-primary d-flex align-items-center responsive-content">
                                                        <input type="radio" name="sale_status" value="pending" id="pending_status">
                                                        <i class="fa fa-clock pr-1"></i> Pending
                                                    </label>
                                                    <label class="btn btn-flat-success d-flex align-items-center responsive-content">
                                                        <input type="radio" name="sale_status" value="delivered" id="delivered_status">
                                                        <i class="fa fa-check pr-1"></i> Delivered
                                                    </label>

                                                    <label class="btn btn-flat-danger d-flex align-items-center responsive-content">
                                                        <input type="radio" name="sale_status" value="cancelled" id="cancelled_status">
                                                        <i class="fa fa-times pr-1"></i> Cancelled
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 <?php echo ($salesConsultantID != $_SESSION['userRole'] && hasAccess("sale", "Edit") !== 'false') ?: "makeDisable"; ?>">
                                        <label class="col-form-label" for="dealNote">Deal Notes</label>
                                        <div class="form-group">
                                            <textarea class="form-control autosize" name="dealNote" id="dealNote" placeholder="Deal Notes..."></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
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
                                    <div class="form-group col-md-4">
                                        <label for="salesConsultant" class="col-form-label">Sales Consultant</label>
                                        <input type="text" class="form-control" id="salesConsultant" readonly name="salesConsultant" placeholder="Sales Consultant">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="stockNo" class="col-form-label">Stock #</label>
                                        <input type="text" class="form-control" id="stockNo" readonly name="stockNo" placeholder="Stock No">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="vehicle" class="col-form-label">Vehicle</label>
                                        <input type="text" class="form-control" id="vehicle" readonly name="vehicle" placeholder="Vehicle">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="vin" class="col-form-label">Vin</label>
                                        <input type="text" class="form-control" style="padding: 5px!important;" id="vin" readonly name="vin" placeholder="Vin">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="state" class="col-form-label">State</label>
                                        <input type="text" class="form-control" id="state" readonly name="state" placeholder="State">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="display: grid;justify-items: center; align-items: center;">

                                <div>
                                    <p>Max-Upload <strong id="maxLimit">10</strong> </p>
                                    <input type="file" class="form-control-file" id="images" name="images[]" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> multiple accept="image/*" />
                                </div>
                                <div class="carousel slick-2" id="slickSlider" style="overflow: hidden">

                                </div>
                            </div>
                        </div>

                        <h5 class="my-4">Sales Incentives</h5>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">College</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" onchange="checkValue(this)" data-live-search="true" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> id="college" name="college" data-size="5">
                                                <optgroup>
                                                    <option>No</option>
                                                    <option>Yes</option>
                                                </optgroup>
                                                <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="collegeDate" <?php echo ($isEditAllowed == true ?: 'readonly'); ?> placeholder="Select date" id="collegeDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Military</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" onchange="checkValue(this)" data-live-search="true" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> id="military" name="military" data-size="5">
                                                <optgroup>
                                                    <option>No</option>
                                                    <option>Yes</option>
                                                </optgroup>
                                                <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="militaryDate" placeholder="Select date" <?php echo ($isEditAllowed == true ?: 'readonly'); ?> id="militaryDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Loyalty</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" onchange="checkValue(this)" data-live-search="true" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> id="loyalty" name="loyalty" data-size="5">
                                                <optgroup>
                                                    <option>No</option>
                                                    <option>Yes</option>
                                                </optgroup>
                                                <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="loyaltyDate" placeholder="Select date" <?php echo ($isEditAllowed == true ?: 'readonly'); ?> id="loyaltyDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Conquest</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" onchange="checkValue(this)" data-live-search="true" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> id="conquest" name="conquest" data-size="5">
                                                <optgroup>
                                                    <option>No</option>
                                                    <option>Yes</option>
                                                </optgroup>
                                                <optgroup class="salesManagerList" label="YES/APPROVED BY">

                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="conquestDate" placeholder="Select date" <?php echo ($isEditAllowed == true ?: 'readonly'); ?> id="conquestDate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Lease Loyalty</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" onchange="checkValue(this)" data-live-search="true" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> id="leaseLoyalty" name="leaseLoyalty" data-size="5">
                                                <optgroup>
                                                    <option>No</option>
                                                    <option>Yes</option>
                                                </optgroup>
                                                <optgroup class="salesManagerList" label="YES/APPROVED BY">
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="leaseLoyaltyDate" placeholder="Select date" <?php echo ($isEditAllowed == true ?: 'readonly'); ?> id="leaseLoyaltyDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Right to Repair</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" onchange="checkValue(this)" data-live-search="true" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> id="misc1" name="misc1" data-size="5">
                                                <optgroup>
                                                    <option>No</option>
                                                    <option>Yes</option>
                                                </optgroup>
                                                <optgroup class="salesManagerList" label="YES/APPROVED BY">
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="misc1Date" placeholder="Select date" <?php echo ($isEditAllowed == true ?: 'readonly'); ?> id="misc1Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Misc 2</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" onchange="checkValue(this)" data-live-search="true" <?php echo ($isEditAllowed == true ?: 'disabled'); ?> id="misc2" name="misc2" data-size="5">
                                                <optgroup>
                                                    <option>No</option>
                                                    <option>Yes</option>
                                                </optgroup>
                                                <optgroup class="salesManagerList" label="YES/APPROVED BY">
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="misc2Date" placeholder="Select date" <?php echo ($isEditAllowed == true ?: 'readonly'); ?> id="misc2Date">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if ($isEditAllowed == true) {
                ?>
                    <div class="modal-footer modal-footer-bordered">
                        <button type="submit" class="btn btn-primary mr-2" id="updateBtn">Save Changes</button>
                        <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                    </div>
                <?php
                }
                ?>
            </form>

        </div>
    </div>
</div>





<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/incentives.js"></script>