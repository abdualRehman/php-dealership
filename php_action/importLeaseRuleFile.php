<?php
error_reporting(0);

require_once './db/core.php';
require_once '../assets/plugin/php-excel-reader/excel_reader2.php';
require_once '../assets/plugin/SpreadsheetReader.php';
require_once './updateMatrixRules.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'error' => array(), 'settingError' => array());

function reformatDate($date, $from_format = 'm-d-Y', $to_format = 'Y-m-d')
{
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

if ($_POST) {
    $p12_24_33 = (isset($_POST['12_24_33i'])) ? mysqli_real_escape_string($connect, $_POST['12_24_33i']) : "";
    $p12_36_48 = (isset($_POST['12_36_48i'])) ? mysqli_real_escape_string($connect, $_POST['12_36_48i']) : "";
    $p10_24_33 = (isset($_POST['10_24_33i'])) ? mysqli_real_escape_string($connect, $_POST['10_24_33i']) : "";
    $p10_36_48 = (isset($_POST['10_36_48i'])) ? mysqli_real_escape_string($connect, $_POST['10_36_48i']) : "";
    
    $expireIn = (isset($_POST['expireIni'])) ? mysqli_real_escape_string($connect, $_POST['expireIni']) : "";
    $expireIn = ($expireIn === '') ? "" : reformatDate($expireIn);


    // $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' , '.Csv'];
    $allowedFileType = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    if (in_array($_FILES["excelFile"]["type"], $allowedFileType)) {


        $targetPath = '../assets/uploads/' . $_FILES['excelFile']['name'];

        move_uploaded_file($_FILES['excelFile']['tmp_name'], $targetPath);

        $Reader = new SpreadsheetReader($targetPath);

        $sheetCount = count($Reader->sheets());
        for ($i = 0; $i < $sheetCount; $i++) {
            $Reader->ChangeSheet($i);

            $count = 0;

            foreach ($Reader as $Row) {
                if ($count == 0) {
                    // check column format
                    $c1 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[0])));
                    $c2 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[1])));
                    $c3 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[2])));
                    $c4 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[3])));
                    $c5 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[4])));
                    $c6 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[5])));
                    $c7 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[6])));
                    $c8 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[7])));
                    $c9 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[8])));
                    $c10 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[9])));
                    $c11 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[10])));
                    $c12 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[11])));
                    $c13 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[12])));
                    $c14 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[13])));
                    $c15 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[14])));
                    $c16 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[15])));


                    if (
                        $c1 != 'year' ||  $c2 != 'trim' ||
                        $c3 != 'modelcode' ||  $c4 != '24' ||
                        $c5 != '27' ||  $c6 != '30' ||
                        $c7 != '33' ||  $c8 != '36' ||
                        $c9 != '39' ||  $c10 != '42' ||
                        $c11 != '45' ||  $c12 != '48' ||
                        $c13 != '51' ||  $c14 != '54' || $c15 != '57' || $c16 != '60'
                    ) {
                        echo "File is not in correct format";
                        break;
                    } else {

                        $count++;
                        continue;
                    }
                } else {

                    $year = (isset($Row[0])) ? mysqli_real_escape_string($connect, $Row[0]) : "";
                    $trim = (isset($Row[1])) ? mysqli_real_escape_string($connect, $Row[1]) : "";
                    $modelcode = (isset($Row[2])) ? mysqli_real_escape_string($connect, $Row[2]) : "";
                    $i24 = (isset($Row[3])) ? mysqli_real_escape_string($connect, $Row[3]) : "";
                    $i27 = (isset($Row[4])) ? mysqli_real_escape_string($connect, $Row[4]) : "";
                    $i30 = (isset($Row[5])) ? mysqli_real_escape_string($connect, $Row[5]) : "";
                    $i33 = (isset($Row[6])) ? mysqli_real_escape_string($connect, $Row[6]) : "";
                    $i36 = (isset($Row[7])) ? mysqli_real_escape_string($connect, $Row[7]) : "";
                    $i39 = (isset($Row[8])) ? mysqli_real_escape_string($connect, $Row[8]) : "";
                    $i42 = (isset($Row[9])) ? mysqli_real_escape_string($connect, $Row[9]) : "";
                    $i45 = (isset($Row[10])) ? mysqli_real_escape_string($connect, $Row[10]) : "";
                    $i48 = (isset($Row[11])) ? mysqli_real_escape_string($connect, $Row[11]) : "";
                    $i51 = (isset($Row[12])) ? mysqli_real_escape_string($connect, $Row[12]) : "";
                    $i54 = (isset($Row[13])) ? mysqli_real_escape_string($connect, $Row[13]) : "";
                    $i57 = (isset($Row[14])) ? mysqli_real_escape_string($connect, $Row[14]) : "";
                    $i60 = (isset($Row[15])) ? mysqli_real_escape_string($connect, $Row[15]) : "";


                    if (
                        !empty($year) || !empty($trim) ||
                        !empty($modelcode) || !empty($i24) ||
                        !empty($i27) || !empty($i30) ||
                        !empty($i33) || !empty($i36) ||
                        !empty($i39) || !empty($i42) ||
                        !empty($i45) || !empty($i48) ||
                        !empty($i51) || !empty($i54) || !empty($i57) || !empty($i60)
                    ) {

                        $model = 'All';
                        $modelcode = trim($modelcode, ' ');
                        $modelcode_arr = explode("/", $modelcode);
                        $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
                        foreach ($modelcode_arr as $modelcodeV) {
                            if (strlen($modelcodeV) != 0) {
                                $modelcodeV = trim($modelcodeV, ' ');
                                $checkSql = "SELECT model FROM `manufature_price` WHERE year = '$year' AND model_code = '$modelcodeV' AND location = '$location' LIMIT 1";
                                $result = $connect->query($checkSql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $model = $row['model'];
                                    }
                                }
                                $checkleaseSql = "SELECT * FROM `lease_rule` WHERE model = '$model' AND year = '$year' AND modelno = '$modelcodeV' AND status = '1' AND location = '$location' ";
                                $cresult = $connect->query($checkleaseSql);
                                if ($cresult->num_rows > 0) {

                                    while ($row1 = $cresult->fetch_assoc()) {

                                        $ruleId = $row1['id'];
                                        $sql = "UPDATE `lease_rule` SET 
                                                `model`='$model',
                                                `year`='$year',
                                                `modelno`='$modelcodeV',
                                                `expire_in`='$expireIn',
                                                `24`='$i24',
                                                `27`='$i27',
                                                `30`='$i30',
                                                `33`='$i33',
                                                `36`='$i36',
                                                `39`='$i39',
                                                `42`='$i42',
                                                `45`='$i45',
                                                `48`='$i48',
                                                `51`='$i51',
                                                `54`='$i54',
                                                `57`='$i57',
                                                `60`='$i60',
                                                `12_24_33`='$p12_24_33',
                                                `12_36_48`='$p12_36_48',
                                                `10_24_33`='$p10_24_33',
                                                `10_36_48`='$p10_36_48' 
                                                WHERE id = '$ruleId' ";
                                        if ($connect->query($sql) === true) {
                                            $valid['success'] = true;
                                            $valid['messages'] = "Successfully Added";
                                        } else {
                                            $valid['success'] = false;
                                            $valid['error'][] = $connect->error . ' Model no = ' . $modelcodeV;
                                        }
                                    }
                                } else {
                                    $sql = "INSERT INTO `lease_rule`( `model`, `year`, `modelno` , `ex_modelno` , `expire_in`, `24`, `27`, `30`, `33`, `36`, `39`, `42`, `45`, `48`, `51`, `54`, `57`, `60`, `12_24_33`, `12_36_48`, `10_24_33`, `10_36_48`, `status` , `location`) 
                                    VALUES (
                                        '$model',
                                        '$year',
                                        '$modelcodeV',
                                        '',
                                        '$expireIn',
                                        '$i24',
                                        '$i27',
                                        '$i30',
                                        '$i33',
                                        '$i36',
                                        '$i39',
                                        '$i42',
                                        '$i45',
                                        '$i48',
                                        '$i51',
                                        '$i54',
                                        '$i57',
                                        '$i60',
                                        '$p12_24_33',
                                        '$p12_36_48',
                                        '$p10_24_33',
                                        '$p10_36_48',
                                        1 , '$location' )";

                                    if ($connect->query($sql) === true) {
                                        $valid['success'] = true;
                                        $valid['messages'] = "Successfully Added";
                                    } else {
                                        $valid['success'] = false;
                                        $valid['error'][] = $connect->error . ' Model no = ' . $modelcodeV;
                                    }
                                }
                            }
                        }
                        $obj = updateAllLeaseRules();
                        $obj = json_decode($obj);
                        if ($obj->success === 'false') {
                            $valid['settingError'][] = $obj->messages;
                        }
                    }
                }
            }
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Invalid File Type. Upload Excel File.";
    }


    $connect->close();
    echo json_encode($valid);
}
