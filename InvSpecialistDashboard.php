<?php

if (hasAccess("invsplst", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
?>

<style>
    .dataTables_scrollHead,
    .dataTables_scrollHeadInner {
        max-width: 100% !important;
    }

    .dataTables_scroll {
        overflow: auto !important;
    }

    .clear-selection {
        text-decoration: underline;
        cursor: pointer;
    }

    .text-oriange {
        color: #f2752d !important;
    }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row m-auto">
            <div class="col-4">

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <div class="text-center m-auto justify-content-center">
                            <div class="portlet m-0">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="done-percentage"><span></span> %</h2>
                                            <span class="widget10-subtitle">
                                                Percent Done
                                            </span>
                                        </div>
                                        <div class="widget10-addon">
                                            <div class="avatar avatar-label-info avatar-circle widget8-avatar m-0">
                                                <div class="avatar-display"><i class="fa fa-percent"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="inspectionTable">
                            <!-- <div class="form-row text-left">
                                <div class="col-md-12 p-1 pr-2">
                                    <button class="btn btn-primary p-2" onclick="toggleFilterClass2()">
                                        <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                                    </button>
                                </div>
                            </div> -->
                            <table id="datatable-1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Stock no.</th>
                                        <th>Vin</th>
                                        <th>Age</th>
                                        <th>Year</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Notes 1</th>
                                        <th>Notes 2</th>
                                        <th>UCI</th>
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
                <h5 class="modal-title">Used Cars</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="updateUsedCarsForm" autocomplete="off" method="post" action="./php_action/updateUsedCarsNotes2.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="vehicleId" id="vehicleId" />
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row align-items-baseline">
                                    <label for="stockno" class="col-sm-3 offset-sm-1 col-form-label">Stock no. Vin</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control" name="stockno" id="stockno" readonly autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="notes_1" class="col-form-label text-center">Notes 1</label>
                                        <div class="form-group">
                                            <select class="selectpicker" name="notes_1" id="notes_1">
                                                <option value="" selected disabled hidden>Please select</option>
                                                <option value="Missing Section">Missing Section</option>
                                                <option value="Locked">Locked</option>
                                                <option value="Sold">Sold</option>
                                                <option value="Packages">Packages</option>
                                                <option value="Wholesale">Wholesale</option>
                                            </select>
                                            <div class="p-1 pb-0">
                                                <span class="badge-text-primary clear-selection" id="clear-selection" data-id="notes_1">Clear Selection</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="notes_2" class="col-form-label text-center">Notes 2</label>
                                        <div class="form-group">
                                            <select class="selectpicker" name="notes_2" id="notes_2">
                                                <option value="" selected disabled hidden>Please select</option>
                                                <option value="RO not Closed">RO not Closed</option>
                                                <option value="Description RO is done">Description RO is done</option>
                                                <option value="Wholesale">Wholesale</option>
                                                <option value="Sold">Sold</option>
                                                <option value="Done">Done</option>
                                            </select>
                                            <div class="p-1 pb-0">
                                                <span class="badge-text-primary clear-selection" id="clear-selection" data-id="notes_2">Clear Selection</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <label for="uci" class="col-form-label text-center">UCI</label>
                                        <div class="form-group">
                                            <select class="selectpicker" name="uci" id="uci">
                                                <option value="" selected disabled hidden>Please select</option>
                                                <option value="need">Need</option>
                                                <option value="opened">Opened</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                            <div class="p-1 pb-0">
                                                <span class="badge-text-primary clear-selection" id="clear-selection" data-id="uci">Clear Selection</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="form-group col-sm-12 d-none">
                                    <select class="selectpicker d-none" name="salesConsultant" id="salesConsultant" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Sales Consultant</option>
                                    </select>
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

<?php include('includes/footer.php') ?>
<script type="text/javascript" src="./custom/js/InvSpecialistDashboard.js"></script>