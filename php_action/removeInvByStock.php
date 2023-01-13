<?php

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$stockAr = $_POST['stockAr'];

if ($stockAr) {

    foreach ($stockAr as $stock) {
        // code to be executed;
        $stkno = mysqli_real_escape_string($connect, $stock);

        // $sql = "UPDATE inventory SET status = 2 WHERE stockno = '$stkno'";

        // $sql = "UPDATE inventory SET status = 2 , balance = '0' , lot = '' WHERE stockno = '$stkno'";


        $sql = "UPDATE inventory SET 
        inventory.balance =  CASE WHEN status = '1' THEN '0' ELSE balance END ,
        inventory.lot =  CASE WHEN status = '1' THEN '' ELSE lot END,
        status = CASE WHEN status = '1' THEN 2 ELSE status END
        WHERE stockno = '$stkno'";

        // $checkSql = "SELECT `sales`.* FROM `sales` INNER JOIN inventory ON sales.stock_id = inventory.id WHERE inventory.stockno = '$stkno' AND sales.status = 1";
        // $result = $connect->query($checkSql);

        // // // check if stock id exist in sale table then update the inventory with status otherwise drop this record
        // if ($result->num_rows > 0) {
        //     $sql = "UPDATE inventory SET stockno = CONCAT(stockno , '_', id ) , status = 2 WHERE stockno = '$stkno'";
        // } else {
        //     $sql = "DELETE FROM `inventory` WHERE `inventory`.`stockno` = '$stkno' ";
        // }

        // $sql = "UPDATE inventory SET stockno = CONCAT(stockno , '_', id) , status = 2 WHERE stockno = '$stkno'";

        if ($connect->query($sql) === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Removed";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }

    $connect->close();
    echo json_encode($valid);
} // /if $_POST