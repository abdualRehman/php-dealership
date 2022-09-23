<?php
require_once 'db/core.php';


$id = $_POST['id'];

if ($id) {

	$sql = "UPDATE notifications  SET is_read = 1 - is_read  WHERE id = '$id'";

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