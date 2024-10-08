<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("leaserule", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("leaserule", "Edit") !== 'false') {
    echo '<input type="hidden" name="isAllowed" id="isAllowed" value="true" />';
} else {
    echo '<input type="hidden" name="isAllowed" id="isAllowed" value="false" />';
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

    .DTFC_RightBodyLiner {
        width: 100% !important;
        overflow-x: hidden;
        overflow-y: auto !important;
    }

    #datatable-1 tbody tr td {
        padding: 10px;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 600px;
            margin: 1.75rem auto;
        }

        .modal-dialog table.detialsTable {
            width: max-content;
        }
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
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Lease Rule List</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("leaserule", "Add") !== 'false') {
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Set New Rule
                        </button>';
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#importNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Import File
                        </button>';
                        }
                        ?>

                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Year</th>
                                    <th>Model no.</th>
                                    <th>Exclude Model No</th>
                                    <th>Expire In</th>
                                    <th>24</th>
                                    <th>27</th>
                                    <th>30</th>
                                    <th>33</th>
                                    <th>36</th>
                                    <th>39</th>
                                    <th>42</th>
                                    <th>45</th>
                                    <th>48</th>
                                    <th>51</th>
                                    <th>54</th>
                                    <th>57</th>
                                    <th>60</th>
                                    <th>Action</th>
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


<div class="modal fade" id="modal8">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Lease Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editLeaseRule.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="ruleId" id="ruleId">
                        <br>
                        <h3 class="h4">Stock Details:</h3>
                        <table class="table" id="productTable1">
                            <thead>
                                <tr>
                                    <th style="width:20%;text-align:center">Model</th>
                                    <th style="width:20%;text-align:center">Year</th>
                                    <th style="width:20%;text-align:center">Model No.</th>
                                    <th style="width:20%;text-align:center">Exclude Model No.</th>
                                    <th style="width:20%;text-align:center">Expire In</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModel" name="editModel" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>Select Model</option>
                                            <option value="All">All</option>
                                            <?php
                                            $sql = "SELECT model FROM `manufature_price` WHERE status = 1 GROUP BY model";
                                            $result = $connect->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_array()) {
                                                    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                                }
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editYear" name="editYear" placeholder="Year">
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editModelno" name="editModelno" placeholder="Model No.">
                                    </td>
                                    <td class="form-group">
                                        <!-- <input type="text" class="form-control" id="editExModelno" name="editExModelno" placeholder="Exclude Model No."> -->
                                        <select class="form-control select21" id="editExModelno" name="editExModelno[]" multiple="multiple" title="Exclude Model No.">
                                            <optgroup label="Press Enter to add">

                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control" id="editExpireIn" name="editExpireIn" placeholder="Expire In.">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <div class="form-row mt-4 mb-4">
                            <div class="col-md-6">
                                <h3 class="h6 mb-3">12,000 Miles Per Year </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="float-label">
                                            <input type="text" id="e12_24_33" name="e12_24_33" class="form-control" placeholder=" ">
                                            <label for="e12_24_33">24-33 -%</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="float-label">
                                            <input type="text" id="e12_36_48" name="e12_36_48" class="form-control" placeholder=" ">
                                            <label for="e12_36_48">36-48 -%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="h6 mb-3">10,000 Miles Per Year </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="float-label">
                                            <input type="text" id="e10_24_33" name="e10_24_33" class="form-control" placeholder=" ">
                                            <label for="e10_24_33">24-33 -%</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="float-label">
                                            <input type="text" id="e10_36_48" name="e10_36_48" class="form-control" placeholder=" ">
                                            <label for="e10_36_48">36-48 -%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h3 class="h5">Rule Details: 15,000 - %</h3>
                        <div class="form-row" style="overflow-x: auto;">
                            <div class="col-md-12">
                                <table class="table table-bordered detialsTable">
                                    <thead>
                                        <tr>
                                            <th>24</th>
                                            <th>27</th>
                                            <th>30</th>
                                            <th>33</th>
                                            <th>36</th>
                                            <th>39</th>
                                            <th>42</th>
                                            <th>45</th>
                                            <th>48</th>
                                            <th>51</th>
                                            <th>54</th>
                                            <th>57</th>
                                            <th>60</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-warning">
                                        <tr>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e24" name="e24" placeholder="24">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e27" name="e27" placeholder="27">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e30" name="e30" placeholder="30">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e33" name="e33" placeholder="33">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e36" name="e36" placeholder="36">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e39" name="e39" placeholder="39">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e42" name="e42" placeholder="42">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e45" name="e45" placeholder="45">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e48" name="e48" placeholder="48">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e51" name="e51" placeholder="51">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e54" name="e54" placeholder="54">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e57" name="e57" placeholder="57">
                                            </th>
                                            <th class="text-center ">
                                                <input type="text" class="form-control" id="e60" name="e60" placeholder="60">
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Update Changes</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Rule</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createLeaseRule.php">
                <!-- <form id="addNewRule" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <h3 class="h4">Stock Details:</h3>
                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:25%;text-align:center">Model</th>
                                <th style="width:20%;text-align:center">Year</th>
                                <th style="width:15%;text-align:center">Model No.</th>
                                <th style="width:15%;text-align:center">Exclude Model No.</th>
                                <th style="width:15%;text-align:center">Expire In</th>
                                <th style="width:10%;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="model1" name="model[]" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select Model</option>
                                        <option value="All">All</option>
                                        <?php
                                        $sql = "SELECT model FROM `manufature_price` WHERE status = 1 GROUP BY model";
                                        $result = $connect->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_array()) {
                                                echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                        }
                                        ?>

                                    </select>
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="year1" name="year[]" placeholder="Year">
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="modelno1" name="modelno[]" placeholder="Model No.">
                                </td>
                                <td class="form-group">
                                    <!-- <input type="text" class="form-control select2 select21" id="exModelno1" name="exModelno[]" placeholder="Exclude Model No."> -->
                                    <select class="form-control select21" id="exModelno1" name="exModelno1[]" multiple="multiple" title="Exclude Model No.">
                                        <optgroup label="Press Enter to add">
                                    </select>
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control" id="expireIn1" name="expireIn[]" placeholder="Expire In.">
                                </td>
                                <td class="form-group text-center">
                                    <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <hr>
                    <div class="form-row mt-4 mb-4">
                        <div class="col-md-6">
                            <h3 class="h6 mb-3">12,000 Miles Per Year </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="12_24_33" value="1" name="12_24_33" class="form-control" placeholder=" ">
                                        <label for="12_24_33">24-33 -%</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="12_36_48" value="2" name="12_36_48" class="form-control" placeholder=" ">
                                        <label for="12_36_48">36-48 -%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6 mb-3">10,000 Miles Per Year </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="10_24_33" value="2" name="10_24_33" class="form-control" placeholder=" ">
                                        <label for="10_24_33">24-33 -%</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="10_36_48" value="3" name="10_36_48" class="form-control" placeholder=" ">
                                        <label for="10_36_48">36-48 -%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h3 class="h5">Rule Details: 15,000 - %</h3>
                    <div class="form-row" style="overflow-x: auto;">
                        <div class="col-md-12">
                            <table class="table table-bordered detialsTable">
                                <thead>
                                    <tr>
                                        <th>24</th>
                                        <th>27</th>
                                        <th>30</th>
                                        <th>33</th>
                                        <th>36</th>
                                        <th>39</th>
                                        <th>42</th>
                                        <th>45</th>
                                        <th>48</th>
                                        <th>51</th>
                                        <th>54</th>
                                        <th>57</th>
                                        <th>60</th>
                                    </tr>
                                </thead>
                                <tbody class="table-warning">
                                    <tr>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="24" name="24" placeholder="24">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="27" name="27" placeholder="27">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="30" name="30" placeholder="30">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="33" name="33" placeholder="33">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="36" name="36" placeholder="36">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="39" name="39" placeholder="39">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="42" name="42" placeholder="42">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="45" name="45" placeholder="45">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="48" name="48" placeholder="48">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="51" name="51" placeholder="51">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="54" name="54" placeholder="54">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="57" name="57" placeholder="57">
                                        </th>
                                        <th class="text-center ">
                                            <input type="text" class="form-control" id="60" name="60" placeholder="60">
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br><br>

                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="importNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Import File</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="ImportRule" autocomplete="off" method="post" action="../php_action/importLeaseRuleFile.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <h3 class="h4">Import RESIDUALS.CSV File:</h3>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="excelFile">File</label>
                                <input type="file" class="form-control-file" id="excelFile" name="excelFile" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expireIni">Expire In.</label>
                                <input type="text" class="form-control" id="expireIni" name="expireIni" placeholder="Expire In.">
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-md-12">
                            <div class="alert alert-outline-info fade show mb-0">
                                <div class="alert-icon"><i class="fa fa-info"></i></div>
                                <div class="alert-content">
                                    <h4 class="alert-heading">Please Note!</h4>
                                    <a href="./files/Lease_Rules.xlsx" download class="btn btn-success float-right">Download Format File</a>
                                    <p>The following Excel File column sequence should match the image below.</p>
                                    <code>"Year" "Trim" "Model Code" "24" "27" ...</code>
                                    <hr>
                                </div>
                                <button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="form-row mt-4 mb-4">
                        <div class="col-md-6">
                            <h3 class="h6 mb-3">12,000 Miles Per Year </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="12_24_33i" value="1" name="12_24_33i" class="form-control" placeholder=" ">
                                        <label for="12_24_33i">24-33 -%</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="12_36_48i" value="2" name="12_36_48i" class="form-control" placeholder=" ">
                                        <label for="12_36_48i">36-48 -%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6 mb-3">10,000 Miles Per Year </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="10_24_33i" value="2" name="10_24_33i" class="form-control" placeholder=" ">
                                        <label for="10_24_33i">24-33 -%</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-label">
                                        <input type="text" id="10_36_48i" value="3" name="10_36_48i" class="form-control" placeholder=" ">
                                        <label for="10_36_48i">36-48 -%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" id="importSubmitBtn" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/leaseRules.js"></script>