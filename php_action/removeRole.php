<?php 	

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$roleId = $_POST['roleId'];

if($roleId) { 

 $sql = "UPDATE role SET role_status = 2 WHERE role_id = {$roleId}";

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