<?php

require_once 'db/core.php';


// $userRole;
// if ($_SESSION['userRole']) {
//     $userRole = $_SESSION['userRole'];
// }
// if ($userRole != $salesConsultantID) {
//     $sql = "SELECT sale_todo.sale_todo_id , users.username as sale_consultant , sales.date , sales.fname , sales.lname , sale_todo.vin_check , sale_todo.insurance , sale_todo.trade_title , sale_todo.registration , sale_todo.inspection , sale_todo.salesperson_status , sale_todo.paid , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , sales.state
//     FROM `sale_todo` INNER JOIN sales ON sale_todo.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE sales.status = 1 AND sale_todo.status = 1 ORDER BY sales.sales_consultant ASC";
// } else {
//     $uid = $_SESSION['userId'];
//     $sql = "SELECT sale_todo.sale_todo_id , users.username as sale_consultant , sales.date , sales.fname , sales.lname , sale_todo.vin_check , sale_todo.insurance , sale_todo.trade_title , sale_todo.registration , sale_todo.inspection , sale_todo.salesperson_status , sale_todo.paid , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , sales.state
//     FROM `sale_todo` INNER JOIN sales ON sale_todo.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE sales.status = 1 AND sale_todo.status = 1 AND sales.sales_consultant = '$uid' ORDER BY sales.sales_consultant ASC";
// }

$sql = "SELECT sales.* , inventory.stockno, inventory.vin , inventory.year , inventory.make , inventory.model , inventory.stocktype , inventory.certified , rdr.delivered, rdr.entered, rdr.approved, rdr.rdr_notes 
FROM sales LEFT JOIN inventory ON sales.stock_id = inventory.id LEFT JOIN rdr ON sales.sale_id = rdr.sale_id WHERE sales.status = 1 AND sales.sale_status != 'cancelled'";

$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $id = $row['sale_id'];

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
        
        
        $delivered = $row['delivered'];
        $entered = $row['entered'];
        $approved = $row['approved'];
        $rdr_notes = $row['rdr_notes'];

        



        


       

        $output['data'][] = array(
            $id,
            $status,
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

$connect->close();

echo json_encode($output);
