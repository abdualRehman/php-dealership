<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

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

    /* .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    } */

    #datatable-1 tbody tr td {
        text-align: center;
        padding: 10px;
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 1000px !important;
        }
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 900px;
            margin: 1.75rem auto;
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
        width: -webkit-fill-available !important;
        color: white;
        max-height: 300px;
        overflow-y: hidden;
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

                    <div class="portlet-body" >

                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
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
                                    <th>Misc 1</th>
                                    <th>Misc 2</th>
                                    <th>Misc 3</th>
                                    <th>ColD</th>
                                    <th>MilD</th>
                                    <th>LoyD</th>
                                    <th>ConD</th>
                                    <th>M1D</th>
                                    <th>M2D</th>
                                    <th>M3D</th>
                                    <th>Images</th>
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
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="saleDate" class="col-form-label">Date</label>
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" readonly name="saleDate" placeholder="Select date" id="saleDate">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="customerName" class="col-form-label">Customer Name</label>
                                        <input type="text" class="form-control" id="customerName" readonly name="customerName" placeholder="Customer Name">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="stockNo" class="col-form-label">Stock #</label>
                                        <input type="text" class="form-control" id="stockNo" readonly name="stockNo" placeholder="Stock No">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="vehicle" class="col-form-label">Vehicle</label>
                                        <input type="text" class="form-control" id="vehicle" readonly name="vehicle" placeholder="Vehicle">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="state" class="col-form-label">State</label>
                                        <input type="text" class="form-control" id="state" readonly name="state" placeholder="State">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="display: grid;justify-items: center; align-items: center;">

                                <div>
                                    <p>Max-Upload <strong id="maxLimit">10</strong> </p>
                                    <input type="file" class="form-control-file" id="images" name="images[]" multiple accept="image/*" />
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
                                            <select class="selectpicker" data-live-search="true" id="college" name="college" data-size="5">
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
                                            <input type="text" class="form-control datePicker" name="collegeDate" placeholder="Select date" id="collegeDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Military</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" data-live-search="true" id="military" name="military" data-size="5">
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
                                            <input type="text" class="form-control datePicker" name="militaryDate" placeholder="Select date" id="militaryDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Loyalty</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" data-live-search="true" id="loyalty" name="loyalty" data-size="5">
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
                                            <input type="text" class="form-control datePicker" name="loyaltyDate" placeholder="Select date" id="loyaltyDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Conquest</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
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
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="conquestDate" placeholder="Select date" id="conquestDate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Misc 1</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker" data-live-search="true" id="misc1" name="misc1" data-size="5">
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
                                            <input type="text" class="form-control datePicker" name="misc1Date" placeholder="Select date" id="misc1Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Misc 2</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
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
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="misc2Date" placeholder="Select date" id="misc2Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="vincheck" class="col-md-2 col-form-label text-center">Misc 3</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
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
                                    <div class="col-md-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control datePicker" name="misc3Date" placeholder="Select date" id="misc3Date">
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





<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/incentives.js"></script>