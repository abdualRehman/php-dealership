<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
function reformatDate($date, $from_format = 'm-d-Y H:i', $to_format = 'Y-m-d H:i') {
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux,$to_format);
}
if ($_POST) {
    
    $saleDate = mysqli_real_escape_string($connect, $_POST['saleDate']);
    // $saleDate = date('Y-m-d H:i:s',strtotime($saleDate));
    $saleDate = reformatDate($saleDate);


    $status = mysqli_real_escape_string($connect, $_POST['status']);  // sale status
    $stockId = mysqli_real_escape_string($connect, $_POST['stockId']);
    $salesConsultant = mysqli_real_escape_string($connect, $_POST['salesPerson']);
    $dealNote = mysqli_real_escape_string($connect, $_POST['dealNote']);
    $iscertified = mysqli_real_escape_string($connect, $_POST['iscertified']);
    
    $gross = mysqli_real_escape_string($connect, $_POST['profit']);


    // echo $saleDate . "<br />";
    // echo $status . '<br />';
    // echo $stockId . '<br />';
    // echo $salesConsultant . '<br />';
    // echo $dealNote . '<br />';
    // echo $iscertified . '<br />';



    // customer details
    $fname = mysqli_real_escape_string($connect, $_POST['fname']);
    $mname = mysqli_real_escape_string($connect, $_POST['mname']);
    $lname = mysqli_real_escape_string($connect, $_POST['lname']);
    $state = mysqli_real_escape_string($connect, $_POST['state']);

    $address1 = mysqli_real_escape_string($connect, $_POST['address1']);
    $address2 = mysqli_real_escape_string($connect, $_POST['address2']);
    $city = mysqli_real_escape_string($connect, $_POST['city']);
    $country = mysqli_real_escape_string($connect, $_POST['country']);
    $zipCode = mysqli_real_escape_string($connect, $_POST['zipCode']);
    $mobile = mysqli_real_escape_string($connect, $_POST['mobile']);
    $altContact = mysqli_real_escape_string($connect, $_POST['altContact']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);

    // echo $fname . "<br />";
    // echo $mname . '<br />';
    // echo $lname . '<br />';
    // echo $state . '<br />';
    // echo $address1 . "<br />";
    // echo $address2 . '<br />';
    // echo $country . '<br />';
    // echo $zipCode . '<br />';
    // echo $mobile . '<br />';
    // echo $altContact . '<br />';
    // echo $email . '<br />';


    // incentives
    $college = mysqli_real_escape_string($connect, $_POST['college']);
    $military = mysqli_real_escape_string($connect, $_POST['military']);
    $loyalty = mysqli_real_escape_string($connect, $_POST['loyalty']);
    $conquest = mysqli_real_escape_string($connect, $_POST['conquest']);
    $misc1 = mysqli_real_escape_string($connect, $_POST['misc1']);
    $misc2 = mysqli_real_escape_string($connect, $_POST['misc2']);
    $misc3 = mysqli_real_escape_string($connect, $_POST['misc3']);


    // echo $college . "<br />";
    // echo $military . '<br />';
    // echo $loyalty . '<br />';
    // echo $conquest . '<br />';
    // echo $misc1 . '<br />';
    // echo $misc2 . '<br />';
    // echo $misc3 . '<br />';

    // sales person Todo
    $vincheck = mysqli_real_escape_string($connect, $_POST['vincheck']);
    $insurance = mysqli_real_escape_string($connect, $_POST['insurance']);
    $tradeTitle = mysqli_real_escape_string($connect, $_POST['tradeTitle']);
    $registration = mysqli_real_escape_string($connect, $_POST['registration']);
    $inspection = mysqli_real_escape_string($connect, $_POST['inspection']);
    $salePStatus = mysqli_real_escape_string($connect, $_POST['salePStatus']);
    $paid = mysqli_real_escape_string($connect, $_POST['paid']);

    // echo $vincheck . "<br />";
    // echo $insurance . '<br />';
    // echo $tradeTitle . '<br />';
    // echo $registration . '<br />';
    // echo $inspection . '<br />';
    // echo $salePStatus . '<br />';
    // echo $paid . '<br />';




    $sql = "INSERT INTO `sales` ( 
        `date`, 
        `stock_id`, 
        `sales_consultant`, 
        `deal_notes`, 
        `certified`, 
        `gross`, 
        `fname`, 
        `mname`, 
        `lname`, 
        `state`, 
        `address1`, 
        `address2`, 
        `city`, 
        `country`, 
        `zipcode`, 
        `mobile`, 
        `altcontact`, 
        `email`, 
        `sale_status`, 
        `status`) VALUES (
            '$saleDate',
            '$stockId',
            '$salesConsultant',
            '$dealNote',
            '$iscertified',
            '$gross',
            '$fname',
            '$mname',
            '$lname',
            '$state',
            '$address1',
            '$address2',
            '$city',
            '$country',
            '$zipCode',
            '$mobile',
            '$altContact',
            '$email',
            '$status', 1 )";
    
    $sale_id ="";
    if ($connect->query($sql) === true) {
        $sale_id = $connect->insert_id;
    }

    if($sale_id){
        $insentiveSql = "INSERT INTO `sale_incentives`( 
            `sale_id`, 
            `college`, 
            `military`, 
            `loyalty`, 
            `conquest`, 
            `misc1`, 
            `misc2`, 
            `misc3`, 
            `status`) VALUES (
                '$sale_id',
                '$college',
                '$military',
                '$loyalty',
                '$conquest',
                '$misc1',
                '$misc2',
                '$misc3', 1 )";
        
        $saleTodoSql = "INSERT INTO `sale_todo`( 
            `sale_id`, 
            `vin_check`, 
            `insurance`, 
            `trade_title`, 
            `registration`, 
            `inspection`, 
            `salesperson_status`, 
            `paid`, 
            `status`
            ) VALUES (
                '$sale_id',
                '$vincheck',
                '$insurance',
                '$tradeTitle',
                '$registration',
                '$inspection',
                '$salePStatus',
                '$paid', 1 )";
        

        if($connect->query($insentiveSql) === true && $connect->query($saleTodoSql) === true ){
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        }else{
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }


    






    $connect->close();
    echo json_encode($valid);

} // /if $_POST
