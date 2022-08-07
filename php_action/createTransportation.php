<?php

require_once './db/core.php';

$valid = array('success' => false, 'messages' => array(), 'errorMessages' => array(), 'id' => '', 'settingError' => array());


if ($_POST) {


    $stockId = (isset($_POST['stockId'])) ? mysqli_real_escape_string($connect, $_POST['stockId']) : "";
    $status = (isset($_POST['status'])) ? mysqli_real_escape_string($connect, $_POST['status']) : "";
    $locNum = (isset($_POST['locNum'])) ? mysqli_real_escape_string($connect, $_POST['locNum']) : "";
    $damageType = (isset($_POST['damageType'])) ? mysqli_real_escape_string($connect, $_POST['damageType']) : "";
    $damageSeverity = (isset($_POST['damageSeverity'])) ? mysqli_real_escape_string($connect, $_POST['damageSeverity']) : "";
    $damageGrid = (isset($_POST['damageGrid'])) ? mysqli_real_escape_string($connect, $_POST['damageGrid']) : "";


    $sql = "INSERT INTO `transportation`(`stock_id`, `loc_num`, `damage_type`, `damage_severity`, `damage_grid`, `transport_status`, `status`) 
    VALUES ('$stockId' , '$locNum' , '$damageType' , '$damageSeverity' , '$damageGrid' , '$status' , 1 )";

    if ($connect->query($sql) === true) {
        $valid['success'] = true;
        $valid['messages'][] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'][] = $connect->error;
        // $valid['messages'] = mysqli_error($connect);
    }



    $connect->close();

    echo json_encode($valid);
} // /if $_POST
// echo json_encode($valid);














// require_once './db/core.php';
// require_once '../assets/plugin/php-excel-reader/excel_reader2.php';
// require_once '../assets/plugin/SpreadsheetReader.php';
// require_once './updateMatrixRules.php';

// $valid['success'] = array('success' => false, 'messages' => array(), 'error' => array(), 'settingError' => array());

// function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
// {
//     $date_aux = date_create_from_format($from_format, $date);
//     return date_format($date_aux, $to_format);
// }

// if ($_POST) {



//     // $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' , '.Csv'];
//     $allowedFileType = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//     if (in_array($_FILES["excelFile"]["type"], $allowedFileType)) {


//         $targetPath = '../assets/uploads/' . $_FILES['excelFile']['name'];

//         // move_uploaded_file($_FILES['excelFile']['tmp_name'], $targetPath);

//         $Reader = new SpreadsheetReader($targetPath);

//         $sheetCount = count($Reader->sheets());
//         for ($i = 0; $i < $sheetCount; $i++) {
//             $Reader->ChangeSheet($i);

//             $count = 1;

//             foreach ($Reader as $Row) {
//                 if ($count == 0) {
//                     // check column format
//                     $c1 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[0])));
//                     $c2 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[1])));
//                     $c3 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[2])));
//                     $c4 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[3])));
//                     $c5 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[4])));
//                     $c6 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[5])));
//                     $c7 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[6])));
//                     $c8 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[7])));
//                     $c9 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[8])));
//                     $c10 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[9])));
//                     $c11 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[10])));
//                     $c12 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[11])));
//                     $c13 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[12])));
//                     $c14 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[13])));
//                     $c15 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[14])));
//                     $c16 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[15])));


//                     if (
//                         $c1 != 'year' ||  $c2 != 'trim' ||
//                         $c3 != 'modelcode' ||  $c4 != '24' ||
//                         $c5 != '27' ||  $c6 != '30' ||
//                         $c7 != '33' ||  $c8 != '36' ||
//                         $c9 != '39' ||  $c10 != '42' ||
//                         $c11 != '45' ||  $c12 != '48' ||
//                         $c13 != '51' ||  $c14 != '54' || $c15 != '57' || $c16 != '60'
//                     ) {
//                         echo "File is not in correct format";
//                         break;
//                     } else {

//                         $count++;
//                         continue;
//                     }
//                 } else {


//                     $trim = (isset($Row[1])) ? mysqli_real_escape_string($connect, $Row[1]) : "";
//                     $modelcode = (isset($Row[2])) ? mysqli_real_escape_string($connect, $Row[2]) : "";
//                     $i24 = (isset($Row[3])) ? mysqli_real_escape_string($connect, $Row[3]) : "";
//                     $i27 = (isset($Row[4])) ? mysqli_real_escape_string($connect, $Row[4]) : "";


//                     if (
//                         !empty($trim) || !empty($modelcode) ||
//                         !empty($i27) || !empty($i24)
//                     ) {

//                         echo $trim . ' -  ' . $modelcode . ' - ' . $i24 . ' - ' . $i27 . '<br/>';

//                         $sql = "INSERT INTO `default_options`(`location_number`, `damage_type`, `damage_severty`, `grid_loation`, `status`) 
//                         VALUES (
//                             '$trim' , '$modelcode' , '$i24' , '$i27' , 1
//                         )";

//                         if ($connect->query($sql) === true) {
//                             $valid['success'] = true;
//                             $valid['messages'] = "Successfully Added";
//                         } else {
//                             $valid['success'] = false;
//                             $valid['error'][] = $connect->error . ' trim no = ' . $trim;
//                         }
//                     }
//                 }
//             }
//         }
//     } else {
//         $valid['success'] = false;
//         $valid['messages'] = "Invalid File Type. Upload Excel File.";
//     }


//     $connect->close();
//     echo json_encode($valid);
// }









