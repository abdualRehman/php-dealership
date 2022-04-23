<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';


if ($_GET['r'] == 'man') {
    if ( hasAccess("manprice", "Add") === 'false' && hasAccess("manprice", "Edit") === 'false' && hasAccess("manprice", "Remove") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'add') {
    if (hasAccess("manprice", "Add") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>add</div>";
} // /else manage order


?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.min.css" rel="stylesheet" />
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

    .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    }

    #datatable-1 tbody tr td {
        padding: 10px;
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
                            <h3 class="portlet-title">New Price By Manufacture</h3>
                            <button class="btn btn-info mr-2 p-2" onclick="toggleFilterClass()">
                                <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                            </button>
                            <?php
                            if (hasAccess("manprice", "Add") !== 'false') {
                            ?>
                                <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/manufaturePrice.php?r=add" class="btn btn-primary mr-2 p-2">
                                    <i class="fa fa-plus ml-1 mr-2"></i> Import New File
                                </a>
                            <?php
                            }
                            ?>

                        </div>
                        <div class="portlet-body">

                            <table id="datatable-1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <button style="border: none; background: transparent; font-size: 14px;" id="MyTableCheckAllButton">
                                                <i class="far fa-square"></i><br> Select
                                            </button>
                                        </th>
                                        <th>Year</th>
                                        <th>Model</th>
                                        <th>Model Code</th>
                                        <th>MSRP</th>
                                        <th>Dlr Inv</th>
                                        <th>Model Description</th>
                                        <th>Trim</th>
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
                    <h5 class="modal-title">Edit Details</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="editForm" autocomplete="off" method="post" action="../php_action/editManufaturePrice.php">
                    <input type="hidden" name="manId" id="manId">
                    <div class="modal-body">

                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="year">Year</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="year" name="year">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3"><label for="model">Model</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="model" name="model">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3"><label for="modelCode">Model Code</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="modelCode" name="modelCode">
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="msrp">MSRP</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="msrp" name="msrp">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="dlrInv">Dlr Inv.</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="dlrInv" name="dlrInv">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="modelDescription">Model Description</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="modelDescription" name="modelDescription">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="trim">Trim</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="trim" name="trim">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-bordered">
                        <button class="btn btn-primary mr-2" type="submit">Update</button>
                        <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
} else if ($_GET['r'] == 'add') {
?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Manufacture Price</h3>
                            <div class="portlet-addon mr-2">
                                <div class="nav nav-pills portlet-nav" id="portlet1-tab">
                                    <a class="nav-item nav-link active" id="portlet1-add-tab" data-toggle="tab" href="#portlet1-add">Add Manufacture Price</a>
                                    <a class="nav-item nav-link" id="portlet2-import-tab" data-toggle="tab" href="#portlet2-import">Import CSV</a>
                                </div>
                            </div>

                            <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/manufaturePrice.php?r=man" class="btn btn-outline-info">
                                <i class="fa fa-arrow-right"></i>
                                Manage
                            </a>
                        </div>
                        <div class="portlet-body">

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="portlet1-add">

                                    <form id="addForm" autocomplete="off" method="post" action="../php_action/createManufacturePrice.php">
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="year">Year</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="year" name="year">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3"><label for="model">Model</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="model" name="model">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3"><label for="modelCode">Model Code</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="modelCode" name="modelCode">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-3 mb-3">
                                                <label for="msrp">MSRP</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="msrp" name="msrp">
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="dlrInv">Dlr Inv.</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="dlrInv" name="dlrInv">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="modelDescription">Model Description</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="modelDescription" name="modelDescription">
                                                </div>
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
                                    <form id="importManForm" autocomplete="off" method="post" action="../php_action/importManufacturePrice.php" enctype="multipart/form-data">
                                        <div class="form-row pl-3">

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
                                                    <a href="./files/New_Car_Pricing_From_Manufacture.xlsx" download class="btn btn-success float-right">Download Format File</a>
                                                    <p>The following Excel File column sequence should match the image below.</p>
                                                    <code>"Year" "Model" "Model Code" "MSRP" "Dlr Inv" ...</code>
                                                    <hr>
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
    </div>

<?php
}
?>

<?php require_once('../includes/footer.php') ?>

<script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.js"></script>
<script type="text/javascript" src="../custom/js/manufacturePrice.js"></script>