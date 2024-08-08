<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("incr", "View") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}

if (hasAccess("incr", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
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
            max-width: 1200PX;
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
                        <h3 class="portlet-title">Incentives Rule List</h3>
                        <button class="btn btn-primary mr-2 p-2" onclick="toggleFilterClass()">
                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                        </button>
                        <?php
                        if (hasAccess("incr", "Add") !== 'false') {
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
                                    <th>Model Type</th>
                                    <th>Ex Model No</th>
                                    <th>State</th>
                                    <th>College</th>
                                    <th>Military</th>
                                    <th>Loyalty</th>
                                    <th>Conquest</th>
                                    <th>Lease Loyalty</th>
                                    <th>Right to Repair</th>
                                    <th>Misc 2</th>
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
                <h5 class="modal-title">Edit Incentive Rule</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editRuleForm" action="../php_action/editIncentiveRule.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="ruleId" id="ruleId">
                        <br><br>
                        <table class="table" id="productTable1">
                            <thead>
                                <tr>
                                    <th style="width:15%;text-align:center">Model</th>
                                    <th style="width:15%;text-align:center">Year</th>
                                    <th style="width:20%;text-align:center">Model No.</th>
                                    <th style="width:15%;text-align:center">Model Type</th>
                                    <th style="width:20%;text-align:center">Exclude Model No</th>
                                    <th style="width:15%;text-align:center">State</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1" class="0">
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModel" name="editModel" data-live-search="true" data-size="4">
                                            <option value="0" selected disabled>Select Model</option>
                                            <option value="All">All</option>
                                            <option value="ACCORD">ACCORD</option>
                                            <option value="ACCORD HYBRID">ACCORD HYBRID</option>
                                            <option value="CIVIC">CIVIC</option>
                                            <option value="CIVIC HYBRID">CIVIC HYBRID</option>
                                            <option value="CR-V">CR-V</option>
                                            <option value="CR-V HYBRID">CR-V HYBRID</option>
                                            <option value="HR-V">HR-V</option>
                                            <option value="INSIGHT">INSIGHT</option>
                                            <option value="ODYSSEY">ODYSSEY</option>
                                            <option value="PASSPORT">PASSPORT</option>
                                            <option value="PILOT">PILOT</option>
                                            <option value="PROLOGUE">PROLOGUE</option>
                                            <option value="RIDGELINE">RIDGELINE</option>
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editYear" name="editYear" placeholder="Year">
                                    </td>
                                    <td class="form-group">
                                        <input type="text" class="form-control typeahead typeahead1" id="editModelno" name="editModelno" placeholder="Model No.">
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control selectpicker w-auto" id="editModelType" name="editModelType">
                                            <option value="ALL" selected>ALL</option>
                                            <option value="NEW">NEW</option>
                                            <option value="USED">USED</option>
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control tags select21" id="editExModelno" name="editExModelno[]" multiple="multiple" title="Exclude Model No.">
                                            <optgroup label="Press Enter to add">
                                        </select>
                                    </td>
                                    <td class="form-group">
                                        <select class="form-control selectpicker" id="editState" name="editState[]" multiple="multiple" title="Select State" data-width="200px" data-actions-box="true" data-size="12" >
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
                                        </select>
                                        <!-- <select class="form-control select31" id="editState" name="editState[]" multiple="multiple" title="Select State">
                                        </select> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>

                        <div class="form-row editCheckbox">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                        <div class="custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editCollege" name="editCollege">
                                            <label class="custom-control-label h5" for="editCollege">
                                                College
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 editCollege">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ecollegeV" id="ecollegeV" placeholder="$" disabled>
                                            <input type="text" class="form-control expireIn" name="ecollegeE" id="ecollegeE" placeholder="Expire In" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                        <div class="custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editMilitary" name="editMilitary">
                                            <label class="custom-control-label h5" for="editMilitary">
                                                Military
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 editMilitary">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="emilitaryV" id="emilitaryV" placeholder="$" disabled />
                                            <input type="text" class="form-control expireIn" name="emilitaryE" id="emilitaryE" placeholder="Expire In" disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                        <div class="custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editLoyalty" name="editLoyalty">
                                            <label class="custom-control-label h5" for="editLoyalty">
                                                Loyalty
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 editLoyalty">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="eloyaltyV" id="eloyaltyV" placeholder="$" disabled />
                                            <input type="text" class="form-control expireIn" name="eloyaltyE" id="eloyaltyE" placeholder="Expire In" disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                        <div class="custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editConquest" name="editConquest">
                                            <label class="custom-control-label h5" for="editConquest">
                                                Conquest
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 editConquest">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="econquestV" id="econquestV" placeholder="$" disabled />
                                            <input type="text" class="form-control expireIn" name="econquestE" id="econquestE" placeholder="Expire In" disabled />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                        <div class="custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editLeaseLoyalty" name="editLeaseLoyalty">
                                            <label class="custom-control-label h5" for="editLeaseLoyalty">
                                                Lease Loyalty
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 editLeaseLoyalty">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="eleaseLoyaltyV" id="eleaseLoyaltyV" placeholder="$" disabled />
                                            <input type="text" class="form-control expireIn" name="eleaseLoyaltyE" id="eleaseLoyaltyE" placeholder="Expire In" disabled />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                        <div class="custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editMisc1" name="editMisc1">
                                            <label class="custom-control-label h5" for="editMisc1">
                                                Right to Repair
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 editMisc1">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="emisc1V" id="emisc1V" placeholder="$" disabled />
                                            <input type="text" class="form-control expireIn" name="emisc1E" id="emisc1E" placeholder="Expire In" disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                        <div class="custom-control-lg custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editMisc2" name="editMisc2">
                                            <label class="custom-control-label h5" for="editMisc2">
                                                Misc 2
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 editMisc2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="emisc2V" id="emisc2V" placeholder="$" disabled />
                                            <input type="text" class="form-control expireIn" name="emisc2E" id="emisc2E" placeholder="Expire In" disabled />
                                        </div>
                                    </div>
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
                <h5 class="modal-title">New Rule</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewRule" autocomplete="off" method="post" action="../php_action/createRule.php">
                <!-- <form id="addNewRule" autocomplete="off" method="post" action="#"> -->
                <div class="modal-body">
                    <br><br>
                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th style="width:15%;text-align:center">Model</th>
                                <th style="width:15%;text-align:center">Year</th>
                                <th style="width:15%;text-align:center">Model No.</th>
                                <th style="width:15%;text-align:center">Model Type</th>
                                <th style="width:15%;text-align:center">Exclude Model No</th>
                                <th style="width:15%;text-align:center">State</th>
                                <th style="width:10%;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1" class="0">
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="model1" name="model[]" data-live-search="true" data-size="4">
                                        <option value="0" selected disabled>Select Model</option>
                                        <option value="All">All</option>
                                        <option value="ACCORD">ACCORD</option>
                                        <option value="ACCORD HYBRID">ACCORD HYBRID</option>
                                        <option value="CIVIC">CIVIC</option>
                                        <option value="CIVIC HYBRID">CIVIC HYBRID</option>
                                        <option value="CR-V">CR-V</option>
                                        <option value="CR-V HYBRID">CRV HYBRID</option>
                                        <option value="HR-V">HR-V</option>
                                        <option value="INSIGHT">INSIGHT</option>
                                        <option value="ODYSSEY">ODYSSEY</option>
                                        <option value="PASSPORT">PASSPORT</option>
                                        <option value="PILOT">PILOT</option>
                                        <option value="PROLOGUE">PROLOGUE</option>
                                        <option value="RIDGELINE">RIDGELINE</option>
                                    </select>
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="year1" name="year[]" placeholder="Year">
                                </td>
                                <td class="form-group">
                                    <input type="text" class="form-control typeahead typeahead1" id="modelno1" name="modelno[]" placeholder="Model No.">
                                </td>
                                <td class="form-group">
                                    <select class="form-control selectpicker w-auto" id="modelType1" name="modelType[]">
                                        <option value="ALL" selected>ALL</option>
                                        <option value="NEW">NEW</option>
                                        <option value="USED">USED</option>
                                    </select>
                                </td>
                                <td class="form-group">
                                    <select class="form-control tags select21" id="exModelno1" name="exModelno1[]" multiple="multiple" title="Exclude Model No.">
                                        <optgroup label="Press Enter to add">
                                    </select>
                                </td>
                                <td class="form-group">
                                    <select class="form-control selectpicker" id="state1" name="state1[]" multiple="multiple" title="Select State" data-width="200px" data-actions-box="true" data-size="12" >
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
                                    </select>
                                    <!-- <select class="form-control select31" id="state1" name="state1[]" multiple="multiple" title="Select State">
                                    </select> -->
                                </td>
                                <td class="form-group text-center">
                                    <button type="button" id="addRowBtn" class="btn btn-info" data-loading-text="Loading..." onclick="addRow()">Add New</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <br><br>

                    <div class="form-row" id="permissionsDiv">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="college" name="college">
                                        <label class="custom-control-label h5" for="college">
                                            College
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9 college">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="collegeV" id="collegeV" placeholder="$" disabled>
                                        <input type="text" class="form-control expireIn" name="collegeE" id="collegeE" placeholder="Expire In" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="military" name="military">
                                        <label class="custom-control-label h5" for="military">
                                            Military
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9 military">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="militaryV" id="militaryV" placeholder="$" disabled />
                                        <input type="text" class="form-control expireIn" name="militaryE" id="militaryE" placeholder="Expire In" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="loyalty" name="loyalty">
                                        <label class="custom-control-label h5" for="loyalty">
                                            Loyalty
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9 loyalty">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="loyaltyV" id="loyaltyV" placeholder="$" disabled />
                                        <input type="text" class="form-control expireIn" name="loyaltyE" id="loyaltyE" placeholder="Expire In" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="conquest" name="conquest">
                                        <label class="custom-control-label h5" for="conquest">
                                            Conquest
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9 conquest">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="conquestV" id="conquestV" placeholder="$" disabled />
                                        <input type="text" class="form-control expireIn" name="conquestE" id="conquestE" placeholder="Expire In" disabled />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="leaseLoyalty" name="leaseLoyalty">
                                        <label class="custom-control-label h5" for="leaseLoyalty">
                                            Lease Loyalty
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9 leaseLoyalty">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="leaseLoyaltyV" id="leaseLoyaltyV" placeholder="$" disabled />
                                        <input type="text" class="form-control expireIn" name="leaseLoyaltyE" id="leaseLoyaltyE" placeholder="Expire In" disabled />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="misc1" name="misc1">
                                        <label class="custom-control-label h5" for="misc1">
                                            Right to Repair
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9 misc1">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="misc1V" id="misc1V" placeholder="$" disabled />
                                        <input type="text" class="form-control expireIn" name="misc1E" id="misc1E" placeholder="Expire In" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 text-md-left mb-2 align-self-center">
                                    <div class="custom-control-lg custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="misc2" name="misc2">
                                        <label class="custom-control-label h5" for="misc2">
                                            Misc 2
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9 misc2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="misc2V" id="misc2V" placeholder="$" disabled />
                                        <input type="text" class="form-control expireIn" name="misc2E" id="misc2E" placeholder="Expire In" disabled />
                                    </div>
                                </div>
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
<script type="text/javascript" src="../custom/js/incentiveRules.js"></script>