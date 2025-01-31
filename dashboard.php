<?php include('php_action/db/core.php') ?>
<?php include('includes/header.php') ?>

<?php
loadDefaultRoles();

if ($_SESSION['userRole'] === $_SESSION['deliveryCoordinatorID']) {
    include('deliveryCoordinatorDashboard.php');
} else if ($_SESSION['userRole'] === $_SESSION['inventorySpecialistID']) {
    if (hasAccess("invsplst", "View") === 'true') {
        include('InvSpecialistDashboard.php');
    } else {
        include('includes/footer.php');
    }
} else if ($_SESSION['userRole'] === $_SESSION['serviceID']) {
    if (hasAccess("lotWizards", "View") === 'true') {
        echo "<script type='text/javascript'>window.location.href='{$siteurl}/wizard/lotwizards.php'</script>";
        exit;
    } else {
        include('includes/footer.php');
    }
} else if (
    $_SESSION['userRole'] === $_SESSION['bdcManagerID'] ||
    $_SESSION['userRole'] === $_SESSION['bdcSalesID'] ||
    $_SESSION['userRole'] === $_SESSION['ccsID']
) {
    if (hasAccess("bdc", "View") === 'true') {
        // echo ("<script>location.href = '" . $siteurl . "/more/bdc.php';</script>");
        echo "<script type='text/javascript'>window.location.href='{$siteurl}/more/bdc.php'</script>";
        exit;
    } else {
        include('includes/footer.php');
    }
} else {
?>
    <style>
        .font-small {
            font-size: 1rem !important;
        }
    </style>

    <div class="content">
        <div class="container-fluid">


            <?php
            if ($_SESSION['userRole'] == $_SESSION['officeID'] || $_SESSION['userRole'] == $_SESSION['financeManagerID']) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet text-center">
                            <div class="widget10 widget10-vertical-md">
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title responsive-content-title" id="regC"></h2>
                                        <span class="widget10-subtitle">
                                            <?php
                                            echo (hasAccess("regp", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/registrationProblem.php" class="link-primary">Registration Problems</a>' : 'Registration Problems';
                                            ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title responsive-content-title" id="titleC"></h2>
                                        <span class="widget10-subtitle">
                                            <?php
                                            echo (hasAccess("usedCars", "View") !== 'false' || hasAccess("usedCars", "TitleView") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/wizard/usedCars.php?filter=titleIssue" class="link-primary">Used Cars No Title</a>' : 'Used Cars No Title';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title responsive-content-title" id="warrantyC"></h2>
                                        <span class="widget10-subtitle">
                                            <?php
                                            echo (hasAccess("warranty", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/more/warrantyCancellation.php" class="link-primary">Warranty Cancellations</a>' : 'Warranty Cancellations';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            } else if ($_SESSION['userRole'] ==  $_SESSION['salesManagerID'] || $_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == 'branchAdmin' || $_SESSION['userRole'] == $_SESSION['generalManagerID']) {
            ?>


                <div class="row">
                    <div class="col-md-4">
                        <div class="portlet text-center">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title">
                                    <?php
                                    echo (hasAccess("sale", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=man&filter=today" class="link-primary">Today Average</a>' : 'Today Average';
                                    ?>
                                </h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="avgN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="avgU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="avgT"></h2>
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
                                <h3 class="portlet-title">
                                    <?php
                                    echo (hasAccess("sale", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=man&filter=today" class="link-primary">Today Total</a>' : 'Today Total';
                                    ?>
                                </h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="todayN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="todayU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="todayT"></h2>
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
                                <h3 class="portlet-title">
                                    <?php
                                    echo (hasAccess("sale", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=man&filter=pending" class="link-primary">Pending</a>' : 'Pending';
                                    ?>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="penN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="penU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="penT"></h2>
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
                                        <h2 class="widget10-title responsive-content-title" id="regC"></h2>
                                        <span class="widget10-subtitle">
                                            <?php
                                            echo (hasAccess("regp", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/registrationProblem.php" class="link-primary">Registration Problems</a>' : 'Registration Problems';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title responsive-content-title" id="todoC"></h2>
                                        <span class="widget10-subtitle">
                                            <?php
                                            echo (hasAccess("todo", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldTodo.php" class="link-primary">Sales Consultants To Do’s</a>' : 'Sales Consultants To Do’s';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="widget10-item">
                                    <div class="widget10-content">
                                        <h2 class="widget10-title responsive-content-title" id="titleC"></h2>
                                        <span class="widget10-subtitle">
                                            <?php
                                            echo (hasAccess("usedCars", "View") !== 'false' || hasAccess("usedCars", "TitleView") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/wizard/usedCars.php?filter=titleIssue" class="link-primary">Used Cars No Title</a>' : 'Used Cars No Title';
                                            ?>
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
                                    <?php
                                    echo (hasAccess("sale", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=man&filter=month" class="link-primary">Sold This Month</a>' : 'Sold This Month';
                                    ?>
                                </h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="currentMonthN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="currentMonthU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="currentMonthT"></h2>
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
                                <h3 class="portlet-title">
                                    <?php
                                    echo (hasAccess("sale", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=man&filter=today" class="link-primary">Today Total</a>' : 'Today Total';
                                    ?>
                                </h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="todayCN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="todayCU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>

                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="todayCT"></h2>
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
                                <h3 class="portlet-title">
                                    <?php
                                    echo (hasAccess("sale", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=man&filter=pending" class="link-primary">Pending</a>' : 'Pending';
                                    ?>
                                </h3>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="penN"></h2>
                                            <span class="widget10-subtitle">New</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="penU"></h2>
                                            <span class="widget10-subtitle">Used</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="penT"></h2>
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
                                    <div class="col-sm-4">
                                        <h4 class="portlet-title font-small text-center">
                                            <?php
                                            echo (hasAccess("regp", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/registrationProblem.php" class="link-primary">Registration Problems</a>' : 'Registration Problems';
                                            ?>
                                        </h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4 class="portlet-title font-small text-center">
                                            <?php
                                            echo (hasAccess("todo", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldTodo.php" class="link-primary">To Do’s</a>' : 'To Do’s';
                                            ?>
                                        </h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4 class="portlet-title font-small text-center">
                                            <?php
                                            echo (hasAccess("todo", "View") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/sales/soldLogs.php?r=man&filter=cards" class="link-primary">Cards</a>' : 'Cards';
                                            ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="widget10 widget10-vertical-md">
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="regC"></h2>
                                            <span class="widget10-subtitle">&nbsp;</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="todoC"></h2>
                                            <span class="widget10-subtitle">&nbsp;</span>
                                        </div>
                                    </div>
                                    <div class="widget10-item">
                                        <div class="widget10-content">
                                            <h2 class="widget10-title responsive-content-title" id="cardsC"></h2>
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

            <?php
            if ($_SESSION['userRole'] != $_SESSION['officeID'] && $_SESSION['userRole'] != $_SESSION['financeManagerID']) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-header portlet-header-bordered">
                                <h3 class="portlet-title">Monthly Chart</h3>
                                <input type="hidden" name="uid_graph" id="uid_graph" value="<?php echo ($_SESSION['userRole'] == $salesConsultantID) ? $_SESSION['userId'] : "null"; ?>" />
                                <div class="form-group m-auto">
                                    <div class="custom-control custom-control-lg custom-switch btn-lg">
                                        <input type="checkbox" class="custom-control-input" id="changeView" name="changeView">
                                        <label class="custom-control-label" for="changeView">&nbsp;</label>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="custom-control custom-control-lg custom-checkbox mr-3">
                                        <input type="checkbox" class="custom-control-input" id="activeUserGraph" checked />
                                        <label class="custom-control-label" for="activeUserGraph">
                                            Active</label>
                                    </div>
                                    <div class="custom-control custom-control-lg custom-checkbox mr-3">
                                        <input type="checkbox" class="custom-control-input" id="inActiveUserGraph">
                                        <label class="custom-control-label" for="inActiveUserGraph">
                                            In Active</label>
                                    </div>

                                </div>
                                <div class="show d-flex mr-2">
                                    <input type="text" class="form-control" name="date_range" data-attribute="date_range" data-id="1" autocomplete="off" />
                                </div>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="searchStatus">
                                    <label class="btn btn-flat-primary">
                                        <input type="radio" name="searchStatus" value="lastMonth"> Last Month <span class="badge badge-lg p-1" id="lastMonthCount"></span>
                                    </label>
                                    <label class="btn btn-flat-primary">
                                        <input type="radio" name="searchStatus" value="thisMonth" id="thisMonth"> This Month <span class="badge badge-lg p-1" id="thisMonthCount"></span>
                                    </label>
                                </div>
                                <!-- </div> -->
                            </div>
                            <div class="portlet-body">
                                <div id="chart"></div>
                                <div id="chartTableDiv" class="d-none">
                                    <table id="chartTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center!important;">Rank</th>
                                                <th style="text-align: center!important;">Sales Consultant</th>
                                                <th style="text-align: center!important;">Used</th>
                                                <th style="text-align: center!important;">New</th>
                                                <th style="text-align: center!important;">Total</th>
                                                <th id="max_number_row"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                echo '<div class="d-none" id="chart"></div>';
                echo '<div class="d-none" id="chartTable"></div>';
            }
            ?>

        </div>
    </div>



    <?php include('includes/footer.php'); ?>
    <script type="text/javascript" src="./custom/js/dashboard.js"></script>
    <script>
        $('.nav-link').removeClass('active');
        $('#dashboard').addClass('active');
    </script>

<?php
}
?>