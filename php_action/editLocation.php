<?php 	

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
    
    $locId = $_POST['locId'];

    $dealerno = mysqli_real_escape_string($connect, $_POST['edealerno']);
    $dealership = mysqli_real_escape_string($connect, $_POST['edealership']);
    $address = mysqli_real_escape_string($connect, $_POST['eaddress']);
    $city = mysqli_real_escape_string($connect, $_POST['ecity']);
    $state = mysqli_real_escape_string($connect, $_POST['estate']);
    $zip = mysqli_real_escape_string($connect, $_POST['ezip']);
    $miles = mysqli_real_escape_string($connect, $_POST['emiles']);
    $travelTime = mysqli_real_escape_string($connect, $_POST['etravelTime']);
    $roundTrip = mysqli_real_escape_string($connect, $_POST['eroundTrip']);
    $phone = mysqli_real_escape_string($connect, $_POST['ephone']);
    $fax = mysqli_real_escape_string($connect, $_POST['efax']);
    $mcontact = mysqli_real_escape_string($connect, $_POST['emcontact']);
    $cell = mysqli_real_escape_string($connect, $_POST['ecell']);
    $preffer = mysqli_real_escape_string($connect, $_POST['epreffer']);

    

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