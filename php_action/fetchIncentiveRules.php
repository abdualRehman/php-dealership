<?php

require_once 'db/core.php';

// $sql = "SELECT `id`, `from_date`, `to_date`, `model`, `year`, `modelno`, `college`, `military`, `loyalty`, `conquest`, `misc1`, `misc2`, `misc3` , `type` , `ex_modelno` FROM `incentive_rules` WHERE status = 1";
$sql = "SELECT `id`, `model`, `year`, `modelno`, `ex_modelno`, `type`, `college`, `college_e`, `military`, `military_e`, `loyalty`, `loyalty_e`, `conquest`, `conquest_e`, `misc1`, `misc1_e`, `misc2`, `misc2_e`, `lease_loyalty`, `lease_loyalty_e` FROM `incentive_rules` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    // while ($row = $result->fetch_array()) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];


        $ex_modelno = $row['ex_modelno'];
        $ex_modelno = str_replace('_', ' ', $ex_modelno);

        // date_default_timezone_set('Australia/Melbourne');

        $date = date('Y-m-d');
        $date1 = new DateTime($date);

        $college_e = new DateTime($row['college_e']);
        $diff = $date1->diff($college_e)->format("%r%a");
        if ($diff <= 0) {
            $college = "Expire";
        } else {
            $college = ($row['college'] != '') ?  $row['college'] : "N/A";
        }

        $military_e = new DateTime($row['military_e']);
        $diff = $date1->diff($military_e)->format("%r%a");
        if ($diff <= 0) {
            $military = "Expire";
        } else {
            $military = ($row['military'] !='') ? $row['military']  : "N/A";
        }
        
        $loyalty_e = new DateTime($row['loyalty_e']);
        $diff = $date1->diff($loyalty_e)->format("%r%a");
        if ($diff <= 0) {
            $loyalty = "Expire";
        } else {
            $loyalty = ($row['loyalty'] !='') ? $row['loyalty']  : "N/A";
        }
        $conquest_e = new DateTime($row['conquest_e']);
        $diff = $date1->diff($conquest_e)->format("%r%a");
        if ($diff <= 0) {
            $conquest = "Expire";
        } else {
            $conquest = ($row['conquest'] !='') ? $row['conquest']  : "N/A";
        }

        $misc1_e = new DateTime($row['misc1_e']);
        $diff = $date1->diff($misc1_e)->format("%r%a");
        if ($diff <= 0) {
            $misc1 = "Expire";
        } else {
            $misc1 = ($row['misc1'] !='') ? $row['misc1']  : "N/A";
        }

        $misc2_e = new DateTime($row['misc2_e']);
        $diff = $date1->diff($misc2_e)->format("%r%a");
        if ($diff <= 0) {
            $misc2 = "Expire";
        } else {
            $misc2 = ($row['misc2'] != '') ? $row['misc2']  : "N/A";
        }

        $lease_loyalty_e = new DateTime($row['lease_loyalty_e']);
        $diff = $date1->diff($lease_loyalty_e)->format("%r%a");
        if ($diff <= 0) {
            $lease_loyalty = "Expire";
        } else {
            $lease_loyalty = ($row['lease_loyalty'] != '') ? $row['lease_loyalty']  : "N/A";
        }


        $button = '
            <div class="show d-flex" >' .
            // (hasAccess("incr", "Edit") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#modal8" onclick="editRule(' . $id . ')" >
            //         <i class="fa fa-edit"></i>
            //     </button>' : "") .
            (hasAccess("incr", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeRule(' . $id . ')" >
                    <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>
        ';



        $output['data'][] = array(
            $row['model'],
            $row['year'],
            $row['modelno'],
            $row['type'],  // type
            $ex_modelno,            
            $college,
            $military,
            $loyalty,
            $conquest,
            $lease_loyalty,
            $misc1,
            $misc2,
            $button,
            $id,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
