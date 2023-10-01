<?php

require_once 'db/core.php';


$id = $_POST['id'];

$sql = "SELECT inventory.id, inventory.stockno, inventory.year, inventory.make, inventory.model, inventory.modelno, inventory.color, 
inventory.lot, inventory.vin, inventory.mileage, inventory.age, inventory.balance, inventory.retail, inventory.certified, inventory.stocktype, 
inventory.wholesale, inventory.status , sales.sale_status FROM inventory LEFT JOIN sales ON inventory.id = sales.stock_id 
WHERE inventory.id = '$id' ORDER BY inventory.id ASC";

$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $salespersonTodoArray = array();
        $incentiveRulesArray = array();
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


        $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

        // $ruleSql = "SELECT * FROM `incentive_rules` WHERE model = '$model' AND ( year = '$year' OR year = 'ALL' ) AND ( modelno = '$modelno' OR modelno = 'ALL' ) AND type = '$stockType' AND status = 1 LIMIT 1";
        $ruleSql = "SELECT * FROM `incentive_rules` WHERE ( model = '$model' OR model = 'All' ) AND 
        ( year = '$year' OR year = 'All' ) AND ( modelno = '$modelno' OR modelno = 'All' ) AND 
        (type = '$stockType' OR type = 'ALL' ) AND status = 1 AND location = '$location' AND
        `ex_modelno` NOT LIKE '%_" . $modelno . "_%' ORDER BY FIELD(model, '$model') DESC, FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC, FIELD(type, '$stockType') DESC";
        $result1 = $connect->query($ruleSql);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $incentiveRulesArray[] = array(
                    $row1['state'],
                    $row1['college'],
                    $row1['college_e'],
                    $row1['military'],
                    $row1['military_e'],
                    $row1['loyalty'],
                    $row1['loyalty_e'],
                    $row1['conquest'],
                    $row1['conquest_e'],
                    $row1['misc1'],
                    $row1['misc1_e'],
                    $row1['misc2'],
                    $row1['misc2_e'],
                    $row1['lease_loyalty'],
                    $row1['lease_loyalty_e'],
                );
                // $college =  $row1['college'];
                // $college_e =  $row1['college_e'];
                // $military =  $row1['military'];
                // $military_e =  $row1['military_e'];
                // $loyalty =  $row1['loyalty'];
                // $loyalty_e =  $row1['loyalty_e'];
                // $conquest =  $row1['conquest'];
                // $conquest_e =  $row1['conquest_e'];
                // $misc1 =  $row1['misc1'];
                // $misc1_e =  $row1['misc1_e'];
                // $misc2 =  $row1['misc2'];
                // $misc2_e =  $row1['misc2_e'];
                // $lease_loyalty =  $row1['lease_loyalty'];
                // $lease_loyalty_e =  $row1['lease_loyalty_e'];
            }
        }

        // $ruleSql = "SELECT * FROM `salesperson_rules` WHERE model = '$model' AND ( year = '$year' OR year = 'ALL' ) AND ( modelno = '$modelno' OR modelno = 'ALL' ) AND type = '$stockType' AND status = 1 LIMIT 1";
        $ruleSql = "SELECT * FROM `salesperson_rules` WHERE ( model = '$model' OR model = 'All' ) AND 
        ( year = '$year' OR year = 'All' ) AND ( modelno = '$modelno' OR modelno = 'All' ) AND 
        (type = '$stockType' OR type = 'ALL' )  AND status = 1 AND location = '$location' AND
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


        $cars_to_dealers_pending = "false";
        $lot_wizards_bills_notpaid = "false";
        $statusSql2 = "SELECT 
        (SELECT COUNT(inv_id) FROM `car_to_dealers` WHERE car_to_dealers.status = 1 AND inv_id = '$stock_id' AND work_needed != '' AND date_returned = '' AND location = '$location') as carstodealers , 
        (SELECT COUNT(inv_id) FROM `inspections` WHERE inspections.status = 1 AND inspections.repair_returned != '' AND inspections.repair_paid = '' AND inspections.inv_id = '$stock_id') as lotwizardsBills";
        $rslt2 = $connect->query($statusSql2);
        if ($rslt2->num_rows > 0) {
            $rowStatus = $rslt2->fetch_assoc();
            $cars_to_dealers_pending = $rowStatus['carstodealers'] > 0 ? "true" : "false";
            $lot_wizards_bills_notpaid = $rowStatus['lotwizardsBills'] > 0 ? "true" : "false";
        }


        $output['data'] = array(
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
            $saleStatusArray, // sale Status // 16
            'incentivesArray' => $incentiveRulesArray,  // 17
            $row['status'], // 18
            $cars_to_dealers_pending,  // 19
            $lot_wizards_bills_notpaid,  // 20
            'spTodoArray' => $salespersonTodoArray, // 21
        );
        // $output['data'] = array(
        //     $row['id'],  // id //0
        //     $row['stockno'],  //stockno //1
        //     $row['year'],  // year //2
        //     $row['make'],  // make  //3
        //     $row['model'],  // model //4
        //     $row['modelno'], // modelno //5
        //     $row['color'], // color //6
        //     $row['lot'], // lot //7
        //     $row['vin'], // vin //8
        //     $row['mileage'], // mileage //9
        //     $row['age'], // age // 10
        //     $row['balance'], // balance // 11
        //     $row['retail'], // retail // 12
        //     $row['certified'], // certified // 13
        //     $row['stocktype'], // stocktype // 14
        //     $row['wholesale'], // wholesale // 15
        //     $saleStatusArray, // sale Status // 16
        //     $college,
        //     $college_e,
        //     $military,
        //     $military_e,
        //     $loyalty,
        //     $loyalty_e,
        //     $conquest,
        //     $conquest_e,
        //     $misc1,
        //     $misc1_e,
        //     $misc2,
        //     $misc2_e,
        //     $lease_loyalty,
        //     $lease_loyalty_e,
        //     $row['status'],
        //     $cars_to_dealers_pending,
        //     $lot_wizards_bills_notpaid,
        //     'spTodoArray' => $salespersonTodoArray,
        // );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
