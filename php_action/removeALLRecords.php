<?php

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'settingError' => array());

$tableName = $_POST['data'];

if ($tableName) {

	$sql = "UPDATE ".$tableName." SET status = 2 WHERE status = 1";

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