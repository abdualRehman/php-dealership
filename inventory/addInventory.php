<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("inventory", "Add") === 'false' ) {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
?>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Add Inventory</h3>
                        <div class="portlet-addon">
                            <div class="nav nav-tabs portlet-nav" id="portlet1-tab">
                                <a class="nav-item nav-link active" id="portlet1-add-tab" data-toggle="tab" href="#portlet1-add">Add Inventory</a>
                                <a class="nav-item nav-link" id="portlet2-import-tab" data-toggle="tab" href="#portlet2-import">Import CSV</a>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="portlet1-add">

                                <form id="addInvForm" autocomplete="off" method="post" action="../php_action/createInv.php">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="stockno">Stock No.</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="stockno" name="stockno">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3"><label for="year">Year</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="year" name="year">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3"><label for="make">Make</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="make" name="make">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 mb-3">
                                            <label for="model">Model</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="model" name="model">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="modelno">Model No.</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="modelno" name="modelno">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="color">Color</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="color" name="color">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="lot">Lot</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="lot" name="lot">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="vin">VIN</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="vin" name="vin">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="mileage">Mileage</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="mileage" name="mileage">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="age">Age</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="age" name="age">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-row">

                                        <div class="col-md-4 mb-3">
                                            <label for="balance">Balance</label>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="balance" name="balance">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="retail">Retail</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="retail" name="retail">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="stockType">Stock Type</label>
                                            <div class="form-group">
                                                <select class="custom-select w-100" id="stockType" name="stockType">
                                                    <option value="0" selected="selected" disabled="disabled">Choose...
                                                    </option>
                                                    <option value="NEW">NEW</option>
                                                    <option value="USED">USED</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group d-flex">
                                        <div class="custom-control custom-checkbox mr-5">
                                            <input type="checkbox" class="custom-control-input" id="certified" name="certified">
                                            <label class="custom-control-label" for="certified">Certified</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="wholesale" name="wholesale">
                                            <label class="custom-control-label" for="wholesale">Wholesale</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                </form>

                            </div>
                            <div class="tab-pane fade" id="portlet2-import">

                                <div class="form-row p-3">
                                    <div class="col-md-3">
                                        <p class="h4">Import Excel File here!</p>
                                    </div>
                                </div>
                                <form id="importInvForm" autocomplete="off" method="post" action="../php_action/importInv.php" enctype="multipart/form-data">
                                    <div class="form-row pl-3">
                                        <!-- <div class="custom-file col-md-6 form-group">
                                            <input type="file" class="custom-file-input" id="excelFile1" name="excelFile1">
                                            <label class="custom-file-label" for="excelFile1">Choose
                                                file</label>
                                        </div> -->
                                        <div class="form-group mb-0">
                                            <input type="file" class="form-control-file" id="excelFile" name="excelFile" />
                                        </div>

                                        <div class="col-md-6">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="row p-3 d-none" id="errorDiv">
                                    <div class="col-md-12 mb-3">
                                        <button class="btn btn-danger float-right" onclick="clearErrorsList()">Clear Logs</button>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="list-group list-group-action" id="errorList">

                                        </div>
                                    </div>
                                </div>

                                <div class="row p-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-outline-info fade show mb-0">
                                            <div class="alert-icon"><i class="fa fa-info"></i></div>
                                            <div class="alert-content">
                                                <h4 class="alert-heading">Please Note!</h4>
                                                <p>The following Excel File column sequence should match the image below.</p>
                                                <code>"Stock No." "Year" "Make" "Model" "Model No." ...</code>
                                                <hr>
                                                <p class="mb-0">
                                                    <img src="excelformat.PNG" alt="format" class="img card-img-top">
                                                </p>
                                            </div><button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <?php require_once('../includes/footer.php') ?>
    <script type="text/javascript" src="../custom/js/addInventory.js"></script>