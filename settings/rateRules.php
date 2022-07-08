<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("raterule", "Add") === 'false' && hasAccess("raterule", "Edit") === 'false' && hasAccess("raterule", "Remove") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

// if ($_GET['r'] == 'man') {
//     echo "<div class='div-request d-none'>man</div>";
// } else if ($_GET['r'] == 'edit') {
//     echo "<div class='div-request d-none'>edit</div>";
// } else if ($_GET['r'] == 'add') {
//     echo "<div class='div-request d-none'>add</div>";
// }
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

    .col-md-3 {
        padding: 5px;
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



<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Rate Rule List</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("raterule", "Add") !== 'false') {
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Set New Rule
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
                                    <th>Finance Expires</th>
                                    <th>Lease Expires</th>
                                    <th>24-36</th>
                                    <th>37-48</th>
                                    <th>49-60</th>
                                    <th>61-72</th>
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
                <h5 class="modal-title">Edit Rate Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editRateRule.php" method="post">
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
                                    <th style="width:25%;text-align:center">Model</th>
                                    <th style="width:20%;text-align:center">Year</th>
                                    <th style="width:20%;text-align:center">Model No.</th>
                                    <th style="width:20%;text-align:center">Exclude Model No.</th>
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
                                </tr>
                            </tbody>
                        </table>
                        <hr>

                        <h3 class="h5 mb-4">Finance</h3>
                        <div class="form-row">
                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_24_36" name="ef_24_36" class="form-control" placeholder=" ">
                                    <label for="ef_24_36">Finance 24-36</label>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_37_48" name="ef_37_48" class="form-control" placeholder=" ">
                                    <label for="ef_37_48">Finance 37-48</label>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_49_60" name="ef_49_60" class="form-control" placeholder=" ">
                                    <label for="ef_49_60">Finance 49-60</label>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_61_72" name="ef_61_72" class="form-control" placeholder=" ">
                                    <label for="ef_61_72">Finance 61-72</label>
                                </div>
                            </div>
                        </div>
                        <h3 class="h5 mt-4 mb-4">Finance 659-610</h3>
                        <div class="form-row">
                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_659_610_24_36" name="ef_659_610_24_36" class="form-control" placeholder=" ">
                                    <label for="ef_659_610_24_36">Finance 24-36</label>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_659_610_37_60" name="ef_659_610_37_60" class="form-control" placeholder=" ">
                                    <label for="ef_659_610_37_60">Finance 37-60</label>
                                </div>
                            </div>

                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_659_610_61_72" name="ef_659_610_61_72" class="form-control" placeholder=" ">
                                    <label for="ef_659_610_61_72">Finance 61-72</label>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="float-label">
                                    <input type="text" id="ef_expire" name="ef_expire" class="form-control" placeholder=" ">
                                    <label for="ef_expire">Finance Expires</label>
                                </div>
                            </div>
                        </div>
                        <h3 class="h5 mt-4 mb-4">Lease</h3>
                        <div class="form-row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <div class="float-label">
                                            <input type="text" id="elease_660" name="elease_660" class="form-control" placeholder=" ">
                                            <label for="elease_660">Lease > 660</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <div class="float-label">
                                            <input type="text" id="elease_659_610" name="elease_659_610" class="form-control" placeholder=" ">
                                            <label for="elease_659_610">Lease 659 – 610</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <div class="float-label">
                                            <input type="text" id="elease_one_pay_660" name="elease_one_pay_660" class="form-control" placeholder=" ">
                                            <label for="elease_one_pay_660">One Pay > 600</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <div class="float-label">
                                            <input type="text" id="elease_one_pay_659_610" name="elease_one_pay_659_610" class="form-control" placeholder=" ">
                                            <label for="elease_one_pay_659_610">OnePay 659-610</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 form-group" style="padding: 5px;">
                                <div class="float-label">
                                    <input type="text" id="elease_expire" name="elease_expire" class="form-control" placeholder=" ">
                                    <label for="elease_expire">Lease Expire</label>
                                </div>
                            </div>

                        </div>
                        <br><br>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Rule</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createRateRule.php">
                <!-- <form id="addNewRule" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <br>
                    <h3 class="h4">Stock Details:</h3>
                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:25%;text-align:center">Model</th>
                                <th style="width:20%;text-align:center">Year</th>
                                <th style="width:20%;text-align:center">Model No.</th>
                                <th style="width:20%;text-align:center">Exclude Model No.</th>
                                <th style="width:15%;text-align:center"></th>
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

                                <td class="form-group text-center">
                                    <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <hr>

                    <h3 class="h5 mb-4">Finance</h3>
                    <div class="form-row">
                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_24_36" name="f_24_36" class="form-control" placeholder=" ">
                                <label for="f_24_36">Finance 24-36</label>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_37_48" name="f_37_48" class="form-control" placeholder=" ">
                                <label for="f_37_48">Finance 37-48</label>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_49_60" name="f_49_60" class="form-control" placeholder=" ">
                                <label for="f_49_60">Finance 49-60</label>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_61_72" name="f_61_72" class="form-control" placeholder=" ">
                                <label for="f_61_72">Finance 61-72</label>
                            </div>
                        </div>
                    </div>
                    <h3 class="h5 mt-4 mb-4">Finance 659-610</h3>
                    <div class="form-row">
                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_659_610_24_36" name="f_659_610_24_36" class="form-control" placeholder=" ">
                                <label for="f_659_610_24_36">Finance 24-36</label>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_659_610_37_60" name="f_659_610_37_60" class="form-control" placeholder=" ">
                                <label for="f_659_610_37_60">Finance 37-60</label>
                            </div>
                        </div>

                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_659_610_61_72" name="f_659_610_61_72" class="form-control" placeholder=" ">
                                <label for="f_659_610_61_72">Finance 61-72</label>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <div class="float-label">
                                <input type="text" id="f_expire" name="f_expire" class="form-control" placeholder=" ">
                                <label for="f_expire">Finance Expires</label>
                            </div>
                        </div>
                    </div>
                    <h3 class="h5 mt-4 mb-4">Lease</h3>
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <div class="float-label">
                                        <input type="text" id="lease_660" name="lease_660" class="form-control" placeholder=" ">
                                        <label for="lease_660">Lease > 660</label>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <div class="float-label">
                                        <input type="text" id="lease_659_610" name="lease_659_610" class="form-control" placeholder=" ">
                                        <label for="lease_659_610">Lease 659 – 610</label>
                                    </div>
                                </div>

                                <div class="col-md-3 form-group">
                                    <div class="float-label">
                                        <input type="text" id="lease_one_pay_660" name="lease_one_pay_660" class="form-control" placeholder=" ">
                                        <label for="lease_one_pay_660">One Pay > 600</label>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <div class="float-label">
                                        <input type="text" id="lease_one_pay_659_610" name="lease_one_pay_659_610" class="form-control" placeholder=" ">
                                        <label for="lease_one_pay_659_610">OnePay 659-610</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 form-group" style="padding: 5px;">
                            <div class="float-label">
                                <input type="text" id="lease_expire" name="lease_expire" class="form-control" placeholder=" ">
                                <label for="lease_expire">Lease Expire</label>
                            </div>
                        </div>

                    </div>
                    <br><br>

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
<script type="text/javascript" src="../custom/js/rateRules.js"></script>