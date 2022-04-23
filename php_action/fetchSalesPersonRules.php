<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `model`, `year`, `modelno`, `vin_check`, `insurance`, `trade_title`, `registration`, `inspection`, `salesperson_status`, `paid` , `type` , `ex_modelno` , `state` FROM `salesperson_rules` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

function chnageFormat($input)
{
    $data = preg_split('/(?=[A-Z])/', $input);
    $string = implode(' ', $data);
    return ucwords($string);
}

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $ex_modelno = $row[12];
        $ex_modelno = str_replace('_', ' ', $ex_modelno);

        $state = $row[13];


        $button = '
            <div class="show d-flex" >' .
            (hasAccess("sptr", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editRule(' . $id . ')" >
                    <i class="fa fa-edit"></i>
                </button>' : "") .
            (hasAccess("sptr", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>
        ';


        $vin_check = ($row[4] === 'N/A') ? "Disabled" : chnageFormat($row[4]);
        $insurance = ($row[5] === 'N/A') ? "Disabled" : chnageFormat($row[5]);
        $trade_title = ($row[6] === 'N/A') ? "Disabled" : chnageFormat($row[6]);
        $registration = ($row[7] === 'N/A') ? "Disabled" : chnageFormat($row[7]);
        $inspection = ($row[8] === 'N/A') ? "Disabled" : chnageFormat($row[8]);
        $salesperson_status = ($row[9] === 'N/A') ? "Disabled" : chnageFormat($row[9]);
        $paid = ($row[10] === 'N/A') ? "Disabled" : chnageFormat($row[10]);



        $output['data'][] = array(
            $row[1],
            $row[2],
            $row[3],
            $state,
            $row[11],  // type
            $ex_modelno,
            $vin_check,
            $insurance,
            $trade_title,
            $registration,
            $inspection,
            $salesperson_status,
            $paid,
            $button,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
