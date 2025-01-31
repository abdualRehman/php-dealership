<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {

    $filter = array();
    function addPermissions($roleId, $module, $func, $permission)
    {
        global $connect;
        $sql = "UPDATE `role_mod` SET `permission` = '$permission' WHERE `role_id` = '$roleId' AND `modules` = '$module' AND `functions` = '$func'";
        $connect->query($sql) === true;
    }

    $role_id = $_POST['roleId'];


    $editRoleName = $_POST['editRoleName'];
    $editRoleDes = $_POST['editRoleDes'];

    // $userAdd = $_POST['userAdd'];
    // $userEdit = $_POST['userEdit'];
    // $userRemove = $_POST['userRemove'];
    // $userView = $_POST['userView'];

    // $invAdd = $_POST['invAdd'];
    // $invEdit = $_POST['invEdit'];
    // $invRemove = $_POST['invRemove'];
    // $invView = $_POST['invView'];


    $sql = "UPDATE `role` SET `role_name` = '$editRoleName' , `role_des` = '$editRoleDes' WHERE `role_id` = '$role_id' ";


    $AddFurther = false;
    if ($connect->query($sql) === true) {
        $AddFurther = true;
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }


    if ($AddFurther == true) {
        // users
        // addPermissions($roleId , 'users' , 'Add' , $userAdd);
        // addPermissions($roleId , 'users' , 'Edit' , $userEdit);
        // addPermissions($roleId , 'users' , 'Remove' , $userRemove);
        // addPermissions($roleId , 'users' , 'View' , $userView);

        // matrix
        addPermissions($role_id, 'matrix', 'View', (isset($_POST['matrixView'])) ? "true" : "false");

        // swap
        addPermissions($role_id, 'swap', 'Add', (isset($_POST['swapAdd'])) ? "true" : "false");
        addPermissions($role_id, 'swap', 'Edit', (isset($_POST['swapEdit'])) ? "true" : "false");
        addPermissions($role_id, 'swap', 'Remove', (isset($_POST['swapRemove'])) ? "true" : "false");
        addPermissions($role_id, 'swap', 'View', (isset($_POST['swapView'])) ? "true" : "false");

        // INCENTIVES
        addPermissions($role_id, 'incentives', 'Edit', (isset($_POST['incentivesEdit'])) ? "true" : "false");
        addPermissions($role_id, 'incentives', 'View', (isset($_POST['incentivesView'])) ? "true" : "false");
        // INVENTORY
        addPermissions($role_id, 'inventory', 'Add', (isset($_POST['invAdd'])) ? "true" : "false");
        addPermissions($role_id, 'inventory', 'Edit', (isset($_POST['invEdit'])) ? "true" : "false");
        addPermissions($role_id, 'inventory', 'Remove', (isset($_POST['invRemove'])) ? "true" : "false");
        addPermissions($role_id, 'inventory', 'View', (isset($_POST['invView'])) ? "true" : "false");
        // sale
        addPermissions($role_id, 'sale', 'Add', (isset($_POST['saleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'sale', 'Edit', (isset($_POST['saleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'sale', 'Remove', (isset($_POST['saleRemove'])) ? "true" : "false");
        addPermissions($role_id, 'sale', 'View', (isset($_POST['saleView'])) ? "true" : "false");
        addPermissions($role_id, 'sale', 'Details', (isset($_POST['saleDetails'])) ? "true" : "false");

        // todo
        addPermissions($role_id, 'todo', 'Edit', (isset($_POST['todoEdit'])) ? "true" : "false");
        addPermissions($role_id, 'todo', 'View', (isset($_POST['todoView'])) ? "true" : "false");

        // regp
        addPermissions($role_id, 'regp', 'Add', (isset($_POST['regpAdd'])) ? "true" : "false");
        addPermissions($role_id, 'regp', 'Edit', (isset($_POST['regpEdit'])) ? "true" : "false");
        addPermissions($role_id, 'regp', 'Remove', (isset($_POST['regpRemove'])) ? "true" : "false");
        addPermissions($role_id, 'regp', 'View', (isset($_POST['regpView'])) ? "true" : "false");
        // users
        addPermissions($role_id, 'user', 'Add', (isset($_POST['userAdd'])) ? "true" : "false");
        addPermissions($role_id, 'user', 'Edit', (isset($_POST['userEdit'])) ? "true" : "false");
        addPermissions($role_id, 'user', 'Remove', (isset($_POST['userRemove'])) ? "true" : "false");
        addPermissions($role_id, 'user', 'View', (isset($_POST['userView'])) ? "true" : "false");

        // role
        addPermissions($role_id, 'role', 'Add', (isset($_POST['roleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'role', 'Edit', (isset($_POST['roleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'role', 'Remove', (isset($_POST['roleRemove'])) ? "true" : "false");

        // incr
        addPermissions($role_id, 'incr', 'Add', (isset($_POST['incrAdd'])) ? "true" : "false");
        addPermissions($role_id, 'incr', 'Edit', (isset($_POST['incrEdit'])) ? "true" : "false");
        addPermissions($role_id, 'incr', 'Remove', (isset($_POST['incrRemove'])) ? "true" : "false");
        addPermissions($role_id, 'incr', 'View', (isset($_POST['incrView'])) ? "true" : "false");

        // sptr
        addPermissions($role_id, 'sptr', 'Add', (isset($_POST['sptrAdd'])) ? "true" : "false");
        addPermissions($role_id, 'sptr', 'Edit', (isset($_POST['sptrEdit'])) ? "true" : "false");
        addPermissions($role_id, 'sptr', 'Remove', (isset($_POST['sptrRemove'])) ? "true" : "false");
        addPermissions($role_id, 'sptr', 'View', (isset($_POST['sptrView'])) ? "true" : "false");

        // swploc
        addPermissions($role_id, 'swploc', 'Add', (isset($_POST['swplocAdd'])) ? "true" : "false");
        addPermissions($role_id, 'swploc', 'Edit', (isset($_POST['swplocEdit'])) ? "true" : "false");
        addPermissions($role_id, 'swploc', 'Remove', (isset($_POST['swplocRemove'])) ? "true" : "false");
        addPermissions($role_id, 'swploc', 'View', (isset($_POST['swplocView'])) ? "true" : "false");

        // manprice
        addPermissions($role_id, 'manprice', 'Add', (isset($_POST['manpriceAdd'])) ? "true" : "false");
        addPermissions($role_id, 'manprice', 'Edit', (isset($_POST['manpriceEdit'])) ? "true" : "false");
        addPermissions($role_id, 'manprice', 'Remove', (isset($_POST['manpriceRemove'])) ? "true" : "false");
        addPermissions($role_id, 'manprice', 'View', (isset($_POST['manpriceView'])) ? "true" : "false");

        // matrixrule
        addPermissions($role_id, 'matrixrule', 'Add', (isset($_POST['matrixruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'matrixrule', 'Edit', (isset($_POST['matrixruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'matrixrule', 'Remove', (isset($_POST['matrixruleRemove'])) ? "true" : "false");
        addPermissions($role_id, 'matrixrule', 'View', (isset($_POST['matrixruleView'])) ? "true" : "false");

        // bdcrule
        addPermissions($role_id, 'bdcrule', 'Add', (isset($_POST['bdcruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'bdcrule', 'Edit', (isset($_POST['bdcruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'bdcrule', 'Remove', (isset($_POST['bdcruleRemove'])) ? "true" : "false");
        addPermissions($role_id, 'bdcrule', 'View', (isset($_POST['bdcruleView'])) ? "true" : "false");

        // raterule
        addPermissions($role_id, 'raterule', 'Add', (isset($_POST['rateruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'raterule', 'Edit', (isset($_POST['rateruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'raterule', 'Remove', (isset($_POST['rateruleRemove'])) ? "true" : "false");
        addPermissions($role_id, 'raterule', 'View', (isset($_POST['rateruleView'])) ? "true" : "false");

        // leaseruleAdd
        addPermissions($role_id, 'leaserule', 'Add', (isset($_POST['leaseruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'leaserule', 'Edit', (isset($_POST['leaseruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'leaserule', 'Remove', (isset($_POST['leaseruleRemove'])) ? "true" : "false");
        addPermissions($role_id, 'leaserule', 'View', (isset($_POST['leaseruleView'])) ? "true" : "false");

        // leaseruleAdd
        addPermissions($role_id, 'cashincrule', 'Add', (isset($_POST['cashincruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'cashincrule', 'Edit', (isset($_POST['cashincruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'cashincrule', 'Remove', (isset($_POST['cashincruleRemove'])) ? "true" : "false");
        addPermissions($role_id, 'cashincrule', 'View', (isset($_POST['cashincruleView'])) ? "true" : "false");

        // lotWizards
        // addPermissions($role_id, 'lotWizards', 'Add', (isset($_POST['lotWizardsAdd'])) ? "true" : "false");
        addPermissions($role_id, 'lotWizards', 'Edit', (isset($_POST['lotWizardsEdit'])) ? "true" : "false");
        addPermissions($role_id, 'lotWizards', 'View', (isset($_POST['lotWizardsView'])) ? "true" : "false");

        // used Cars
        // addPermissions($role_id, 'usedCars', 'Add', (isset($_POST['usedCarsAdd'])) ? "true" : "false");
        addPermissions($role_id, 'usedCars', 'Edit', (isset($_POST['usedCarsEdit'])) ? "true" : "false");
        addPermissions($role_id, 'usedCars', 'View', (isset($_POST['usedCarsView'])) ? "true" : "false");
        addPermissions($role_id, 'usedCars', 'TitleView', (isset($_POST['usedCarsTitleView'])) ? "true" : "false");
        addPermissions($role_id, 'usedCars', 'TitleEdit', (isset($_POST['usedCarsTitleEdit'])) ? "true" : "false");

        // bodyshops contact
        addPermissions($role_id, 'bodyshops', 'Add', (isset($_POST['bodyshopsAdd'])) ? "true" : "false");
        addPermissions($role_id, 'bodyshops', 'Edit', (isset($_POST['bodyshopsEdit'])) ? "true" : "false");
        addPermissions($role_id, 'bodyshops', 'Remove', (isset($_POST['bodyshopsRemove'])) ? "true" : "false");
        addPermissions($role_id, 'bodyshops', 'View', (isset($_POST['bodyshopsView'])) ? "true" : "false");

        // Matrix Files Upload
        addPermissions($role_id, 'matrixfile', 'Add', (isset($_POST['matrixfileAdd'])) ? "true" : "false");


        // Inv Specialist - Dashboard
        addPermissions($role_id, 'invsplst', 'Edit', (isset($_POST['invsplstEdit'])) ? "true" : "false");
        addPermissions($role_id, 'invsplst', 'View', (isset($_POST['invsplstView'])) ? "true" : "false");

        // retail delivery registration
        addPermissions($role_id, 'rdr', 'Edit', (isset($_POST['rdrEdit'])) ? "true" : "false");
        addPermissions($role_id, 'rdr', 'View', (isset($_POST['rdrView'])) ? "true" : "false");

        // transportation damage
        addPermissions($role_id, 'tansptDmg', 'Add', (isset($_POST['tansptDmgAdd'])) ? "true" : "false");
        addPermissions($role_id, 'tansptDmg', 'Edit', (isset($_POST['tansptDmgEdit'])) ? "true" : "false");
        addPermissions($role_id, 'tansptDmg', 'Remove', (isset($_POST['tansptDmgRemove'])) ? "true" : "false");
        addPermissions($role_id, 'tansptDmg', 'View', (isset($_POST['tansptDmgView'])) ? "true" : "false");

        // lot wizards bills
        addPermissions($role_id, 'wizardsBill', 'Edit', (isset($_POST['wizardsBillEdit'])) ? "true" : "false");
        addPermissions($role_id, 'wizardsBill', 'View', (isset($_POST['wizardsBillView'])) ? "true" : "false");

        // Delivery Coordinator Appointments
        addPermissions($role_id, 'appointment', 'Add', (isset($_POST['appointmentAdd'])) ? "true" : "false");
        addPermissions($role_id, 'appointment', 'Edit', (isset($_POST['appointmentEdit'])) ? "true" : "false");
        addPermissions($role_id, 'appointment', 'Remove', (isset($_POST['appointmentRemove'])) ? "true" : "false");
        addPermissions($role_id, 'appointment', 'View', (isset($_POST['appointmentView'])) ? "true" : "false");

        // lot transportation bills
        addPermissions($role_id, 'tansptBill', 'Edit', (isset($_POST['tansptBillEdit'])) ? "true" : "false");
        addPermissions($role_id, 'tansptBill', 'View', (isset($_POST['tansptBillView'])) ? "true" : "false");

        // BDC - Leeds
        addPermissions($role_id, 'bdc', 'Add', (isset($_POST['bdcAdd'])) ? "true" : "false");
        addPermissions($role_id, 'bdc', 'Edit', (isset($_POST['bdcEdit'])) ? "true" : "false");
        addPermissions($role_id, 'bdc', 'Remove', (isset($_POST['bdcRemove'])) ? "true" : "false");
        addPermissions($role_id, 'bdc', 'View', (isset($_POST['bdcView'])) ? "true" : "false");

        // warranty cancellation 
        addPermissions($role_id, 'warranty', 'Add', (isset($_POST['warrantyAdd'])) ? "true" : "false");
        addPermissions($role_id, 'warranty', 'Edit', (isset($_POST['warrantyEdit'])) ? "true" : "false");
        addPermissions($role_id, 'warranty', 'Remove', (isset($_POST['warrantyRemove'])) ? "true" : "false");
        addPermissions($role_id, 'warranty', 'View', (isset($_POST['warrantyView'])) ? "true" : "false");

        // today availibility
        addPermissions($role_id, 'todayavail', 'Edit', (isset($_POST['todayavailEdit'])) ? "true" : "false");
        addPermissions($role_id, 'todayavail', 'View', (isset($_POST['todayavailView'])) ? "true" : "false");

        // writedowns
        addPermissions($role_id, 'writedown', 'Edit', (isset($_POST['writedownEdit'])) ? "true" : "false");
        addPermissions($role_id, 'writedown', 'View', (isset($_POST['writedownView'])) ? "true" : "false");

        // dealership contact
        addPermissions($role_id, 'dealership', 'Add', (isset($_POST['dealershipAdd'])) ? "true" : "false");
        addPermissions($role_id, 'dealership', 'Edit', (isset($_POST['dealershipEdit'])) ? "true" : "false");
        addPermissions($role_id, 'dealership', 'Remove', (isset($_POST['dealershipRemove'])) ? "true" : "false");
        addPermissions($role_id, 'dealership', 'View', (isset($_POST['dealershipView'])) ? "true" : "false");
        
        // Website Links 
        addPermissions($role_id, 'weblink', 'Add', (isset($_POST['weblinkAdd'])) ? "true" : "false");
        addPermissions($role_id, 'weblink', 'Edit', (isset($_POST['weblinkEdit'])) ? "true" : "false");
        addPermissions($role_id, 'weblink', 'Remove', (isset($_POST['weblinkRemove'])) ? "true" : "false");
        addPermissions($role_id, 'weblink', 'View', (isset($_POST['weblinkView'])) ? "true" : "false");
    }

    $roleId = $_SESSION['userRole'];

    $checkSql = "SELECT modules , functions , permission FROM `role_mod` WHERE role_id = '$roleId'";
    $result = $connect->query($checkSql);
    $output = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($output, $row);
        }
    }
    $_SESSION['permissionsArray'] = $output;




    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);