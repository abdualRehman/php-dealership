<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';


if ($_GET['r'] == 'man') {
    if (hasAccess("swploc", "View") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'add') {
    if (hasAccess("swploc", "Add") === 'false') {
        echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
    }
    echo "<div class='div-request d-none'>add</div>";
} // /else manage order


?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.min.css" rel="stylesheet" />
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
                            <h3 class="portlet-title">Locations</h3>
                            <?php
                            if (hasAccess("swploc", "Add") !== 'false') {
                            ?>
                                <button type="button" data-toggle="modal" data-target="#addNewLocation" class="btn btn-primary mr-2 p-2">
                                    <i class="fa fa-plus ml-1 mr-2"></i> Add Location
                                </button>
                            <?php
                            }
                            ?>
                            <button class="btn btn-info mr-2 p-2" onclick="toggleFilterClass()">
                                <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                            </button>
                            <?php
                            if (hasAccess("swploc", "Add") !== 'false') {
                            ?>
                                <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/locations.php?r=add" class="btn btn-primary mr-2 p-2">
                                    <i class="fa fa-plus ml-1 mr-2"></i> Import New File
                                </a>
                            <?php
                            }
                            ?>

                        </div>
                        <div class="portlet-body">

                            <table id="datatable-1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Dealership</th>
                                        <th>Dealer #</th>
                                        <th>Phone</th>
                                        <th>Main Contact</th>
                                        <th>Cell</th>
                                        <th>Call or Text</th>
                                        <th>Round Trip</th>
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


    <div class="modal fade" id="addNewLocation">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">New Swap Location</h5>
                    <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form id="addNewSwapLocation" autocomplete="off" method="post" action="../php_action/createLocation.php">
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

                    </div>
                    <div class="modal-footer modal-footer-bordered">
                        <button class="btn btn-primary mr-2" type="submit">Create</button>
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showDetails">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal-dialog-scrollable">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Location Details</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">

                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">


                        <div class="form-row">
                            <div class="col-md-12">
                                <h3 class="h3 text-primary text-underline text-center mb-5" id="locHading">
                                    <u>HERB CHAMBERS HONDA BURLINGTON
                                        207753
                                    </u>
                                </h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 m-auto">
                                <p class="h5 text-center">
                                    Address: <span id="locAddress" class="font-weight-bolder"></span>
                                </p>
                                <p class="h5 text-center">
                                    Phone: <span class="font-weight-bolder" id="locphone"> </span> FAX: <span class="font-weight-bolder" id="locfax"> </span>
                                </p>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class="col-md-12 m-auto">
                                <p class="p text-center">
                                    Main Contact: <span class="font-weight-bolder" id="locMcontact"> </span> Cell: <span class="font-weight-bolder" id="locCellandPreffer"> </span>
                                </p>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class="col-md-12 m-auto">
                                <p class="h6 text-center"><u>Estimate Travel Time</u></p>
                                <p class="h6 text-center">
                                    Distance: <span class="font-weight-bolder" id="locDis"></span> Miles Travel Time: <span class="font-weight-bolder" id="loctravelTime"> </span> Round trip: <span class="font-weight-bolder" id="locRoundTrip"> </span>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
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
                <form id="editLocationForm" autocomplete="off" method="post" action="../php_action/editLocation.php">
                    <input type="hidden" name="locId" id="locId">
                    <div class="modal-body">

                        <div class="text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <div class="showResult d-none">

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="edealerno">Dealer #</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="edealerno" name="edealerno">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3"><label for="edealership">Dealership</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="edealership" name="edealership">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3"><label for="eaddress">Address</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="eaddress" name="eaddress">
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="ecity">City</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="ecity" name="ecity">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="estate">State</label>
                                    <div class="form-group">
                                        <!-- <input type="text" class="form-control" id="state" name="state"> -->
                                        <select class="form-control selectpicker w-auto" name="estate" id="estate" data-live-search="true" data-size="4">
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
                                    <label for="ezip">Zip</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="ezip" name="ezip">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="emiles">Miles</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="emiles" name="emiles">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="etravelTime">Travel Time</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="etravelTime" name="etravelTime">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="eroundTrip">Round Trip</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="eroundTrip" name="eroundTrip">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ephone">Phone</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="ephone" name="ephone">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="efax">Fax</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="efax" name="efax">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">

                                <div class="col-md-4 mb-3">
                                    <label for="emcontact">Main Contact</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="emcontact" name="emcontact">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ecell">Cell</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="ecell" name="ecell">
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="epreffer">Preffer Call or Text</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="epreffer" name="epreffer">
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
                            <h3 class="portlet-title">Locations</h3>
                            <div class="portlet-addon mr-2">
                                <div class="nav nav-pills portlet-nav" id="portlet1-tab">
                                    <a class="nav-item nav-link active" id="portlet1-add-tab" data-toggle="tab" href="#portlet1-add">Add Location</a>
                                    <a class="nav-item nav-link" id="portlet2-import-tab" data-toggle="tab" href="#portlet2-import">Import CSV</a>
                                </div>
                            </div>

                            <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/locations.php?r=man" class="btn btn-outline-info">
                                <i class="fa fa-arrow-right"></i>
                                Manage
                            </a>
                        </div>
                        <div class="portlet-body">

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="portlet1-add">

                                    <form id="addLocationForm" autocomplete="off" method="post" action="../php_action/createLocation.php">
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
                                                    <!-- <input type="text" class="form-control" id="state" name="state"> -->
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
                                        <button class="btn btn-primary" type="submit">Submit form</button>
                                    </form>

                                </div>
                                <div class="tab-pane fade" id="portlet2-import">

                                    <div class="form-row p-3">
                                        <div class="col-md-3">
                                            <p class="h4">Import Excel File here!</p>
                                        </div>
                                    </div>
                                    <form id="importLocForm" autocomplete="off" method="post" action="../php_action/importLocations.php" enctype="multipart/form-data">
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
                                                    <a href="./files/SWAP_LOCATIONS.xlsx" download class="btn btn-success float-right">Download Format File</a>
                                                    <p>The following Excel File column sequence should match the image below.</p>
                                                    <code>"DEALER #" "DEALERSHIP" "ADDRESS" "CITY" "STATE" ...</code>
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
<script type="text/javascript" src="../custom/js/locations.js"></script>