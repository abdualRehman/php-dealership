<?php

require_once 'db/core.php';


$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}


if ($userRole != $salesConsultantID) {
    $sql = "SELECT sale_incentives.incentive_id , users.username as sale_consultant , sales.date , sales.fname , sales.lname , sale_incentives.college , sale_incentives.military , sale_incentives.loyalty , sale_incentives.conquest, sale_incentives.misc1 , sale_incentives.misc2 , sale_incentives.lease_loyalty , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , sales.state ,
    sale_incentives.college_date, sale_incentives.military_date , sale_incentives.loyalty_date , sale_incentives.conquest_date , sale_incentives.misc1_date , sale_incentives.misc2_date , sale_incentives.lease_loyalty_date , sale_incentives.images , sales.sale_status
    FROM `sale_incentives` INNER JOIN sales ON sale_incentives.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE sales.status = 1 AND sales.sale_status != 'cancelled' AND sale_incentives.status = 1 ORDER BY sales.sales_consultant ASC";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT sale_incentives.incentive_id , users.username as sale_consultant , sales.date , sales.fname , sales.lname , sale_incentives.college , sale_incentives.military , sale_incentives.loyalty , sale_incentives.conquest, sale_incentives.misc1 , sale_incentives.misc2 , sale_incentives.lease_loyalty , inventory.stockno ,  inventory.stocktype , inventory.year , inventory.model , inventory.make , sales.state ,
    sale_incentives.college_date, sale_incentives.military_date , sale_incentives.loyalty_date , sale_incentives.conquest_date , sale_incentives.misc1_date , sale_incentives.misc2_date , sale_incentives.lease_loyalty_date , sale_incentives.images , sales.sale_status
    FROM `sale_incentives` INNER JOIN sales ON sale_incentives.sale_id = sales.sale_id INNER JOIN users ON sales.sales_consultant = users.id INNER JOIN inventory ON sales.stock_id = inventory.id WHERE sales.status = 1 AND sales.sale_status != 'cancelled' AND sale_incentives.status = 1 AND sales.sales_consultant = '$uid' ORDER BY sales.sales_consultant ASC";
}
$result = $connect->query($sql);

$output = array('data' => array());

function fetchSalesManagerName($manId)
{
    global $connect;
    $userSql = "SELECT username FROM users WHERE id = '$manId'";
    $result = $connect->query($userSql);
    $row = $result->fetch_array();
    return $row[0];
}

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        $date = date("M-d-Y", strtotime($date));  // formating date
        $sale_consultant = $row['sale_consultant'];
        $id = $row['incentive_id'];
        $customerName = $row['fname'] . ' ' . $row['lname'];

        $stockNo = $row['stockno'];
        $vehicle = $row['stocktype'] . ' ' . $row['year'] . ' ' . $row['make'] . ' ' . $row['model'];
        $state = $row['state'];


        $college = (is_numeric($row['college'])) ? "Yes/Approved by <br />" .fetchSalesManagerName($row['college']) : $row['college'];
        $military = (is_numeric($row['military'])) ? "Yes/Approved by <br />" .fetchSalesManagerName($row['military']) : $row['military'];
        $loyalty = (is_numeric($row['loyalty'])) ? "Yes/Approved by <br />" .fetchSalesManagerName($row['loyalty']) : $row['loyalty'];
        $conquest = (is_numeric($row['conquest'])) ? "Yes/Approved by <br />" .fetchSalesManagerName($row['conquest']) : $row['conquest'];
        $misc1 = (is_numeric($row['misc1'])) ? "Yes/Approved by <br />" .fetchSalesManagerName($row['misc1']) : $row['misc1'];
        $misc2 = (is_numeric($row['misc2'])) ? "Yes/Approved by <br />" .fetchSalesManagerName($row['misc2']) : $row['misc2'];
        $lease_loyalty = (is_numeric($row['lease_loyalty'])) ? "Yes/Approved by <br />" .fetchSalesManagerName($row['lease_loyalty']) : $row['lease_loyalty'];

        $college_date = $row['college_date'];
        $military_date = $row['military_date'];
        $loyalty_date = $row['loyalty_date'];
        $conquest_date = $row['conquest_date'];
        $misc1_date = $row['misc1_date'];
        $misc2_date = $row['misc2_date'];
        $lease_loyalty_date = $row['lease_loyalty_date'];
        $images = $row['images'];
    
        
        


        $button = '
        <div class="show d-inline-flex" >
            <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#editDetails" onclick="editDetails(' . $id . ')" >
                <i class="fa fa-edit"></i>
            </button>
            <!-- <button class="btn btn-label-primary btn-icon" onclick="removeTodo(' . $id . ')" >
                <i class="fa fa-trash"></i>
            </button>    -->
        </div>';

        $output['data'][] = array(
            $date,
            $customerName,
            $stockNo,
            $vehicle,
            // $state,
            $college,
            $military,
            $loyalty,
            $conquest,
            $lease_loyalty,
            $misc1,
            $misc2,
            $college_date,  
            $military_date,
            $loyalty_date, 
            $conquest_date,
            $misc1_date, 
            $misc2_date, 
            $lease_loyalty_date,
            $images,
            // $button // 19 index
            $id, // 19 index
            $row['sale_status']
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
