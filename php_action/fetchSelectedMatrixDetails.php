<?php

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT a.year , a.model , a.model_code , a.trim , a.net, a.hb, a.`invoice` , a.`m.s.r.p` , a.bdc , 
b.`f_24-36` , b.`f_37-48` , b.`f_49-60` , b.`f_61-72`, b.`f_659_610_24-36` ,b.`f_659_610_37-60` , b.`f_659_610_61-72` , b.f_expire , 
b.lease_660 , b.lease_659_610 , b.lease_one_pay_660, b.lease_one_pay_659_610 , b.lease_expire , 
c.10_36_48, c.10_24_33 , c.12_36_48 , c.12_24_33 , c.24 , c.27, c.30 , c.33 , c.36 , c.39 , c.42 , c.45 , c.48 , c.51 , c.54 , c.57 , c.60 ,
d.expire_in , d.dealer , d.other , d.lease , c.expire_in as residual_expire FROM `manufature_price` as a LEFT JOIN rate_rule as b ON a.rate_rule_id = b.id  LEFT JOIN lease_rule as c ON a.lease_rule_id = c.id
LEFT JOIN cash_incentive_rules d ON a.cash_incentive_rule_id = d.id WHERE a.id = '$id'";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} // if num_rows

$connect->close();

echo json_encode($row);
