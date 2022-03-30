<?php

require_once 'db/core.php';

$id = $_POST['id'];
$sql = "SELECT id , username , email FROM `users` LEFT JOIN role ON users.role = role.role_id WHERE role.role_id = '$id' AND users.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $output['data'][] = array(
            $row[0],  // id
            $row[1],  //username
            $row[2],  // email
        );
    } // /while 
} // if num_rows

$connect->close();
echo json_encode($output);
