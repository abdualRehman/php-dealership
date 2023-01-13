<?php

require_once 'db/core.php';


$valid['success'] = array('success' => false, 'messages' => array());



$submittedBy = $_SESSION['userId'];


if ($_POST) {

    for ($i = 0; $i < count($_POST['id']); $i++) {
        // code to be executed;
        $id = mysqli_real_escape_string($connect, $_POST['id'][$i]);
        $attribute = mysqli_real_escape_string($connect, $_POST['attribute'][$i]);
        $value = mysqli_real_escape_string($connect, $_POST['value'][$i]);


        $checkSql = "SELECT * FROM `used_cars` WHERE inv_id = '$id' AND status = 1";
        $result = $connect->query($checkSql);
        if ($result->num_rows > 0) {
            $updatekSql = "UPDATE `used_cars` SET `$attribute` = '$value' WHERE inv_id = '$id'";
            if ($connect->query($updatekSql) === true) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Added";
            } else {
                $valid['success'] = false;
                $valid['messages'] = $connect->error;
                $valid['messages'] = mysqli_error($connect);
            }
        } else {
            $sql = "INSERT INTO `used_cars`( `inv_id`,  `$attribute`, `submitted_by`, `status`) VALUES (
                    '$id' , '$value', '$submittedBy' , 1 )";

            if ($connect->query($sql) === true) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Added";
            } else {
                $valid['success'] = false;
                $valid['messages'] = $connect->error;
                $valid['messages'] = mysqli_error($connect);
            }
        }

        if ($attribute == 'sold_price') {
            $checkSql = "SELECT * FROM `inventory` WHERE id = '$id'";
            $result = $connect->query($checkSql);
            if ($result->num_rows > 0) {
                $row1 = $result->fetch_assoc();
                $balance = $row1['status'] == 1 ? $row1['balance'] : 0;
                $balance = preg_replace('/[\$,]/', '', $balance);
                $balance = floatval($balance);

                $soldPrice = mysqli_real_escape_string($connect, $_POST['value'][$i]);
                $soldPrice = preg_replace('/[\$,]/', '', $soldPrice);
                $soldPrice = floatval($soldPrice);

                $profit = $soldPrice - $balance;

                $updatekSql = "UPDATE `used_cars` SET `profit` = '$profit' WHERE inv_id = '$id'";
                $connect->query($updatekSql) === true;
            }
        }
    }
}


$connect->close();
echo json_encode($valid);
