<?php
// error_reporting(0); error_reporting (E_ALL ^ E_NOTICE);
require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');

function reformatDate($date, $from_format = 'M-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {

    $imageArray = array();

    $incentiveId = $_POST['incentiveId'];

    // echo $incentiveId;

    // sales person Todo
    $college = mysqli_real_escape_string($connect, $_POST['college']);
    $military = mysqli_real_escape_string($connect, $_POST['military']);
    $loyalty = mysqli_real_escape_string($connect, $_POST['loyalty']);
    $conquest = mysqli_real_escape_string($connect, $_POST['conquest']);
    $misc1 = mysqli_real_escape_string($connect, $_POST['misc1']);
    $misc2 = mysqli_real_escape_string($connect, $_POST['misc2']);
    $misc3 = mysqli_real_escape_string($connect, $_POST['misc3']);

    $collegeDate = mysqli_real_escape_string($connect, $_POST['collegeDate']);
    $collegeDate = ($collegeDate) ? reformatDate($collegeDate) : "";

    $militaryDate =  mysqli_real_escape_string($connect, $_POST['militaryDate']);
    $militaryDate = ($militaryDate) ? reformatDate($militaryDate) : "";

    $loyaltyDate = mysqli_real_escape_string($connect, $_POST['loyaltyDate']);
    $loyaltyDate = ($loyaltyDate) ? reformatDate($loyaltyDate) : "";

    $conquestDate = mysqli_real_escape_string($connect, $_POST['conquestDate']);
    $conquestDate = ($conquestDate) ? reformatDate($conquestDate) : "";

    $misc1Date = mysqli_real_escape_string($connect, $_POST['misc1Date']);
    $misc1Date = ($misc1Date) ? reformatDate($misc1Date) : "";

    $misc2Date = mysqli_real_escape_string($connect, $_POST['misc2Date']);
    $misc2Date = ($misc2Date) ? reformatDate($misc2Date) : "";

    $misc3Date = mysqli_real_escape_string($connect, $_POST['misc3Date']);
    $misc3Date = ($misc3Date) ? reformatDate($misc3Date) : "";


    // echo $collegeDate . '<br />';
    // echo $militaryDate . '<br />';
    // echo $militaryDate . '<br />';
    // echo $conquestDate . '<br />';
    // echo $misc1Date . '<br />';
    // echo $misc2Date . '<br />';
    // echo $misc3Date . '<br />';

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

                $folder = '../assets/IncentivesProof/' . $filename;
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


    $imageArray = implode(",", $imageArray);
    // echo $imageArray;




    if ($incentiveId) {
        // $imageArray = json_encode($imageArray);
        // echo "";
        // echo $imageArray;

        $saleTodoSql = "UPDATE `sale_incentives` SET 
        `college`='$college',
        `military`='$military',
        `loyalty`='$loyalty',
        `conquest`='$conquest',
        `misc1`='$misc1',
        `misc2`='$misc2',
        `misc3`='$misc3',
        `college_date`='$collegeDate',
        `military_date`='$militaryDate',
        `loyalty_date`='$loyaltyDate',
        `conquest_date`='$conquestDate',
        `misc1_date`='$misc1Date',
        `misc2_date`='$misc2Date',
        `misc3_date`='$misc3Date',
        `images`='$imageArray' 
        WHERE incentive_id = '$incentiveId'";


        if ($connect->query($saleTodoSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }




    $connect->close();
    echo json_encode($valid);
} // /if $_POST
