<?php 	

require_once 'db/core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$editusername = $_POST['editusername'];
    $editemail = $_POST['editemail']; 
    $editrole = $_POST['editrole'];

    $editpassword = $_POST['editpassword'];
    $editconpassword = $_POST['editconpassword'];
    $userId = $_POST['userId'];
    

	$sql = "UPDATE users SET username = '$editusername', email = '$editemail' , password = '$editpassword' , role = '$editrole' WHERE id = '$userId'";

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