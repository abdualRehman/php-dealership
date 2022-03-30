<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {


    function addPermissions($role_id , $module , $func , $permission )
    {
        global $connect;
        $sql = "UPDATE `role_mod` SET `permission` = '$permission' WHERE `role_id` = '$role_id' AND `modules` = '$module' AND `functions` = '$func'";
        $connect->query($sql) === true;
    }

    $roleId = $_POST['roleId'];


    $editRoleName = $_POST['editRoleName'];
    $editRoleDes = $_POST['editRoleDes'];

    $userAdd = $_POST['userAdd'];
    $userEdit = $_POST['userEdit'];
    $userRemove = $_POST['userRemove'];
    $userView = $_POST['userView'];

    $invAdd = $_POST['invAdd'];
    $invEdit = $_POST['invEdit'];
    $invRemove = $_POST['invRemove'];
    $invView = $_POST['invView'];


    $sql = "UPDATE `role` SET `role_name` = '$editRoleName' , `role_des` = '$editRoleDes' WHERE `role_id` = '$roleId' ";


    $AddFurther = false;
    if ($connect->query($sql) === true) {
        $AddFurther = true;
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    }else{
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }


    if($AddFurther == true ){
        // users
        addPermissions($roleId , 'users' , 'Add' , $userAdd);
        addPermissions($roleId , 'users' , 'Edit' , $userEdit);
        addPermissions($roleId , 'users' , 'Remove' , $userRemove);
        addPermissions($roleId , 'users' , 'View' , $userView);
        
        // inventory
        addPermissions($roleId , 'inv' , 'Add' , $invAdd);
        addPermissions($roleId , 'inv' , 'Edit' , $invEdit);
        addPermissions($roleId , 'inv' , 'Remove' , $invRemove);
        addPermissions($roleId , 'inv' , 'View' , $invView);
    }

    


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);