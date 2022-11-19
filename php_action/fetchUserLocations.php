<?php

require_once 'db/core.php';

$sql = "SELECT `id` , `name`, `status` FROM `user_location` WHERE status != 2";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {
        $id = $row[0];

        $button = '
        <div class="show" >
            <div class="custom-control custom-control-lg custom-checkbox">
                <input type="checkbox" name="' . $id . 'checkbox" class="custom-control-input editCheckbox" id="' . $id . '" ' . (($row['status'] != '1') ? '' : 'checked="checked"') . ' >
                <label class="custom-control-label" for="' . $id . '"></label> 
            </div>
        ';
        if ($id != 1) {
            $button .= '
                <button class="btn btn-label-primary btn-icon mr-1" onclick="removeLoc(' . $id . ')" >
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            ';
        }
        $button .= '
        </div>
        ';

        $output['data'][] = array(
            $id,
            $row[1],
            $button,
            $row['status']
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
