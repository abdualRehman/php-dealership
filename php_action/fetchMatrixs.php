<?php

require_once 'db/core.php';

$sql = "SELECT `id`, `year`, `model`, `model_code`, `msrp`, `dlr_inv`, `model_des`, `trim`, `net`, `hb`, `invoice`, `bdc`, `m.s.r.p` ,  `status` FROM `manufature_price` WHERE status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {

        $id = $row[0];

        $year = trim($row[1]);
        $model = trim($row[2]); // model
        $model_code = trim($row[3]); // model code


        $matrixRuleSql = "SELECT `model`, `year`, `modelno`, `ex_modelno`, `destination`, `hb`, `status` FROM `matrix_rule` 
        WHERE model = '$model' AND ( year = '$year' OR year = 'All' ) AND ( modelno = '$model_code' OR modelno = 'All' ) AND ex_modelno != '$model_code' AND status = 1 LIMIT 1";
        $rsult = $connect->query($matrixRuleSql);
        if (($rsult) && $rsult->num_rows > 0) {
            while ($row1 = $rsult->fetch_array()) {
                // echo $year .' - '. $model . ' - ' . $row1[0] . ' - '. $model_code . ' - ' . $row1[4] . ' - ' . $row1[5] . ' - ' . $row1[6] . ' - ' . $row1[7] . ' - ' . $row1[8] .  '<br />';
                // $trim =  $row1[4];
                // $destination =  $row1[5];
                // $net =  $row1[6];
                // $hb =  $row1[7];
                // $invoice = $row[5] + $row1[5];  // invoice = Dlr Inv. + Destination (from CSV File)
                // $msrp = $row[4] + $row1[5];  // msrp =  MSRP + Destination (from CSV File)
                // $bdc = "-";

                $trim =  $row[7];

                // $net =  "$" . ($row[8]) ? number_format($row[8], 2, '.', ',') : "";
                // $hb =  "$" . ($row[9]) ? number_format($row[9], 2, '.', ',') : "";
                // $invoice = "$" . ($row[10]) ? number_format($row[10], 2, '.', ',') : "";
                // $bdc =  "$" . ($row[11]) ? number_format($row[11], 0, '.', ',') : "";
                // $msrp = "$" . ($row[12]) ? number_format($row[12], 0, '.', ',') : "";

                $net =  ($row[8]) ? "$". number_format($row[8], 2, '.', ',') : "";
                $hb =  ($row[9]) ? "$".number_format($row[9], 2, '.', ',') : "";
                $invoice = ($row[10]) ? "$".number_format($row[10], 2, '.', ',') : "";
                $bdc =   ($row[11]) ? "$".number_format($row[11], 0, '.', ',') : "";
                $msrp = ($row[12]) ? "$".number_format($row[12], 0, '.', ',') : "";
                // calculation 


                $button = '
                <div class="show" >
                    <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#showDetails" onclick="showDetails(' . $id . ')" >
                        <i class="fa fa-eye"></i>
                    </button>   
                </div> ';


                $output['data'][] = array(

                    $row[1], // year
                    $row[2], // model
                    $row[3], // model
                    $trim, // $row[7], // trim
                    $net, // $row[8], // net
                    $hb, // $row[9], // hb
                    $invoice, // $row[10], // invoice
                    $msrp, // $row[4], // mrsp
                    $bdc, // $row[11], // bdc
                    $button
                );
            }
        }
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
