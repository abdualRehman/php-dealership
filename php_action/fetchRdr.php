<?php

require_once 'db/core.php';

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$roleSQL = "SELECT `id` , `year` , `make` , `model` , `model_type` , `certified` , `rdr_type` FROM `rdr_rules` WHERE status = 1 AND location = '$location'";
$rulesResults = $connect->query($roleSQL);

$output = array('data' => array());

if ($rulesResults->num_rows > 0) {
    while ($ruleRow = $rulesResults->fetch_assoc()) {

        $year = $ruleRow['year'];
        $year = ($year == 'All' || $year == 'all') ? "true" : $year;
        $make = $ruleRow['make'];
        $make = ($make == 'All' || $make == 'all') ? "true" : $make;
        $model = $ruleRow['model'];
        $model = ($model == 'All' || $model == 'all') ? "true" : $model;
        $model_type = $ruleRow['model_type'];
        $model_type = ($model_type == 'All') ? "true" : strtoupper($model_type);
        $certifiedv = $ruleRow['certified'];
        $certifiedv = ($certifiedv == 'Yes') ? "on" : "off";
        $rdr_type = $ruleRow['rdr_type'];


        $sql = "SELECT sales.* , inventory.stockno, inventory.vin , inventory.year , inventory.make , inventory.model , inventory.stocktype , sales.certified , rdr.delivered, rdr.entered, rdr.approved, rdr.rdr_notes 
        FROM sales LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN rdr ON sales.sale_id = rdr.sale_id 
        WHERE sales.status = 1 AND sales.sale_status != 'cancelled' 
        AND inventory.year = " . ($year == "true" ? "inventory.year"  : "'$year'") . " 
        AND inventory.model " . ($model == "true" ? " = inventory.model"  : "LIKE '$model%' ") . "
        AND inventory.make  " . ($make == "true" ? " = inventory.make"  : " LIKE '$make%' ") . " 
        AND inventory.stocktype = " . ($model_type == "true" ? "inventory.stocktype"  : "'$model_type'") . "
        AND sales.certified ='$certifiedv' AND sales.location = '$location' ";


        $result = $connect->query($sql);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $id = $row['sale_id'];

                $sale_date = $row['date'];
                $reconcileDate = $row['reconcileDate'];

                $status = $row['sale_status'];
                $fname = $row['fname'];
                $lname = $row['lname'];
                $stockNo = $row['stockno'];
                $vin = $row['vin'];


                $year = $row['year'];
                $make = $row['make'];
                $model = $row['model'];
                $modelType = $row['stocktype'];
                $certified = ($row['certified'] == 'on') ? "Yes" : "No";


                $delivered = isset($row['delivered']) ? $row['delivered'] : "";
                $entered = isset($row['entered']) ? $row['entered'] : "";
                $approved = isset($row['approved']) ? $row['approved'] : "";
                $rdr_notes = $row['rdr_notes'];






                $output['data'][] = array(
                    $id,
                    $status,
                    $sale_date,
                    $reconcileDate,
                    $fname,
                    $lname,
                    $stockNo,
                    $vin,
                    $certified,
                    $delivered,
                    $entered,
                    $approved,
                    $rdr_notes
                );
            } // /while 

        } // if num_rows

    }
}


$connect->close();

echo json_encode($output);
