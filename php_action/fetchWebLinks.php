<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$userRole = $_SESSION['userRole'];

$sql = "SELECT * FROM `web_links` WHERE status = 1 AND location = '$location' ".($userRole != 'Admin'? " AND visible_role LIKE '%_" . $userRole . "_%' " : "" )." ORDER BY `name` ASC";
$result = $connect->query($sql);
$output = array('data' => array());
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $editPermission = (hasAccess("weblink", "Edit") !== 'false') ? "true": "false";
        $removePermission = (hasAccess("weblink", "Remove") !== 'false') ? "true": "false";
        $role = $_SESSION['userRole'];
        $id = $row['id'];
        $name = $row['name'];
        $link = $row['link'];
        $visible_role = $row['visible_role'];

        $output['data'][] = array(
            $id,
            $name,
            $link,
            $editPermission,
            $removePermission,
            $visible_role,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
