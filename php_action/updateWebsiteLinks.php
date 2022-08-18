<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {

    $webLinkId = (isset($_POST['webLinkId']) && $_POST['webLinkId'] != '') ? $_POST['webLinkId'] : false;
    $webName = (isset($_POST['webName'])) ? mysqli_real_escape_string($connect, $_POST['webName']) : "";
    $webLink = (isset($_POST['webLink'])) ? mysqli_real_escape_string($connect, $_POST['webLink']) : "";



    if ($webLinkId != false) {
        $updatekSql = "UPDATE `web_links` SET `name`='$webName', `link`='$webLink' WHERE `id`= '$webLinkId'";
        if ($connect->query($updatekSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    } else {
        $sql = "INSERT INTO `web_links` ( `name`, `link`, `status`) VALUES ('$webName','$webLink',1)";

        if ($connect->query($sql) === true) {
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
// echo json_encode($valid);