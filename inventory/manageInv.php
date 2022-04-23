<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if ($_GET['r'] == 'man') {

    if (hasAccess("inventory", "Edit") === 'false' && hasAccess("inventory", "Remove") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'edit') {

    if (hasAccess("inventory", "Edit") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>edit</div>";
} // /else manage order

?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>


<?php

if ($_GET['r'] == 'man') {

?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Manage Inventory</h3>
                            <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                                <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                            </button>

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
                                        <th>Stock No.</th>
                                        <th>Year</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Model No.</th>
                                        <th>Stock Type</th>
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

    <div class="modal fade" id="showDetails">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="stockno">Stock No.</label>
                                <input type="text" class="form-control" id="stockno" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="year">Year</label>
                                <input type="text" class="form-control" id="year" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="make">Make</label>
                                <input type="text" class="form-control" id="make" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="model">Model</label>
                                <input type="text" class="form-control" id="model" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="modelno">Model No</label>
                                <input type="text" class="form-control" id="modelno" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="color">Color</label>
                                <input type="text" class="form-control" id="color" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lot">Lot</label>
                                <input type="text" class="form-control" id="lot" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="vin">Vin</label>
                                <input type="text" class="form-control" id="vin" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="mileage">Mileage</label>
                                <input type="text" class="form-control" id="mileage" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="age">Age</label>
                                <input type="text" class="form-control" id="age" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="balance">Balance</label>
                                <input type="text" class="form-control" id="balance" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="retail">Retail</label>
                                <input type="text" class="form-control" id="retail" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="stockType">Stock Type</label>
                                <input type="text" class="form-control" id="stockType" disabled>
                            </div>
                            <div class="col-md-3 mb-1 d-flex align-items-end p-2">
                                <label class="p-2" for="certified">Certified</label>
                                <label class="form-control" id="certified"></label>
                            </div>
                            <div class="col-md-3 mb-1 d-flex align-items-end p-2 ">
                                <label class="p-2" for="wholesale">Wholesale</label>
                                <label class="form-control" id="wholesale"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?php echo $GLOBALS['siteurl']; ?>/inventory/manageInv.php?r=edit&i=" class="btn btn-primary mr-2" id="editBtn">Edit</a>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<?php
} else if ($_GET['r'] == 'edit') {
?>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Edit Inventory Data</h3>
                            <a href="<?php echo $GLOBALS['siteurl']; ?>/inventory/manageInv.php?r=man" class="btn btn-secondary">
                                <i class="fa fa-arrow-right"></i>
                                Go Back</a>
                        </div>
                        <div class="portlet-body">

                            <form id="editInvForm" autocomplete="off" method="post" action="../php_action/editInv.php">
                                <div class="text-center">
                                    <div class="spinner-grow d-none" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                                </div>
                                <div class="showResult">
                                    <input type="hidden" name="invId" id="invId" value="<?php echo $_GET["i"]; ?>">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3"><label for="stockno">Stock No.</label>
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

                                </div>
                            </form>



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
<script type="text/javascript" src="../custom/js/manageInv.js"></script>