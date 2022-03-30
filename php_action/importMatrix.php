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


        $targetPath = '../assets/uploadsMatrix/' . $_FILES['excelFile']['name'];

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
                    $c17 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[16])));
                    $c18 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[17])));
                    $c19 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[18])));
                    $c20 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[19])));
                    $c21 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[20])));
                    $c22 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[21])));
                    $c23 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[22])));
                    $c24 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[23])));
                    $c25 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[24])));
                    $c26 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[25])));
                    $c27 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[26])));
                    $c28 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[27])));
                    $c29 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[28])));
                    $c30 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[29])));
                    $c31 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[30])));


                    $c32 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[31])));
                    $c33 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[32])));
                    $c34 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[33])));
                    $c35 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[34])));
                    $c36 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[35])));
                    $c37 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[36])));
                    $c38 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[37])));
                    $c39 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[38])));
                    $c40 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[39])));
                    $c41 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[40])));
                    $c42 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[41])));
                    $c43 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[42])));
                    $c44 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[43])));
                    $c45 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[44])));
                    $c46 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[45])));

                    $c47 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[46])));
                    $c48 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[47])));
                    $c49 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[48])));
                    $c50 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[49])));
                    $c51 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[50])));
                    $c52 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[51])));
                    $c53 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[52])));
                    $c54 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[53])));
                    $c55 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[54])));
                    $c56 = strtolower(str_replace(".", "",  preg_replace('/\s*/', '', $Row[55])));



                    if (
                        $c1 != 'year' ||
                        $c2 != 'model' ||
                        $c3 != 'trim' ||
                        $c4 != 'model#' ||
                        $c5 != 'net' ||
                        $c6 != 'hb' ||
                        $c7 != 'invoice' ||
                        $c8 != 'msrp' ||
                        $c9 != 'bdc' ||
                        $c10 != '24-36' ||
                        $c11 != '37-48' ||
                        $c12 != '49-60' ||
                        $c13 != '61-72' ||
                        $c14 != 'dealer' ||
                        $c15 != 'other' ||
                        $c16 != 'lease' ||
                        $c17 != '24/12k' ||
                        $c18 != '27/12k' ||
                        $c19 != '30/12k' ||
                        $c20 != '33/12k' ||
                        $c21 != '36/12k' ||
                        $c22 != '39/12k' ||
                        $c23 != '42/12k' ||
                        $c24 != '45/12k' ||
                        $c25 != '48/12k' ||
                        $c26 != '24/10k' ||
                        $c27 != '27/10k' ||
                        $c28 != '30/10k' ||
                        $c29 != '33/10k' ||
                        $c30 != '36/10k' ||
                        $c31 != '39/10k' ||

                        $c32 != '42/10k' ||
                        $c33 != '45/10k' ||
                        $c34 != '48/10k' ||
                        $c35 != '24/15k' ||
                        $c36 != '27/15k' ||
                        $c37 != '30/15k' ||
                        $c38 != '33/15k' ||
                        $c39 != '36/15k' ||
                        $c40 != '39/15k' ||
                        $c41 != '42/15k' ||
                        $c42 != '45/15k' ||
                        $c43 != '48/15k' ||
                        $c44 != '24-36' ||
                        $c45 != '37-48' ||
                        $c46 != '49-60' ||

                        $c47 != '61-72' ||
                        $c48 != '659-61024-36' ||
                        $c49 != '659-61037-60' ||
                        $c50 != '659-61061-72' ||
                        $c51 != 'financeexpires' ||
                        $c52 != '>660' ||
                        $c53 != 'lease659-610' ||
                        $c54 != 'onepay>660' ||
                        $c55 != 'onepay659-610' ||
                        $c56 != 'leaseexpires'
                    ) {
                        echo "File is not in correct format";
                        break;
                    } else {

                        echo $c1 . ' <br />';
                        echo $c2 . ' <br />';
                        echo $c3 . ' <br />';
                        echo $c4 . ' <br />';
                        echo $c5 . ' <br />';
                        echo $c6 . ' <br />';
                        echo $c7 . ' <br />';
                        echo $c8 . ' <br />';
                        echo $c9 . ' <br />';
                        echo $c10 . ' <br />';
                        echo $c11 . ' <br />';
                        echo $c12 . ' <br />';
                        echo $c13 . ' <br />';
                        echo $c14 . ' <br />';
                        echo $c15 . ' <br />';
                        echo $c16 . ' <br />';
                        echo $c17 . ' <br />';
                        echo $c18 . ' <br />';
                        echo $c19 . ' <br />';
                        echo $c20 . ' <br />';
                        echo $c21 . ' <br />';
                        echo $c22 . ' <br />';
                        echo $c23 . ' <br />';
                        echo $c24 . ' <br />';
                        echo $c25 . ' <br />';
                        echo $c26 . ' <br />';
                        echo $c27 . ' <br />';
                        echo $c28 . ' <br />';
                        echo $c29 . ' <br />';
                        echo $c30 . ' <br />';
                        echo $c31 . ' <br />';

                        echo $c32 . ' <br />';
                        echo $c33 . ' <br />';
                        echo $c34 . ' <br />';
                        echo $c35 . ' <br />';
                        echo $c36 . ' <br />';
                        echo $c37 . ' <br />';
                        echo $c38 . ' <br />';
                        echo $c39 . ' <br />';
                        echo $c40 . ' <br />';
                        echo $c41 . ' <br />';
                        echo $c42 . ' <br />';
                        echo $c43 . ' <br />';
                        echo $c44 . ' <br />';
                        echo $c45 . ' <br />';
                        echo $c46 . ' <br />';

                        echo $c47 . ' <br />';
                        echo $c48 . ' <br />';
                        echo $c49 . ' <br />';
                        echo $c50 . ' <br />';
                        echo $c51 . ' <br />';
                        echo $c52 . ' <br />';
                        echo $c53 . ' <br />';
                        echo $c54 . ' <br />';
                        echo $c55 . ' <br />';
                        echo $c56 . ' <br />';

                        $count++;
                        continue;
                    }
                } else {


                    $c1 = (isset($Row[0])) ?  mysqli_real_escape_string($connect, $Row[0]) : "";
                    $c2 = (isset($Row[1])) ?  mysqli_real_escape_string($connect, $Row[1]) : "";
                    $c3 = (isset($Row[2])) ?  mysqli_real_escape_string($connect, $Row[2]) : "";
                    $c4 = (isset($Row[3])) ?  mysqli_real_escape_string($connect, $Row[3]) : "";
                    $c5 = (isset($Row[4])) ?  mysqli_real_escape_string($connect, $Row[4]) : "";
                    $c6 = (isset($Row[5])) ?  mysqli_real_escape_string($connect, $Row[5]) : "";
                    $c7 = (isset($Row[6])) ?  mysqli_real_escape_string($connect, $Row[6]) : "";
                    $c8 = (isset($Row[7])) ?  mysqli_real_escape_string($connect, $Row[7]) : "";
                    $c9 = (isset($Row[8])) ?  mysqli_real_escape_string($connect, $Row[8]) : "";
                    $c10 = (isset($Row[9])) ?  mysqli_real_escape_string($connect, $Row[9]) : "";
                    $c11 = (isset($Row[10])) ?  mysqli_real_escape_string($connect, $Row[10]) : "";
                    $c12 = (isset($Row[11])) ?  mysqli_real_escape_string($connect, $Row[11]) : "";
                    $c13 = (isset($Row[12])) ?  mysqli_real_escape_string($connect, $Row[12]) : "";
                    $c14 = (isset($Row[13])) ?  mysqli_real_escape_string($connect, $Row[13]) : "";
                    $c15 = (isset($Row[14])) ?  mysqli_real_escape_string($connect, $Row[14]) : "";

                    $c16 = (isset($Row[15])) ?  mysqli_real_escape_string($connect, $Row[15]) : "";
                    $c17 = (isset($Row[16])) ?  mysqli_real_escape_string($connect, $Row[16]) : "";
                    $c18 = (isset($Row[17])) ?  mysqli_real_escape_string($connect, $Row[17]) : "";
                    $c19 = (isset($Row[18])) ?  mysqli_real_escape_string($connect, $Row[18]) : "";
                    $c20 = (isset($Row[19])) ?  mysqli_real_escape_string($connect, $Row[19]) : "";
                    $c21 = (isset($Row[20])) ?  mysqli_real_escape_string($connect, $Row[20]) : "";
                    $c22 = (isset($Row[21])) ?  mysqli_real_escape_string($connect, $Row[21]) : "";
                    $c23 = (isset($Row[22])) ?  mysqli_real_escape_string($connect, $Row[22]) : "";
                    $c24 = (isset($Row[23])) ?  mysqli_real_escape_string($connect, $Row[23]) : "";
                    $c25 = (isset($Row[24])) ?  mysqli_real_escape_string($connect, $Row[24]) : "";
                    $c26 = (isset($Row[25])) ?  mysqli_real_escape_string($connect, $Row[25]) : "";
                    $c27 = (isset($Row[26])) ?  mysqli_real_escape_string($connect, $Row[26]) : "";
                    $c28 = (isset($Row[27])) ?  mysqli_real_escape_string($connect, $Row[27]) : "";
                    $c29 = (isset($Row[28])) ?  mysqli_real_escape_string($connect, $Row[28]) : "";
                    $c30 = (isset($Row[29])) ?  mysqli_real_escape_string($connect, $Row[29]) : "";
                    $c31 = (isset($Row[30])) ?  mysqli_real_escape_string($connect, $Row[30]) : "";

                    $c32 = (isset($Row[31])) ?  mysqli_real_escape_string($connect, $Row[31]) : "";
                    $c33 = (isset($Row[32])) ?  mysqli_real_escape_string($connect, $Row[32]) : "";
                    $c34 = (isset($Row[33])) ?  mysqli_real_escape_string($connect, $Row[33]) : "";
                    $c35 = (isset($Row[34])) ?  mysqli_real_escape_string($connect, $Row[34]) : "";
                    $c36 = (isset($Row[35])) ?  mysqli_real_escape_string($connect, $Row[35]) : "";
                    $c37 = (isset($Row[36])) ?  mysqli_real_escape_string($connect, $Row[36]) : "";
                    $c38 = (isset($Row[37])) ?  mysqli_real_escape_string($connect, $Row[37]) : "";
                    $c39 = (isset($Row[38])) ?  mysqli_real_escape_string($connect, $Row[38]) : "";
                    $c40 = (isset($Row[39])) ?  mysqli_real_escape_string($connect, $Row[39]) : "";
                    $c41 = (isset($Row[40])) ?  mysqli_real_escape_string($connect, $Row[40]) : "";
                    $c42 = (isset($Row[41])) ?  mysqli_real_escape_string($connect, $Row[41]) : "";
                    $c43 = (isset($Row[42])) ?  mysqli_real_escape_string($connect, $Row[42]) : "";
                    $c44 = (isset($Row[43])) ?  mysqli_real_escape_string($connect, $Row[43]) : "";
                    $c45 = (isset($Row[44])) ?  mysqli_real_escape_string($connect, $Row[44]) : "";
                    $c46 = (isset($Row[45])) ?  mysqli_real_escape_string($connect, $Row[45]) : "";

                    $c47 = (isset($Row[46])) ?  mysqli_real_escape_string($connect, $Row[46]) : "";
                    $c48 = (isset($Row[47])) ?  mysqli_real_escape_string($connect, $Row[47]) : "";
                    $c49 = (isset($Row[48])) ?  mysqli_real_escape_string($connect, $Row[48]) : "";
                    $c50 = (isset($Row[49])) ?  mysqli_real_escape_string($connect, $Row[49]) : "";
                    $c51 = (isset($Row[50])) ?  mysqli_real_escape_string($connect, $Row[50]) : "";
                    $c52 = (isset($Row[51])) ?  mysqli_real_escape_string($connect, $Row[51]) : "";
                    $c53 = (isset($Row[52])) ?  mysqli_real_escape_string($connect, $Row[52]) : "";
                    $c54 = (isset($Row[53])) ?  mysqli_real_escape_string($connect, $Row[53]) : "";
                    $c55 = (isset($Row[54])) ?  mysqli_real_escape_string($connect, $Row[54]) : "";
                    $c56 = (isset($Row[55])) ?  mysqli_real_escape_string($connect, $Row[55]) : "";






                    // if (
                    //     !empty($stockno) || !empty($year) ||
                    //     !empty($make) || !empty($model) ||
                    //     !empty($modelno) || !empty($color) ||
                    //     !empty($lot) || !empty($vin) ||
                    //     !empty($mileage) || !empty($age) ||
                    //     !empty($balance) || !empty($retail) ||
                    //     !empty($stockType) || !empty($certified) || !empty($wholesale)
                    // ) {


                    //     // $sql = "INSERT INTO `inventory`(`stockno`, `year`, `make`, `model`, `modelno`, `color`, `lot`, `vin`, `mileage`, `age`, `balance`, `retail`, `certified`, `stocktype`, `wholesale`,  `status`) 
                    //     // VALUES (
                    //     //     '$stockno',
                    //     //     '$year',
                    //     //     '$make',
                    //     //     '$model',
                    //     //     '$modelno',
                    //     //     '$color',
                    //     //     '$lot',
                    //     //     '$vin',
                    //     //     '$mileage',
                    //     //     '$age',
                    //     //     '$balance',
                    //     //     '$retail',
                    //     //     '$certified',
                    //     //     '$stockType',
                    //     //     '$wholesale',
                    //     //     1 )";

                    //     // // update Inv data if this stock number already exist with deleted id with sale 
                    //     // $updatekSql = "UPDATE `inventory` SET `year`='$year', `make`='$make',
                    //     // `model`='$model',`modelno`='$modelno',`color`='$color',`lot`='$lot',`vin`='$vin',`mileage`='$mileage',`age`='$age',
                    //     // `balance`='$balance',`retail`='$retail',`certified`='$certified',`stocktype`='$stockType',`wholesale`='$wholesale' WHERE stockno LIKE ('" . $stockno . "_%') AND status = 2 ";
                    //     // $connect->query($updatekSql);

                    //     if ($connect->query($sql) === true) {
                    //         $valid['success'] = true;
                    //         $valid['messages'] =  "Successfully Added";
                    //     } else {
                    //         $valid['success'] = true;
                    //         $valid['erorStock'][] = $connect->error . ' stock no = ' . $stockno;
                    //     }
                    // }
                }
            }
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Invalid File Type. Upload Excel File.";
    }



    // echo "successfully added";
    // $connect->close();

    // echo json_encode($valid);
}
