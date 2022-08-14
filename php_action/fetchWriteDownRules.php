<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `age_from`, `age_to`, `pencent_balance`, `balance_from`, `balance_to`, `max_writedown` FROM `writedown_rules` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

function asDollars($value)
{
    if ($value < 0) return "-" . asDollars(-$value);
    return '$' . number_format($value, 2);
}

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {
        $id = $row[0];

        $agrG = $row[1] . ' - ' . $row[2];
        $percentBalance = $row[3];
        $balanceF =  $row[4];
        $balanceT =  $row[5];
        $writedown =  $row[6];

        // $button = '
        //     <div class="show d-flex" >' .
        //     (hasAccess("raterule", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
        //             <i class="fa fa-trash"></i>
        //         </button>' : "") .
        //     '</div>
        // ';
        $button = '
            <div class="show d-flex" >
            <button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        ';


        $output['data'][] = array(
            $agrG,
            $percentBalance,
            asDollars($balanceF),
            asDollars($balanceT),
            asDollars($writedown),
            $button,
            $id,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
