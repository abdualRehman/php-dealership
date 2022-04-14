<?php

require_once 'db/core.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'settingError' => array());

$id = $_POST['id'];

if ($id) {

	$sql = "UPDATE bdc_rules SET status = 2 WHERE id = '$id'";

	if ($connect->query($sql) === TRUE) {
		// update All Matrix Becaause we don't know about weather used delete exclude model no or not
		$obj = updateAllBdc();
		$obj = json_decode($obj);

		if ($obj->success === 'false') {
			$valid['settingError'][] = $obj->messages;
		}

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