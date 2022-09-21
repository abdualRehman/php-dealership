<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("bdc", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("bdc", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
$userRole = $_SESSION['userRole'];
echo '<input type="hidden" name="loggedInUserRole" id="loggedInUserRole" value="' . $userRole . '" />';



// setting manager id
$managerID = "";
if ($_SESSION['userRole'] == $bdcManagerID) {
    $managerID = $_SESSION['userId'];
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

    body.theme-light .disabled-div {
        background-color: #eee !important;
        pointer-events: none;
    }

    body.theme-dark .disabled-div {
        background-color: #757575 !important;
        pointer-events: none;
    }

    .font-size-initial {
        font-weight: 900 !important;
        font-size: large;
    }

    .clear-selection {
        text-decoration: underline;
        cursor: pointer;
    }

    .btn-group.btn-group-toggle label {
        place-content: center !important;
    }
</style>

<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-header text-center">
                        <h3 class="portlet-title font-size-initial">SOLD</h3>
                    </div>
                    <div class="portlet-body counterDiv">
                        <div class="row text-center">
                            <p class="col-md-4 h4 text-primary"> New <br> <span id="newSold">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Used <br> <span id="usedSold">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Total <br> <span id="totalSold">0</span> </p>
                        </div>
                        <hr class="hr">
                        <div class="row text-center">
                            <p class="col-md-12 h6 text-center">Verified</p>
                            <span class="col-md-4 h4 text-primary" id="newSoldv">0</span>
                            <span class="col-md-4 h4 text-primary" id="usedSoldv">0</span>
                            <span class="col-md-4 h4 text-primary" id="totalSoldv">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-header text-center">
                        <h3 class="portlet-title font-size-initial">INTERNET</h3>
                    </div>
                    <div class="portlet-body counterDiv">
                        <div class="row text-center">
                            <p class="col-md-4 h4 text-primary"> New <br> <span id="newInt">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Used <br> <span id="usedInt">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Total <br> <span id="totalInt">0</span> </p>
                        </div>
                        <hr class="hr">
                        <div class="row text-center">
                            <p class="col-md-12 h6 text-center">Verified</p>
                            <span class="col-md-4 h4 text-primary" id="newIntv">0</span>
                            <span class="col-md-4 h4 text-primary" id="usedIntv">0</span>
                            <span class="col-md-4 h4 text-primary" id="totalIntv">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-header text-center">
                        <h3 class="portlet-title font-size-initial">AUTOALERT</h3>
                    </div>
                    <div class="portlet-body counterDiv">
                        <div class="row text-center">
                            <p class="col-md-4 h4 text-primary"> New <br> <span id="newAa">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Used <br> <span id="usedAa">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Total <br> <span id="totalAa">0</span> </p>
                        </div>
                        <hr class="hr">
                        <div class="row text-center">
                            <p class="col-md-12 h6 text-center">Verified</p>
                            <span class="col-md-4 h4 text-primary" id="newAav">0</span>
                            <span class="col-md-4 h4 text-primary" id="usedAav">0</span>
                            <span class="col-md-4 h4 text-primary" id="totalAav">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="portlet">
                    <div class="portlet-header text-center">
                        <h3 class="portlet-title font-size-initial">LEADS</h3>
                    </div>
                    <div class="portlet-body counterDiv">
                        <div class="row text-center">
                            <p class="col-md-4 h4 text-primary"> Unsold <br> <span id="unSoldLead">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Sold <br> <span id="soldLead">0</span> </p>
                            <p class="col-md-4 h4 text-primary"> Total <br> <span id="totalLead">0</span> </p>
                        </div>
                        <hr class="hr">
                        <div class="row text-center">
                            <p class="col-md-12 h6 text-center">Verified</p>
                            <span class="col-md-4 h4 text-primary" id="unSoldLeadv">0</span>
                            <span class="col-md-4 h4 text-primary" id="SoldLeadv">0</span>
                            <span class="col-md-4 h4 text-primary" id="totalLeadv">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">BDC - Leads</h3>
                        <div class="justify-content-right align-items-center">
                            <div class="row d-flex justify-content-center flex-row p-0 mb-2 w-100">
                                <div class="row w-100">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" placeholder="Select Date" name="datefilter" value="" />
                                    </div>
                                </div>

                            </div>
                        </div>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("bdc", "Add") !== 'false') {
                            echo '<button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
                            <i class="fa fa-plus ml-1 mr-2"></i> Add New
                        </button>';
                        }
                        ?>

                    </div>
                    <div class="portlet-body">
                        <div class="remove-messages"></div>
                        <table id="datatable-1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>CCS</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Entity</th>
                                    <th>Vehicle</th>
                                    <th>Sales Consultant</th>
                                    <th>Sold / Show</th>
                                    <th>New / Used</th>
                                    <th>Source</th>
                                    <th>Notes</th>
                                    <th>Verified</th>
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
                <h5 class="modal-title">Edit Lead</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editLeadForm" action="../php_action/editBdcLead.php" method="post">
                <input type="hidden" name="leadId" id="leadId">
                <input type="hidden" name="eapprovedBy" id="eapprovedBy" value="<?php echo $managerID; ?>">
                <div class="modal-body">

                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row align-items-baseline">
                                    <label for="eleadDate" class="col-sm-1 offset-sm-1 col-form-label">Date</label>
                                    <div class="form-group col-sm-4">
                                        <div class="form-group input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control leadDate" name="eleadDate" id="eleadDate" />
                                        </div>
                                    </div>
                                    <label for="eentityId" class="col-sm-2 text-md-center col-form-label">Entity ID</label>
                                    <div class="form-group col-sm-4">
                                        <input type="text" class="form-control" name="eentityId" id="eentityId" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control text-center" name="esubmittedBy" id="esubmittedBy" value="<?php echo $_SESSION['userName']; ?>" readonly placeholder="Client Care Specialist" autocomplete="off" autofill="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row align-items-baseline">
                                    <label for="efname" class="col-sm-3 offset-sm-1 col-form-label">First Name</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control" name="efname" id="efname" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row align-items-baseline">
                                    <label for="elname" class="col-sm-4 text-md-center col-form-label">Last Name</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control" name="elname" id="elname" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row align-items-baseline">
                                    <label for="esalesConsultant" class="col-sm-5 col-form-label">Sales Consultant</label>
                                    <div class="form-group col-sm-7">
                                        <select class="form-control selectpicker w-auto" id="esalesConsultant" name="esalesConsultant" data-live-search="true" data-size="4">
                                            <option value="" selected disabled>Select</option>
                                            <optgroup class="salesConsultant"></optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row align-items-baseline">
                                    <label for="evehicle" class="col-sm-3 offset-sm-1 col-form-label">Vehicle</label>
                                    <div class="form-group col-sm-8">
                                        <input type="text" class="form-control typeahead typeahead1" id="evehicle" name="evehicle" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="eleadType">
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="eleadType" value="new" id="enew">
                                        New
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="eleadType" value="used" id="eused">
                                        Used
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row align-items-baseline">
                                    <label for="eleadStatus" class="col-sm-4 col-form-label">Sold / Show</label>
                                    <div class="form-group col-sm-8 text-center">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="eleadStatus">
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="eleadStatus" value="sold" id="esold" />
                                                Sold
                                            </label>
                                            <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                                <input type="radio" name="eleadStatus" value="show" id="eshow" />
                                                Show
                                            </label>
                                        </div>
                                        <span class="badge-text-primary pl-2 clear-selection" data-id="eleadStatus">Clear Selection</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-baseline">
                            <label for="esource" class="col-sm-1 text-sm-right col-form-label">Source</label>
                            <div class="form-group col-sm-11">
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="esource">
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="esource" value="internet" id="einternet">
                                        Internet
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="esource" value="autoAlert" id="eautoAlert">
                                        Auto Alert
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="esource" value="phoneUp" id="ephoneUp">
                                        Phone Up
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="esource" value="freshUp" id="efreshUp">
                                        Fresh Up
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="esource" value="carCode" id="ecarCode">
                                        Car Code
                                    </label>
                                </div>
                            </div>

                            <label for="eleadNotes" class="col-sm-1 text-sm-right col-form-label">Notes</label>
                            <div class="form-group col-sm-11">
                                <textarea class="form-control autosize" name="eleadNotes" id="eleadNotes" placeholder="Notes..."></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="row bdc_manager align-items-baseline" id="ebdc_manager">

                            <div class="col-md-12 mb-3 mt-3">
                                <p class="h5 text-center">
                                    Business Development Center Manager Verified
                                </p>
                            </div>
                            <label for="evarifiedStatus" class="col-sm-1 text-sm-right col-form-label">Status</label>
                            <div class="form-group col-sm-11">
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="evarifiedStatus">
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="evarifiedStatus" value="ok" id="eok">
                                        OK
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="evarifiedStatus" value="showVerified" id="eshowVerified">
                                        Show Verified
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="evarifiedStatus" value="doesNotCount" id="edoesNotCount">
                                        Does Not Count
                                    </label>
                                    <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                        <input type="radio" name="evarifiedStatus" value="lastMonth" id="elastMonth">
                                        Last Month
                                    </label>
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

<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Add Lead</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewLead" autocomplete="off" method="post" action="../php_action/createBdcLead.php">
                <input type="hidden" name="approvedBy" id="approvedBy" value="<?php echo $managerID; ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row align-items-baseline">
                                <label for="leadDate" class="col-sm-1 offset-sm-1 col-form-label">Date</label>
                                <div class="form-group col-sm-4">
                                    <div class="form-group input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control leadDate" name="leadDate" id="leadDate" />
                                    </div>
                                </div>
                                <label for="entityId" class="col-sm-2 text-md-center col-form-label">Entity ID</label>
                                <div class="form-group col-sm-4">
                                    <input type="text" class="form-control" name="entityId" id="entityId" autocomplete="off" autofill="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control text-center" name="submittedBy" id="submittedBy" value="<?php echo $_SESSION['userName']; ?>" readonly placeholder="Client Care Specialist" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row align-items-baseline">
                                <label for="fname" class="col-sm-3 offset-sm-1 col-form-label">First Name</label>
                                <div class="form-group col-sm-8">
                                    <input type="text" class="form-control" name="fname" id="fname" autocomplete="off" autofill="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row align-items-baseline">
                                <label for="lname" class="col-sm-4 text-md-center col-form-label">Last Name</label>
                                <div class="form-group col-sm-8">
                                    <input type="text" class="form-control" name="lname" id="lname" autocomplete="off" autofill="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row align-items-baseline">
                                <label for="salesConsultant" class="col-sm-5 col-form-label">Sales Consultant</label>
                                <div class="form-group col-sm-7">
                                    <select class="form-control selectpicker w-auto" id="salesConsultant" name="salesConsultant" data-live-search="true" data-size="4">
                                        <option value="" selected disabled>Select</option>
                                        <optgroup class="salesConsultant"></optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row align-items-baseline">
                                <label for="vehicle" class="col-sm-3 offset-sm-1 col-form-label">Vehicle</label>
                                <div class="form-group col-sm-8">
                                    <input type="text" class="form-control typeahead typeahead1" id="vehicle" name="vehicle" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="leadType">
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="leadType" value="new" id="new">
                                    New
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="leadType" value="used" id="used">
                                    Used
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row align-items-baseline">
                                <label for="leadStatus" class="col-sm-4 col-form-label">Sold / Show</label>
                                <div class="form-group col-sm-8 text-center">
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="leadStatus">
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="leadStatus" value="sold" id="leadStatusSold" />
                                            Sold
                                        </label>
                                        <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                            <input type="radio" name="leadStatus" value="show" id="leadStatusShow" />
                                            Show
                                        </label>
                                    </div>
                                    <span class="badge-text-primary pl-2 clear-selection" data-id="leadStatus">Clear Selection</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-baseline">
                        <label for="source" class="col-sm-1 text-sm-right col-form-label">Source</label>
                        <div class="form-group col-sm-11">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="source">
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="source" value="internet" id="internet">
                                    Internet
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="source" value="autoAlert" id="autoAlert">
                                    Auto Alert
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="source" value="phoneUp" id="phoneUp">
                                    Phone Up
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="source" value="freshUp" id="freshUp">
                                    Fresh Up
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="source" value="carCode" id="carCode">
                                    Car Code
                                </label>
                            </div>
                        </div>

                        <label for="leadNotes" class="col-sm-1 text-sm-right col-form-label">Notes</label>
                        <div class="form-group col-sm-11">
                            <textarea class="form-control autosize" name="leadNotes" id="leadNotes" placeholder="Notes..."></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row bdc_manager align-items-baseline" id="bdc_manager">

                        <div class="col-md-12 mb-3 mt-3">
                            <p class="h5 text-center">
                                Business Development Center Manager Verified
                            </p>
                        </div>
                        <label for="varifiedStatus" class="col-sm-1 text-sm-right col-form-label">Status</label>
                        <div class="form-group col-sm-11">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="varifiedStatus">
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="varifiedStatus" value="ok" id="ok">
                                    OK
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="varifiedStatus" value="showVerified" id="showVerified">
                                    Show Verified
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="varifiedStatus" value="doesNotCount" id="doesNotCount">
                                    Does Not Count
                                </label>
                                <label class="btn btn-flat-primary d-flex align-items-center m-2 rounded">
                                    <input type="radio" name="varifiedStatus" value="lastMonth" id="lastMonth">
                                    Last Month
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/bdc.js"></script>