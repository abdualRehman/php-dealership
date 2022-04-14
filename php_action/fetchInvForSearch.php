<?php

require_once 'db/core.php';

// $sql = "SELECT `id`, `stockno`, `year`, `make`, `model`, `modelno`, `color`, `lot`, `vin`, `mileage`, `age`, `balance`, `retail`, `certified`, `stocktype`, `wholesale`, `status` FROM `inventory` WHERE status = 1 ORDER BY `id` DESC LIMIT 3";
$sql = "SELECT inventory.id, inventory.stockno, inventory.year, inventory.make, inventory.model, inventory.modelno, inventory.color, 
inventory.lot, inventory.vin, inventory.mileage, inventory.age, inventory.balance, inventory.retail, inventory.certified, inventory.stocktype, 
inventory.wholesale, inventory.status , sales.sale_status FROM inventory LEFT JOIN sales ON inventory.id = sales.stock_id 
WHERE inventory.status = 1 ORDER BY inventory.id ASC";
// $sql = "SELECT inventory.id, inventory.stockno, inventory.year, inventory.make, inventory.model, inventory.modelno, inventory.color, inventory.lot, inventory.vin, inventory.mileage, inventory.age, inventory.balance, inventory.retail, inventory.certified, inventory.stocktype, inventory.wholesale, inventory.status , sales.sale_status FROM inventory LEFT JOIN sales ON inventory.id = sales.stock_id WHERE inventory.status = 1 ORDER BY inventory.id ASC limit 3";
$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {

        $salespersonTodoArray = array();

        $year = $row[2];  // model
        $model = $row[4];  // model
        $modelno = $row[5];  // model no
        $stockType = $row[14]; // stocktype


        // Incentive rules
        $inc_from_date = null;
        $inc_to_date = null;
        $college = 'N/A';
        $military = 'N/A';
        $loyalty = 'N/A';
        $conquest = 'N/A';
        $misc1 = 'N/A';
        $misc2 = 'N/A';
        $misc3 = 'N/A';

        // Sales Person Todo rules
        $sp_from_date = null;
        $sp_to_date = null;
        $vin_check = 'N/A';
        $insurance = 'N/A';
        $trade_title = 'N/A';
        $registration = 'N/A';
        $inspection = 'N/A';
        $salesperson_status = 'N/A';
        $paid = 'N/A';


        // $ruleSql = "SELECT * FROM `incentive_rules` WHERE model = '$model' AND 
        // ( year = '$year' OR year = 'ALL' ) AND ( modelno = '$modelno' OR modelno = 'ALL' ) AND type = '$stockType' AND status = 1 LIMIT 1";
        $ruleSql = "SELECT * FROM `incentive_rules` WHERE model = '$model' AND 
        ( year = '$year' OR year = 'ALL' ) AND ( modelno = '$modelno' OR modelno = 'ALL' ) AND 
        (type = '$stockType' OR type = 'BOTH' ) AND status = 1 AND
        `ex_modelno` NOT LIKE '%_" . $modelno . "_%' ORDER BY FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC, FIELD(type, '$stockType') DESC LIMIT 1";
        $result1 = $connect->query($ruleSql);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_array()) {
                // if($model === 'ACCORD'){
                //     echo $model . ' - '. $year . ' - ' . $modelno . ' - ' . $stockType . '<br />';
                //     echo $row1['model'] . ' - '. $row1['year'] . ' - ' . $row1['modelno'] . ' - ' . $row1['type'] . '<br />';
                //     echo '<hr />';
                // }

                $college =  $row1['college'];
                $military =  $row1['military'];
                $loyalty =  $row1['loyalty'];
                $conquest =  $row1['conquest'];
                $misc1 =  $row1['misc1'];
                $misc2 =  $row1['misc2'];
                $misc3 =  $row1['misc3'];
                $inc_from_date =  $row1['from_date'];
                $inc_to_date =  $row1['to_date'];
            }
        }


        // $ruleSql = "SELECT * FROM `salesperson_rules` WHERE model = '$model' AND 
        // ( year = '$year' OR year = 'ALL' ) AND ( modelno = '$modelno' OR modelno = 'ALL' ) AND 
        // (type = '$stockType' OR type = 'ALL' )  AND status = 1 AND
        // `ex_modelno` NOT LIKE '%_" . $modelno . "_%' ORDER BY FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC, FIELD(type, '$stockType') DESC LIMIT 1";

        $ruleSql = "SELECT * FROM `salesperson_rules` WHERE model = '$model' AND 
        ( year = '$year' OR year = 'ALL' ) AND ( modelno = '$modelno' OR modelno = 'ALL' ) AND 
        (type = '$stockType' OR type = 'ALL' )  AND status = 1 AND
        `ex_modelno` NOT LIKE '%_" . $modelno . "_%' ORDER BY FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC, FIELD(type, '$stockType') DESC";
        $result2 = $connect->query($ruleSql);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_array()) {

                // array_push( $salespersonTodoArray ,  );
                $salespersonTodoArray[] = array(
                    $row2['model'],
                    $row2['year'],
                    $row2['modelno'],
                    $row2['type'],
                    $row2['state'],

                    $row2['vin_check'],
                    $row2['insurance'],
                    $row2['trade_title'],
                    $row2['registration'],
                    $row2['inspection'],
                    $row2['salesperson_status'],
                    $row2['paid'],
                    $row2['from_date'],
                    $row2['to_date']
                );
                // $vin_check =  $row2['vin_check'];
                // $insurance =  $row2['insurance'];
                // $trade_title =  $row2['trade_title'];
                // $registration =  $row2['registration'];
                // $inspection =  $row2['inspection'];
                // $salesperson_status =  $row2['salesperson_status'];
                // $paid =  $row2['paid'];
                // $sp_from_date =  $row2['from_date'];
                // $sp_to_date =  $row2['to_date'];
            }
        }




        $id = $row[0];

        $certified;
        if ($row[13] == 'on') {
            $certified = "Certified";
        } else {
            $certified = "";
        }
        $wholesale;
        if ($row[15] == 'on') {
            $wholesale = "Wholesale";
        } else {
            $wholesale = "";
        }




        // echo json_encode($salespersonTodoArray) . " <br />";

        $output['data'][] = array(
            $row[0],  // id //0
            $row[1],  //stockno //1
            $row[2],  // year //2
            $row[3],  // make  //3
            $row[4],  // model //4
            $row[5], // modelno //5
            $row[6], // color //6
            $row[7], // lot //7
            $row[8], // vin //8
            $row[9], // mileage //9
            $row[10], // age // 10
            $row[11], // balance // 11
            $row[12], // retail // 12
            $row[13], // certified // 13
            $row[14], // stocktype // 14
            $row[15], // wholesale // 15
            $row[17], // sale Status // 16
            $inc_from_date, // 117
            $inc_to_date, // 18
            $college, // 19
            $military, // 20
            $loyalty, // 21
            $conquest, // 22
            $misc1, // 23
            $misc2, // 24
            $misc3, // 25

            // $sp_from_date, // 26
            // $sp_to_date, // 27
            // $vin_check, // 28
            // $insurance, // 29
            // $trade_title, // 30
            // $registration, // 31
            // $inspection, // 32
            // $salesperson_status, // 33
            // $paid, // 34
            'spTodoArray' => $salespersonTodoArray,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
