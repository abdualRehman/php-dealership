<?php

require_once 'db/core.php';


$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}

$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';

if ($userRole != $salesConsultantID) {
    $sql = "SELECT sale_todo.sale_todo_id , users.username as sale_consultant , sales.date , sales.fname , sales.lname , sale_todo.vin_check , sale_todo.insurance , sale_todo.trade_title , sale_todo.registration , sale_todo.inspection , sale_todo.salesperson_status , sale_todo.paid , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , sales.state
    FROM `sale_todo` INNER JOIN sales ON sale_todo.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE sales.status = 1 AND sale_todo.status = 1 AND sales.location = '$location' ORDER BY sales.sales_consultant ASC";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT sale_todo.sale_todo_id , users.username as sale_consultant , sales.date , sales.fname , sales.lname , sale_todo.vin_check , sale_todo.insurance , sale_todo.trade_title , sale_todo.registration , sale_todo.inspection , sale_todo.salesperson_status , sale_todo.paid , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , sales.state
    FROM `sale_todo` INNER JOIN sales ON sale_todo.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE sales.status = 1 AND sale_todo.status = 1 AND sales.sales_consultant = '$uid' AND sales.location = '$location' ORDER BY sales.sales_consultant ASC";
}

$result = $connect->query($sql);

$output = array('data' => array());


if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        // $date = date("M-d-Y", strtotime($date));  // formating date
        $date = date("m-d-Y", strtotime($date));  // formating date
        $sale_consultant = $row['sale_consultant'];
        $id = $row['sale_todo_id'];
        $customerName = $row['fname'] . ' ' . $row['lname'];

        $stockNo = $row['stockno'];
        $vehicle = $row['stocktype'].' '.$row['year']. ' ' .$row['make'] . ' '.$row['model'];
        $state = $row['state'];


        $vin_check = $row['vin_check'];
        $insurance = $row['insurance'];
        $trade_title = $row['trade_title'];
        $registration = $row['registration'];
        $inspection = $row['inspection'];
        $salesperson_status = $row['salesperson_status'];
        $paid = $row['paid'];
        // $vin_check = preg_split('/(?=[A-Z])/',  $row['vin_check'] );
        // $vin_check = implode(' ', $vin_check);

        // $insurance = preg_split('/(?=[A-Z])/',  $row['insurance'] );
        // $insurance = implode(' ', $insurance);

        // $trade_title = preg_split('/(?=[A-Z])/',  $row['trade_title'] );
        // $trade_title = implode(' ', $trade_title);

        // $registration = preg_split('/(?=[A-Z])/',  $row['registration'] );
        // $registration = implode(' ', $registration);

        // $inspection = preg_split('/(?=[A-Z])/',  $row['inspection'] );
        // $inspection = implode(' ', $inspection);

        // $salesperson_status = preg_split('/(?=[A-Z])/',  $row['salesperson_status'] );
        // $salesperson_status = implode(' ', $salesperson_status);

        // $paid = preg_split('/(?=[A-Z])/',  $row['paid'] );
        // $paid = implode(' ', $paid);

        
        $button = '
        <div class="show d-inline-flex" >
            <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#editDetails" onclick="editDetails(' . $id . ')" >
                <i class="fa fa-edit"></i>
            </button>
        </div>';

        $output['data'][] = array(
            $date,
            $customerName,
            $stockNo,
            $vehicle,
            $state,
            $vin_check,
            $insurance,
            $trade_title,
            $registration,
            $inspection,
            $salesperson_status,
            $paid,
            $id,
            $sale_consultant
            // $button,

        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
