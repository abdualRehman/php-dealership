<?php 	

require_once 'db/core.php';

$id = $_POST['id'];

$sql = "SELECT bdc_lead.id,  bdc_lead.date , users.username as ccs , bdc_lead.lname, bdc_lead.fname, bdc_lead.entity, bdc_lead.vehicle, bdc_lead.sales_consultant, bdc_lead.lead_status, bdc_lead.lead_type, bdc_lead.source, bdc_lead.notes, bdc_lead.verified, bdc_lead.verified_by, bdc_lead.status FROM bdc_lead LEFT JOIN users ON bdc_lead.ccs = users.id WHERE bdc_lead.id = '$id'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);
