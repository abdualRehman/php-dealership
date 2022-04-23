<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {

    $filter = array();

    function addPermissions($role_id, $module, $func, $permission)
    {
        global $filter;
        $filter[] = "('$role_id' , '$module' , '$func' , '$permission' )";
    }

    $roleName = $_POST['roleName'];
    $roleDes = $_POST['roleDes'];


    $sql = "INSERT INTO `role`(`role_name`, `role_des`, `role_status`) VALUES ('$roleName' , '$roleDes' , 1)";

    $role_id = "";
    if ($connect->query($sql) === true) {
        $role_id = $connect->insert_id;
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }


    if ($role_id != "") {

        // matrix
        addPermissions($role_id, 'matrix', 'View', (isset($_POST['matrixView'])) ? "true" : "false");

        // swap
        addPermissions($role_id, 'swap', 'Add', (isset($_POST['swapAdd'])) ? "true" : "false");
        addPermissions($role_id, 'swap', 'Edit', (isset($_POST['swapEdit'])) ? "true" : "false");
        addPermissions($role_id, 'swap', 'Remove', (isset($_POST['swapRemove'])) ? "true" : "false");

        // INCENTIVES
        addPermissions($role_id, 'incentives', 'Edit', (isset($_POST['incentivesEdit'])) ? "true" : "false");

        // INVENTORY
        addPermissions($role_id, 'inventory', 'Add', (isset($_POST['invAdd'])) ? "true" : "false");
        addPermissions($role_id, 'inventory', 'Edit', (isset($_POST['invEdit'])) ? "true" : "false");
        addPermissions($role_id, 'inventory', 'Remove', (isset($_POST['invRemove'])) ? "true" : "false");
        // sale
        addPermissions($role_id, 'sale', 'Add', (isset($_POST['saleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'sale', 'Edit', (isset($_POST['saleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'sale', 'Remove', (isset($_POST['saleRemove'])) ? "true" : "false");

        // todo
        addPermissions($role_id, 'todo', 'Edit', (isset($_POST['todoEdit'])) ? "true" : "false");

        // regp
        addPermissions($role_id, 'regp', 'Add', (isset($_POST['regpAdd'])) ? "true" : "false");
        addPermissions($role_id, 'regp', 'Edit', (isset($_POST['regpEdit'])) ? "true" : "false");
        addPermissions($role_id, 'regp', 'Remove', (isset($_POST['regpRemove'])) ? "true" : "false");
        // users
        addPermissions($role_id, 'user', 'Add', (isset($_POST['userAdd'])) ? "true" : "false");
        addPermissions($role_id, 'user', 'Edit', (isset($_POST['userEdit'])) ? "true" : "false");
        addPermissions($role_id, 'user', 'Remove', (isset($_POST['userRemove'])) ? "true" : "false");

        // role
        addPermissions($role_id, 'role', 'Add', (isset($_POST['roleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'role', 'Edit', (isset($_POST['roleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'role', 'Remove', (isset($_POST['roleRemove'])) ? "true" : "false");

        // incr
        addPermissions($role_id, 'incr', 'Add', (isset($_POST['incrAdd'])) ? "true" : "false");
        addPermissions($role_id, 'incr', 'Edit', (isset($_POST['incrEdit'])) ? "true" : "false");
        addPermissions($role_id, 'incr', 'Remove', (isset($_POST['incrRemove'])) ? "true" : "false");

        // sptr
        addPermissions($role_id, 'sptr', 'Add', (isset($_POST['sptrAdd'])) ? "true" : "false");
        addPermissions($role_id, 'sptr', 'Edit', (isset($_POST['sptrEdit'])) ? "true" : "false");
        addPermissions($role_id, 'sptr', 'Remove', (isset($_POST['sptrRemove'])) ? "true" : "false");

        // sptr
        addPermissions($role_id, 'swploc', 'Add', (isset($_POST['swplocAdd'])) ? "true" : "false");
        addPermissions($role_id, 'swploc', 'Edit', (isset($_POST['swplocEdit'])) ? "true" : "false");
        addPermissions($role_id, 'swploc', 'Remove', (isset($_POST['swplocRemove'])) ? "true" : "false");

        // manprice
        addPermissions($role_id, 'manprice', 'Add', (isset($_POST['manpriceAdd'])) ? "true" : "false");
        addPermissions($role_id, 'manprice', 'Edit', (isset($_POST['manpriceEdit'])) ? "true" : "false");
        addPermissions($role_id, 'manprice', 'Remove', (isset($_POST['manpriceRemove'])) ? "true" : "false");

        // matrixrule
        addPermissions($role_id, 'matrixrule', 'Add', (isset($_POST['matrixruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'matrixrule', 'Edit', (isset($_POST['matrixruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'matrixrule', 'Remove', (isset($_POST['matrixruleRemove'])) ? "true" : "false");

        // bdcrule
        addPermissions($role_id, 'bdcrule', 'Add', (isset($_POST['bdcruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'bdcrule', 'Edit', (isset($_POST['bdcruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'bdcrule', 'Remove', (isset($_POST['bdcruleRemove'])) ? "true" : "false");

        // raterule
        addPermissions($role_id, 'raterule', 'Add', (isset($_POST['rateruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'raterule', 'Edit', (isset($_POST['rateruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'raterule', 'Remove', (isset($_POST['rateruleRemove'])) ? "true" : "false");

        // leaseruleAdd
        addPermissions($role_id, 'leaserule', 'Add', (isset($_POST['leaseruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'leaserule', 'Edit', (isset($_POST['leaseruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'leaserule', 'Remove', (isset($_POST['leaseruleRemove'])) ? "true" : "false");

        // leaseruleAdd
        addPermissions($role_id, 'cashincrule', 'Add', (isset($_POST['cashincruleAdd'])) ? "true" : "false");
        addPermissions($role_id, 'cashincrule', 'Edit', (isset($_POST['cashincruleEdit'])) ? "true" : "false");
        addPermissions($role_id, 'cashincrule', 'Remove', (isset($_POST['cashincruleRemove'])) ? "true" : "false");



        $sql = "INSERT INTO `role_mod`(`role_id`, `modules`, `functions`, `permission`) VALUES " . implode(",", $filter) . "";
        $connect->query($sql) === true;
    }


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);