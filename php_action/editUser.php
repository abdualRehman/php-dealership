<?php

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$editusername = $_POST['editusername'];
	$editemail = $_POST['editemail'];
	$editrole = $_POST['editrole'];

	$userId = $_POST['userId'];

	$location = $_POST['location'];
	$extention = $_POST['extention'];
	$mobile = $_POST['mobile'];



	$monStart = $_POST['monStart'];
	$monEnd = ($monStart != "") ? $_POST['monEnd'] : "";
	$tueStart = $_POST['tueStart'];
	$tueEnd = ($tueStart != "") ? $_POST['tueEnd'] : "";
	$wedStart = $_POST['wedStart'];
	$wedEnd = ($wedStart != "") ? $_POST['wedEnd'] : "";
	$thuStart = $_POST['thuStart'];
	$thuEnd = ($thuStart != "") ? $_POST['thuEnd'] : "";
	$friStart = $_POST['friStart'];
	$friEnd = ($friStart != "") ? $_POST['friEnd'] : "";
	$satStart = $_POST['satStart'];
	$satEnd = ($satStart != "") ? $_POST['satEnd'] : "";
	$sunStart = $_POST['sunStart'];
	$sunEnd = ($sunStart != "") ? $_POST['sunEnd'] : "";





	$sql = "UPDATE users SET `username` = '$editusername', `email` = '$editemail' , `role` = '$editrole' , `location` = '$location' , `extention` = '$extention' , `mobile` = '$mobile' WHERE `id` = '$userId'";

	$sql2 = "SELECT * FROM `schedule` WHERE `user_id` = '$userId'";
	$result = $connect->query($sql2);
	if ($result->num_rows > 0) {

		$sql3 = "UPDATE `schedule` SET `mon_start`='$monStart',`mon_end`='$monEnd',
			`tue_start`='$tueStart',`tue_end`='$tueEnd',`wed_start`='$wedStart',`wed_end`='$wedEnd',
			`thu_start`='$thuStart',`thu_end`='$thuEnd',`fri_start`='$friStart',`fri_end`='$friEnd',
			`sat_start`='$satStart',`sat_end`='$satEnd',`sun_start`='$sunStart',`sun_end`='$sunEnd' WHERE `user_id` = '$userId'";
	} else {
		$sql3 = "INSERT INTO `schedule`(`user_id`, `mon_start`, `mon_end`, `tue_start`, `tue_end`, `wed_start`, `wed_end`, `thu_start`, `thu_end`, `fri_start`, `fri_end`, `sat_start`, `sat_end`, `sun_start`, `sun_end`) 
            VALUES ('$userId' , '$monStart' , '$monEnd' , '$tueStart' , '$thuEnd' , '$wedStart' , '$wedEnd' , '$thuStart' , '$thuEnd' , '$friStart' , '$friEnd' , '$satStart' , '$satEnd' , '$sunStart' , '$sunEnd')";
	}


	if ($connect->query($sql) === TRUE && $connect->query($sql3) === true) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Updated";
	} else {
		$valid['success'] = false;
		$valid['messages'] = $connect->error;
		$valid['messages'] = mysqli_error($connect);
	}




	$connect->close();

	echo json_encode($valid);
} // /if $_POST