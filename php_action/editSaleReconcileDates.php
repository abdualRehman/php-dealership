<?php

require_once 'db/core.php';
$valid['success'] = array('success' => false, 'messages' => array());

function reformatDateOnly($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {

    // $reconcileDate = $_POST['reconcileDate'];
    $reconcileDate = "";
    if (isset($_POST['reconcileDate']) && $_POST['reconcileDate'] != "") {
        $reconcileDate = mysqli_real_escape_string($connect, $_POST['reconcileDate']);
        $reconcileDate = reformatDateOnly($reconcileDate);
    }

    $sql = "UPDATE sales LEFT JOIN inventory ON sales.stock_id = inventory.id  
    SET sales.reconcileDate = '$reconcileDate' 
    WHERE sales.status = 1 AND sales.location = '1' AND ( inventory.stocktype = 'NEW' OR inventory.stocktype = 'USED' ) and (sales.sale_status='pending')";

    if ($connect->query($sql) === TRUE ) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Removed";
    } else {
        $valid['success'] = false;
        $valid['messages'] = $connect->error;
        $valid['messages'] = mysqli_error($connect);
    }
    $connect->close();
    echo json_encode($valid);
}
