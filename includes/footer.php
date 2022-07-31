<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <p class="text-left mb-0">Copyright <i class="far fa-copyright"></i> <span id="copyright-year"></span> AR Webs. All rights reserved</p>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <p class="text-right mb-0">Hand-crafted and made with <i class="fa fa-heart text-danger"></i></p>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="scrolltop"><button class="btn btn-info btn-icon btn-lg"><i class="fa fa-angle-up"></i></button></div>

<div class="sidemenu sidemenu-right sidemenu-wider" id="sidemenu-todo">
    <div class="sidemenu-header">
        <h3 class="sidemenu-title" id="todayDate"></h3>
        <div class="sidemenu-addon">
            <button class="btn btn-label-info ml-2 btn-icon" onclick="toggleSidebar(this)">
                <i class="fa fa-expand"></i>
            </button>
        </div>
    </div>
    <div class="sidemenu-body pb-0" data-simplebar="data-simplebar">
        <div class="portlet p-1 pt-5 pb-5">
            <table id="datatable-4" class="table table-bordered m-0" style="margin:0px!important;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Availibility</th>
                        <th>Sales Consultant</th>
                        <th>OFF BDC Notes</th>
                        <th>Manager</th>
                        <th>SUN</th>
                        <th>MON</th>
                        <th>TUE</th>
                        <th>WED</th>
                        <th>THU</th>
                        <th>FRI</th>
                        <th>SAT</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="sidemenu sidemenu-right sidemenu-wider" id="sidemenu-settings">
    <div class="sidemenu-header">
        <h3 class="sidemenu-title">Settings</h3>
        <div class="sidemenu-addon"><button class="btn btn-label-danger btn-icon" data-dismiss="sidemenu"><i class="fa fa-times"></i></button></div>
    </div>
    <div class="sidemenu-body pb-0" data-simplebar="data-simplebar">
        <div class="portlet portlet-bordered">
            <div class="portlet-header portlet-header-bordered">
                <div class="portlet-icon"><i class="fa fa-bolt"></i></div>
                <h3 class="portlet-title">Performance</h3>
            </div>
            <div class="portlet-body">
                <div class="widget4 mb-3">
                    <div class="widget4-group">
                        <div class="widget4-display">
                            <h6 class="widget4-subtitle">CPU Load</h6>
                        </div>
                        <div class="widget4-addon">
                            <h6 class="widget4-subtitle text-info">60%</h6>
                        </div>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-info" style="width: 60%"></div>
                    </div>
                </div>
                <div class="widget4 mb-3">
                    <div class="widget4-group">
                        <div class="widget4-display">
                            <h6 class="widget4-subtitle">CPU Temparature</h6>
                        </div>
                        <div class="widget4-addon">
                            <h6 class="widget4-subtitle text-success">42°</h6>
                        </div>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: 30%"></div>
                    </div>
                </div>
                <div class="widget4">
                    <div class="widget4-group">
                        <div class="widget4-display">
                            <h6 class="widget4-subtitle">RAM Usage</h6>
                        </div>
                        <div class="widget4-addon">
                            <h6 class="widget4-subtitle text-danger">6,532 MB</h6>
                        </div>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" style="width: 80%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet portlet-bordered">
            <div class="portlet-header">
                <h3 class="portlet-title">Customer care</h3>
            </div>
            <div class="portlet-body">
                <div class="form-group">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting1"> <label class="custom-control-label" for="generalSetting1">Enable notifications</label></div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting2" checked="checked"> <label class="custom-control-label" for="generalSetting2">Enable case tracking</label></div>
                </div>
                <div class="form-group mb-0">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting3"> <label class="custom-control-label" for="generalSetting3">Support portal</label></div>
                </div>
            </div>
        </div>
        <div class="portlet portlet-bordered">
            <div class="portlet-header">
                <h3 class="portlet-title">Reports</h3>
            </div>
            <div class="portlet-body">
                <div class="form-group">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting4"> <label class="custom-control-label" for="generalSetting4">Generate reports</label></div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting5" checked="checked"> <label class="custom-control-label" for="generalSetting5">Enable report export</label></div>
                </div>
                <div class="form-group mb-0">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting6"> <label class="custom-control-label" for="generalSetting6">Allow data</label></div>
                </div>
            </div>
        </div>
        <div class="portlet portlet-bordered">
            <div class="portlet-header">
                <h3 class="portlet-title">Projects</h3>
            </div>
            <div class="portlet-body">
                <div class="form-group">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting7"> <label class="custom-control-label" for="generalSetting7">Enable create projects</label></div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting8" checked="checked"> <label class="custom-control-label" for="generalSetting8">Enable custom projects</label></div>
                </div>
                <div class="form-group mb-0">
                    <div class="custom-control custom-control-lg custom-switch"><input type="checkbox" class="custom-control-input" id="generalSetting9"> <label class="custom-control-label" for="generalSetting9">Enable project review</label></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editSchedule">
    <div class="modal-dialog modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Edit Availability</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="editAvailibilityForm" method="post">
                <div class="modal-body">
                    <div id="edit-messages"></div>
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <input type="hidden" name="shceduleId" id="shceduleId" />
                        <h3 class="h4">Today's Availability</h3>
                        <div class="form-row">
                            <div class="col-md-12">
                                <label class="col-form-label" for="availability">Availability</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="availability" id="availability">
                                        <option value="" selected disabled>Choose</option>
                                        <option value="Available">Available</option>
                                        <option value="With Customer">With Customer</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="OFF">Off</option>
                                        <option value="Called out">Called out</option>
                                        <option value="Vacation">Vacation</option>
                                        <option value="See Notes">See Notes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label" for="offNotes">Off BDC Notes</label>
                                <textarea class="form-control autosize" name="offNotes" id="offNotes"></textarea>
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



<div class="float-btn float-btn-right">
    <button class="btn btn-flat-primary btn-icon mb-2" id="theme-toggle" data-toggle="tooltip" data-placement="right" title="Change theme"><i class="fa fa-moon"></i></button>
</div>
<script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/assets/build/scripts/mandatory.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/assets/build/scripts/core.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/assets/build/scripts/vendor1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-lightbox/0.2.12/slick-lightbox.min.js"></script>

<!-- <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script> -->

<!-- <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script> -->
<script src="https://cdn.datatables.net/rowgroup/1.0.2/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/custom/js/footer.js"></script>
</body>

</html>