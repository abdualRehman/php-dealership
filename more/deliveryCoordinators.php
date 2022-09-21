<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';
// if user has his add permission and edit permission then he can access this page
if (hasAccess("appointment", "View") === 'false') {
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
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>
<?php require_once('./deliveryCoordinatorComponent.php') ?>




<?php require_once('../includes/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.js"></script>
<script type="text/javascript" src="../custom/js/deliveryCoordinators.js"></script>
<script>
    setNavLink() // for setting navlink active
</script>