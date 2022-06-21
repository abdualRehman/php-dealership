<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

// if (hasAccess("swap", "Add") === 'false' && hasAccess("swap", "Edit") === 'false' && hasAccess("swap", "Remove") === 'false') {
if (hasAccess("swap", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("swap", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
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
        text-align: center;
    }

    .dropdown-header.optgroup-1 {
        padding: 0px !important;
    }

    .font-initial {
        font-size: initial;
    }

    @media (min-width: 1025px) {

        .modal-lg,
        .modal-xl {
            max-width: 900px;
        }
    }
</style>

<?php
// if (hasAccess("swap", "Edit") === 'false') {
//     echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
// } else {
//     echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
// }
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <div class="row w-100 p-0 m-0">
                            <div class="col-md-2 d-flex align-items-center p-0 mb-2">
                                <h3 class="portlet-title">Swaps List</h3>
                            </div>
                            <div class="col-md-8 d-flex justify-content-center align-items-center p-0 mb-2">
                                <div class="row d-flex justify-content-center flex-row p-0 m-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-flat-primary">
                                                    <input type="radio" name="radio-status" id="pending" value="pending">
                                                    Pending
                                                    <span class="badge badge-lg p-1" id="pendingCount"></span>
                                                </label>
                                                <label class="btn btn-flat-primary">
                                                    <input type="radio" name="radio-status" value="paperWork">
                                                    Paperwork Done
                                                    <span class="badge badge-lg p-1" id="paperWorkCount"></span>
                                                </label>
                                                <label class="btn btn-flat-primary">
                                                    <input type="radio" name="radio-status" value="completed">
                                                    Completed
                                                    <span class="badge badge-lg p-1" id="completedCount"></span>
                                                </label>
                                                <label class="btn btn-flat-primary">
                                                    <input type="radio" name="radio-status" value="all" id="modAll">
                                                    Show All
                                                    <span class="badge badge-lg p-1" id="AllCount"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2 p-1 d-flex justify-content-end align-items-center">
                                <div class="d-inline-flex">
                                    <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                                        <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                                    </button>
                                    <?php
                                    if (hasAccess("swap", "Add") !== 'false') {
                                    ?>
                                        <button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                                            <i class="fa fa-plus ml-1 mr-2"></i> Add Swap
                                        </button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width:10%;">From Dealer</th>
                                    <th style="width:10%;">Vehicle In Details</th>
                                    <th style="width:10%;">Vehicle Out Details</th>
                                    <th style="width:10%;">Sales Consultant</th>
                                    <th style="width:20%;">Notes</th>
                                    <th style="width:10%;">Transferred In</th>
                                    <th style="width:10%;">Transferred Out</th>
                                    <th style="width:10%;">Status</th>
                                    <th style="width:10%;">Action</th>
                                    <th style="width:10%;">ID</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Swaps</h5>
                <button type="button" class="btn btn-label-danger btn-icon closeBtn" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editSwapForm" action="../php_action/editSwap.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="swapId" id="swapId">

                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="efromDealer" class="col-form-label text-md-center">From Dealer:</label>
                                        <div class="form-group">
                                            <select class="selectpicker required" name="efromDealer" id="efromDealer" data-live-search="true" data-size="4">
                                                <optgroup class="fromDealer">
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="clearBtn" class="col-form-label text-md-center">&nbsp;</label>
                                        <div class="form-group" id="clearBtn">
                                            <button type="button" class="badge badge-outline-danger badge-square badge-lg cursor-pointer" onclick="resetDealerFrom()">X</button>
                                            <button type="button" data-toggle="modal" data-target="#addNewLocation" class="badge badge-outline-primary badge-square badge-lg cursor-pointer"> <i class="fa fa-plus"></i> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label" for="esalesConsultant">Sales Consultant:</label>
                                <div class="form-group">
                                    <select class="selectpicker" name="esalesPerson" id="esalesPerson" data-live-search="true" data-size="4">
                                        <optgroup class="salesPerson">
                                            <option value="0" selected disabled>Sales Consultant</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="estatus" class="col-form-label text-md-center">Status:</label>
                                <select class="form-control selectpicker w-auto" name="estatus" id="estatus">
                                    <option value="pending" selected>Pending</option>
                                    <option value="paperworkDone">Paperwork Done</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row mt-3">
                            <div class="col-md-6">
                                <h6 class="h5 text-center mb-3">Vehicle In</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="estockIn" class="col-form-label text-md-center">Stock# In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="estockIn" name="estockIn" placeholder="Stock# In">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="evehicleIn" class="col-form-label text-md-center">Vehicle In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="evehicleIn" name="evehicleIn" placeholder="Vehicle In">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="ecolorIn" class="col-form-label text-md-center">Color In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="ecolorIn" name="ecolorIn" placeholder="Color In">
                                    </div>
                                </div>

                                <div class="row pt-3 pb-3 m-auto" style="width: 80%;">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input evehicleDetails evehicleIn" id="einvReceived" name="einvReceived">
                                        <label class="custom-control-label" for="einvReceived">Invoice Received</label>
                                    </div>
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input evehicleDetails evehicleIn" id="etransferredIn" name="etransferredIn">
                                        <label class="custom-control-label" for="etransferredIn">Transferred In</label>
                                    </div>
                                </div>
                                <div id="einDetails" class="mt-3 d-none">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="evinIn" class="col-form-label text-md-center">Vin In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <input type="text" class="form-control" id="evinIn" name="evinIn">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="einvIn" class="col-form-label text-md-center">Invoice In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="invIn" name="invIn"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvIn' , 'ehbIn' , 'ehbtIn' , 'ehdagIn' , 'eaddsIn'  , 'enetcostIn')" id="einvIn" name="einvIn">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="ehbIn" class="col-form-label text-md-center">Holdback In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="hbIn" name="hbIn"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvIn' , 'ehbIn' , 'ehbtIn' , 'ehdagIn' , 'eaddsIn'  , 'enetcostIn')" id="ehbIn" name="ehbIn">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="emsrpIn" class="col-form-label text-md-center">MSRP -dest In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="msrpIn" name="msrpIn"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateHTB('emsrpIn' , 'ehbtIn' , 'einpercentage')" id="emsrpIn" name="emsrpIn">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="ehdagIn" class="col-form-label text-md-center">Hdag In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="hdagIn" name="hdagIn"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvIn' , 'ehbIn' , 'ehbtIn' , 'ehdagIn' , 'eaddsIn'  , 'enetcostIn')" id="ehdagIn" name="ehdagIn">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="eaddsIn" class="col-form-label text-md-center">Adds In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="addsIn" name="addsIn"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvIn' , 'ehbIn' , 'ehbtIn' , 'ehdagIn' , 'eaddsIn'  , 'enetcostIn')" id="eaddsIn" name="eaddsIn">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="eaddsInNotes" class="col-form-label text-md-center">Adds In Notes</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <input type="text" class="form-control" id="eaddsInNotes" name="eaddsInNotes">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="ehbtIn" class="col-form-label text-md-center">HBT In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="hbtIn" name="hbtIn" readonly> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" onclick="calculateCost('einvIn' , 'ehbIn' , 'ehbtIn' , 'ehdagIn' , 'eaddsIn'  , 'enetcostIn')" id="ehbtIn" name="ehbtIn" readonly>
                                                <input type="text" name="einpercentage" id="einpercentage" oninput="calculateHTB('emsrpIn' , 'ehbtIn' , 'einpercentage')" class="form-control" value="1.5" style="max-width: 80px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="enetcostIn" class="col-form-label text-md-center">Net Cost In</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="netcostIn" name="netcostIn" readonly> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control font-initial" id="enetcostIn" name="enetcostIn" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <h6 class="h5 text-center mb-3">Vehicle Out</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="estockOut" class="col-form-label text-md-center">Stock# Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="stockOut" name="stockOut" placeholder="Stock# Out"> -->
                                        <select class="selectpicker" name="estockOut" onchange="changeStockDetails(this , 'evehicleOut' , 'ecolorOut' , 'evinOut' )" id="estockOut" data-live-search="true" data-size="4">
                                            <!-- <option value="0" selected disabled>Stock# Out</option> -->
                                            <optgroup class="stockOut">
                                                <option value="0" selected disabled>Stock# Out</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="evehicleOut" class="col-form-label text-md-center">Vehicle Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="evehicleOut" name="evehicleOut" placeholder="Vehicle Out">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="ecolorOut" class="col-form-label text-md-center">Color Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="ecolorOut" name="ecolorOut" placeholder="Color Out">
                                    </div>
                                </div>

                                <div class="row pt-3 pb-3 m-auto" style="width: 80%;">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input evehicleDetails evehicleOut" id="einvSent" name="einvSent">
                                        <label class="custom-control-label" for="einvSent">Invoice Sent</label>
                                    </div>
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input evehicleOut" id="etagged" name="etagged">
                                        <label class="custom-control-label" for="etagged">Tagged</label>
                                    </div>
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input evehicleDetails evehicleOut" id="etransferredOut" name="etransferredOut">
                                        <label class="custom-control-label" for="etransferredOut">Transferred Out</label>
                                    </div>
                                </div>
                                <div id="eoutDetails" class="mt-3 d-none">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="evinOut" class="col-form-label text-md-center">Vin Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <input type="text" class="form-control" id="evinOut" name="evinOut">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="einvOut" class="col-form-label text-md-center">Invoice Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="invOut" name="invOut"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvOut' , 'ehbOut' , 'ehbtOut' , 'ehdagOut' , 'eaddsOut'  , 'enetcostOut')" id="einvOut" name="einvOut">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="ehbOut" class="col-form-label text-md-center">Holdback Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="hbOut" name="hbOut"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvOut' , 'ehbOut' , 'ehbtOut' , 'ehdagOut' , 'eaddsOut'  , 'enetcostOut')" id="ehbOut" name="ehbOut">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="emsrpOut" class="col-form-label text-md-center">MSRP -dest Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="msrpOut" name="msrpOut"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateHTB('emsrpOut' , 'ehbtOut' ,'eoutpercentage')" id="emsrpOut" name="emsrpOut">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="ehdagOut" class="col-form-label text-md-center">Hdag Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="hdagOut" name="hdagOut"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvOut' , 'ehbOut' , 'ehbtOut' , 'ehdagOut' , 'eaddsOut'  , 'enetcostOut')" id="ehdagOut" name="ehdagOut">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="eaddsOut" class="col-form-label text-md-center">Adds Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="addsOut" name="addsOut"> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" oninput="calculateCost('einvOut' , 'ehbOut' , 'ehbtOut' , 'ehdagOut' , 'eaddsOut'  , 'enetcostOut')" id="eaddsOut" name="eaddsOut">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="eaddsOutNotes" class="col-form-label text-md-center">Adds Out Notes</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <input type="text" class="form-control" id="eaddsOutNotes" name="eaddsOutNotes">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="ehbtOut" class="col-form-label text-md-center">HBT Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="hbtOut" name="hbtOut" readonly> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" onclick="calculateCost('einvOut' , 'ehbOut' , 'ehbtOut' , 'ehdagOut' , 'eaddsOut'  , 'enetcostOut')" id="ehbtOut" name="ehbtOut" readonly>
                                                <input type="text" name="eoutpercentage" id="eoutpercentage" oninput="calculateHTB('emsrpOut' , 'ehbtOut' ,'eoutpercentage')" class="form-control" value="1.5" style="max-width: 80px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="enetcostOut" class="col-form-label text-md-center">Net Cost Out</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <!-- <input type="text" class="form-control" id="netcostOut" name="netcostOut" readonly> -->
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control font-initial" id="enetcostOut" name="enetcostOut" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-3 justify-content-center">
                            <div class="col-md-10 form-group">
                                <textarea class="form-control autosize" name="edealNote" id="edealNote" placeholder="Notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered justify-content-space-between">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <label for="submittedBy" class="col-form-label">Submitted By: <strong id="submittedBy"> </strong> </label>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary mr-2">Update Changes</button>
                            <button type="button" class="btn btn-label-primary btn-icon mr-1" id="editBtnPrint" onclick="printDetails()">
                                <i class="fa fa-print"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Swap</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewSwap" autocomplete="off" method="post" action="../php_action/createSwap.php">
                <!-- <form id="addNewSwap" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="fromDealer" class="col-form-label text-md-center">From Dealer:</label>
                                    <div class="form-group">
                                        <select class="selectpicker required" name="fromDealer" id="fromDealer" data-live-search="true" data-size="4">
                                            <optgroup class="fromDealer">
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="clearBtn" class="col-form-label text-md-center">&nbsp;</label>
                                    <div class="form-group" id="clearBtn">
                                        <button type="button" class="badge badge-outline-danger badge-square badge-lg cursor-pointer" onclick="resetDealerFrom()">X</button>
                                        <button type="button" data-toggle="modal" data-target="#addNewLocation" class="badge badge-outline-primary badge-square badge-lg cursor-pointer"> <i class="fa fa-plus"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label" for="salesConsultant">Sales Consultant:</label>
                            <div class="form-group">
                                <select class="selectpicker" name="salesPerson" id="salesPerson" data-live-search="true" data-size="4">
                                    <optgroup class="salesPerson">
                                        <option value="0" selected disabled>Sales Consultant</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="col-form-label text-md-center">Status:</label>
                            <select class="form-control selectpicker w-auto" name="status" id="status">
                                <option value="pending" selected>Pending</option>
                                <option value="paperworkDone">Paperwork Done</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row mt-3">
                        <div class="col-md-6">
                            <h6 class="h5 text-center mb-3">Vehicle In</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="stockIn" class="col-form-label text-md-center">Stock# In</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" id="stockIn" name="stockIn" placeholder="Stock# In">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="vehicleIn" class="col-form-label text-md-center">Vehicle In</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" id="vehicleIn" name="vehicleIn" placeholder="Vehicle In">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="colorIn" class="col-form-label text-md-center">Color In</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" id="colorIn" name="colorIn" placeholder="Color In">
                                </div>
                            </div>

                            <div class="row pt-3 pb-3 m-auto" style="width: 80%;">
                                <div class="custom-control-lg custom-checkbox">
                                    <input type="checkbox" class="custom-control-input vehicleDetails vehicleIn" id="invReceived" name="invReceived">
                                    <label class="custom-control-label" for="invReceived">Invoice Received</label>
                                </div>
                                <div class="custom-control-lg custom-checkbox">
                                    <input type="checkbox" class="custom-control-input vehicleDetails vehicleIn" id="transferredIn" name="transferredIn">
                                    <label class="custom-control-label" for="transferredIn">Transferred In</label>
                                </div>
                            </div>
                            <div id="inDetails" class="mt-3 d-none">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="vinIn" class="col-form-label text-md-center">Vin In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="vinIn" name="vinIn">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="invIn" class="col-form-label text-md-center">Invoice In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="invIn" name="invIn"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invIn' , 'hbIn' , 'hbtIn' , 'hdagIn' , 'addsIn'  , 'netcostIn')" id="invIn" name="invIn">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="hbIn" class="col-form-label text-md-center">Holdback In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="hbIn" name="hbIn"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invIn' , 'hbIn' , 'hbtIn' , 'hdagIn' , 'addsIn'  , 'netcostIn')" id="hbIn" name="hbIn">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="msrpIn" class="col-form-label text-md-center">MSRP -dest In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="msrpIn" name="msrpIn"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateHTB('msrpIn' , 'hbtIn' , 'inpercentage')" id="msrpIn" name="msrpIn">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="hdagIn" class="col-form-label text-md-center">Hdag In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="hdagIn" name="hdagIn"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invIn' , 'hbIn' , 'hbtIn' , 'hdagIn' , 'addsIn'  , 'netcostIn')" id="hdagIn" name="hdagIn">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="addsIn" class="col-form-label text-md-center">Adds In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="addsIn" name="addsIn"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invIn' , 'hbIn' , 'hbtIn' , 'hdagIn' , 'addsIn'  , 'netcostIn')" id="addsIn" name="addsIn">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="addsInNotes" class="col-form-label text-md-center">Adds In Notes</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="addsInNotes" name="addsInNotes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="hbtIn" class="col-form-label text-md-center">HBT In</label>
                                    </div>
                                    <div class="col-md-9 form-group">

                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" onclick="calculateCost('invIn' , 'hbIn' , 'hbtIn' , 'hdagIn' , 'addsIn'  , 'netcostIn')" id="hbtIn" name="hbtIn" readonly>

                                            <input type="text" name="inpercentage" id="inpercentage" oninput="calculateHTB('msrpIn' , 'hbtIn' , 'inpercentage')" class="form-control" value="1.5" style="max-width: 80px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <!-- <input type="text" class="form-control" id="hbtIn" name="hbtIn" readonly> -->
                                        <!-- <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" onclick="calculateCost('invIn' , 'hbIn' , 'hbtIn' , 'hdagIn' , 'addsIn'  , 'netcostIn')" id="hbtIn" name="hbtIn" readonly>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="netcostIn" class="col-form-label text-md-center">Net Cost In</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="netcostIn" name="netcostIn" readonly> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control font-initial" id="netcostIn" name="netcostIn" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <h6 class="h5 text-center mb-3">Vehicle Out</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="stockOut" class="col-form-label text-md-center">Stock# Out</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <!-- <input type="text" class="form-control" id="stockOut" name="stockOut" placeholder="Stock# Out"> -->
                                    <select class="selectpicker" name="stockOut" onchange="changeStockDetails(this , 'vehicleOut' , 'colorOut' , 'vinOut' )" id="stockOut" data-live-search="true" data-size="4">
                                        <!-- <option value="0" selected disabled>Stock# Out</option> -->
                                        <optgroup class="stockOut">
                                            <option value="0" selected disabled>Stock# Out</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="vehicleOut" class="col-form-label text-md-center">Vehicle Out</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" id="vehicleOut" name="vehicleOut" placeholder="Vehicle Out">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="colorOut" class="col-form-label text-md-center">Color Out</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" id="colorOut" name="colorOut" placeholder="Color Out">
                                </div>
                            </div>

                            <div class="row pt-3 pb-3 m-auto" style="width: 80%;">
                                <div class="custom-control-lg custom-checkbox">
                                    <input type="checkbox" class="custom-control-input vehicleDetails vehicleOut" id="invSent" name="invSent">
                                    <label class="custom-control-label" for="invSent">Invoice Sent</label>
                                </div>
                                <div class="custom-control-lg custom-checkbox">
                                    <input type="checkbox" class="custom-control-input vehicleOut" id="tagged" name="tagged">
                                    <label class="custom-control-label" for="tagged">Tagged</label>
                                </div>
                                <div class="custom-control-lg custom-checkbox">
                                    <input type="checkbox" class="custom-control-input vehicleDetails vehicleOut" id="transferredOut" name="transferredOut">
                                    <label class="custom-control-label" for="transferredOut">Transferred Out</label>
                                </div>
                            </div>
                            <div id="outDetails" class="mt-3 d-none">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="vinOut" class="col-form-label text-md-center">Vin Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="vinOut" name="vinOut">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="invOut" class="col-form-label text-md-center">Invoice Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="invOut" name="invOut"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invOut' , 'hbOut' , 'hbtOut' , 'hdagOut' , 'addsOut'  , 'netcostOut')" id="invOut" name="invOut">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="hbOut" class="col-form-label text-md-center">Holdback Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="hbOut" name="hbOut"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invOut' , 'hbOut' , 'hbtOut' , 'hdagOut' , 'addsOut'  , 'netcostOut')" id="hbOut" name="hbOut">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="msrpOut" class="col-form-label text-md-center">MSRP -dest Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="msrpOut" name="msrpOut"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateHTB('msrpOut' , 'hbtOut' , 'outpercentage')" id="msrpOut" name="msrpOut">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="hdagOut" class="col-form-label text-md-center">Hdag Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="hdagOut" name="hdagOut"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invOut' , 'hbOut' , 'hbtOut' , 'hdagOut' , 'addsOut'  , 'netcostOut')" id="hdagOut" name="hdagOut">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="addsOut" class="col-form-label text-md-center">Adds Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="addsOut" name="addsOut"> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="calculateCost('invOut' , 'hbOut' , 'hbtOut' , 'hdagOut' , 'addsOut'  , 'netcostOut')" id="addsOut" name="addsOut">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="addsOutNotes" class="col-form-label text-md-center">Adds Out Notes</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input type="text" class="form-control" id="addsOutNotes" name="addsOutNotes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="hbtOut" class="col-form-label text-md-center">HBT Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="hbtOut" name="hbtOut" readonly> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" onclick="calculateCost('invOut' , 'hbOut' , 'hbtOut' , 'hdagOut' , 'addsOut' , 'netcostOut')" id="hbtOut" name="hbtOut" readonly>
                                            <input type="text" name="outpercentage" id="outpercentage" oninput="calculateHTB('msrpOut' , 'hbtOut' , 'outpercentage')" class="form-control" value="1.5" style="max-width: 80px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="netcostOut" class="col-form-label text-md-center">Net Cost Out</label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <!-- <input type="text" class="form-control" id="netcostOut" name="netcostOut" readonly> -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control font-initial" id="netcostOut" name="netcostOut" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mt-3 justify-content-center">
                        <div class="col-md-10 form-group">
                            <textarea class="form-control autosize" name="dealNote" id="dealNote" placeholder="Notes..."></textarea>
                        </div>
                    </div>




                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-label-primary btn-icon mr-1" id="createBtnPrint" onclick="printDetails()">
                        <i class="fa fa-print"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="addNewLocation">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Swap Location</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewSwapLocation" autocomplete="off" method="post" action="../php_action/createLocation.php">
                <!-- <form id="addNewSwap" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="dealerno">Dealer #</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="dealerno" name="dealerno">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3"><label for="dealership">Dealership</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="dealership" name="dealership">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3"><label for="address">Address</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="city">City</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="state">State</label>
                            <div class="form-group">
                                <select class="form-control selectpicker w-auto" name="state" id="state" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>State</option>
                                    <option value="MA">MA</option>
                                    <option value="RI">RI</option>
                                    <option value="CT">CT</option>
                                    <option value="NH">NH</option>
                                    <option value="AL">AL</option>
                                    <option value="AK">AK</option>
                                    <option value="AZ">AZ</option>
                                    <option value="AR">AR</option>
                                    <option value="CA">CA</option>
                                    <option value="CO">CO</option>
                                    <option value="DC">DC</option>
                                    <option value="DE">DE</option>
                                    <option value="FL">FL</option>
                                    <option value="GA">GA</option>
                                    <option value="HI">HI</option>
                                    <option value="ID">ID</option>
                                    <option value="IL">IL</option>
                                    <option value="IN">IN</option>
                                    <option value="IA">IA</option>
                                    <option value="KS">KS</option>
                                    <option value="KY">KY</option>
                                    <option value="LA">LA</option>
                                    <option value="ME">ME</option>
                                    <option value="MD">MD</option>
                                    <option value="MI">MI</option>
                                    <option value="MN">MN</option>
                                    <option value="MS">MS</option>
                                    <option value="MO">MO</option>
                                    <option value="MT">MT</option>
                                    <option value="NE">NE</option>
                                    <option value="NV">NV</option>
                                    <option value="NJ">NJ</option>
                                    <option value="NM">NM</option>
                                    <option value="NY">NY</option>
                                    <option value="NC">NC</option>
                                    <option value="ND">ND</option>
                                    <option value="OH">OH</option>
                                    <option value="OK">OK</option>
                                    <option value="OR">OR</option>
                                    <option value="PA">PA</option>
                                    <option value="SC">SC</option>
                                    <option value="SD">SD</option>
                                    <option value="TN">TN</option>
                                    <option value="TX">TX</option>
                                    <option value="UT">UT</option>
                                    <option value="VT">VT</option>
                                    <option value="VA">VA</option>
                                    <option value="WA">WA</option>
                                    <option value="WV">WV</option>
                                    <option value="WI">WI</option>
                                    <option value="WY">WY</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="zip">Zip</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="zip" name="zip">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="miles">Miles</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="miles" name="miles">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="travelTime">Travel Time</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="travelTime" name="travelTime">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="roundTrip">Round Trip</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="roundTrip" name="roundTrip">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="phone">Phone</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fax">Fax</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="fax" name="fax">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="col-md-4 mb-3">
                            <label for="mcontact">Main Contact</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="mcontact" name="mcontact">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cell">Cell</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="cell" name="cell">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="preffer">Preffer Call or Text</label>
                            <div class="d-flex">
                                <div class="custom-control custom-checkbox mr-5">
                                    <input type="checkbox" class="custom-control-input" id="call" name="call">
                                    <label class="custom-control-label" for="call">Call</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="text" name="text">
                                    <label class="custom-control-label" for="text">Text</label>
                                </div>

                            </div>
                        </div>

                    </div>

                    <hr>
                    <button class="btn btn-primary" type="submit">Create</button>
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php require_once('../includes/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.js"></script>
<script type="text/javascript" src="../custom/js/swaps.js"></script>