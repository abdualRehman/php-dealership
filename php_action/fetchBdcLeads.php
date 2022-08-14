<?php

require_once 'db/core.php';


$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}


if ($userRole != $ccsID) {
    $sql = "SELECT `id`, `date`, `ccs`, `lname`, `fname`, `entity`, `vehicle`, `sales_consultant`, `lead_status`, `lead_type`, `source`, `notes`, `verified`, `verified_by` FROM `bdc_lead` WHERE status = 1";
} else {
    $uid = $_SESSION['userId'];
    $sql = "SELECT `id`, `date`, `ccs`, `lname`, `fname`, `entity`, `vehicle`, `sales_consultant`, `lead_status`, `lead_type`, `source`, `notes`, `verified`, `verified_by` FROM `bdc_lead` WHERE `status` = 1 AND `ccs` = '$uid'";
}






$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $date = $row['date'];
        $ccs = $row['ccs'];
        $lname = $row['lname'];
        $fname = $row['fname'];
        $entity = $row['entity'];
        $vehicle = $row['vehicle'];
        $sales_consultant = $row['sales_consultant'];
        $lead_status = $row['lead_status'];
        $lead_type = $row['lead_type'];
        $source = $row['source'];
        $notes = $row['notes'];
        $verified = $row['verified'];


        if (isset($ccs)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$ccs'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $ccs = $row1['username'];
            $colorCode = $row1['color'];
        } else {
            $ccs = "";
            $colorCode = '';
        }

        if (isset($sales_consultant)) {
            $sql1 = "SELECT * FROM `users` WHERE id = '$sales_consultant'";
            $result1 = $connect->query($sql1);
            $row1 = $result1->fetch_assoc();
            $sales_consultant = $row1['username'];
        } else {
            $sales_consultant = "";
        }




        $button = '
            <div class="show d-flex" >' .
            (hasAccess("bdc", "Remove") !== 'false' ? '<button class="btn btn-label-primary btn-icon mr-1" onclick="removeLead(' . $id . ')" >
                <i class="fa fa-trash"></i>
            </button>' : "") .
            '</div>
        ';



        $output['data'][] = array(
            $id,
            $date,
            $ccs,
            $lname,
            $fname,
            $entity,
            $vehicle,
            $sales_consultant,
            ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $lead_status)),
            ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $lead_type)),
            ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $source)),
            $notes,
            ucfirst(preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $verified)),
            $button,
            $colorCode,
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
