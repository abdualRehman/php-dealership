<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array());

$sql = "SELECT * FROM `role` where role_status != 2";
$result = $connect->query($sql);

function updatePermission($role_id, $module, $fun, $permission)
{
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
        
        // updatePermission($id, 'bdc', 'Add', "true");
        // updatePermission($id, 'bdc', 'Edit', "true");
        // updatePermission($id, 'bdc', 'Remove', "true");
        // updatePermission($id, 'bdc', 'View', "true");

        // updatePermission($id, 'dealership', 'Add', "true");
        // updatePermission($id, 'dealership', 'Edit', "true");
        // updatePermission($id, 'dealership', 'Remove', "true");

        // updatePermission($id, 'todayavail', 'Edit', "true");
        // updatePermission($id, 'todayavail', 'View', "true");
        // updatePermission($id, 'sale', 'Details', "true");
        // updatePermission($id, 'writedown', 'Edit', "true");
        // updatePermission($id, 'writedown', 'View', "true");



        updatePermission($id, 'incentives', 'View', "true");
        updatePermission($id, 'inventory', 'View', "true");
        updatePermission($id, 'todo', 'View', "true");
        updatePermission($id, 'user', 'View', "true");
        updatePermission($id, 'incr', 'View', "true");
        updatePermission($id, 'sptr', 'View', "true");
        updatePermission($id, 'swploc', 'View', "true");
        updatePermission($id, 'manprice', 'View', "true");
        updatePermission($id, 'matrixrule', 'View', "true");
        updatePermission($id, 'bdcrule', 'View', "true");
        updatePermission($id, 'raterule', 'View', "true");
        updatePermission($id, 'leaserule', 'View', "true");
        updatePermission($id, 'cashincrule', 'View', "true");
        updatePermission($id, 'usedCars', 'TitleView', "true");
        updatePermission($id, 'usedCars', 'TitleEdit', "true");
        
        updatePermission($id, 'bodyshops', 'View', "true");
        updatePermission($id, 'tansptDmg', 'View', "true");
        updatePermission($id, 'appointment', 'View', "true");
        updatePermission($id, 'warranty', 'View', "true");
        updatePermission($id, 'dealership', 'View', "true");

        updatePermission($id, 'weblink', 'Add', "true");
        updatePermission($id, 'weblink', 'Edit', "true");
        updatePermission($id, 'weblink', 'Remove', "true");
        updatePermission($id, 'weblink', 'View', "true");

        



        echo $id . ' - ' . $name . '<br />';
        // }
    }
}



$connect->close();
