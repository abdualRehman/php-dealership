<?php

require_once 'db/core.php';

$id = $_POST['id'];

// $sql = "SELECT sales.date ,  inventory.stockno , sales.fname , sales.mname , sales.lname, sales.state , users.username as salesConsultant, sales.sale_status , sales.deal_notes, 
// sales.certified, inventory.stocktype , inventory.year, inventory.make , inventory.model, inventory.vin , inventory.mileage , inventory.age , inventory.lot, inventory.balance , 
// sales.gross , sales.sale_id , sales.address1 , sales.address2 , sales.city , sales.country , sales.zipcode , sales.mobile, sales.altcontact, sales.email , sales.stock_id , sales.sales_consultant , sale_incentives.* , sale_todo.*
// FROM `sales` INNER JOIN inventory ON sales.stock_id = inventory.id INNER JOIN users ON users.id = sales.sales_consultant INNER JOIN sale_incentives ON sales.sale_id = sale_incentives.sale_id INNER JOIN sale_todo ON sales.sale_id = sale_todo.sale_id
// WHERE sales.sale_id = '$id'";
$sql = "SELECT sales.date , sales.stock_id , inventory.stockno , sales.fname , sales.mname , sales.lname, sales.state , users.username as salesConsultant, sales.sale_status , sales.deal_notes, 
sales.certified, inventory.stocktype , inventory.year, inventory.make , inventory.model, inventory.vin , inventory.mileage , inventory.age , inventory.lot, inventory.balance , sales.gross , sales.sale_id , sales.address1 , sales.address2 , sales.city , sales.country , sales.zipcode , sales.mobile, sales.altcontact, sales.email , sales.cb_address1 , sales.cb_address2 , sales.cb_city , sales.cb_country , sales.cb_zipcode , sales.cb_mobile , sales.cb_altcontact , sales.cb_email, sales.reconcileDate, sales.finance_manager , sales.deal_type , sales.submitted_by, sales.stock_id , sales.sales_consultant, sales.consultant_notes , sales.thankyou_cards ,
sale_incentives.college , sale_incentives.military, sale_incentives.loyalty , sale_incentives.conquest , sale_incentives.misc1 , sale_incentives.misc2 , sale_incentives.lease_loyalty , sale_incentives.status as sale_incentives_status, 
sale_todo.vin_check , sale_todo.insurance , sale_todo.trade_title , sale_todo.registration , sale_todo.inspection , sale_todo.salesperson_status , sale_todo.paid , sale_todo.status as sale_todo_status
FROM `sales` INNER JOIN inventory ON sales.stock_id = inventory.id INNER JOIN users ON users.id = sales.sales_consultant INNER JOIN sale_incentives ON sales.sale_id = sale_incentives.sale_id INNER JOIN sale_todo ON sales.sale_id = sale_todo.sale_id
WHERE sales.sale_id = '$id'";
$result = $connect->query($sql);

$output = array();
if ($result->num_rows > 0) {
    // $row = $result->fetch_array();
    $row = $result->fetch_assoc();

    $output = $row;
    $submittedBy = $row['submitted_by'];
    $financeManager = $row['finance_manager'];

    if (isset($submittedBy)) {
        $sql1 = "SELECT * FROM `users` WHERE id = '$submittedBy'";
        $result1 = $connect->query($sql1);
        $row1 = $result1->fetch_assoc();
        $output['submittedBy'] = $row1['username'];
    } else {
        $output['submittedBy'] = "";
    }
    if (isset($financeManager)) {
        $sql2 = "SELECT * FROM `users` WHERE id = '$financeManager'";
        $result2 = $connect->query($sql2);
        $row2 = $result2->fetch_assoc();
        $output['financeManager'] = $row2['username'];
    } else {
        $output['financeManager'] = "";
    }

} // if num_rows

$connect->close();

echo json_encode($output);
