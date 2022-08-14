<?php

require_once 'db/core.php';




// $userRole;
// if ($_SESSION['userRole']) {
//     $userRole = $_SESSION['userRole'];
// }

// if ($userRole != $salesConsultantID) {
//     $sql = "SELECT a.id , a.contract_date , a.problem_date , a.customer_name, inventory.stockno ,a.vehicle , a.problem , a.notes , b.username as sales_consultant , c.username as finance_manager , a.p_status 
//     FROM `registration_problems` as a LEFT JOIN users as b ON b.id = a.sales_consultant LEFT JOIN users as c ON c.id = a.finance_manager LEFT JOIN inventory ON inventory.id = a.stock_id
//     WHERE a.status = 1";
// } else {
//     $uid = $_SESSION['userId'];

//     $sql = "SELECT a.id , a.contract_date , a.problem_date , a.customer_name, inventory.stockno ,a.vehicle , a.problem , a.notes , b.username as sales_consultant , c.username as finance_manager , a.p_status
//     FROM `registration_problems` as a LEFT JOIN users as b ON b.id = a.sales_consultant LEFT JOIN users as c ON c.id = a.finance_manager LEFT JOIN inventory ON inventory.id = a.stock_id
//     WHERE a.status = 1 AND a.sales_consultant = '$uid'";
// }

$sql = "SELECT  warrenty_cancellation.id, warrenty_cancellation.customer_name, warrenty_cancellation.warrenty, warrenty_cancellation.date_cancelled, warrenty_cancellation.refund_des, users.username as finance_manager, warrenty_cancellation.date_sold, warrenty_cancellation.paid, warrenty_cancellation.notes FROM `warrenty_cancellation` LEFT JOIN users ON warrenty_cancellation.finance_manager = users.id WHERE warrenty_cancellation.status = 1";

$result = $connect->query($sql);


$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $customer_name = $row['customer_name'];
        $warrenty = $row['warrenty'];
        $date_cancelled = $row['date_cancelled'];
        $refund_des = $row['refund_des'];
        $finance_manager = $row['finance_manager'];
        $date_sold = $row['date_sold'];
        $paid = $row['paid'];
        $notes = $row['notes'];




        $button = '
            <div class="show d-flex" >' .
            (hasAccess("warranty", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeCancellation(' . $id . ')" >
                <i class="fa fa-trash"></i>
                </button>' : "") .
            '</div>';

        $output['data'][] = array(
            $id,
            $customer_name,
            $warrenty,
            $date_cancelled,
            $refund_des,
            $finance_manager,
            $date_sold,
            $paid,
            $notes,
            $button
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
