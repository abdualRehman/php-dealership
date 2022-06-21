<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array());

$sql = "SELECT * FROM `role` where role_status = 1";
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

        // updatePermission($id, 'bodyshops', 'Add', "true");
        // updatePermission($id, 'bodyshops', 'Edit', "true");
        // updatePermission($id, 'bodyshops', 'Remove', "true");


        // updatePermission($id, 'lotWizards', 'Edit', "true");
        // updatePermission($id, 'lotWizards', 'View', "true");
        // updatePermission($id, 'usedCars', 'Edit', "true");
        // updatePermission($id, 'usedCars', 'View', "true");

        echo $id . ' - ' . $name . '<br />';
        // }
    }
}



$connect->close();
