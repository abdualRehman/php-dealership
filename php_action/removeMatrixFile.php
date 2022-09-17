<?php

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'settingError' => array());

$fileType = $_POST['fileType'];
$fileName = $_POST['fileName'];

if ($fileType) {
	$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
	$sql = "UPDATE settings SET `file_name` = '' WHERE file_type = '$fileType' AND location = '$location'";
	$targetPath = "../assets/uploadMatrixRateFiles/" . $fileName;
	unlink($targetPath);

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