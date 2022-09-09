<?php

require_once './db/core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'id' => '');
// print_r($valid);
function reformatDate($date, $from_format = 'm-d-Y H:i', $to_format = 'Y-m-d H:i')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}
function reformatDateOnly($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}
if ($_POST) {

    $sale_id = $_POST['saleId'];

    $saleDate = mysqli_real_escape_string($connect, $_POST['saleDate']);
    // $saleDate = date('Y-m-d H:i:s',strtotime($saleDate));
    $saleDate = reformatDate($saleDate);

    $reconcileDate = "";
    if (isset($_POST['reconcileDate']) && $_POST['reconcileDate'] != "") {
        $reconcileDate = mysqli_real_escape_string($connect, $_POST['reconcileDate']);
        $reconcileDate = reformatDateOnly($reconcileDate);
    }

    $status = mysqli_real_escape_string($connect, $_POST['status']);  // sale status
    $stockId = mysqli_real_escape_string($connect, $_POST['stockId']);
    $salesConsultant = mysqli_real_escape_string($connect, $_POST['salesPerson']);
    // $financeManager = mysqli_real_escape_string($connect, $_POST['financeManager']);    
    $financeManager = (isset($_POST['financeManager'])) ? mysqli_real_escape_string($connect, $_POST['financeManager']) : "";
    $dealType = (isset($_POST['dealType'])) ? mysqli_real_escape_string($connect, $_POST['dealType']) : "";  // sale dealType
    $dealNote = mysqli_real_escape_string($connect, $_POST['dealNote']);
    $iscertified = mysqli_real_escape_string($connect, $_POST['iscertified']);

    $gross = mysqli_real_escape_string($connect, $_POST['profit']);




    // echo $sale_id . "<br />";
    // echo $saleDate . "<br />";
    // echo $status . '<br />';
    // echo $stockId . '<br />';
    // echo $salesConsultant . '<br />';
    // echo $dealNote . '<br />';
    // echo $iscertified . '<br />';
    // echo $gross . '<br />';



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

    $cbfname = mysqli_real_escape_string($connect, $_POST['cbfname']);
    $cbmname = mysqli_real_escape_string($connect, $_POST['cbmname']);
    $cblname = mysqli_real_escape_string($connect, $_POST['cblname']);
    $cbstate = (isset($_POST['cbstate'])) ? mysqli_real_escape_string($connect, $_POST['cbstate']) : "0";

    $cbAddress1 = mysqli_real_escape_string($connect, $_POST['cbAddress1']);
    $cbAddress2 = mysqli_real_escape_string($connect, $_POST['cbAddress2']);
    $cbCity = mysqli_real_escape_string($connect, $_POST['cbCity']);
    $cbCountry = mysqli_real_escape_string($connect, $_POST['cbCountry']);
    $cbZipCode = mysqli_real_escape_string($connect, $_POST['cbZipCode']);
    $cbMobile = mysqli_real_escape_string($connect, $_POST['cbMobile']);
    $cbAltContact = mysqli_real_escape_string($connect, $_POST['cbAltContact']);
    $cbEmail = mysqli_real_escape_string($connect, $_POST['cbEmail']);

    $consultantNote = isset($_POST['consultantNote']) ? mysqli_real_escape_string($connect, $_POST['consultantNote']) : "";
    $thankyouCard = isset($_POST['thankyouCard']) ? mysqli_real_escape_string($connect, $_POST['thankyouCard']) : '';

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
    $college = (isset($_POST['college'])) ? mysqli_real_escape_string($connect, $_POST['college']) : "No";
    $military = (isset($_POST['military'])) ? mysqli_real_escape_string($connect, $_POST['military']) : "No";
    $loyalty = (isset($_POST['loyalty'])) ?  mysqli_real_escape_string($connect, $_POST['loyalty']) : "No";
    $conquest = (isset($_POST['conquest'])) ?  mysqli_real_escape_string($connect, $_POST['conquest']) : "No";
    $misc1 = (isset($_POST['misc1'])) ?  mysqli_real_escape_string($connect, $_POST['misc1']) : "No";
    $misc2 = (isset($_POST['misc2'])) ?  mysqli_real_escape_string($connect, $_POST['misc2']) : "No";
    $leaseLoyalty = (isset($_POST['leaseLoyalty'])) ?  mysqli_real_escape_string($connect, $_POST['leaseLoyalty']) : "No";


    // echo $college . "<br />";
    // echo $military . '<br />';
    // echo $loyalty . '<br />';
    // echo $conquest . '<br />';
    // echo $misc1 . '<br />';
    // echo $misc2 . '<br />';
    // echo $leaseLoyalty . '<br />';

    // sales person Todo
    $vincheck = (isset($_POST['vincheck'])) ? mysqli_real_escape_string($connect, $_POST['vincheck']) : "checkTitle";
    $insurance = (isset($_POST['insurance'])) ? mysqli_real_escape_string($connect, $_POST['insurance']) : "need";
    $tradeTitle = (isset($_POST['tradeTitle'])) ? mysqli_real_escape_string($connect, $_POST['tradeTitle']) : "need";
    $registration = (isset($_POST['registration'])) ? mysqli_real_escape_string($connect, $_POST['registration']) : "pending";
    $inspection = (isset($_POST['inspection'])) ? mysqli_real_escape_string($connect, $_POST['inspection']) : "need";
    $salePStatus = (isset($_POST['salePStatus'])) ? mysqli_real_escape_string($connect, $_POST['salePStatus']) : "dealWritten";
    $paid = (isset($_POST['paid'])) ? mysqli_real_escape_string($connect, $_POST['paid']) : "no";

    // echo $vincheck . "<br />";
    // echo $insurance . '<br />';
    // echo $tradeTitle . '<br />';
    // echo $registration . '<br />';
    // echo $inspection . '<br />';
    // echo $salePStatus . '<br />';
    // echo $paid . '<br />';


    if ($sale_id) {


        $sql = "UPDATE `sales` SET 
        `date`='$saleDate',
        `stock_id`='$stockId',
        `sales_consultant`='$salesConsultant',
        `deal_notes`='$dealNote',
        `certified`='$iscertified',
        `gross`='$gross',
        `fname`='$fname',
        `mname`='$mname',
        `lname`='$lname',
        `state`='$state',
        `address1`='$address1',
        `address2`='$address2',
        `city`='$city',
        `country`='$country',
        `zipcode`='$zipCode',
        `mobile`='$mobile',
        `altcontact`='$altContact',
        `email`='$email',
        `cb_fname`='$cbfname',
        `cb_mname`='$cbmname',
        `cb_lname`='$cblname',
        `cb_state`='$cbstate',
        `cb_address1`='$cbAddress1',
        `cb_address2`='$cbAddress2',
        `cb_city`='$cbCity',
        `cb_country`='$cbCountry',
        `cb_zipcode`='$cbZipCode',
        `cb_mobile`='$cbMobile',
        `cb_altcontact`='$cbAltContact',
        `cb_email`='$cbEmail',

        `reconcileDate` = '$reconcileDate', 
        `finance_manager`= '$financeManager' , 
        `deal_type` = '$dealType',
        
        `sale_status`='$status',
        `consultant_notes`='$consultantNote',
        `thankyou_cards`='$thankyouCard'
        WHERE sale_id = '$sale_id' ";


        // `cb_fname`=" . (isset($_POST['cbfname']) ? "'$cbfname'" : "`cb_fname`") . ",
        // `cb_mname`=" . (isset($_POST['cbmname']) ? "'$cbmname'" : "`cb_mname`") . ",
        // `cb_lname`=" . (isset($_POST['cblname']) ? "'$cblname'" : "`cb_lname`") . ",
        // `cb_state`=" . (isset($_POST['cbstate']) ? "'$cbstate'" : "`cb_state`") . ",
        // `cb_address1`=" . (isset($_POST['cbAddress1']) ? "'$cbAddress1'" : "`cb_address1`") . ",
        // `cb_address2`=" . (isset($_POST['cbAddress2']) ? "'$cbAddress2'" : "`cb_address2`") . ",
        // `cb_city`=" . (isset($_POST['cbCity']) ? "'$cbCity'" : "`cb_city`") . ",
        // `cb_country`=" . (isset($_POST['cbCountry']) ? "'$cbCountry'" : "`cb_country`") . ",
        // `cb_zipcode`=" . (isset($_POST['cbZipCode']) ? "'$cbZipCode'" : "`cb_zipcode`") . ",
        // `cb_mobile`=" . (isset($_POST['cbMobile']) ? "'$cbMobile'" : "`cb_mobile`") . ",
        // `cb_altcontact`=" . (isset($_POST['cbAltContact']) ? "'$cbAltContact'" : "`cb_altcontact`") . ",
        // `cb_email`=" . (isset($_POST['cbEmail']) ? "'$cbEmail'" : "`cb_email`") . ",




        $insentiveSql = "UPDATE `sale_incentives` SET 
        `college`=" . (isset($_POST['college']) ? "'$college'" : "`college`") . ",
        `military`=" . (isset($_POST['military']) ? "'$military'" : "`military`") . ",
        `loyalty`=" . (isset($_POST['loyalty']) ? "'$loyalty'" : "`loyalty`") . ",
        `conquest`=" . (isset($_POST['conquest']) ? "'$conquest'" : "`conquest`") . ",
        `misc1`=" . (isset($_POST['misc1']) ? "'$misc1'" : "`misc1`") . ",
        `misc2`=" . (isset($_POST['misc2']) ? "'$misc2'" : "`misc2`") . ",
        `lease_loyalty`=" . (isset($_POST['leaseLoyalty']) ? "'$leaseLoyalty'" : "`lease_loyalty`") . "
        WHERE sale_id = '$sale_id' ";


        $saleTodoSql = "UPDATE `sale_todo` SET 
        `vin_check`=" . (isset($_POST['vincheck']) ? "'$vincheck'" : "`vin_check`") . ",
        `insurance`=" . (isset($_POST['insurance']) ? "'$insurance'" : "`insurance`") . ",
        `trade_title`=" . (isset($_POST['tradeTitle']) ? "'$tradeTitle'" : "`trade_title`") . ",
        `registration`=" . (isset($_POST['registration']) ? "'$registration'" : "`registration`") . ",
        `inspection`=" . (isset($_POST['inspection']) ? "'$inspection'" : "`inspection`") . ",
        `salesperson_status`=" . (isset($_POST['salePStatus']) ? "'$salePStatus'" : "`salesperson_status`") . ",
        `paid`=" . (isset($_POST['paid']) ? "'$paid'" : "`paid`") . "
        WHERE sale_id = '$sale_id' ";


        if ($connect->query($sql) === true && $connect->query($insentiveSql) === true && $connect->query($saleTodoSql) === true) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = $connect->error;
            $valid['messages'] = mysqli_error($connect);
        }
    }









    $connect->close();
    echo json_encode($valid);
} // /if $_POST
