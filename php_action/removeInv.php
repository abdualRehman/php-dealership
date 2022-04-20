<?php

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$invId = $_POST['invId'];

if ($invId) {

	$sql = "UPDATE inventory SET status = 2 WHERE id = '$invId'";

	// $checkSql = "SELECT * FROM `sales` WHERE stock_id = '$invId' AND status = 1";
	// $result = $connect->query($checkSql);
	// // check if stock id exist in sale table then update the inventory with status otherwise drop this record
	// if ($result->num_rows > 0) {
	// 	$sql = "UPDATE inventory SET stockno = CONCAT(stockno , '_', '$invId') , status = 2 WHERE id = '$invId'";
	// }else{
	// 	$sql = "DELETE FROM `inventory` WHERE `inventory`.`id` = '$invId' ";
	// }

	if ($connect->query($sql) === TRUE) {
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