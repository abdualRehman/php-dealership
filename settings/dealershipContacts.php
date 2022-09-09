<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if (hasAccess("dealership", "Add") === 'false' && hasAccess("dealership", "Edit") === 'false' && hasAccess("dealership", "Remove") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("dealership", "Edit") === 'false') {
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
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Company Dealership Contacts</h3>

                        <?php
                        if (hasAccess("dealership", "Add") !== 'false') {
                            echo ' <button class="btn btn-primary mr-2 p-2" data-toggle="modal" data-target="#addNew">
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
                                    <th>Brand</th>
                                    <th>Dealership Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Zip</th>
                                    <th>Telephone</th>
                                    <th>Fax</th>
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


<div class="modal fade" id="addNew">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">New Dealership Details</h5>
                <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form id="addNewForm" autocomplete="off" method="post" action="../php_action/createDealershipContact.php">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="brand" class="col-form-label">Brand</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="brand" id="brand" placeholder="Brand Name" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="dealership" class="col-form-label">Dealership Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="dealership" id="dealership" placeholder="Dealership Name" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
                            <label for="address" class="col-form-label">Address</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="address" id="address" placeholder="Address" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="col-form-label">City</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="city" id="city" placeholder="City" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="state" class="col-form-label">State</label>
                            <div class="form-group">
                                <select class="form-control selectpicker w-auto" name="state" id="state" data-live-search="true" data-size="4">
                                    <option value="0" selected disabled>State</option>
                                    <option value="MA">MA</option>
                                    <option value="RI" selected>RI</option>
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
                        <div class="col-md-3">
                            <label for="zip" class="col-form-label">Zip</label>
                            <div class="form-group">
                                <input type="text" class="form-control zipCode" name="zip" id="zip" placeholder="Zip" maxlength="5" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="telephone" class="col-form-label">Dealership #</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Telephone" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fax" class="col-form-label">Fax</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="fax" id="fax" placeholder="Fax" autocomplete="off" autofill="off" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal8">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Dealership Details</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editForm" action="../php_action/editDealershipContact.php" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="dealershipId" id="dealershipId">
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="ebrand" class="col-form-label">Brand</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="ebrand" id="ebrand" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="edealership" class="col-form-label">Dealership Name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="edealership" id="edealership" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="eaddress" class="col-form-label">Address</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="eaddress" id="eaddress" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="ecity" class="col-form-label">City</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="ecity" id="ecity" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="estate" class="col-form-label">State</label>
                                    <div class="form-group">
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
                                <div class="col-md-3">
                                    <label for="ezip" class="col-form-label">Zip</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control zipCode" name="ezip" id="ezip" maxlength="5" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="etelephone" class="col-form-label">Dealership #</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="etelephone" id="etelephone" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="efax" class="col-form-label">Fax</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="efax" id="efax" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="egeneralManager" class="col-form-label">General Manager</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="egeneralManager" id="egeneralManager" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="egeneralManagerContact" class="col-form-label">General Manager Contact</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="egeneralManagerContact" id="egeneralManagerContact" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="eusedcarManager" class="col-form-label">Used Car Manager</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="eusedcarManager" id="eusedcarManager" autocomplete="off" autofill="off" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="eusedcarManagerContact" class="col-form-label">Used Car Manager Contact</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="eusedcarManagerContact" id="eusedcarManagerContact" autocomplete="off" autofill="off" />
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


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/dealershipContacts.js"></script>