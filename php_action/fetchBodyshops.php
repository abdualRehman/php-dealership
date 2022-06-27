<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `business_name`, `shop`, `address`, `city`, `state`, `zip`, `contact_person`, `contact_number`, `status` FROM `bodyshops` WHERE `status` != 2 ORDER BY `id` DESC";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
            <div class="show d-flex" >
            <div class="custom-control custom-control-lg custom-checkbox">
                <input type="checkbox" name="' . $id . 'checkbox" class="custom-control-input editCheckbox" id="' . $id . '" ' . (($row[9] == '0') ? '' : 'checked="checked"') . ' >
                <label class="custom-control-label" for="' . $id . '"></label> 
            </div>
            ' .
            (hasAccess("bodyshops", "Remove") !== 'false' ?  '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeShop(' . $id . ')" >
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
            $button,
            $row[9],
            $id,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
