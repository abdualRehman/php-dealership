<?php
require_once './db/core.php';

$validate = array('success' => "false", 'messages' => array());

$filter = array();
function addPermissions($role_id, $module, $func, $permission)
{
    global $filter;
    $filter[] = "('$role_id' , '$module' , '$func' , '$permission' )";
}

function createRole(String $RoleName = null, $loc_id = null)
{
    global $connect;
    global $validate, $filter;


    if ($RoleName != null && $loc_id != null) {

        $role_id = '';
        $PermissionAllowed = '';
        $sql = "INSERT INTO `role` ( `role_name`, `role_des`, `location_id` , `role_status`) VALUES ('$RoleName' , '' , '$loc_id' , 3)";
        if ($connect->query($sql) === true) {
            $role_id = $connect->insert_id;
        }
        if($RoleName == 'Branch Admin'){
            $PermissionAllowed = "true";
        }else{
            $PermissionAllowed = "false";
        }
      
        if ($role_id != "") {
            $filter = array();
            // matrix
            addPermissions($role_id, 'matrix', 'View', $PermissionAllowed);
            // swap
            addPermissions($role_id, 'swap', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'swap', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'swap', 'Remove', $PermissionAllowed);
            addPermissions($role_id, 'swap', 'View', $PermissionAllowed);
            // INCENTIVES
            addPermissions($role_id, 'incentives', 'Edit', $PermissionAllowed);
            // INVENTORY
            addPermissions($role_id, 'inventory', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'inventory', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'inventory', 'Remove', $PermissionAllowed);
            // sale
            addPermissions($role_id, 'sale', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'sale', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'sale', 'Remove', $PermissionAllowed);
            addPermissions($role_id, 'sale', 'View', $PermissionAllowed);
            addPermissions($role_id, 'sale', 'Details', $PermissionAllowed);
            // todo
            addPermissions($role_id, 'todo', 'Edit', $PermissionAllowed);
            // regp
            addPermissions($role_id, 'regp', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'regp', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'regp', 'Remove', $PermissionAllowed);
            addPermissions($role_id, 'regp', 'View', $PermissionAllowed);
            // users
            addPermissions($role_id, 'user', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'user', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'user', 'Remove', $PermissionAllowed);
            // role
            addPermissions($role_id, 'role', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'role', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'role', 'Remove', $PermissionAllowed);
            // incr
            addPermissions($role_id, 'incr', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'incr', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'incr', 'Remove', $PermissionAllowed);
            // sptr
            addPermissions($role_id, 'sptr', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'sptr', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'sptr', 'Remove', $PermissionAllowed);
            // swploc
            addPermissions($role_id, 'swploc', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'swploc', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'swploc', 'Remove', $PermissionAllowed);
            // manprice
            addPermissions($role_id, 'manprice', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'manprice', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'manprice', 'Remove', $PermissionAllowed);
            // matrixrule
            addPermissions($role_id, 'matrixrule', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'matrixrule', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'matrixrule', 'Remove', $PermissionAllowed);
            // bdcrule
            addPermissions($role_id, 'bdcrule', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'bdcrule', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'bdcrule', 'Remove', $PermissionAllowed);
            // raterule
            addPermissions($role_id, 'raterule', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'raterule', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'raterule', 'Remove', $PermissionAllowed);
            // leaseruleAdd
            addPermissions($role_id, 'leaserule', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'leaserule', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'leaserule', 'Remove', $PermissionAllowed);
            // leaseruleAdd
            addPermissions($role_id, 'cashincrule', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'cashincrule', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'cashincrule', 'Remove', $PermissionAllowed);
            // lotWizards
            // addPermissions($role_id, 'lotWizards', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'lotWizards', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'lotWizards', 'View', $PermissionAllowed);
            // used Cars
            // addPermissions($role_id, 'usedCars', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'usedCars', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'usedCars', 'View', $PermissionAllowed);
            // bodyshops contact
            addPermissions($role_id, 'bodyshops', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'bodyshops', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'bodyshops', 'Remove', $PermissionAllowed);
            // Matrix Files Upload
            addPermissions($role_id, 'matrixfile', 'Add', $PermissionAllowed);
            // Inv Specialist - Dashboard
            addPermissions($role_id, 'invsplst', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'invsplst', 'View', $PermissionAllowed);
            // retail delivery registration
            addPermissions($role_id, 'rdr', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'rdr', 'View', $PermissionAllowed);
            // transportation damage
            addPermissions($role_id, 'tansptDmg', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'tansptDmg', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'tansptDmg', 'Remove', $PermissionAllowed);
            // lot wizards bills
            addPermissions($role_id, 'wizardsBill', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'wizardsBill', 'View', $PermissionAllowed);
            // Delivery Coordinator Appointments
            addPermissions($role_id, 'appointment', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'appointment', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'appointment', 'Remove', $PermissionAllowed);
            // lot transportation bills
            addPermissions($role_id, 'tansptBill', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'tansptBill', 'View', $PermissionAllowed);
            // BDC - Leeds
            addPermissions($role_id, 'bdc', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'bdc', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'bdc', 'Remove', $PermissionAllowed);
            addPermissions($role_id, 'bdc', 'View', $PermissionAllowed);
            // warranty cancellation 
            addPermissions($role_id, 'warranty', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'warranty', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'warranty', 'Remove', $PermissionAllowed);
            // today availibility
            addPermissions($role_id, 'todayavail', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'todayavail', 'View', $PermissionAllowed);
            // writedowns
            addPermissions($role_id, 'writedown', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'writedown', 'View', $PermissionAllowed);
            // dealership contact
            addPermissions($role_id, 'dealership', 'Add', $PermissionAllowed);
            addPermissions($role_id, 'dealership', 'Edit', $PermissionAllowed);
            addPermissions($role_id, 'dealership', 'Remove', $PermissionAllowed);



            $sql1 = "INSERT INTO `role_mod`(`role_id`, `modules`, `functions`, `permission`) VALUES " . implode(",", $filter) . "";
            // echo $sql1 . '<hr />';
            $connect->query($sql1) === true;

            // setting up default roles in database to fetch exact data later on
            $sql2 = "INSERT INTO `default_roles`(`role_id`, `role_name`, `location_id`, `status`) 
            VALUES ( '$role_id' , '$RoleName' , '$loc_id' , 1)";
            // echo $sql2 . '<hr />';
            $connect->query($sql2);
        }
    }
}

function removeDefaulRoles($loc_id = null)
{
    global $connect;
    if ($loc_id) {
        $sql = "DELETE role.* , role_mod.* FROM role INNER JOIN role_mod ON role.role_id = role_mod.role_id 
        where role.location_id = '$loc_id'";
        $connect->query($sql);
        $sql1 = "DELETE FROM `default_roles` WHERE `location_id` = '$loc_id'";
        $connect->query($sql1);
    }
}
