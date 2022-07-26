<?php

require_once './db/core.php';


$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '', 'settingError' => array());

if ($_POST) {

    $sale_id = $_POST['sale_id'];


    $deliveryDate = mysqli_real_escape_string($connect, $_POST['deliveryDate']);
    $enteredDate = mysqli_real_escape_string($connect, $_POST['enteredDate']);
    $approvedDate = mysqli_real_escape_string($connect, $_POST['approvedDate']);
    $rdrNotes = mysqli_real_escape_string($connect, $_POST['rdrNotes']);
    // echo $sale_id . '<br />';


    $checkSql = "SELECT * FROM `rdr` WHERE `sale_id`='$sale_id' AND `status`=1";
    $result = $connect->query($checkSql);

    if ($result->num_rows > 0) {

        // echo $deliveryDate . ' <br /> ';
        // echo $enteredDate . '<br />';
        // echo $approvedDate . '<br />';
        // echo $rdrNotes . '<br />';
        $sql = "UPDATE `rdr` SET `delivered`='$deliveryDate',`entered`='$enteredDate',`approved`='$approvedDate',`rdr_notes`='$rdrNotes' 
        WHERE `sale_id` = '$sale_id'";

        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {

        $sql = "INSERT INTO `rdr` 
        (`sale_id`, `delivered`, `entered`, `approved`, `rdr_notes`, `status`) VALUES 
        ('$sale_id' , '$deliveryDate' , '$enteredDate' , '$approvedDate' , '$rdrNotes' , 1)";

        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }




    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);