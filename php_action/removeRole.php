<?php 	

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$roleId = $_POST['roleId'];

if($roleId) { 

 $sql = "DELETE FROM role WHERE role_id = {$roleId}";
 $sql1 = "DELETE FROM `role_mod` WHERE role_id = {$roleId}";
 $connect->query($sql1) === TRUE;

 if($connect->query($sql) === TRUE) {
 	$valid['success'] = true;
	$valid['messages'] = "Successfully Removed";		
 } else {
 	$valid['success'] = false;
 	$valid['messages'] = "Error while remove the Role";
 }
 
 $connect->close();

 echo json_encode($valid);
 
} // /if $_POST