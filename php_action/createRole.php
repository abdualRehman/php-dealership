<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
if ($_POST) {


    function addPermissions( $role_id , $module , $func , $permission )
    {
        global $connect;
        $sql = "INSERT INTO `role_mod`(`role_id`, `modules`, `functions`, `permission`) 
        VALUES ('$role_id' , '$module' , '$func' , '$permission' )";
        $connect->query($sql) === true;
    }

    $roleName = $_POST['roleName'];
    $roleDes = $_POST['roleDes'];

    $userAdd = $_POST['userAdd'];
    $userEdit = $_POST['userEdit'];
    $userRemove = $_POST['userRemove'];
    $userView = $_POST['userView'];

    $invAdd = $_POST['invAdd'];
    $invEdit = $_POST['invEdit'];
    $invRemove = $_POST['invRemove'];
    $invView = $_POST['invView'];


    $sql = "INSERT INTO `role`(`role_name`, `role_des`, `role_status`) VALUES ('$roleName' , '$roleDes' , 1)";

    $role_id ="";
    if ($connect->query($sql) === true) {
        $role_id = $connect->insert_id;
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    }else{
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }


    if($role_id != "" ){

        // users
        addPermissions($role_id , 'users' , 'Add' , $userAdd);
        addPermissions($role_id , 'users' , 'Edit' , $userEdit);
        addPermissions($role_id , 'users' , 'Remove' , $userRemove);
        addPermissions($role_id , 'users' , 'View' , $userView);
        
        // inventory
        addPermissions($role_id , 'inv' , 'Add' , $invAdd);
        addPermissions($role_id , 'inv' , 'Edit' , $invEdit);
        addPermissions($role_id , 'inv' , 'Remove' , $invRemove);
        addPermissions($role_id , 'inv' , 'View' , $invView);
    }

    


    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);