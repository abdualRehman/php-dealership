<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if ($_GET['r'] == 'man') {
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'add') {
    echo "<div class='div-request d-none'>add</div>";
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
                            <button class="btn btn-info mr-2 p-2" onclick="toggleFilterClass()">
                                <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                            </button>
                            <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/manMatrix.php?r=add" class="btn btn-primary mr-2 p-2">
                                <i class="fa fa-plus ml-1 mr-2"></i> Import New File
                            </a>

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

<?php
} else if ($_GET['r'] == 'add') {
?>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Edit Inventory Data</h3>
                            <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/manMatrix.php?r=man" class="btn btn-info">
                                <i class="fa fa-arrow-right"></i>
                                Manage</a>
                        </div>
                        <div class="portlet-body">

                            <div class="form-row p-3">
                                <div class="col-md-3">
                                    <p class="h4">Import Excel File here!</p>
                                </div>
                            </div>
                            <form id="importMatrixForm" autocomplete="off" method="post" action="../php_action/importMatrix.php" enctype="multipart/form-data">
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
                                            <a href="./MATRIX.xlsx" download class="btn btn-success float-right" >Download Format File</a>
                                            <p>The following Excel File column sequence should match the image below.</p>
                                            <code>"Year." "Model" "Trim" "Model #" "NET" , "HP" ...</code>
                                            <hr>
                                            <p class="mb-0">
                                                <img src="excelformat.PNG" alt="format" class="img card-img-top">
                                            </p>
                                        </div>
                                        <button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                        
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
<script type="text/javascript" src="../custom/js/manageMatrix.js"></script>