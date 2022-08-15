<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array());

$sql = "SELECT * FROM `role` where role_status != 2";
$result = $connect->query($sql);

function updatePermission($role_id, $module, $fun, $permission)
{
    // echo $role_id . ' - ' . $module . '<br />';
    global $connect;
    $sql = "SELECT * FROM `role_mod` WHERE role_id = '$role_id' AND modules = '$module' AND functions = '$fun' ";
    $result = $connect->query($sql);
    if ($result->num_rows > 0) {

        $sql1 = "UPDATE `role_mod` SET `permission`='$permission' WHERE role_id = '$role_id' AND modules = '$module' AND functions = '$fun' ";
        $connect->query($sql1);
    } else {
        $sql1 = "INSERT INTO `role_mod`(`role_id`, `modules`, `functions`, `permission`) VALUES  ('$role_id','$module','$fun','$permission' )";
        $connect->query($sql1);
    }
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['role_id'];
        $name = $row['role_name'];
        // if ($id == '66') {
        // updatePermission($id, 'sale', 'View', "true");
        // updatePermission($id, 'regp', 'View', "false");

        // updatePermission($id, 'matrixfile', 'Add', "true");
        
        // updatePermission($id, 'swap', 'View', "true");

       
        //    ---------------------------------------------------------------------------------------
       
        // updatePermission($id, 'invsplst', 'Edit', "true");
        // updatePermission($id, 'invsplst', 'View', "true");
        
        // updatePermission($id, 'rdr', 'Edit', "true");
        // updatePermission($id, 'rdr', 'View', "true");

        // updatePermission($id, 'tansptDmg', 'Add', "true");
        // updatePermission($id, 'tansptDmg', 'Edit', "true");
        // updatePermission($id, 'tansptDmg', 'Remove', "true");

        // updatePermission($id, 'wizardsBill', 'Edit', "true");
        // updatePermission($id, 'wizardsBill', 'View', "true");

        // updatePermission($id, 'appointment', 'Add', "true");
        // updatePermission($id, 'appointment', 'Edit', "true");
        // updatePermission($id, 'appointment', 'Remove', "true");

        // updatePermission($id, 'tansptBill', 'Edit', "true");
        // updatePermission($id, 'tansptBill', 'View', "true");
        
        // updatePermission($id, 'dbc', 'Add', "true");
        // updatePermission($id, 'dbc', 'Edit', "true");
        // updatePermission($id, 'dbc', 'Remove', "true");

        // updatePermission($id, 'warranty', 'Add', "true");
        // updatePermission($id, 'warranty', 'Edit', "true");
        // updatePermission($id, 'warranty', 'Remove', "true");

        // updatePermission($id, 'todayavail', 'Edit', "true");
        // updatePermission($id, 'todayavail', 'View', "true");
        // updatePermission($id, 'sale', 'Details', "true");
        // updatePermission($id, 'writedown', 'Edit', "true");
        // updatePermission($id, 'writedown', 'View', "true");


        echo $id . ' - ' . $name . '<br />';
        // }
    }
}



$connect->close();
