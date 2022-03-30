<?php 	

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$id = $_POST['id'];

if($id) { 

 
 $sqlTodo = "UPDATE sale_todo SET status = 2 WHERE sale_todo_id = '$id'";

 if($connect->query($sqlTodo) === TRUE ) {
 	$valid['success'] = true;
	$valid['messages'] = "Successfully Removed";		
 } else {
 	$valid['success'] = false;
 	$valid['messages'] = $connect->error;
    $valid['messages'] = mysqli_error($connect);
 }
 
 $connect->close();

 echo json_encode($valid);
 
} // /if $_POST