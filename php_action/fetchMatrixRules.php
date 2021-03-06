<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `destination`, `hb`, `status` FROM `matrix_rule` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
            <div class="show d-flex" >' .
            (hasAccess("matrixrule", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editRule(' . $id . ')" >
                    <i class="fa fa-edit"></i>
                </button>' : "") .
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
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
