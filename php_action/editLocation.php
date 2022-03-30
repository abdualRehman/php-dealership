<?php 	

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
    
    $locId = $_POST['locId'];

    $dealerno = mysqli_real_escape_string($connect, $_POST['dealerno']);
    $dealership = mysqli_real_escape_string($connect, $_POST['dealership']);
    $address = mysqli_real_escape_string($connect, $_POST['address']);
    $city = mysqli_real_escape_string($connect, $_POST['city']);
    $state = mysqli_real_escape_string($connect, $_POST['state']);
    $zip = mysqli_real_escape_string($connect, $_POST['zip']);
    $miles = mysqli_real_escape_string($connect, $_POST['miles']);
    $travelTime = mysqli_real_escape_string($connect, $_POST['travelTime']);
    $roundTrip = mysqli_real_escape_string($connect, $_POST['roundTrip']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $fax = mysqli_real_escape_string($connect, $_POST['fax']);
    $mcontact = mysqli_real_escape_string($connect, $_POST['mcontact']);
    $cell = mysqli_real_escape_string($connect, $_POST['cell']);
    $preffer = mysqli_real_escape_string($connect, $_POST['preffer']);

    

	$sql = "UPDATE `locations` SET 
    `dealer_no`='$dealerno',`dealership`='$dealership',`address`='$address',`city`='$city',`state`='$state',`zip`='$zip',`miles`='$miles',`travel_time`='$travelTime',`round_trip`='$roundTrip',`phone`='$phone',`fax`='$fax',`main_contact`='$mcontact',`cell`='$cell',`preffer`='$preffer'
    WHERE id = '$locId' ";

	if($connect->query($sql) === TRUE) {
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