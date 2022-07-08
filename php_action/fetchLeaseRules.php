<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `24`, `27`, `30`, `33`, `36`, `39`, `42`, `45`, `48`, `51`, `54`, `57`, `60`, `12_24_33`, `12_36_48`, `10_24_33`, `10_36_48`, `status` FROM `lease_rule` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
            <div class="show d-flex" >' .
            // (hasAccess("leaserule", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editRule(' . $id . ')" >
            //         <i class="fa fa-edit"></i>
            //     </button>' : "") .
            (hasAccess("leaserule", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
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
            $row[7],
            $row[8],
            $row[9],
            $row[10],
            $row[11],
            $row[12],
            $row[13],
            $row[14],
            $row[15],
            $row[16],
            $row[17],
            $button,
            $id,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
