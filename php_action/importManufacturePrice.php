<?php
error_reporting(0);

require_once './db/core.php';
require_once './updateMatrixRules.php';
require_once '../assets/plugin/php-excel-reader/excel_reader2.php';
require_once '../assets/plugin/SpreadsheetReader.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'erorStock' => array(), 'settingError' => array());

function tofloat($numberString)
{
    return floatval(preg_replace("/[^0-9.]/", '', $numberString));
}

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


                    if (
                        $c1 != 'year' || $c2 != 'model' || $c3 != 'modelcode' || $c4 != 'msrp' || $c5 != 'dlrinv' || $c6 != 'modeldescription'
                    ) {
                        // echo "File is not in correct format";
                        $valid['success'] = false;
                        $valid['messages'] =  "File is not in correct format";
                        break;
                    } else {

                        $count++;
                        continue;
                    }
                } else {


                    $year = (isset($Row[0])) ?  mysqli_real_escape_string($connect, $Row[0]) : "";
                    $model = (isset($Row[1])) ?  mysqli_real_escape_string($connect, $Row[1]) : "";
                    $modelcode = (isset($Row[2])) ?  mysqli_real_escape_string($connect, $Row[2]) : "";
                    $msrp = (isset($Row[3])) ?  mysqli_real_escape_string($connect, $Row[3]) : "";
                    $dlrinv = (isset($Row[4])) ?  mysqli_real_escape_string($connect, $Row[4]) : "";
                    $modeldescription = (isset($Row[5])) ?  mysqli_real_escape_string($connect, $Row[5]) : "";


                    if (
                        !empty($year) || !empty($model) ||
                        !empty($modelcode) || !empty($msrp) ||
                        !empty($dlrinv) || !empty($modeldescription)
                    ) {

                        $year = trim($year);
                        $model = ucwords(trim($model));
                        $modelcode = trim($modelcode);

                        $msrp = tofloat($msrp);
                        $dlrinv = tofloat($dlrinv);
                        $location = $_SESSION['userLoc'];

                        $sql = "INSERT INTO `manufature_price`(`year`, `model`, `model_code`, `msrp`, `dlr_inv`, `model_des`, `trim`, `status` , `location`) 
                        VALUES (
                            '$year',
                            '$model',
                            '$modelcode',
                            '$msrp',
                            '$dlrinv',
                            '$modeldescription',
                            '$modeldescription',
                            1,
                            '$location'
                        )";


                        if ($connect->query($sql) === true) {

                            // $obj = updateManufacturePriceByRule($model, $year, $modelcode);
                            $obj = updateAllMaxtix();
                            $obj = json_decode($obj);
                            if ($obj->success === 'false') {
                                $valid['settingError'][] = $obj->messages;
                            }

                            $valid['success'] = true;
                            $valid['messages'] =  "Successfully Added";
                        } else {
                            $valid['success'] = true;
                            $valid['erorStock'][] = $connect->error . ' Model Code = ' . $modelcode;
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
