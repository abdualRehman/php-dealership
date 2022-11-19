<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sql = "SELECT users.id, users.username, users.email, role.role_name , users.mobile , users.extention , users.status FROM `users` LEFT JOIN role ON users.role = role.role_id WHERE `status` != 2 AND users.location = '$location' ORDER BY role.role_name asc";


$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $userid = $row[0];
        if (is_null($row[3])) {
            $row[3] = "Admin";
        }

        $button = "";
        if ($row[1] != 'Admin' && hasAccess("user", "Edit") !== 'false' &&  hasAccess("user", "Remove") !== 'false') {
            $button = '
            <div class="show d-flex" >
                <div class="custom-control custom-control-lg custom-checkbox">
                    <input type="checkbox" name="' . $userid . 'checkbox" class="custom-control-input editCheckbox" id="' . $userid . '" ' . (($row['status'] != '1') ? '' : 'checked="checked"') . ' >
                    <label class="custom-control-label" for="' . $userid . '"></label> 
                </div>
                <div class="dropdown show">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action</button>
                        <div class="dropdown-menu dropdown-menu-left" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);">' .
                    (hasAccess("user", "Edit") !== 'false' ?  '<a class="dropdown-item" data-toggle="modal" data-target="#modal8" onclick="editUser(' . $userid . ')" >Edit Details</a> <a class="dropdown-item" data-toggle="modal" data-target="#modal9" onclick="editPasswords(' . $userid . ')" >Edit Password</a>' : "") .
                    (hasAccess("user", "Remove") !== 'false' ? '<a class="dropdown-item" data-toggle="modal" data-target="#modal7" onclick="removeUser(' . $userid . ')" >Remove</a>' : "") .
                    '</div>
                </div>
            </div>

            ';
        }

        $output['data'][] = array(
            $row[3],
            $row[1],
            $row[2],
            $row[4],
            $row[5],
            $button,
            $row['status'],
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
