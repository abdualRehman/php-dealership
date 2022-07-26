<?php

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$arrayObj = $_POST['arrayObj'];

if ($arrayObj) {

    foreach ($arrayObj as $stockArray) {
        $submittedBy = $_SESSION['userId'];
        // code to be executed;
        $id = mysqli_real_escape_string($connect, $stockArray['id']);
        $retailStatus = mysqli_real_escape_string($connect, $stockArray['retailStatus']);
        $titlePriority = mysqli_real_escape_string($connect, $stockArray['titlePriority']);
        $uci = mysqli_real_escape_string($connect, $stockArray['uci']);


        $checkSql = "SELECT * FROM `used_cars` WHERE inv_id = '$id' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result->num_rows > 0) {
            // update Inv data if this stock number already exist with deleted id with sale 
            // $updatekSql = "UPDATE `used_cars` SET 
            // `retail_status`='$retailStatus',`date_in`='',
            // `certified`='false',`title`='false',
            // `purchase_from`='',`uci`='$uci',
            // `uci_ro`='',`uci_approved`='',
            // `uci_close`='',`oci_ok`='false',
            // `title_priority`='$titlePriority',`sales_consultant`='',
            // `customer`='',`title_notes`='',
            // `key`='false',`date_sent`='',
            // `date_sold`='' , `sold_price`='',`online_description`='',
            // `ro_online_notes`='',`submitted_by`='$submittedBy' WHERE inv_id = '$id'";
            $updatekSql = "UPDATE `used_cars` SET 
            `retail_status`='$retailStatus', `uci`='$uci',
            `title_priority`='$titlePriority',`submitted_by`='$submittedBy' WHERE inv_id = '$id'";
            if ($connect->query($updatekSql) === true) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Added";
            } else {
                $valid['success'] = false;
                $valid['messages'] = $connect->error;
                $valid['messages'] = mysqli_error($connect);
            }
        } else {
            // $sql = "INSERT INTO `used_cars`(
            //     `inv_id`, `retail_status`, `date_in`, 
            //     `certified`, `title`, `purchase_from`, 
            //     `uci`, `uci_ro`, `uci_approved`, `uci_close`, 
            //     `oci_ok`, `title_priority`, `sales_consultant`, 
            //     `customer`, `title_notes`, `key`, 
            //     `date_sent`, `date_sold`, `sold_price` ,`online_description`, 
            //     `ro_online_notes`, `submitted_by`, `status`) VALUES (
            //         '$id', '$retailStatus' , '',
            //         'false' , 'false' , '',
            //         '$uci' , '', '' , '',
            //         'false' , '$titlePriority' , '',
            //         '' , '' , 'false',
            //         '' , '' , '' , '',
            //         '' , '$submittedBy' , 1
            //     )";
            $sql = "INSERT INTO `used_cars`(
                `inv_id`, `retail_status`,
                `uci`,  `title_priority`, `submitted_by`, `status`) VALUES (
                    '$id', '$retailStatus' ,
                    '$uci' , '$titlePriority' , '$submittedBy' , 1 )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Added";
            } else {
                $valid['success'] = false;
                $valid['messages'] = $connect->error;
                $valid['messages'] = mysqli_error($connect);
            }
        }
    }

    $connect->close();
    echo json_encode($valid);
} // /if $_POST