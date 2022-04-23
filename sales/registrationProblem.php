<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("regp", "Add") === 'false' && hasAccess("regp", "Edit") === 'false' && hasAccess("regp", "Remove") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>

<style>
    .custom-checkbox {
        display: inline-grid;
        margin: auto;
        align-items: center;
    }

    #datatable-1 tbody tr td {
        padding: 10px 6px;
    }

    .dropdown-header.optgroup-1 {
        padding: 0px !important;
    }

    .tt-is-under-cursor {
        background-color: #c9c8c8 !important;
        color: #FFFFFF !important;
    }

    .tt-cursor {
        background-color: #c9c8c8 !important;
        color: #FFFFFF !important;
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Registration Problems</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("regp", "Add") !== 'false') {
                        ?>
                            <button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                                <i class="fa fa-plus ml-1 mr-2"></i> Create New
                            </button>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Contract Date</th>
                                    <th>Age</th>
                                    <th>Problem Date</th>
                                    <th>Customer Name</th>
                                    <th>Sales Consultant</th>
                                    <th>Finance Manager</th>
                                    <th>Vehicle</th>
                                    <th>Problem</th>
                                    <th>Sales Consultant Notes</th>
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


<div class="modal fade" id="modal8">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Matrix Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editProblemForm" action="../php_action/editRegistrationProblem.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="problemId" id="problemId">
                        <div class="form-row">
                            <label for="econtractDate" class="col-sm-2 col-form-label text-md-center">Contract Date:</label>
                            <div class="col-sm-4">
                                <div class="form-group input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control setDate" name="econtractDate" placeholder="Select date" id="econtractDate">
                                </div>
                            </div>
                            <label for="eproblemDate" class="col-sm-2 col-form-label text-md-center">Problem Date:</label>
                            <div class="col-sm-4">
                                <div class="form-group input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control setDate" name="eproblemDate" placeholder="Select date" id="eproblemDate">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="ecustomerName" class="col-form-label text-md-center">Customer Name:</label>
                                <div class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="ecustomerName" name="ecustomerName" placeholder="Customer Name*">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label" for="esalesConsultant">Sales Consultant:</label>
                                <div class="form-group">
                                    <select class="selectpicker" name="esalesConsultant" id="esalesConsultant" data-live-search="true" data-size="4">
                                        <optgroup class="salesConsultantList">
                                            <option value="0" selected disabled>Sales Consultant</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label" for="efinanceManager">Finance Manager:</label>
                                <div class="form-group">
                                    <select class="selectpicker" name="efinanceManager" id="efinanceManager" data-live-search="true" data-size="4">
                                        <optgroup class="financeManagerList">
                                            <option value="0" selected disabled>Finance Manager</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="estockId">Stock No.</label>
                                <div class="form-group">
                                    <!-- <select class="selectpicker" onchange="changeStockDetails(this , 'evehicle')" name="estockId" id="estockId" data-live-search="true" data-size="4">
                                        <optgroup class="stockIdList">
                                            <option value="0" selected disabled>Stock No:</option>
                                        </optgroup>
                                    </select> -->
                                    <input type="text" class="form-control" id="estockId" name="estockId" placeholder="Stock No.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="evehicle">Vehicle</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="evehicle" name="evehicle" placeholder="Vehicle">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="eproblem">Problem</label>
                                <div class="form-group">
                                    <textarea class="form-control autosize" name="eproblem" id="eproblem" placeholder="Problem..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="enotes">Sales Consultant Notes</label>
                                <div class="form-group">
                                    <textarea class="form-control autosize" name="enotes" id="enotes" placeholder="Sales Consultant Notes..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-primary mr-2">Update Changes</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Registration Problem</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewProblem" autocomplete="off" method="post" action="../php_action/createRegistrationProblem.php">
                <!-- <form id="addNewProblem" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <div class="form-row">
                        <label for="contractDate" class="col-sm-2 col-form-label text-md-center">Contract Date:</label>
                        <div class="col-sm-4">
                            <div class="form-group input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control setDate" name="contractDate" placeholder="Select date" id="contractDate">
                            </div>
                        </div>
                        <label for="problemDate" class="col-sm-2 col-form-label text-md-center">Problem Date:</label>
                        <div class="col-sm-4">
                            <div class="form-group input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control setDate" name="problemDate" placeholder="Select date" id="problemDate">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <label for="customerName" class="col-form-label text-md-center">Customer Name:</label>
                            <div class="form-group">
                                <input type="text" class="form-control typeahead typeahead1" id="customerName" name="customerName" placeholder="Customer Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label" for="salesConsultant">Sales Consultant:</label>
                            <div class="form-group">
                                <select class="selectpicker" name="salesConsultant" id="salesConsultant" data-live-search="true" data-size="4">
                                    <optgroup class="salesConsultantList">
                                        <option value="0" selected disabled>Sales Consultant</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label" for="financeManager">Finance Manager:</label>
                            <div class="form-group">
                                <select class="selectpicker" name="financeManager" id="financeManager" data-live-search="true" data-size="4">
                                    <optgroup class="financeManagerList">
                                        <option value="0" selected disabled>Finance Manager</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="stockId">Stock No.</label>
                            <div class="form-group">
                                <!-- <select class="selectpicker" onchange="changeStockDetails(this , 'vehicle')" name="stockId" id="stockId" data-live-search="true" data-size="4">
                                    <optgroup class="stockIdList">
                                        <option value="0" selected disabled>Stock No:</option>
                                    </optgroup>
                                </select> -->
                                <input type="text" class="form-control" id="stockId" name="stockId" placeholder="Stock No.">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="vehicle">Vehicle</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="vehicle" name="vehicle" placeholder="Vehicle">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="problem">Problem</label>
                            <div class="form-group">
                                <textarea class="form-control autosize" name="problem" id="problem" placeholder="Problem..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="notes">Sales Consultant Notes</label>
                            <div class="form-group">
                                <textarea class="form-control autosize" name="notes" id="notes" placeholder="Sales Consultant Notes..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<!-- <script type="text/javascript" src="../custom/js/matrixRules.js"></script> -->
<script type="text/javascript" src="../custom/js/registrationProblem.js"></script>