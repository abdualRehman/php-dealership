<?php

// if user has his add permission and edit permission then he can access this page
if (hasAccess("appointment", "Add") === 'false' && hasAccess("appointment", "Edit") === 'false') {
    echo "<script>location.href='" . $GLOBALS['siteurl'] . "/error.php';</script>";
}
if (hasAccess("appointment", "Edit") === 'false') {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="false" />';
} else {
    echo '<input type="hidden" name="isEditAllowed" id="isEditAllowed" value="true" />';
}
$userRole = $_SESSION['userRole'];
echo '<input type="hidden" name="loggedInUserRole" id="loggedInUserRole" value="' . $userRole . '" />';
echo '<input type="hidden" name="currentUser" id="currentUser" value="' . $_SESSION['userName'] . '">';
echo '<input type="hidden" name="currentUserId" id="currentUserId" value="' . $_SESSION['userId'] . '">';

// setting manager id
$managerID = "";
if ($_SESSION['userRole'] == $bdcManagerID) {
    $managerID = $_SESSION['userId'];
    echo $managerID;
}

?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./custom/css/customDatatable.css">
</head>


<div class="container-fluid mt-3">
    <div class="row m-auto justify-content-center">
        <div class="col-md-4">
            <div class="portlet text-center">
                <div class="portlet-header portlet-header-bordered">
                    <h3 class="portlet-title text-primary"> Last Month </h3>
                </div>
                <div class="portlet-body">
                    <div class="widget10 widget10-vertical-md">
                        <div class="widget10-item">
                            <div class="widget10-content">
                                <h2 class="widget10-title" id="lmconfirmed"></h2>
                                <span class="widget10-subtitle">Deliveries Selected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="portlet text-center">
                <div class="portlet-header portlet-header-bordered">
                    <h3 class="portlet-title text-primary"> This Month</h3>
                </div>
                <div class="portlet-body">
                    <div class="widget10 widget10-vertical-md">
                        <div class="widget10-item">
                            <div class="widget10-content">
                                <h2 class="widget10-title" id="tmconfirmed"></h2>
                                <span class="widget10-subtitle">Deliveries Selected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<?php require_once('./more/deliveryCoordinatorComponent.php') ?>




<?php require_once('includes/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.js"></script>
<script type="text/javascript" src="./custom/js/deliveryCoordinators.js"></script>
<script>
    setNavLink("dashboard") // for setting navlink active
    // $('.nav-link').removeClass('active');
    // $('#dashboard').addClass('active');
</script>