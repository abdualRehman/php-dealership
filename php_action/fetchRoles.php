<?php

use LDAP\Result;

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sql = "SELECT `role_id`, `role_name`, `role_des` , `role_status` FROM `role` WHERE `role_status` != 2 AND `location_id` = '$location'";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $roleId = $row[0];
        $status = $row[3];

        $button = "";

        $button = '<div class="dropdown show">
            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action</button>
                <div class="dropdown-menu dropdown-menu-left" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);">' .
            (hasAccess("role", "Edit") !== 'false' ? '<a class="dropdown-item" href="' . $GLOBALS['siteurl'] . '/users/roleList.php?r=edit&i=' . $roleId . '" >Edit</a>' : "");
        if (hasAccess("role", "Remove") !== 'false' && $status != '3') {
            $button .= '<a class="dropdown-item" onclick="removeRole(' . $roleId . ')" >Remove</a>';
        }
        $button .= '</div>
            </div>';


        $output['data'][] = array(
            $row[1],
            $row[2],
            $button
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
