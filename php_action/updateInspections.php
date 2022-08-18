<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $submittedBy = $_SESSION['userId'];

    $imageArray = array();
    $vehicleId = (isset($_POST['vehicleId'])) ? mysqli_real_escape_string($connect, $_POST['vehicleId']) : "";
    $lotNotes = (isset($_POST['lotNotes'])) ? mysqli_real_escape_string($connect, $_POST['lotNotes']) : "";
    $recon = (isset($_POST['recon'])) ? mysqli_real_escape_string($connect, $_POST['recon']) : "";

    // $repais = (isset($_POST['repais'])) ? mysqli_real_escape_string($connect, $_POST['repais']) : "";
    $repais = (isset($_POST['repais'])) ? implode("__", $_POST['repais']) : "";
    $repais = ($repais ===  "") ? "" :   "__" . $repais . "__";

    $bodyshop = (isset($_POST['bodyshop'])) ? mysqli_real_escape_string($connect, $_POST['bodyshop']) : "0";
    // $bodyshop = (isset($_POST['bodyshop'])) ? implode("__", $_POST['bodyshop']) : "";
    // $bodyshop = ($bodyshop ===  "") ? "" :   "__" . $bodyshop . "__";


    $bodyshopNotes = (isset($_POST['bodyshopNotes'])) ? mysqli_real_escape_string($connect, $_POST['bodyshopNotes']) : "";
    $estimate = (isset($_POST['estimate'])) ? mysqli_real_escape_string($connect, $_POST['estimate']) : "";
    $repairPaid = (isset($_POST['repairPaid'])) ? mysqli_real_escape_string($connect, $_POST['repairPaid']) : "";
    $repairSent = (isset($_POST['repairSent'])) ? mysqli_real_escape_string($connect, $_POST['repairSent']) : "";
    $repairReturn = (isset($_POST['repairReturn'])) ? mysqli_real_escape_string($connect, $_POST['repairReturn']) : "";
    $resend = (isset($_POST['resend'])) ? "true" : "false";

    $windshield = (isset($_POST['windshield'])) ? implode("__", $_POST['windshield']) : "";
    $windshield = ($windshield ===  "") ? "" :   "__" . $windshield . "__";
    $windshield = (isset($_POST['windshield_done'])) ? $windshield . "Done__" : $windshield;

    $wheels = (isset($_POST['wheels'])) ? implode("__", $_POST['wheels']) : "";
    $wheels = ($wheels ===  "") ? "" :   "__" . $wheels . "__";
    $wheels = (isset($_POST['wheels_done'])) ? $wheels . "Done__" : $wheels;


    $name = $_FILES['images']['name'];
    $temp_name  = $_FILES['images']['tmp_name'];

    if ($_FILES['images']['error'][0] == UPLOAD_ERR_OK) {
        // if (isset($name) && !empty($name)) {
        $target_path = "uploads/"; //Declaring Path for uploaded images
        for ($i = 0; $i < count($_FILES['images']['name']); $i++) { //loop to get individual element from the array

            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($_FILES['images']['tmp_name'][$i]);

            if (in_array($detectedType, $allowedTypes)) {

                $filename = $_FILES["images"]["name"][$i];
                $tempname = $_FILES["images"]["tmp_name"][$i];

                $folder = '../assets/inspections/' . $filename;
                // Now let's move the uploaded image into the folder: image
                move_uploaded_file($tempname, $folder);

                array_push($imageArray, $filename);
            } else {
                // echo $file_extension . ' This is file not allowed !!';
                $valid['success'] = false;
                $valid['messages'] = "This is file not allowed !!";
                exit();
            }
        }
    }
    if (isset($_POST['uploads'])) {
        for ($i = 0; $i < count($_POST['uploads']); $i++) {
            // echo $_POST['uploads'][$i] . "<br />";
            array_push($imageArray, $_POST['uploads'][$i]);
        }
    }
    $imageArray = implode("__", $imageArray);
    // echo $imageArray . "<br />";




    $repairSent_log = "";
    $repairRetrun_log = "";
    $repairs_log = "";
    $repairPaid_log = "";


    $checkSql = "SELECT * FROM `inspections` WHERE inv_id = '$vehicleId' AND status = 1";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            if ($resend == 'true' && $row['resend'] == 'false') {
                $repairSent_log = $row['repair_sent'];
                $repairRetrun_log = $row['repair_returned'];
                $repairs_log = $row['repairs'];
                $repairPaid_log = $row['repair_paid'];
            }
        }

        // update Inv data if this stock number already exist with deleted id with sale 
        $updatekSql = "UPDATE `inspections` 
        SET `lot_notes`='$lotNotes',`recon`='$recon',`repairs`='$repais',`shops`='$bodyshop',
        `shops_notes`='$bodyshopNotes',`estimated`='$estimate',`repair_sent`='$repairSent',
        `repair_returned`='$repairReturn',`repair_paid`='$repairPaid',`resend`='$resend',
        `pictures`='$imageArray',`windshield`='$windshield',`wheels`='$wheels',`submitted_by`='$submittedBy',
        `repair_sent_log`='$repairSent_log',`repair_returned_log`='$repairRetrun_log',
        `repairs_log`='$repairs_log',`repair_paid_log`='$repairPaid_log'
        WHERE inv_id = '$vehicleId'";

        if ($connect->query($updatekSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $sql = "INSERT INTO `inspections`
        (`inv_id`, `lot_notes`, `recon`, `repairs`, `shops`, 
        `shops_notes`, `estimated`, `repair_sent`, `repair_returned`, 
        `repair_paid`, `resend`, `pictures`, `windshield`, `wheels`, 
        `submitted_by`, `status`) VALUES
        (
            '$vehicleId',
            '$lotNotes',
            '$recon',
            '$repais',
            '$bodyshop',
            '$bodyshopNotes',
            '$estimate',
            '$repairSent',
            '$repairReturn',
            '$repairPaid',
            '$resend',
            '$imageArray',
            '$windshield',
            '$wheels',
            '$submittedBy', 1
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



    // echo $vehicleId . '<br />';
    // echo $lotNotes . '<br />';
    // echo $recon . '<br />';
    // echo $repais . '<br />';
    // echo $bodyshop . '<br />';
    // echo $estimate . '<br />';
    // echo $repairPaid . '<br />';
    // echo $repairSent . '<br />';  // dates
    // echo $repairReturn . '<br />'; // dates
    // echo $windshield . '<br />';  // dates
    // echo $wheels . '<br />'; // dates
    // echo $resend . '<br />'; // dates






    $connect->close();

    // echo json_encode($valid);

} // /if $_POST
echo json_encode($valid);
