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

    $sql = "UPDATE settings SET file_type='$fileType', file_name='$fileName' WHERE file_type = '$fileType' ";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'] =  "Successfully Added";
    } else {
        $valid['success'] = true;
        $valid['erorStock'] = $connect->error;
    }

    // echo "successfully added";
    $connect->close();
    echo json_encode($valid);
}
