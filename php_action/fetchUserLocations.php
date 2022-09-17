<?php

require_once 'db/core.php';

$sql = "SELECT `id` , `name` FROM `user_location` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {
        $id = $row[0];
        
        $button = '';
        if ($id != 1) {
            $button = '
                <div class="show d-flex" >
                <button class="btn btn-label-primary btn-icon mr-1" onclick="removeLoc(' . $id . ')" >
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            ';
        }

        $output['data'][] = array(
            $id,
            $row[1],
            $button
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
