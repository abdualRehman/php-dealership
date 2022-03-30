<?php

require_once 'db/core.php';

// $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
// inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id
// FROM `sales` INNER JOIN inventory ON sales.stock_id = inventory.id INNER JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1";

$sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.lname , users.username, sales.sale_status , sales.deal_notes , 
inventory.year, inventory.make , inventory.model , sales.gross , sales.sale_id , inventory.lot , inventory.certified, inventory.balance , inventory.status
FROM `sales` INNER JOIN inventory ON sales.stock_id = inventory.id INNER JOIN users ON users.id = sales.sales_consultant WHERE sales.status = 1";
$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    while ($row = $result->fetch_array()) {
        
        $invStatus = $row[15]; 
        
        $certified = ($row[13] == 'on') ? 'Yes' : 'No';
        // $balance = ($invStatus == 1) ? $row[14] : "";
        // $lot = ($invStatus == 1) ? $row[12] : "";
        $balance = $row[14];
        $lot = $row[12];

        
        $id = $row[11];

        $date = $row[0];
        $date = date("M-d-Y", strtotime($date));  // formating date
        $vehicle = $row[7] . ' ' . $row[8]. ' ' . $row[9]; // vehicle details

        $gross = round(($row[10]) , 2);


        $button = '
        <div class="show d-inline-flex" >
            <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#showDetails" onclick="showDetails(' . $id . ')" >
                <i class="fa fa-eye"></i>
            </button>
            <a href="'.$GLOBALS['siteurl'].'/sales/soldLogs.php?r=edit&i='.$id.'" class="btn btn-label-primary btn-icon mr-1" >
                <i class="fa fa-edit"></i>
            </a>
            <button class="btn btn-label-primary btn-icon" onclick="removeSale('.$id.')" >
                <i class="fa fa-trash"></i>
            </button>    
        </div>';
            
        $output['data'][] = array(
          
            $date,
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $vehicle,
            $certified,
            $lot,
            $gross,
            $row[5],
            $row[6],
            $balance,
            $button,
           
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
