<?php

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'settingError' => array());

$id = $_POST['id'];
$tdid = $_POST['tdid'];

if ($id) {

	$checkTransportationDamagesSQL = "SELECT COUNT(transportation_id) AS damage_count FROM transportation_damages WHERE status = 1 AND transportation_id = '$id'";
	$checkResult = $connect->query($checkTransportationDamagesSQL);

	if ($checkResult) {
		$row = $checkResult->fetch_assoc();
		$damageCount = $row['damage_count'];

		$sql = "UPDATE transportation_damages SET status = 2 WHERE id = '$tdid'";

		if ($connect->query($sql) === TRUE) {
			// Run the $sql1 query only if the damage count equals 1
			if ($damageCount == 1) {
				$sql1 = "UPDATE transportation SET status = 2 WHERE id = '$id'";
				if ($connect->query($sql1) !== TRUE) {
					$valid['success'] = false;
					$valid['messages'] = $connect->error;
					$valid['messages'] = mysqli_error($connect);
				}
			}

			$valid['success'] = true;
			$valid['messages'] = "Successfully Removed";
		} else {
			$valid['success'] = false;
			$valid['messages'] = $connect->error;
			$valid['messages'] = mysqli_error($connect);
		}
	} else {
		$valid['success'] = false;
		$valid['messages'] = $connect->error;
		$valid['messages'] = mysqli_error($connect);
	}

	$connect->close();

	echo json_encode($valid);
} // /if $_POST