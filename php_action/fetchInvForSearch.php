<?php

require_once 'db/core.php';

// $sql = "SELECT inventory.id, inventory.stockno, inventory.year, inventory.make, inventory.model, inventory.modelno, inventory.color, 
// inventory.lot, inventory.vin, inventory.mileage, inventory.age, inventory.balance, inventory.retail, inventory.certified, inventory.stocktype, 
// inventory.wholesale, inventory.status , sales.sale_status FROM inventory LEFT JOIN sales ON inventory.id = sales.stock_id 
// WHERE inventory.status = 1 ORDER BY inventory.id ASC";

$sql = "SELECT inventory.id, inventory.stockno, inventory.year, inventory.make, inventory.model, inventory.modelno, inventory.color, 
inventory.lot, inventory.vin, inventory.mileage, inventory.age, inventory.balance, inventory.retail, inventory.certified, inventory.stocktype, 
inventory.wholesale, inventory.status FROM inventory WHERE inventory.status = 1 ORDER BY inventory.id ASC";


$result = $connect->query($sql);
$output = array('data' => array());


if ($result->num_rows > 0) {

    // $row = $result->fetch_array();
    // pg_fetch_assoc()

    while ($row = $result->fetch_assoc()) {

        $salespersonTodoArray = array();
        $saleStatusArray = array();

        $stock_id = $row['id'];
        $statusSql = "SELECT sales.sale_status FROM sales WHERE sales.stock_id = '$stock_id'";
        $rslt = $connect->query($statusSql);
        if ($rslt->num_rows > 0) {
            while ($r = $rslt->fetch_assoc()) {
                $saleStatusArray[] = $r['sale_status'];
            }
        }


        $year = $row['year'];  // year
        $model = $row['model'];  // model
        $modelno = $row['modelno'];  // model no
        $stockType = $row['stocktype']; // stocktype


        // Incentive rules
        $college = 'N/A';
        $college_e = 'N/A';
        $military = 'N/A';
        $military_e = 'N/A';
        $loyalty = 'N/A';
        $loyalty_e = 'N/A';
        $conquest = 'N/A';
        $conquest_e = 'N/A';
        $misc1 = 'N/A';
        $misc1_e = 'N/A';
        $misc2 = 'N/A';
        $misc2_e = 'N/A';
        $lease_loyalty = 'N/A';
        $lease_loyalty_e = 'N/A';

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
        $ruleSql = "SELECT * FROM `incentive_rules` WHERE ( model = '$model' OR model = 'All' ) AND 
        ( year = '$year' OR year = 'All' ) AND ( modelno = '$modelno' OR modelno = 'All' ) AND 
        (type = '$stockType' OR type = 'ALL' ) AND status = 1 AND
        `ex_modelno` NOT LIKE '%_" . $modelno . "_%' ORDER BY FIELD(model, '$model') DESC, FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC, FIELD(type, '$stockType') DESC LIMIT 1";
        $result1 = $connect->query($ruleSql);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_array()) {
                // if($model === 'ACCORD'){
                //     echo $model . ' - '. $year . ' - ' . $modelno . ' - ' . $stockType . '<br />';
                //     echo $row1['model'] . ' - '. $row1['year'] . ' - ' . $row1['modelno'] . ' - ' . $row1['type'] . '<br />';
                //     echo '<hr />';
                // }

                $college =  $row1['college'];
                $college_e =  $row1['college_e'];
                $military =  $row1['military'];
                $military_e =  $row1['military_e'];
                $loyalty =  $row1['loyalty'];
                $loyalty_e =  $row1['loyalty_e'];
                $conquest =  $row1['conquest'];
                $conquest_e =  $row1['conquest_e'];
                $misc1 =  $row1['misc1'];
                $misc1_e =  $row1['misc1_e'];
                $misc2 =  $row1['misc2'];
                $misc2_e =  $row1['misc2_e'];
                $lease_loyalty =  $row1['lease_loyalty'];
                $lease_loyalty_e =  $row1['lease_loyalty_e'];
                
            }
        }


        // $ruleSql = "SELECT * FROM `salesperson_rules` WHERE model = '$model' AND 
        // ( year = '$year' OR year = 'ALL' ) AND ( modelno = '$modelno' OR modelno = 'ALL' ) AND 
        // (type = '$stockType' OR type = 'ALL' )  AND status = 1 AND
        // `ex_modelno` NOT LIKE '%_" . $modelno . "_%' ORDER BY FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC, FIELD(type, '$stockType') DESC";

        $ruleSql = "SELECT * FROM `salesperson_rules` WHERE ( model = '$model' OR model = 'All' ) AND 
        ( year = '$year' OR year = 'All' ) AND ( modelno = '$modelno' OR modelno = 'All' ) AND 
        (type = '$stockType' OR type = 'ALL' )  AND status = 1 AND
        `ex_modelno` NOT LIKE '%_" . $modelno . "_%' ORDER BY FIELD(model, '$model') DESC, FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC, FIELD(type, '$stockType') DESC";

        $result2 = $connect->query($ruleSql);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_array()) {

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
                );
            }
        }





        // if ($row[4] === "RIDGELINE") {

        // echo json_encode($salespersonTodoArray) . " <br />";
        $output['data'][] = array(
            $row['id'],  // id //0
            $row['stockno'],  //stockno //1
            $row['year'],  // year //2
            $row['make'],  // make  //3
            $row['model'],  // model //4
            $row['modelno'], // modelno //5
            $row['color'], // color //6
            $row['lot'], // lot //7
            $row['vin'], // vin //8
            $row['mileage'], // mileage //9
            $row['age'], // age // 10
            $row['balance'], // balance // 11
            $row['retail'], // retail // 12
            $row['certified'], // certified // 13
            $row['stocktype'], // stocktype // 14
            $row['wholesale'], // wholesale // 15
            // $row['sale_status'], // sale Status // 16
            $saleStatusArray, // sale Status // 16
            $college, 
            $college_e,
            $military,
            $military_e, 
            $loyalty,
            $loyalty_e, 
            $conquest,
            $conquest_e, 
            $misc1,
            $misc1_e, 
            $misc2, 
            $misc2_e, 
            $lease_loyalty,
            $lease_loyalty_e,
            $row['status'],
            'spTodoArray' => $salespersonTodoArray,
        );
        // $output['data'][] = array(
        //     $row[0],  // id //0
        //     $row[1],  //stockno //1
        //     $row[2],  // year //2
        //     $row[3],  // make  //3
        //     $row[4],  // model //4
        //     $row[5], // modelno //5
        //     $row[6], // color //6
        //     $row[7], // lot //7
        //     $row[8], // vin //8
        //     $row[9], // mileage //9
        //     $row[10], // age // 10
        //     $row[11], // balance // 11
        //     $row[12], // retail // 12
        //     $row[13], // certified // 13
        //     $row[14], // stocktype // 14
        //     $row[15], // wholesale // 15
        //     $row[17], // sale Status // 16
        //     $inc_from_date, // 117
        //     $inc_to_date, // 18
        //     $college, // 19
        //     $military, // 20
        //     $loyalty, // 21
        //     $conquest, // 22
        //     $misc1, // 23
        //     $misc2, // 24
        //     $misc3, // 25
        //     $row[16], // 26 inventory status

        //     // $sp_from_date, // 26
        //     // $sp_to_date, // 27
        //     // $vin_check, // 28
        //     // $insurance, // 29
        //     // $trade_title, // 30
        //     // $registration, // 31
        //     // $inspection, // 32
        //     // $salesperson_status, // 33
        //     // $paid, // 34
        //     'spTodoArray' => $salespersonTodoArray,
        // );


        // }
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
