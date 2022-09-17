<?php

require_once 'db/core.php';
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sql = "SELECT `id`, `stockno`, `year`, `make`, `model`, `modelno`, `color`, `lot`, `vin`, `mileage`, `age`, `balance`, `retail`, `certified`, `stocktype`, `wholesale`, `status` FROM `inventory` WHERE status = 1 AND location = '$location'";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();

    while ($row = $result->fetch_array()) {
        $id = $row[0];


        $button = '
        <div class="show" >
            <button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#showDetails" onclick="showInv(' . $id . ')" >
                <i class="fa fa-eye"></i>
            </button>
            <div class="dropdown d-inline">
                <button class="btn btn-label-primary btn-icon" data-toggle="dropdown">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">' .
            ((hasAccess("inventory", "Edit") !== 'false') ? '<a href="' . $GLOBALS['siteurl'] . '/inventory/manageInv.php?r=edit&i=' . $id . '" class="dropdown-item">
                        <div class="dropdown-icon">
                            <i class="fa fa-edit"></i>
                        </div>
                        <span class="dropdown-content">Edit</span>
                    </a>' : "") .
            ((hasAccess("inventory", "Remove") !== 'false') ? '<button class="dropdown-item" onclick="removeInv(' . $id . ')" >
                        <div class="dropdown-icon">
                            <i class="fa fa-trash"></i>
                        </div>
                        <span class="dropdown-content">Delete</span>
                    </button>' : "")
            . '</div>
            </div>
    </div>
    ';

        $certified;
        if ($row[13] == 'on') {
            $certified = "Certified";
        } else {
            $certified = "";
        }
        $wholesale;
        if ($row[15] == 'on') {
            $wholesale = "Wholesale";
        } else {
            $wholesale = "";
        }


        $output['data'][] = array(
            '',
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $row[5],
            $row[6],
            $row[7],
            $row[8],
            $row[9],
            $row[10],
            $row[11],
            $row[12],
            $certified,
            $row[14],
            $wholesale,
            $button
        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
