<?php
error_reporting(0);

require_once './db/core.php';
require_once '../assets/plugin/php-excel-reader/excel_reader2.php';
require_once '../assets/plugin/SpreadsheetReader.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'erorStock' => array());



if ($_POST) {

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


                    if (
                        $c1 != "stockno" ||  $c2 != "year" ||
                        $c3 != 'make' ||  $c4 != 'model' ||
                        $c5 != 'modelno' ||  $c6 != 'color' ||
                        $c7 != 'lot' ||  $c8 != 'vin' ||
                        $c9 != 'mileage' ||  $c10 != 'age' ||
                        $c11 != 'balance' ||  $c12 != 'retail' ||
                        $c13 != 'certified' ||  $c14 != 'stocktype' || $c15 != "wholesale"
                    ) {
                        echo "File is not in correct format";
                        break;
                    } else {

                        $count++;
                        continue;
                    }
                } else {


                    $stockno = "";
                    if (isset($Row[0])) {
                        $stockno = mysqli_real_escape_string($connect, $Row[0]);
                    }
                    $year = "";
                    if (isset($Row[1])) {
                        $year = mysqli_real_escape_string($connect, $Row[1]);
                    }
                    $make = "";
                    if (isset($Row[2])) {
                        $make = mysqli_real_escape_string($connect, $Row[2]);
                    }
                    $model = "";
                    if (isset($Row[3])) {
                        $model =  mysqli_real_escape_string($connect, $Row[3]);
                    }
                    $modelno = "";
                    if (isset($Row[4])) {
                        $modelno = mysqli_real_escape_string($connect, $Row[4]);
                    }
                    $color = "";
                    if (isset($Row[5])) {
                        $color = mysqli_real_escape_string($connect, $Row[5]);
                    }
                    $lot = "";
                    if (isset($Row[6])) {
                        $lot = mysqli_real_escape_string($connect, $Row[6]);
                    }
                    $vin = "";
                    if (isset($Row[7])) {
                        $vin = mysqli_real_escape_string($connect, $Row[7]);
                    }
                    $mileage = "";
                    if (isset($Row[8])) {
                        $mileage = mysqli_real_escape_string($connect, $Row[8]);
                    }
                    $age = "";
                    if (isset($Row[9])) {
                        $age = mysqli_real_escape_string($connect, $Row[9]);
                    }

                    $balance = "";
                    if (isset($Row[10])) {
                        $balance = mysqli_real_escape_string($connect, $Row[10]);
                    }
                    $retail = "";
                    if (isset($Row[11])) {
                        $retail = mysqli_real_escape_string($connect, $Row[11]);
                        // $stx = sprintf("%.2f", $stx);  // for numbers we can set fload velue upto 2 points
                    }
                    $certified = "";
                    if (isset($Row[12])) {
                        $a = mysqli_real_escape_string($connect, $Row[12]);
                        if (strtolower($a) == 'certified') {
                            $certified = 'on';
                        } else {
                            $certified = 'off';
                        }
                    }

                    $stockType = "";
                    if (isset($Row[12])) {
                        $stockType = mysqli_real_escape_string($connect, $Row[13]);
                    }

                    $wholesale = "";
                    if (isset($Row[14])) {
                        $a = mysqli_real_escape_string($connect, $Row[14]);
                        if (strtolower($a) == 'wholesale') {
                            $wholesale = 'on';
                        } else {
                            $wholesale = 'off';
                        }
                    }


                    if (
                        !empty($stockno) || !empty($year) ||
                        !empty($make) || !empty($model) ||
                        !empty($modelno) || !empty($color) ||
                        !empty($lot) || !empty($vin) ||
                        !empty($mileage) || !empty($age) ||
                        !empty($balance) || !empty($retail) ||
                        !empty($stockType) || !empty($certified) || !empty($wholesale)
                    ) {


                        $sql = "INSERT INTO `inventory`(`stockno`, `year`, `make`, `model`, `modelno`, `color`, `lot`, `vin`, `mileage`, `age`, `balance`, `retail`, `certified`, `stocktype`, `wholesale`,  `status`) 
                        VALUES (
                            '$stockno',
                            '$year',
                            '$make',
                            '$model',
                            '$modelno',
                            '$color',
                            '$lot',
                            '$vin',
                            '$mileage',
                            '$age',
                            '$balance',
                            '$retail',
                            '$certified',
                            '$stockType',
                            '$wholesale',
                            1 )";

                        // update Inv data if this stock number already exist with deleted id with sale 
                        $updatekSql = "UPDATE `inventory` SET `year`='$year', `make`='$make',
                        `model`='$model',`modelno`='$modelno',`color`='$color',`lot`='$lot',`vin`='$vin',`mileage`='$mileage',`age`='$age',
                        `balance`='$balance',`retail`='$retail',`certified`='$certified',`stocktype`='$stockType',`wholesale`='$wholesale' WHERE stockno LIKE ('" . $stockno . "_%') AND status = 2 ";
                        $connect->query($updatekSql);

                        if ($connect->query($sql) === true) {
                            $valid['success'] = true;
                            $valid['messages'] =  "Successfully Added";
                        } else {
                            $valid['success'] = true;
                            $valid['erorStock'][] = $connect->error . ' stock no = ' . $stockno;
                        }
                    }
                }
            }
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Invalid File Type. Upload Excel File.";
    }



    // echo "successfully added";
    $connect->close();

    echo json_encode($valid);
}
