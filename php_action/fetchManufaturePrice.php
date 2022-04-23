<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `year`, `model`, `model_code`, `msrp`, `dlr_inv`, `model_des`, `trim`, `net`, `hb`, `invoice`, `bdc`, `status` FROM `manufature_price` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
        <div class="show d-flex" >' .

            (hasAccess("manprice", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#editDetails" onclick="editDetails(' . $id . ')" >
                <i class="fa fa-edit"></i>
            </button>' : "") .
            (hasAccess("manprice", "Remove") !== 'false' ?  '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeManufacturePrice(' . $id . ')"  >
                <i class="fa fa-trash"></i>
            </button>' : "") .
            '</div>
    ';


        $output['data'][] = array(
            $row[0],
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $row[5],
            $row[6],
            $row[7],
            $button
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
