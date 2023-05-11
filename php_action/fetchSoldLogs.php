<?php

require_once 'db/core.php';

date_default_timezone_set("America/New_York");

$userRole;
if ($_SESSION['userRole']) {
    $userRole = $_SESSION['userRole'];
}
$location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
$sqlQuery = '';


## Custom Field value
$searchByDatePeriod = $_POST['searchByDatePeriod'];
$customStart = $_POST['customStart'];
$customEnd = $_POST['customEnd'];
$searchByCatgry = $_POST['searchByCatgry'];

// ## Search 
$filterQuery = " ";
$searchQuery = " ";

if ($searchByDatePeriod != '') {
    if ($searchByDatePeriod == "currentMonth") {
        // $startDate = date('m/01/Y');
        // $endDate = date('m/t/Y');
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        // not working
        // $searchQuery .= " and (CONVERT(date,date) >= '" . $startDate . "' AND CONVERT(date,date) <= '" . $endDate . "' ) ";
        // $searchQuery .= " and (CONVERT(date,date) BETWEEN '" . $startDate . "' AND '" . $endDate . "' ) ";

        // working
        $searchQuery .= " and ( CAST( IF( (sales.reconcileDate != ''), sales.reconcileDate , sales.date ) as date) BETWEEN '" . $startDate . "' AND '" . $endDate . "' ) ";
    } else if ($searchByDatePeriod == "yesterday") {
        // $yesterday = date("m/d/Y", strtotime("yesterday"));
        $yesterday = date("Y-m-d", strtotime("yesterday"));
        $searchQuery .= " and (CONVERT(date,date) = '" . $yesterday . "')";

        // $searchQuery .= " and ((IF((sales.reconcileDate != ''), sales.reconcileDate , sales.date )) = '" . $yesterday . "')";
    } else if ($searchByDatePeriod == 'today') {
        $today = date("Y-m-d");
        $searchQuery .= " and (CONVERT(date,date) = '" . $today . "')";

        // $searchQuery .= " and ((IF((sales.reconcileDate != ''), sales.reconcileDate , sales.date )) = '" . $today . "')";
    } else if ($searchByDatePeriod == 'all') {
        $searchQuery .= " and (date = date)";
    }
}


if ($customStart != '' && $customEnd != '') {
    // $searchQuery .= " and (date >= '" . $customStart . "' AND date <= '" . $customEnd . "' ) ";
    // $searchQuery .= " and (CONVERT(date,date) BETWEEN '" . $customStart . "' AND '" . $customEnd . "' ) ";
    $searchQuery .= " and ( CAST( IF( (sales.reconcileDate != ''), sales.reconcileDate , sales.date ) as date ) BETWEEN '" . $customStart . "' AND '" . $customEnd . "' ) ";
} else {
    $searchQuery .= " and (date = date)";
}

if ($searchByCatgry != '') {
    $searchQuery .= " and (sale_status='" . $searchByCatgry . "') ";
}


// consultantF

if (isset($_POST['consultantF']) && count($_POST['consultantF']) != 0) {
    $array = $_POST['consultantF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " users.username LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}
if (isset($_POST['stockF']) && count($_POST['stockF']) != 0) {
    $array = $_POST['stockF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " inventory.stockno LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}

if (isset($_POST['vehicleF']) && count($_POST['vehicleF']) != 0) {
    $array = $_POST['vehicleF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " CONCAT( inventory.stocktype ,' ', inventory.year ,' ', inventory.make ,' ', inventory.model ) LIKE '%$value%' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}
if (isset($_POST['typeF']) && count($_POST['typeF']) != 0) {
    $array = $_POST['typeF'];
    $filterQuery .= " AND ( ";
    foreach ($array as $value) {
        if ($value != '') {
            $filterQuery .= " inventory.stocktype = '$value' ";
            if (next($array) == true) $filterQuery .= " OR ";
        }
    }
    $filterQuery .= " ) ";
}









if ($userRole != $salesConsultantID) {

    $sqlQuery = "SELECT CAST( IF((sales.reconcileDate != ''), sales.reconcileDate , sales.date ) AS date ) as date , sales.fname , sales.lname , users.username, inventory.stockno , 
    CONCAT( inventory.stocktype ,' ', inventory.year ,' ', inventory.make ,' ', inventory.model ) as vehicle , 
    inventory.age , 
    sales.certified ,
    inventory.lot , 
    CAST(sales.gross AS INT) as gross, 
    sales.sale_status , 
    sales.deal_notes ,
    inventory.balance , 
    sales.consultant_notes , 
    '' as sales_consultant_status, 
    '' as button, 
    inventory.stocktype, 
    '' as countRow , 
    sales.sale_id , 
    inventory.vin as vin ,
    sales.thankyou_cards , sales.date as sold_date , 
    '' as codp_warn , 
    '' as lwbn_warn , 
    inventory.status as invStatus , sales.stock_id, sales.reconcileDate as reconcileDateOnly
    FROM sales, inventory, users WHERE sales.stock_id = inventory.id AND users.id = sales.sales_consultant AND sales.status = 1 AND sales.location = '$location'  " . $filterQuery;
} else {
    $uid = $_SESSION['userId'];

    $sqlQuery = "SELECT CAST( IF((sales.reconcileDate != ''), sales.reconcileDate , sales.date ) as date ) as date , sales.fname , sales.lname , users.username, inventory.stockno , 
    CONCAT( inventory.stocktype ,' ', inventory.year ,' ', inventory.make ,' ', inventory.model ) as vehicle , 
    inventory.age , sales.certified ,inventory.lot , CAST(sales.gross AS INT) as gross , sales.sale_status , sales.deal_notes ,inventory.balance , 
    sales.consultant_notes , '' as sales_consultant_status, '' as button, inventory.stocktype, '' as countRow , sales.sale_id , 
    inventory.vin as vin ,
    sales.thankyou_cards , sales.date as sold_date , 
    '' as codp_warn , 
    '' as lwbn_warn , 
    inventory.status as invStatus , sales.stock_id , sales.reconcileDate as reconcileDateOnly
    FROM sales, inventory, users WHERE sales.stock_id = inventory.id AND users.id = sales.sales_consultant AND sales.status = 1 AND sales.sales_consultant = '$uid' AND sales.location = '$location' " . $filterQuery;
}




// echo $sqlQuery . '<br />';














$table = <<<EOT
(
    {$sqlQuery} {$searchQuery}
) temp
EOT;

// echo $table;
// echo "<hr />";


$primaryKey = 'sale_id';

$columns = array(
    array(
        'db' => 'date', 'dt' => 0,
        'formatter' => function ($d, $row) {
            $date =  ($d != '') ? date("m-d-Y", strtotime($d)) : '';
            return $date;
        }
    ),
    array('db' => 'fname',  'dt' => 1),
    array('db' => 'lname',   'dt' => 2),
    array('db' => 'username',   'dt' => 3),
    array('db' => 'stockno', 'dt' => 4),
    array('db' => 'vehicle',   'dt' => 5),
    array('db' => 'age',   'dt' => 6),
    array(
        'db' => 'certified',   'dt' => 7,
        'formatter' => function ($d, $row) {
            return $d == 'on' ? 'Yes' : 'No';
        }
    ),
    array(
        'db' => 'lot',   'dt' => 8,
        'formatter' => function ($d, $row) {
            return $row[24] == 1 ? $d : '';
        }
    ),
    array(
        'db' => 'gross',   'dt' => 9,
        'formatter' => function ($d, $row) {
            return  (int)round(($d), 2);
        }
    ),
    array('db' => 'sale_status',   'dt' => 10),
    array('db' => 'deal_notes',   'dt' => 11),
    array(
        'db' => 'balance',   'dt' => 12,
        'formatter' => function ($d, $row) {
            return $row[24] == 1 ? $d : '';
        }
    ),
    array('db' => 'consultant_notes',   'dt' => 13),
    array(
        'db' => 'sales_consultant_status',   'dt' => 14,
        'formatter' => function ($d, $row) {
            global $connect;
            $id = $row[18];
            $sales_consultant_status = '';
            $sql2 = "SELECT salesperson_status FROM `sale_todo` WHERE sale_id = '$id'";
            $result2 = $connect->query($sql2);
            if ($result2->num_rows > 0) {
                $row2 = $result2->fetch_array();
                $sales_consultant_status = $row2[0];
            }
            return $sales_consultant_status;
        }
    ),
    array(
        'db' => 'button',   'dt' => 15,
        'formatter' => function ($d, $row) {
            global $connect, $location, $salesConsultantID, $branchAdmin, $salesManagerID, $generalManagerID;
            $id = $row[18];
            $confirmed = '';
            $sql3 = "SELECT * FROM `appointments` WHERE status = 1 AND location = '$location' AND sale_id = '$id'";
            $result3 = $connect->query($sql3);
            if ($result3->num_rows > 0) {
                $row3 = $result3->fetch_array();
                $confirmed = $row3['confirmed'];
            }
            $button = '
            <div class="show d-inline-flex w-100 justify-content-end" >';

            if (
                ($_SESSION['userRole'] == $salesConsultantID && $confirmed != 'ok') || $_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == $branchAdmin ||
                $_SESSION['userRole'] == $salesManagerID || $_SESSION['userRole'] == $generalManagerID
            ) {
                if ($row['sale_status'] != 'cancelled' && hasAccess("appointment", "Add") !== 'false') {
                    $button .= '<button class="btn btn-label-primary btn-icon mr-1" data-toggle="modal" data-target="#addNewScheduleModel" onclick="addNewSchedule(' . $id . ')" >
                                <i class="far fa-calendar-alt"></i>
                            </button>';
                }
            }

            if (hasAccess("sale", "Remove") !== 'false') {
                $button .= '<button class="btn btn-label-primary btn-icon" onclick="removeSale(' . $id . ')" >
                        <i class="fa fa-trash"></i>
                    </button>';
            }
            $button .= '</div>';

            return $button;
        }
    ),
    array('db' => 'stocktype',   'dt' => 16),
    array(
        'db' => 'countRow',   'dt' => 17,
        'formatter' => function ($d, $row) {
            global $connect;
            $stock_id = $row[25];
            $countRow = 0;
            $sql2 = "SELECT stock_id, COUNT(stock_id) FROM sales WHERE sales.sale_status != 'cancelled' AND sales.status = 1 AND stock_id = '$stock_id' GROUP BY stock_id HAVING COUNT(stock_id) > 1";
            $result2 = $connect->query($sql2);
            if ($result2->num_rows > 0) {
                $row2 = $result2->fetch_array();
                $countRow = $row2[0];
            }
            return $countRow;
        }
    ),
    array('db' => 'sale_id',   'dt' => 18),
    array(
        'db' => 'vin', 'dt' => 19,
        'formatter' => function ($d, $row) {
            return (String)$d;
        }
    ),
    array('db' => 'thankyou_cards',   'dt' => 20),
    array('db' => 'sold_date',   'dt' => 21),
    array(
        'db' => 'codp_warn',   'dt' => 22,
        'formatter' => function ($d, $row) {
            global $connect;
            $stock_id = $row[25];
            $countRow = '';
            $sql2 = "SELECT stock_id, COUNT(stock_id) FROM sales WHERE sales.sale_status != 'cancelled' AND stock_id = '$stock_id' GROUP BY stock_id HAVING COUNT(stock_id) > 1";
            $result2 = $connect->query($sql2);
            if ($result2->num_rows > 0) {
                $row2 = $result2->fetch_array();
                $countRow = $row2[0];
            }
            return $countRow;
        }
    ),
    array(
        'db' => 'lwbn_warn',   'dt' => 23,
        'formatter' => function ($d, $row) {
            global $connect;
            $stock_id = $row[25];
            $countRow = '';
            $sql2 = "SELECT stock_id, COUNT(stock_id) FROM sales WHERE sales.sale_status != 'cancelled' AND stock_id = '$stock_id' GROUP BY stock_id HAVING COUNT(stock_id) > 1";
            $result2 = $connect->query($sql2);
            if ($result2->num_rows > 0) {
                $row2 = $result2->fetch_array();
                $countRow = $row2[0];
            }
            return $countRow;
        }
    ),
    array('db' => 'invStatus',   'dt' => 24),
    array('db' => 'stock_id',   'dt' => 25),
    array('db' => 'reconcileDateOnly',   'dt' => 26),
);


// $sql_details = array(
//     'user' => 'root',
//     'pass' => '',
//     'db'   => 'carshop',
//     'host' => 'localhost'
// );
$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $localhost
);

require('ssp.class.php');



$tC = 0;
$yC = 0;
$cmC = 0;
$allC = 0;
$penC = 0;
$delC = 0;
$canC = 0;
$ndC = 0;

// echo $sqlQuery;

$result = $connect->query($sqlQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {

        $soldDateOnly = $row[21];
        $reconcileDateOnly = $row[26];
        $sale_status = $row[10];
        $thankyou = $row[20];
        $dateFormat = date("Y-m-d", strtotime($soldDateOnly));

        $reconcileDate = ($reconcileDateOnly != '') ? $reconcileDateOnly : $soldDateOnly;
        $rangeDateFormat = date("Y-m-d", strtotime($reconcileDate));


        // current month count
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $rowCategory = "";

        if ($rangeDateFormat >= $startDate && $rangeDateFormat <= $endDate) {
            $cmC += 1;
        }


        // yesterday count
        $yesterday = date("Y-m-d", strtotime("yesterday"));
        if ($dateFormat == $yesterday) {
            $yC += 1;
        }
        // today count
        $today = date("Y-m-d");
        if ($dateFormat == $today) {
            $tC += 1;
        }

        // not done count
        if ($sale_status == 'delivered' && $thankyou != 'on') {
            $ndC += 1;
        }

        // all count
        $allC += 1;
        $searchQuery = " ";
        if ($searchByDatePeriod != '') {
            if ($searchByDatePeriod == "currentMonth") {

                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                if ($rangeDateFormat >= $startDate && $rangeDateFormat <= $endDate) {
                    if ($customStart != '' && $customEnd != '') {
                        if ($rangeDateFormat >= $customStart && $rangeDateFormat <= $customEnd) {
                            if ($sale_status == 'pending') {
                                $penC += 1;
                            } else if ($sale_status == 'delivered') {
                                $delC += 1;
                            } else if ($sale_status == 'cancelled') {
                                $canC += 1;
                            }
                        }
                    } else {
                        if ($sale_status == 'pending') {
                            $penC += 1;
                        } else if ($sale_status == 'delivered') {
                            $delC += 1;
                        } else if ($sale_status == 'cancelled') {
                            $canC += 1;
                        }
                    }
                }
            } else if ($searchByDatePeriod == "yesterday") {
                $yesterday = date("Y-m-d", strtotime("yesterday"));
                if ($dateFormat == $yesterday) {
                    if ($customStart != '' && $customEnd != '') {
                        if ($dateFormat >= $customStart && $dateFormat <= $customEnd) {
                            if ($sale_status == 'pending') {
                                $penC += 1;
                            } else if ($sale_status == 'delivered') {
                                $delC += 1;
                            } else if ($sale_status == 'cancelled') {
                                $canC += 1;
                            }
                        }
                    } else {
                        if ($sale_status == 'pending') {
                            $penC += 1;
                        } else if ($sale_status == 'delivered') {
                            $delC += 1;
                        } else if ($sale_status == 'cancelled') {
                            $canC += 1;
                        }
                    }
                }
            } else if ($searchByDatePeriod == 'today') {
                $today = date("Y-m-d");
                if ($dateFormat == $today) {
                    if ($customStart != '' && $customEnd != '') {
                        if ($dateFormat >= $customStart && $dateFormat <= $customEnd) {
                            if ($sale_status == 'pending') {
                                $penC += 1;
                            } else if ($sale_status == 'delivered') {
                                $delC += 1;
                            } else if ($sale_status == 'cancelled') {
                                $canC += 1;
                            }
                        }
                    } else {
                        if ($sale_status == 'pending') {
                            $penC += 1;
                        } else if ($sale_status == 'delivered') {
                            $delC += 1;
                        } else if ($sale_status == 'cancelled') {
                            $canC += 1;
                        }
                    }
                }
            } else if ($searchByDatePeriod == 'all') {
                if ($customStart != '' && $customEnd != '') {
                    if ($rangeDateFormat >= $customStart && $rangeDateFormat <= $customEnd) {
                        if ($sale_status == 'pending') {
                            $penC += 1;
                        } else if ($sale_status == 'delivered') {
                            $delC += 1;
                        } else if ($sale_status == 'cancelled') {
                            $canC += 1;
                        }
                    }
                } else {
                    if ($sale_status == 'pending') {
                        $penC += 1;
                    } else if ($sale_status == 'delivered') {
                        $delC += 1;
                    } else if ($sale_status == 'cancelled') {
                        $canC += 1;
                    }
                }
            }
        }
    }
}
$dataObj = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);


$dataObj['totalCount']['tC'] = $tC;
$dataObj['totalCount']['yC'] = $yC;
$dataObj['totalCount']['cmC'] = $cmC;
$dataObj['totalCount']['allC'] = $allC;
$dataObj['totalCount']['penC'] = $penC;
$dataObj['totalCount']['delC'] = $delC;
$dataObj['totalCount']['canC'] = $canC;
$dataObj['totalCount']['ndC'] = $ndC;


echo json_encode($dataObj);

// echo json_encode(
//     SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns)
// );
// echo json_encode(
//     SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
// );
