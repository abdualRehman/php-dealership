<?php 	

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$saleId = $_POST['saleId'];

if($saleId) { 

 $sql = "UPDATE sales SET status = 2 WHERE sale_id = '$saleId'";
 $sqlIncentive = "UPDATE sale_incentives SET status = 2 WHERE sale_id = '$saleId'";
 $sqlTodo = "UPDATE sale_todo SET status = 2 WHERE sale_id = '$saleId'";

 if($connect->query($sql) === TRUE  && $connect->query($sqlIncentive) === TRUE && $connect->query($sqlTodo) === TRUE ) {
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