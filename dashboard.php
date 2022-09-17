<?php include('php_action/db/core.php') ?>
<?php include('includes/header.php') ?>

<?php
loadDefaultRoles();

if ($_SESSION['userRole'] == $inventorySpecialistID) {
    if (hasAccess("invsplst", "View") === 'true') {
        include('InvSpecialistDashboard.php');
    } else {
        include('includes/footer.php');
    }
} else {

?>
    <style>
        .font-small{
            font-size: 1rem!important;
        }
    </style>

    <div class="content">
        <div class="container-fluid">


            <?php
            if ($_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == 'Admin') {
            ?>


                <div class="row">
                    <div class="col-md-4">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title"> <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=today" class="link-primary"> Today's Average </a></h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="avgN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="avgU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="avgT"></h2>
                                            <span class="widget10-subtitle">All</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title"> <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=today" class="link-primary"> Today's Total </a></h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="todayN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="todayU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="todayT"></h2>
                                            <span class="widget10-subtitle">All</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title"> <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=pending" class="link-primary"> Pending</a></h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="penN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="penU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="penT"></h2>
                                            <span class="widget10-subtitle">All</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="portlet text-center">
                            <div class="widget10 widget10-vertical-md">
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title" id="regC"></h2>
                                        <span class="widget10-subtitle">
                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/registrationProblem.php" class="link-primary">Registration Problems</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title" id="todoC"></h2>
                                        <span class="widget10-subtitle">
                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldTodo.php" class="link-primary">Sales Consultants To Do’s</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title" id="titleC"></h2>
                                        <span class="widget10-subtitle">
                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/wizard/usedCars.php?filter=titleIssue" class="link-primary">Used Cars No Title</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            } else {
            ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title">
                                    <!-- <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=today" class="link-primary"> Today's Average </a> -->
                                    <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=month" class="link-primary"> Sold This Month </a>
                                </h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="currentMonthN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="currentMonthU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="currentMonthT"></h2>
                                            <span class="widget10-subtitle">All</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title"> <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=today" class="link-primary"> Today's Total </a></h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="todayCN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="todayCU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="todayCT"></h2>
                                            <span class="widget10-subtitle">All</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title"> <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=pending" class="link-primary"> Pending</a></h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="penN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="penU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="penT"></h2>
                                            <span class="widget10-subtitle">All</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <div class="row w-100">
                                    <div class="col-sm-6">
                                        <h4 class="portlet-title font-small text-center"> <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/registrationProblem.php" class="link-primary"> Registration Problems</a></h4>
                                    </div>
                                    <div class="col-sm-6">
                                        <h4 class="portlet-title font-small text-center"> <a href="<?php echo  $GLOBALS['siteurl']; ?>/sales/soldTodo.php" class="link-primary"> Sales Consultants To Do’s</a></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="regC"></h2>
                                            <span class="widget10-subtitle">&nbsp;</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title" id="todoC"></h2>
                                            <span class="widget10-subtitle">&nbsp;</span>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Monthly Chart</h3>
                            <!-- <div class="d-flex flex-row " > -->
                            <div class="show d-flex mr-2">
                                <input type="text" class="form-control" name="date_range" data-attribute="date_range" data-id="1" autocomplete="off" />
                            </div>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="searchStatus">
                                <label class="btn btn-flat-primary">
                                    <input type="radio" name="searchStatus" value="lastMonth"> Last Month <span class="badge badge-lg p-1" id="lastMonthCount"></span>
                                </label>
                                <label class="btn btn-flat-primary">
                                    <input type="radio" name="searchStatus" value="thisMonth" id="thisMonth" > This Month <span class="badge badge-lg p-1" id="thisMonthCount"></span>
                                </label>
                            </div>
                            <!-- </div> -->
                        </div>
                        <div class="portlet-body">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- <div class="row">
                    <div class="col-xl-4">
                        <div class="row portlet-row-fill-md h-100">
                            <div class="col-md-7 col-xl-12">
                                <div class="portlet">
                                    <div class="portlet-header">
                                        <div class="portlet-icon"><i class="fa fa-exchange-alt"></i></div>
                                        <h3 class="portlet-title">Revenue change</h3>
                                        <div class="portlet-addon">
                                            <div class="dropdown"><button class="btn btn-label-primary btn-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></button>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                    <a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-poll"></i></div>
                                                        <span class="dropdown-content">Report</span>
                                                    </a><a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-chart-pie"></i>
                                                        </div><span class="dropdown-content">Charts</span>
                                                    </a><a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-chart-line"></i>
                                                        </div><span class="dropdown-content">Statistics</span>
                                                    </a>
                                                    <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-cog"></i></div>
                                                        <span class="dropdown-content">Settings</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="widget-chart-1"></div>
                                        <div class="row mt-4">
                                            <div class="col-6">
                                                <div class="widget4 mb-3">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h6 class="widget4-subtitle">New York</h6>
                                                        </div>
                                                        <div class="widget4-addon">
                                                            <h6 class="widget4-subtitle">60%</h6>
                                                        </div>
                                                    </div>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-warning" style="width: 60%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="widget4">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h6 class="widget4-subtitle">Sydney</h6>
                                                        </div>
                                                        <div class="widget4-addon">
                                                            <h6 class="widget4-subtitle">90%</h6>
                                                        </div>
                                                    </div>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" style="width: 90%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="widget4 mb-3">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h6 class="widget4-subtitle">San Francisco</h6>
                                                        </div>
                                                        <div class="widget4-addon">
                                                            <h6 class="widget4-subtitle">75%</h6>
                                                        </div>
                                                    </div>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-primary" style="width: 75%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="widget4">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h6 class="widget4-subtitle">Tokyo</h6>
                                                        </div>
                                                        <div class="widget4-addon">
                                                            <h6 class="widget4-subtitle">55%</h6>
                                                        </div>
                                                    </div>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-danger" style="width: 55%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-xl-12">
                                <div class="portlet">
                                    <div class="portlet-header">
                                        <div class="portlet-icon"><i class="fa fa-funnel-dollar"></i></div>
                                        <h3 class="portlet-title">Employee salary</h3>
                                    </div>
                                    <div class="carousel carousel-center my-3" id="widget-carousel-nav">
                                        <div class="carousel-item">
                                            <div class="widget6">
                                                <h5 class="widget6-title">Software Engineer</h5><span class="widget6-subtitle">San Francisco</span>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="widget6">
                                                <h5 class="widget6-title">Javascript Developer</h5><span class="widget6-subtitle">Singapore</span>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="widget6">
                                                <h5 class="widget6-title">Marketing Designer</h5><span class="widget6-subtitle">Edinburgh</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="carousel" id="widget-carousel">
                                            <div class="carousel-item">
                                                <div class="rich-list">
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-2.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Angelica Ramos</h4><span class="rich-list-subtitle">$162,700</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$17</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-1.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Airi Satou</h4><span class="rich-list-subtitle">$433,060</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-danger badge-xl">-$127</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-7.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Colleen Hurst</h4><span class="rich-list-subtitle">$205,500</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$56</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-4.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Brielle Williamson</h4><span class="rich-list-subtitle">$86,000</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$6</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-5.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Garrett Winters</h4><span class="rich-list-subtitle">$327,900</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-danger badge-xl">-$25</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <div class="rich-list">
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-1.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Airi Satou</h4><span class="rich-list-subtitle">$433,060</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-danger badge-xl">-$127</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-2.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Angelica Ramos</h4><span class="rich-list-subtitle">$162,700</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$17</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-5.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Garrett Winters</h4><span class="rich-list-subtitle">$327,900</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-danger badge-xl">-$25</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-4.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Brielle Williamson</h4><span class="rich-list-subtitle">$86,000</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$6</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-7.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Colleen Hurst</h4><span class="rich-list-subtitle">$205,500</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$56</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <div class="rich-list">
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-1.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Airi Satou</h4><span class="rich-list-subtitle">$433,060</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-danger badge-xl">-$127</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-7.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Colleen Hurst</h4><span class="rich-list-subtitle">$205,500</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$56</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-4.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Brielle Williamson</h4><span class="rich-list-subtitle">$86,000</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$6</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-5.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Garrett Winters</h4><span class="rich-list-subtitle">$327,900</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-danger badge-xl">-$25</span>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-item">
                                                        <div class="rich-list-prepend">
                                                            <div class="avatar">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-2.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                        <div class="rich-list-content">
                                                            <h4 class="rich-list-title">Angelica Ramos</h4><span class="rich-list-subtitle">$162,700</span>
                                                        </div>
                                                        <div class="rich-list-append"><span class="badge badge-label-success badge-xl">+$17</span>
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
                    <div class="col-xl-4">
                        <div class="row portlet-row-fill-md h-100">
                            <div class="col-md-4 col-xl-12">
                                <div class="portlet">
                                    <div class="portlet-body">
                                        <div class="widget10-item p-0">
                                            <div class="widget10-content">
                                                <h2 class="widget10-title">$27,639</h2><span class="widget10-subtitle">Total revenue</span>
                                            </div>
                                            <div class="widget10-addon">
                                                <div class="avatar avatar-label-primary avatar-circle widget8-avatar m-0">
                                                    <div class="avatar-display"><i class="fa fa-dollar-sign"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget11 widget11-bottom widget-chart-7" data-chart-color="#2196f3" data-chart-label="Revenue" data-chart-currency="true" data-chart-series="6400, 4000, 7600, 6200, 9800, 6400"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-12">
                                <div class="portlet">
                                    <div class="portlet-body">
                                        <div class="widget10-item p-0">
                                            <div class="widget10-content">
                                                <h2 class="widget10-title">87,123</h2><span class="widget10-subtitle">Order received</span>
                                            </div>
                                            <div class="widget10-addon">
                                                <div class="avatar avatar-label-success avatar-circle widget8-avatar m-0">
                                                    <div class="avatar-display"><i class="fa fa-boxes"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget11 widget11-bottom widget-chart-7" data-chart-color="#4caf50" data-chart-label="Order" data-chart-currency="false" data-chart-series="2000, 4000, 3600, 6200, 2800, 6400"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-12">
                                <div class="portlet">
                                    <div class="portlet-body">
                                        <div class="widget10-item p-0">
                                            <div class="widget10-content">
                                                <h2 class="widget10-title">5,726</h2><span class="widget10-subtitle">Unique visits</span>
                                            </div>
                                            <div class="widget10-addon">
                                                <div class="avatar avatar-label-danger avatar-circle widget8-avatar m-0">
                                                    <div class="avatar-display"><i class="fa fa-link"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget11 widget11-bottom widget-chart-7" data-chart-color="#f44336" data-chart-label="Visit" data-chart-currency="false" data-chart-series="560, 400, 480, 340, 780, 640"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="row portlet-row-fill-md h-100">
                            <div class="col-md-6 col-xl-12">
                                <div class="portlet portlet-primary">
                                    <div class="portlet-header">
                                        <div class="portlet-icon"><i class="fa fa-chalkboard"></i></div>
                                        <h3 class="portlet-title">Company summary</h3>
                                        <div class="portlet-addon">
                                            <div class="dropdown"><button class="btn btn-label-light dropdown-toggle" data-toggle="dropdown">June, 2020</button>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                    <a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-poll"></i></div>
                                                        <span class="dropdown-content">Report</span>
                                                    </a><a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-chart-pie"></i>
                                                        </div><span class="dropdown-content">Charts</span>
                                                    </a><a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-chart-line"></i>
                                                        </div><span class="dropdown-content">Statistics</span>
                                                    </a>
                                                    <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                                                        <div class="dropdown-icon"><i class="fa fa-cog"></i></div>
                                                        <span class="dropdown-content">Settings</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="portlet mb-2">
                                            <div class="portlet-body">
                                                <div class="widget5">
                                                    <h4 class="widget5-title">Monthly income</h4>
                                                    <div class="widget5-group">
                                                        <div class="widget5-item"><span class="widget5-info">Total</span> <span class="widget5-value">$65,880</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Change</span> <span class="widget5-value text-success">+15%</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Sales</span> <span class="widget5-value">554</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet mb-2">
                                            <div class="portlet-body">
                                                <div class="widget5">
                                                    <h4 class="widget5-title">Employee amount</h4>
                                                    <div class="widget5-group">
                                                        <div class="widget5-item"><span class="widget5-info">Total</span> <span class="widget5-value">1250</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Change</span> <span class="widget5-value text-danger">-2%</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Active</span> <span class="widget5-value">1120</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet mb-2">
                                            <div class="portlet-body">
                                                <div class="widget5">
                                                    <h4 class="widget5-title">Product sales</h4>
                                                    <div class="widget5-group">
                                                        <div class="widget5-item"><span class="widget5-info">Total</span> <span class="widget5-value">2350</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Change</span> <span class="widget5-value text-success">+10%</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Last
                                                                report</span> <span class="widget5-value">2220</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet mb-0">
                                            <div class="portlet-body">
                                                <div class="widget5">
                                                    <h4 class="widget5-title">Monthly profit</h4>
                                                    <div class="widget5-group">
                                                        <div class="widget5-item"><span class="widget5-info">Total</span> <span class="widget5-value">$502,100</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Change</span> <span class="widget5-value text-success">+15%</span></div>
                                                        <div class="widget5-item"><span class="widget5-info">Last
                                                                month</span> <span class="widget5-value">$453,000</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-footer text-right"><button class="btn btn-label-light">View
                                            all packages</button></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-12">
                                <div class="row portlet-row-fill-sm">
                                    <div class="col-sm-6">
                                        <div class="portlet">
                                            <div class="portlet-header">
                                                <h3 class="portlet-title">Features</h3>
                                                <div class="portet-addon">
                                                    <div class="dropdown"><button class="btn btn-text-secondary btn-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                            <a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-poll"></i></div><span class="dropdown-content">Report</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-chart-pie"></i></div><span class="dropdown-content">Charts</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-chart-line"></i></div><span class="dropdown-content">Statistics</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="widget8">
                                                    <div class="widget8-content">
                                                        <h4 class="widget8-highlight widget8-highlight-lg text-primary">
                                                            34</h4>
                                                        <h6 class="widget8-title">Proposals</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="portlet-footer"><span class="text-muted">Completed:
                                                    <strong>8</strong></span></div>
                                        </div>
                                        <div class="portlet">
                                            <div class="portlet-body">
                                                <div class="widget8">
                                                    <div class="widget8-addon" data-toggle="tooltip" data-placement="right" title="New users for last month"><i class="fa fa-question"></i></div>
                                                    <div class="widget8-content">
                                                        <div class="avatar avatar-label-primary avatar-circle widget8-avatar">
                                                            <div class="avatar-display"><i class="fa fa-users"></i>
                                                            </div>
                                                        </div>
                                                        <h4 class="widget8-highlight">35.2K</h4>
                                                        <h6 class="widget8-title">Users</h6><span class="widget8-subtitle text-success"><i class="fa fa-caret-up"></i> 0.2%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="portlet">
                                            <div class="portlet-header">
                                                <h3 class="portlet-title">Bug</h3>
                                                <div class="portet-addon">
                                                    <div class="dropdown"><button class="btn btn-text-secondary btn-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                            <a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-poll"></i></div><span class="dropdown-content">Report</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-chart-pie"></i></div><span class="dropdown-content">Charts</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-chart-line"></i></div><span class="dropdown-content">Statistics</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="widget8">
                                                    <div class="widget8-content">
                                                        <h4 class="widget8-highlight widget8-highlight-lg text-danger">
                                                            21</h4>
                                                        <h6 class="widget8-title">Report</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="portlet-footer"><span class="text-muted">Fixed:
                                                    <strong>4</strong></span></div>
                                        </div>
                                        <div class="portlet">
                                            <div class="portlet-body">
                                                <div class="widget8">
                                                    <div class="widget8-content">
                                                        <div class="avatar avatar-label-info avatar-circle widget8-avatar">
                                                            <div class="avatar-display"><i class="fa fa-dollar-sign"></i></div>
                                                        </div>
                                                        <h4 class="widget8-highlight">$23K</h4>
                                                        <h6 class="widget8-title">Profit</h6><span class="widget8-subtitle text-danger"><i class="fa fa-caret-down"></i> 1.4%</span>
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
                    <div class="row portlet-row-fill-xl">
                    <div class="col-xl-4">
                        <div class="portlet">
                            <div class="portlet-header">
                                <div class="portlet-icon"><i class="fa fa-bullhorn"></i></div>
                                <h3 class="portlet-title">Trends</h3>
                                <div class="portlet-addon">
                                    <div class="dropdown"><button class="btn btn-label-primary dropdown-toggle" data-toggle="dropdown">Export</button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated"><a class="dropdown-item" href="#">
                                                <div class="dropdown-icon"><i class="fa fa-poll"></i></div><span class="dropdown-content">Report</span>
                                            </a><a class="dropdown-item" href="#">
                                                <div class="dropdown-icon"><i class="fa fa-chart-pie"></i></div>
                                                <span class="dropdown-content">Charts</span>
                                            </a><a class="dropdown-item" href="#">
                                                <div class="dropdown-icon"><i class="fa fa-chart-line"></i></div>
                                                <span class="dropdown-content">Statistics</span>
                                            </a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                                                <div class="dropdown-icon"><i class="fa fa-cog"></i></div><span class="dropdown-content">Customize</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-3" id="widget-chart-6"></div>
                            <div class="portlet-body">
                                <div class="rich-list rich-list-flush">
                                    <div class="rich-list-item">
                                        <div class="rich-list-prepend">
                                            <div class="avatar avatar-label-warning">
                                                <div class="avatar-display"><i class="fab fa-python"></i></div>
                                            </div>
                                        </div>
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title">Python</h4><span class="rich-list-subtitle">Programming language</span>
                                        </div>
                                    </div>
                                    <div class="rich-list-item">
                                        <div class="rich-list-prepend">
                                            <div class="avatar avatar-label-primary">
                                                <div class="avatar-display"><i class="fab fa-facebook"></i></div>
                                            </div>
                                        </div>
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title">Facebook</h4><span class="rich-list-subtitle">Social media</span>
                                        </div>
                                    </div>
                                    <div class="rich-list-item">
                                        <div class="rich-list-prepend">
                                            <div class="avatar avatar-label-danger">
                                                <div class="avatar-display"><i class="fab fa-angular"></i></div>
                                            </div>
                                        </div>
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title">Angular</h4><span class="rich-list-subtitle">Javascript framework</span>
                                        </div>
                                    </div>
                                    <div class="rich-list-item">
                                        <div class="rich-list-prepend">
                                            <div class="avatar avatar-label-secondary">
                                                <div class="avatar-display"><i class="fab fa-apple"></i></div>
                                            </div>
                                        </div>
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title">Apple</h4><span class="rich-list-subtitle">Technology brand</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="row portlet-row-fill-md">
                            <div class="col-md-6">
                                <div class="portlet">
                                    <div class="portlet-header portlet-header-bordered">
                                        <div class="portlet-icon"><i class="fa fa-clipboard-list"></i></div>
                                        <h3 class="portlet-title">Recent activities</h3>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="timeline timeline-timed">
                                            <div class="timeline-item"><span class="timeline-time">10:00</span>
                                                <div class="timeline-pin"><i class="marker marker-circle text-primary"></i></div>
                                                <div class="timeline-content">
                                                    <div><span>Meeting with</span>
                                                        <div class="avatar-group ml-2">
                                                            <div class="avatar avatar-circle">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-1.webp" alt="Avatar image"></div>
                                                            </div>
                                                            <div class="avatar avatar-circle">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-2.webp" alt="Avatar image"></div>
                                                            </div>
                                                            <div class="avatar avatar-circle">
                                                                <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-3.webp" alt="Avatar image"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-item"><span class="timeline-time">12:45</span>
                                                <div class="timeline-pin"><i class="marker marker-circle text-warning"></i></div>
                                                <div class="timeline-content">
                                                    <p class="mb-0">Lorem ipsum dolor sit amit,consectetur eiusmdd
                                                        tempor incididunt ut labore et dolore magna elit enim at
                                                        minim veniam quis nostrud</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item"><span class="timeline-time">14:00</span>
                                                <div class="timeline-pin"><i class="marker marker-circle text-danger"></i></div>
                                                <div class="timeline-content">
                                                    <p class="mb-0">Received a new feedback on <a href="#">GoFinance</a> App product.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item"><span class="timeline-time">15:20</span>
                                                <div class="timeline-pin"><i class="marker marker-circle text-success"></i></div>
                                                <div class="timeline-content">
                                                    <p class="mb-0">Lorem ipsum dolor sit amit,consectetur eiusmdd
                                                        tempor incididunt ut labore et dolore magna.</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item"><span class="timeline-time">17:00</span>
                                                <div class="timeline-pin"><i class="marker marker-circle text-info"></i></div>
                                                <div class="timeline-content">
                                                    <p class="mb-0">Make Deposit <a href="#">USD 700</a> o ESL.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet">
                                    <div class="portlet-header">
                                        <div class="portlet-icon"><i class="fa fa-bell"></i></div>
                                        <h3 class="portlet-title">Notification</h3>
                                        <div class="portlet-addon">
                                            <div class="dropdown"><button class="btn btn-label-primary dropdown-toggle" data-toggle="dropdown">All</button>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                    <a class="dropdown-item" href="#"><span class="badge badge-label-primary">Personal</span> </a><a class="dropdown-item" href="#"><span class="badge badge-label-info">Work</span> </a><a class="dropdown-item" href="#"><span class="badge badge-label-success">Important</span>
                                                    </a><a class="dropdown-item" href="#"><span class="badge badge-label-danger">Company</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="rich-list rich-list-bordered rich-list-action">
                                            <div class="rich-list-item">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-label-info">
                                                        <div class="avatar-display"><i class="fa fa-file-invoice"></i></div>
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h4 class="rich-list-title">New report has been received</h4>
                                                    <span class="rich-list-subtitle">2 min ago</span>
                                                </div>
                                                <div class="rich-list-append">
                                                    <div class="dropdown"><button class="btn btn-text-secondary btn-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                            <a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-check"></i></div><span class="dropdown-content">Mark as read</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-trash-alt"></i></div><span class="dropdown-content">Delete</span>
                                                            </a>
                                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-cog"></i>
                                                                </div><span class="dropdown-content">Settings</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rich-list-item">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-label-success">
                                                        <div class="avatar-display"><i class="fa fa-shopping-basket"></i></div>
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h4 class="rich-list-title">Last order was completed</h4><span class="rich-list-subtitle">1 hrs ago</span>
                                                </div>
                                                <div class="rich-list-append">
                                                    <div class="dropdown"><button class="btn btn-text-secondary btn-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                            <a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-check"></i></div><span class="dropdown-content">Mark as read</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-trash-alt"></i></div><span class="dropdown-content">Delete</span>
                                                            </a>
                                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-cog"></i>
                                                                </div><span class="dropdown-content">Settings</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rich-list-item">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-label-danger">
                                                        <div class="avatar-display"><i class="fa fa-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h4 class="rich-list-title">Company meeting canceled</h4><span class="rich-list-subtitle">5 hrs ago</span>
                                                </div>
                                                <div class="rich-list-append">
                                                    <div class="dropdown"><button class="btn btn-text-secondary btn-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                            <a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-check"></i></div><span class="dropdown-content">Mark as read</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-trash-alt"></i></div><span class="dropdown-content">Delete</span>
                                                            </a>
                                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-cog"></i>
                                                                </div><span class="dropdown-content">Settings</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rich-list-item">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-label-warning">
                                                        <div class="avatar-display"><i class="fa fa-paper-plane"></i></div>
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h4 class="rich-list-title">New feedback received</h4><span class="rich-list-subtitle">6 hrs ago</span>
                                                </div>
                                                <div class="rich-list-append">
                                                    <div class="dropdown"><button class="btn btn-text-secondary btn-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                            <a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-check"></i></div><span class="dropdown-content">Mark as read</span>
                                                            </a><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-trash-alt"></i></div><span class="dropdown-content">Delete</span>
                                                            </a>
                                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                                                                <div class="dropdown-icon"><i class="fa fa-cog"></i>
                                                                </div><span class="dropdown-content">Settings</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="portlet">
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="widget4 mb-3">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h3 class="widget4-subtitle">Completed Transactions</h3>
                                                            <h2 class="widget4-hightlight">54,234</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="widget4">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h3 class="widget4-subtitle">New Orders</h3>
                                                            <h2 class="widget4-hightlight">242</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="widget4 mb-3">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h3 class="widget4-subtitle">Average Product Price</h3>
                                                            <h2 class="widget4-hightlight">$67,50</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="widget4">
                                                    <div class="widget4-group">
                                                        <div class="widget4-display">
                                                            <h2 class="widget4-subtitle">Satisfication Rate</h2>
                                                        </div>
                                                        <div class="widget4-addon">
                                                            <h2 class="widget4-subtitle">90%</h2>
                                                        </div>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-primary" style="width: 90%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet">
                                    <div class="portlet-header portlet-header-bordered">
                                        <div class="portlet-icon"><i class="fa fa-user-tag"></i></div>
                                        <h3 class="portlet-title">User feeds</h3>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="rich-list rich-list-flush">
                                            <div class="rich-list-item flex-column align-items-stretch">
                                                <div class="rich-list-item p-0">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar">
                                                            <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-1.webp" alt="Avatar image"></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">Airi Satou</h4><span class="rich-list-subtitle">Accountant</span>
                                                    </div>
                                                    <div class="rich-list-append"><button class="btn btn-label-primary">Follow</button></div>
                                                </div>
                                                <p class="text-justify mb-0 mt-2">Lorem ipsum dolor sit amet,
                                                    consectetur adipisicing elit. Voluptatem optio libero deleniti
                                                    minus culpa modi, quam rem eius quaerat aut.</p>
                                            </div>
                                            <div class="rich-list-item flex-column align-items-stretch">
                                                <div class="rich-list-item p-0">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar">
                                                            <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-2.webp" alt="Avatar image"></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">Cedric Kelly</h4><span class="rich-list-subtitle">Senior Javascript
                                                            Developer</span>
                                                    </div>
                                                    <div class="rich-list-append"><button class="btn btn-label-primary">Follow</button></div>
                                                </div>
                                                <p class="text-justify mb-0 mt-2">Lorem ipsum dolor sit amet,
                                                    consectetur adipisicing elit. Minus non, in, culpa libero quidem
                                                    consequatur.</p>
                                            </div>
                                            <div class="rich-list-item flex-column align-items-stretch">
                                                <div class="rich-list-item p-0">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar">
                                                            <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-4.webp" alt="Avatar image"></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">Brielle Williamson</h4><span class="rich-list-subtitle">Integration Specialist</span>
                                                    </div>
                                                    <div class="rich-list-append"><button class="btn btn-label-primary">Follow</button></div>
                                                </div>
                                                <p class="text-justify mb-0 mt-2">Lorem ipsum dolor sit amet,
                                                    consectetur adipisicing elit. Recusandae nesciunt blanditiis
                                                    tempora eius accusamus, libero facere amet! Neque quis odio
                                                    dicta dolor, eaque consectetur. Nihil?</p>
                                            </div>
                                            <div class="rich-list-item flex-column align-items-stretch">
                                                <div class="rich-list-item p-0">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar">
                                                            <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-6.webp" alt="Avatar image"></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">Sonya Frost</h4><span class="rich-list-subtitle">Software Engineer</span>
                                                    </div>
                                                    <div class="rich-list-append"><button class="btn btn-label-primary">Follow</button></div>
                                                </div>
                                                <p class="text-justify mb-0 mt-2">Lorem ipsum dolor sit amet,
                                                    consectetur adipisicing elit. Expedita praesentium rem aut
                                                    aliquam perferendis harum molestiae cum beatae, perspiciatis, at
                                                    nisi reprehenderit minus voluptatibus veritatis. Iste laborum
                                                    possimus nobis vero?</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
        </div>
    </div>





<?php
    include('includes/footer.php');
}
?>
<!-- <script type="text/javascript" src="./assets/app/chart/apex-chart.js"></script> -->
<script type="text/javascript" src="./custom/js/dashboard.js"></script>