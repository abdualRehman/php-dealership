<?php
require_once './db/core.php';
require_once './updateRoles.php';
$valid = array('success' => false, 'messages' => array());

$defaultRoleNmaes = array(
    'Branch Admin' ,'BDC Manager', 'BDC Sales', 'Client Care Specialist', 'Delivery Coordinator',
    'Finance Manager', 'General Manager', 'Inventory Specialist', 'Office', 'Online Manager', 'Sales Consultant', 'Sales Manager'
);
if ($_POST) {

    $locName = (isset($_POST['locName'])) ? mysqli_real_escape_string($connect, $_POST['locName']) : "";
    $sql = "INSERT INTO `user_location`(`name`, `status`) VALUES ('$locName' , 1)";
    $inserted_loc_id = "";
    if ($connect->query($sql) === true) {
        $inserted_loc_id = $connect->insert_id;
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
    }

    // creating default roles for this location

    if ($inserted_loc_id != '') {
        for ($i = 0; $i < count($defaultRoleNmaes); $i++) {
            // echo $defaultRoleNmaes[$i] . '<br />';
            createRole($defaultRoleNmaes[$i], $inserted_loc_id);
        }
    }

    $connect->close();
    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);
