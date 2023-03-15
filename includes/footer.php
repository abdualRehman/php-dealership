<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <p class="text-left mb-0">Copyright <i class="far fa-copyright"></i> <span id="copyright-year"></span> AR Webs. All rights reserved</p>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <p class="text-right mb-0"><a href="https://www.fiverr.com/share/yVzNwz" target="_blank"><i class="fa fa-link text-info"></i> &nbsp; Contact Developer</a></p>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="scrolltop"><button class="btn btn-info btn-icon btn-lg"><i class="fa fa-angle-up"></i></button></div>

<div class="sidemenu sidemenu-right sidemenu-wider" id="sidemenu-todo">
    <div class="sidemenu-header" id="sidemenu-todo-header">
        <h3 class="sidemenu-title" id="todayDate"></h3>
        <div class="sidemenu-addon">
            <button class="btn btn-label-info ml-2 btn-icon" onclick="toggleSidebar(this)">
                <i class="fa fa-expand"></i>
            </button>
        </div>
    </div>
    <div class="sidemenu-body pb-0" id="sidemenu-todo-body" data-simplebar="data-simplebar">
        <div class="portlet p-1 pt-5 pb-5" id="childDiv">
            <table id="datatable-4" class="table table-bordered table-hover m-0" style="margin:0px!important;">
                <thead style="position: sticky; border:1px solid #757575" >
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Availability</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
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
                            <div class="col-md-6">
                                <label class="col-form-label" for="availability">Availability</label>
                                <div class="form-group">
                                    <select class="selectpicker required" name="availability" id="availability">
                                        <option value="" selected disabled>Choose</option>
                                        <option value="Available">Available</option>
                                        <option value="Late">Late</option>
                                        <option value="OFF">Off</option>
                                        <option value="With Customer">With Customer</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Called out">Called out</option>
                                        <option value="Vacation">Vacation</option>
                                        <option value="See Notes">See Notes</option>
                                    </select>
                                </div>
                                <label class="col-form-label" for="offNotes">Off BDC Notes</label>
                                <textarea class="form-control autosize" name="offNotes" id="offNotes"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="availabilityUserName">User Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="availabilityUserName" id="availabilityUserName" disabled />
                                </div>
                                <label class="col-form-label" for="availabilityUserCell">Cell</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="availabilityUserCell" id="availabilityUserCell" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5 mb-5" style="overflow:auto;">
                            <div class="col-md-12 m-auto">
                                <table id="scheduleTable" class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th colspan="7">
                                                <h2>Schedule</h2>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Monday</th>
                                            <th>Tuesday</th>
                                            <th>Wednesday</th>
                                            <th>Thursday</th>
                                            <th>Friday</th>
                                            <th>Saturday</th>
                                            <th>Sunday</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="smonStart" name="smonStart">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="smonEnd" name="smonEnd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="stueStart" name="stueStart">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="stueEnd" name="stueEnd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="swedStart" name="swedStart">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="swedEnd" name="swedEnd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="sthuStart" name="sthuStart">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="sthuEnd" name="sthuEnd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="sfriStart" name="sfriStart">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="sfriEnd" name="sfriEnd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="ssatStart" name="ssatStart">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="ssatEnd" name="ssatEnd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="ssunStart" name="ssunStart">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group was-validated">
                                                            <input type="text" class="form-control timeInterval" id="ssunEnd" name="ssunEnd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
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
<div class="modal fade" id="addWebsiteModal">
    <div class="modal-dialog modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-bordered">
                <h5 class="modal-title">Website Link</h5><button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form class="form-horizontal" id="updateWebsiteForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="webLinkId" id="webLinkId" />
                    <div class="form-row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="webName">Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="webName" name="webName">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="col-form-label" for="visible_role_by">Visible By</label>
                            <div class="form-group">
                                <!-- <input type="text" class="form-control" id="visible_role_by" name="visible_role_by" > -->
                                <select class="form-control selectpicker w-auto" multiple="multiple" name="visible_role_by[]" id="visible_role_by" title="Select" data-size="6" data-actions-box="true">
                                    <?php
                                    $location = (isset($_SESSION['userLoc']) && $_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
                                    $websiteSql = "SELECT role_id, role_name FROM `role` WHERE location_id = '$location' AND role_status != '2'";
                                    $websiteData = $connect->query($websiteSql);

                                    while ($row = $websiteData->fetch_array()) {
                                        echo "<option value='" . $row['role_id'] . "' id='changeRole" . $row['role_id'] . "'>" . $row['role_name'] . "</option>";
                                    } // /while 

                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="col-form-label" for="webLink">Link</label>
                            <textarea class="form-control autosize" name="webLink" id="webLink"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>



<style>
    @media (min-width: 1025px) {

        #editSchedule .modal-lg,
        #editSchedule .modal-xl {
            max-width: 1200px;
        }
    }

    #editSchedule .form-control.is-valid,
    #editSchedule .was-validated .form-control:valid {
        background-image: none !important;
        padding-right: 0px;
    }

    body.theme-light #scheduleTable .was-validated .form-control:valid {
        color: #424242;
        background: #fff;
        border-color: #e0e0e0;
        padding: 5px !important;
        background-image: none !important;
    }

    body.theme-dark #scheduleTable .was-validated .form-control:valid {
        color: #f5f5f5;
        background: #424242;
        border-color: #9e9e9e;
        padding: 5px !important;
        background-image: none !important;
    }

    .ui-timepicker-wrapper {
        width: 8.5em !important;
    }
</style>


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
<link href="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/custom/js/footer.js"></script>

<script>
    let statusBarDiv = $('#statusBar').hasClass('d-none');
    if (statusBarDiv == false) {
        var siteLink = localStorage.getItem('siteURL')
        var timer = null,
            delay = 5000,
            maxCounter = 1;

        function checkAndDisplayNewResults() {
            let statusBarDiv = $('#statusBar').hasClass('d-none');
            // var audio = new Audio('notification.mp3');
            var song = siteLink + '/includes/notification.mp3';
            var audio = new Audio(song);
            if (siteLink && !statusBarDiv && maxCounter < 20) {
                $.ajax({
                    url: siteLink + '/php_action/getTodayData.php',
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {
                        var obj = response.data;
                        if (obj) {
                            $('#todaySoldStatus').html(`Sold Today: ${obj[0]} NEW, ${obj[1]} USED, ${obj[2]} Total`);
                            $('#etodaySoldStatus').html(`Sold Today: ${obj[0]} NEW, ${obj[1]} USED, ${obj[2]} Total`);
                        }

                        // -------------------------------------------------------------------------------
                        // load notificaiton
                        let array = response.notifications;
                        var elements = document.getElementsByClassName('notification-list');
                        elements.forEach(element => {
                            element.innerHTML = '';
                            let delivered = array.some(item => item[6] == 0);
                            if (delivered == true) {
                                var playPromise = audio.play();
                            }
                            let markerCount = 0;
                            let marker = array.some(item => item[5] == 0);
                            if (marker == true) {
                                $('#notificationDropdown .btn-marker').removeClass('d-none');
                            } else {
                                $('#notificationDropdown .btn-marker').addClass('d-none');
                            }
                            array.forEach(notification => {
                                if (notification[5] == 0) {
                                    markerCount += 1;
                                }
                                element.innerHTML += `<div class="rich-list rich-list-action">
                                                <a href="${siteLink}/more/deliveryCoordinators.php?filter=${notification[4]}&id=${notification[0]}" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-info">
                                                            <div class="avatar-display"><i class="fa fa-paper-plane"></i></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">${notification[3]}</h4>
                                                        <span class="rich-list-subtitle">${moment(notification[1] + "+00:00", "YYYY-MM-DD HH:mm:ssZ").fromNow()}</span>
                                                    </div>
                                                    <button class="btn btn-icon text-white rich-list-append" data-action="${notification[5]}" onclick="handle_notification( ${notification[0]} , this )" >
                                                        ${notification[5] == '0' ? '<i class="fa fa-solid fa-envelope"></i>' : '<i class="fa fa-solid fa-envelope-open"></i>'}
                                                    </button>
                                                </a>
                                            </div>`;
                            });
                            $('#notificationDropdown .btn-marker .btn-counter').html(markerCount);
                        });

                        maxCounter++;
                    },
                    error: function(jqXHR, textStatus, errorthrown) {
                        console.log(errorthrown);
                    }
                });
            }
            // when the work is finished, set a timer to call again the function
            timer = setTimeout(checkAndDisplayNewResults, delay);
        }
        // call the function immediately
        setTimeout(() => {
            checkAndDisplayNewResults();
        }, 3000);
    }

    function handle_notification(id, element = null) {
        if (id) {
            if (element != null) {
                event.stopPropagation();
                event.preventDefault();
            }

            let status = element != null ? $(element).data('action') : "";
            let siteLink = localStorage.getItem('siteURL')
            $.ajax({
                url: siteLink + '/php_action/marknotification.php',
                type: "POST",
                data: {
                    id,
                    id
                },
                dataType: 'json',
                success: function(response) {
                    if (element != null) {
                        if (status == 0) {
                            $(element).html('<i class="fa fa-solid fa-envelope-open"></i>');
                        } else {
                            $(element).html('<i class="fa fa-solid fa-envelope"></i>');
                        }
                        $(element).data('action', (1 - Number(status)));
                    }
                }
            });
        }
    }

    function remove_notification() {
        event.stopPropagation();
        event.preventDefault();
        let siteLink = localStorage.getItem('siteURL')
        $.ajax({
            url: siteLink + '/php_action/removeNotification.php',
            type: "POST",
            dataType: 'json',
            success: function(response) {
                console.log(response);
                e1.fire({
                    position: "center",
                    icon: "success",
                    title: response.messages,
                    showConfirmButton: !1,
                    timer: 1500
                });
                var elements = document.getElementsByClassName('notification-list');
                elements.forEach(element => {
                    element.innerHTML = '';
                });

            }
        });
    }

    $(function() {

        // let availablityTable = document.getElementById('sidemenu-todo');
        // if(availablityTable){
        //     console.log(availablityTable);
        // window.onscroll = function () { availablityTableScrollFun() };
        // }    
    });

    // function availablityTableScrollFun() {
    //     console.log("dqwas");
    //     // var desktopH = document.getElementById("sticky-header-desktop");
    //     // var mobileH = document.getElementById("sticky-header-mobile");
    //     // var datatableHeader = $('.table.fixedHeader-floating');
    //     // manageWritedownTable?.fixedHeader.headerOffset(desktopH.offsetHeight + mobileH.offsetHeight - 3);
    //     // if ($(availablityTable).width() < 580) {
    //     //     manageWritedownTable?.fixedHeader.headerOffset(mobileH.offsetHeight - 3);
    //     // }
    //     // if (datatableHeader.length > 0) {
    //     //     let topV = desktopH.offsetHeight + mobileH.offsetHeight - 3;
    //     //     datatableHeader[0].style.top = `${topV}px`;
    //     // }
    // }
</script>


</body>

</html>