<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `destination`, `hb`, `status` FROM `matrix_rule` WHERE status = 1 AND location = '$location'";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
            <div class="show d-flex" >' .
            (hasAccess("matrixrule", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>
        ';


        $output['data'][] = array(
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $row[5],
            $row[6],
            $button,
            $id,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
