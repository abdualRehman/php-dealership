<?php
error_reporting(0);

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array());


if ($_POST) {

    $fileType = $_POST['fileName'];

    $fileName = $_FILES['retailRate']['name'];

    $targetPath = "../assets/uploadMatrixRateFiles/" . $_FILES['retailRate']['name'];
    $targetPath = mysqli_real_escape_string($connect, $targetPath);
    move_uploaded_file($_FILES['retailRate']['tmp_name'], $targetPath);
    $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
    
    $checkSql = "SELECT * FROM `settings` WHERE `file_type` = '$fileType' AND location = '$location'";
    $result = $connect->query($checkSql);
    if ($result->num_rows > 0) {

        $sql = "UPDATE settings SET file_type='$fileType', file_name='$fileName' WHERE file_type = '$fileType' AND location = '$location'";
        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['erorStock'][] = $connect->error;
        }
    } else {
        
        $sql = "INSERT INTO `settings` ( `file_type`, `file_name`, `location`) VALUES ( '$fileType', '$fileName' '$location' )";
        if ($connect->query($sql) === true) {
            $valid['success'] = true;
            $valid['messages'] =  "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['erorStock'][] = $connect->error . ' stock no = ' . $stockno;
        }
    }

    // echo "successfully added";
    $connect->close();
    echo json_encode($valid);
}
