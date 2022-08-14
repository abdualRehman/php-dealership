<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

// if (hasAccess("bdc", "Add") === 'false' && hasAccess("bdc", "Edit") === 'false' && hasAccess("bdc", "Remove") === 'false') {
//     echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
// }
// if (hasAccess("bdc", "Edit") === 'false') {
//     echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
// } else {
//     echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
// }

$userRole = $_SESSION['userRole'];
echo '<input type="hidden" name="loggedInUserRole" id="loggedInUserRole" value="' . $userRole . '" />';
echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';

?>



<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>

<style>
    .col-xs-5ths,
    .col-sm-5ths,
    .col-md-5ths,
    .col-lg-5ths {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-xs-5ths {
        width: 20%;
        float: left;
    }

    @media (min-width: 768px) {
        .col-sm-5ths {
            width: 20%;
            float: left;
        }
    }

    @media (min-width: 992px) {
        .col-md-5ths {
            width: 20%;
            float: left;
        }
    }

    @media (min-width: 1200px) {
        .col-lg-5ths {
            width: 20%;
            float: left;
        }
    }

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

    .font-size-initial {
        font-weight: 900 !important;
        font-size: large;
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-header text-center">
                        <h3 class="portlet-title font-size-initial">Retail</h3>
                    </div>
                    <div class="portlet-body text-center">
                        <h4 class="h4 text-primary text-center counterDiv" id="retailV" ></h4>
                        <hr>
                        <div class="row text-center">
                            <p class="col-md-12 h6 text-center">Count</p>
                            <span class="col-md-12 h4 text-primary counterDiv" id="retailC" ></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-header text-center">
                        <h3 class="portlet-title font-size-initial">Retail Profit</h3>
                    </div>
                    <div class="portlet-body text-center">
                        <h4 class="h4 text-primary text-center counterDiv" id="retailP"></h4>
                        <hr>
                        <div class="row text-center">
                            <p class="col-md-12 h6 text-center">Avg</p>
                            <span class="col-md-12 h4 text-primary counterDiv" id="retailA"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-header text-center">
                        <h3 class="portlet-title font-size-initial">Wholesale</h3>
                    </div>
                    <div class="portlet-body text-center">
                        <h4 class="h4 text-primary text-center counterDiv" id="wholesaleV" ></h4>
                        <hr>
                        <div class="row text-center">
                            <p class="col-md-12 h6 text-center">Count</p>
                            <span class="col-md-12 h4 text-primary counterDiv" id="wholesaleC"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-body text-center">
                        <div class="row text-center">
                            <h3 class="col-md-12 font-size-initial">MMR vs COST (Retail)</h3>
                            <span class="col-md-12 h4 text-primary counterDiv" id="mmr_retailV" >0</span>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <p class="col-md-12 h5 text-center">MMR vs COST (Wholesale)</p>
                            <span class="col-md-12 h4 text-primary counterDiv" id="mmr_balanceV" >0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Car WriteDown</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>

                    </div>
                    <div class="portlet-body">
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Stock no.</th>
                                    <th>Year</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Miles</th>
                                    <th>Lot</th>
                                    <th>Age</th>
                                    <th>Balance</th>
                                    <th>Price</th>
                                    <th>Profit</th>
                                    <th>WRITEDOWN</th>
                                    <th>MMR</th>
                                    <th>MMR vs COST</th>
                                    <th>MMR vs RETAIL</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Writedown</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <!-- <form class="form-horizontal" id="editWritedownForm" action="../php_action/editWritedown.php" method="post"> -->
            <form class="form-horizontal" id="editWritedownForm" method="post">
                <input type="hidden" name="writedownId" id="writedownId">

                <div class="modal-body">

                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">


                        <div class="row">
                            <div class="col-md-8">
                                <div class="row align-items-baseline">
                                    <label for="stockno" class="col-sm-2 col-form-label">Stock no. Vin</label>
                                    <div class="form-group col-sm-10">
                                        <input type="text" class="form-control" name="stockno" id="stockno" readonly autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="row align-items-baseline">
                                    <label for="writedown" class="col-sm-2 col-form-label">Write Down</label>
                                    <div class="form-group col-sm-4">
                                        <input type="text" class="form-control" name="writedown" id="writedown" disabled />
                                    </div>
                                    <label for="mmr" class="col-sm-2 text-md-center col-form-label">MMR</label>
                                    <div class="form-group col-sm-4">
                                        <input type="text" class="form-control" name="mmr" id="mmr" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="row align-items-baseline">
                                    <label for="mmr_retail" class="col-sm-2 col-form-label">MMR vs RETAIL</label>
                                    <div class="form-group col-sm-4">
                                        <input type="text" class="form-control" name="mmr_retail" id="mmr_retail" disabled />
                                    </div>
                                    <label for="mmr_balance" class="col-sm-2 text-md-center col-form-label">MMR vs Balance</label>
                                    <div class="form-group col-sm-4">
                                        <input type="text" class="form-control" name="mmr_balance" id="mmr_balance" autocomplete="off" autofill="off" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="selectedDetails" class="col-form-label">Vehicle</label>
                                <div class="saleDetailsDiv" id="saleDetailsDiv">
                                    <textarea class="form-control autosize" name="selectedDetails" style="border:none ;overflow-y: scroll!important;" id="selectedDetails" readonly placeholder="Type Something..."></textarea>
                                </div>
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



<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/writedown.js"></script>