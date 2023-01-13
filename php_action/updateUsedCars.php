<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $submittedBy = $_SESSION['userId'];

    $vehicleId = (isset($_POST['vehicleId'])) ? mysqli_real_escape_string($connect, $_POST['vehicleId']) : "";

    $invDate = (isset($_POST['invDate'])) ? mysqli_real_escape_string($connect, $_POST['invDate']) : "";

    $retailStatus = (isset($_POST['retailStatus'])) ? mysqli_real_escape_string($connect, $_POST['retailStatus']) : "";
    $purchaseFrom = (isset($_POST['purchaseFrom'])) ? mysqli_real_escape_string($connect, $_POST['purchaseFrom']) : "";

    $certified = (isset($_POST['certified'])) ? "true" : "false";
    $title = (isset($_POST['title'])) ? "true" : "false";



    $titlePriority = (isset($_POST['titlePriority'])) ? mysqli_real_escape_string($connect, $_POST['titlePriority']) : "";
    $salesConsultant = (isset($_POST['salesConsultant'])) ? mysqli_real_escape_string($connect, $_POST['salesConsultant']) : "";
    $customer = (isset($_POST['customerName'])) ? mysqli_real_escape_string($connect, $_POST['customerName']) : "";

    $dateSent = (isset($_POST['dateSent'])) ? mysqli_real_escape_string($connect, $_POST['dateSent']) : "";
    $dateSold = (isset($_POST['dateSold'])) ? mysqli_real_escape_string($connect, $_POST['dateSold']) : "";
    $soldPrice = (isset($_POST['soldPrice'])) ? mysqli_real_escape_string($connect, $_POST['soldPrice']) : "";

    $keys = (isset($_POST['keys'])) ? "true" : "false";

    $titleNotes = (isset($_POST['titleNotes'])) ? mysqli_real_escape_string($connect, $_POST['titleNotes']) : "";
    $wholesaleNotes = (isset($_POST['wholesaleNotes'])) ? mysqli_real_escape_string($connect, $_POST['wholesaleNotes']) : "";
    $onlineDescription = (isset($_POST['onlineDescription'])) ? mysqli_real_escape_string($connect, $_POST['onlineDescription']) : "";
    $roNotes = (isset($_POST['roNotes'])) ? mysqli_real_escape_string($connect, $_POST['roNotes']) : "";


    $uci = (isset($_POST['uci'])) ? mysqli_real_escape_string($connect, $_POST['uci']) : "";
    $uciRo = (isset($_POST['uciRo'])) ? mysqli_real_escape_string($connect, $_POST['uciRo']) : "";
    $uciApproved = (isset($_POST['uciApproved'])) ? mysqli_real_escape_string($connect, $_POST['uciApproved']) : "";
    $uciClosed = (isset($_POST['uciClosed'])) ? mysqli_real_escape_string($connect, $_POST['uciClosed']) : "";


    $oci = (isset($_POST['oci'])) ? "true" : "false";



    // echo $vehicleId . '<br />';
    // echo $retailStatus . '<br />';
    // echo $purchaseFrom . '<br />';
    // echo $certified . '<br />';
    // echo $title . '<br />';
    // echo $titlePriority . '<br />';
    // echo $salesConsultant . '<br />';
    // echo $customer . '<br />';  // dates

    // echo $dateSent . '<br />'; // dates
    // echo $dateSold . '<br />';  // dates
    // echo $soldPrice . '<br />'; // dates
    // echo $keys . '<br />'; // dates

    // echo $titleNotes . '<br />'; // dates
    // echo $onlineDescription . '<br />';  // dates
    // echo $roNotes . '<br />'; // dates

    // echo $uci . '<br />'; // dates
    // echo $uciRo . '<br />';  // dates
    // echo $uciApproved . '<br />'; // dates
    // echo $uciClosed . '<br />'; // dates
    // echo $oci . '<br />'; // dates


    $profit = "";
    $checkSql = "SELECT * FROM `inventory` WHERE id = '$vehicleId'";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        $row1 = $result->fetch_assoc();
        $balance = $row1['status'] == 1 ? $row1['balance'] : 0;
        $balance = preg_replace('/[\$,]/', '', $balance);
        $balance = floatval($balance);

        $soldPrice = $soldPrice != "" ? $soldPrice : 0;
        $soldPrice = preg_replace('/[\$,]/', '', $soldPrice);
        $soldPrice = floatval($soldPrice);

        $profit = $soldPrice - $balance;
    }






    $checkSql = "SELECT * FROM `used_cars` WHERE inv_id = '$vehicleId' AND status = 1";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {
        // update Inv data if this stock number already exist with deleted id with sale 
        $updatekSql = "UPDATE `used_cars` SET 
        `retail_status`='$retailStatus',`date_in`='$invDate',
        `certified`='$certified',`title`='$title',
        `purchase_from`='$purchaseFrom',`uci`='$uci',
        `uci_ro`='$uciRo',`uci_approved`='$uciApproved',
        `uci_close`='$uciClosed',`oci_ok`='$oci',
        `title_priority`='$titlePriority',`sales_consultant`='$salesConsultant',
        `customer`='$customer',`title_notes`='$titleNotes', `wholesale_notes`='$wholesaleNotes',
        `key`='$keys',`date_sent`='$dateSent',
        `date_sold`='$dateSold' , `sold_price`='$soldPrice',`online_description`='$onlineDescription',
        `ro_online_notes`='$roNotes',`submitted_by`='$submittedBy' WHERE inv_id = '$vehicleId'";

        if ($connect->query($updatekSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $sql = "INSERT INTO `used_cars`(
            `inv_id`, `retail_status`, `date_in`, 
            `certified`, `title`, `purchase_from`, 
            `uci`, `uci_ro`, `uci_approved`, `uci_close`, 
            `oci_ok`, `title_priority`, `sales_consultant`, 
            `customer`, `title_notes`, `key`, 
            `date_sent`, `date_sold`, `sold_price` ,`online_description`, 
            `ro_online_notes`, `submitted_by`, `wholesale_notes`, `status`) VALUES (
                '$vehicleId', '$retailStatus' , '$invDate',
                '$certified' , '$title' , '$purchaseFrom',
                '$uci' , '$uciRo', '$uciApproved' , '$uciClosed',
                '$oci' , '$titlePriority' , '$salesConsultant',
                '$customer' , '$titleNotes' , '$keys',
                '$dateSent' , '$dateSold' , '$soldPrice' , '$onlineDescription',
                '$roNotes' , '$submittedBy', '$wholesaleNotes' , 1
            )";

        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }
    $updatekSql = "UPDATE `used_cars` SET `profit` = '$profit' WHERE inv_id = '$vehicleId'";
    $connect->query($updatekSql) === true;



    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);