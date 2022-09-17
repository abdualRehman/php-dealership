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


        $targetPath = '../assets/uploadsLocations/' . $_FILES['excelFile']['name'];

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


                    if (
                        $c1 != "dealer#" ||  $c2 != "dealership" ||
                        $c3 != 'address' ||  $c4 != 'city' ||
                        $c5 != 'state' ||  $c6 != 'zip' ||
                        $c7 != 'miles' ||  $c8 != 'traveltime' ||
                        $c9 != 'roundtrip' ||  $c10 != 'phone' ||
                        $c11 != 'fax' ||  $c12 != 'maincontact' ||
                        $c13 != 'cell' ||  $c14 != 'preffercallortext'
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


                    $dealerno = (isset($Row[0])) ?  mysqli_real_escape_string($connect, $Row[0]) : "";
                    $dealership = (isset($Row[1])) ?  mysqli_real_escape_string($connect, $Row[1]) : "";
                    $address = (isset($Row[2])) ?  mysqli_real_escape_string($connect, $Row[2]) : "";

                    $city = (isset($Row[3])) ?  mysqli_real_escape_string($connect, $Row[3]) : "";
                    $state = (isset($Row[4])) ?  mysqli_real_escape_string($connect, $Row[4]) : "";
                    $zip = (isset($Row[5])) ?  mysqli_real_escape_string($connect, $Row[5]) : "";
                    $miles = (isset($Row[6])) ?  mysqli_real_escape_string($connect, $Row[6]) : "";
                    $traveltime = (isset($Row[7])) ?  mysqli_real_escape_string($connect, $Row[7]) : "";
                    $roundtrip = (isset($Row[8])) ?  mysqli_real_escape_string($connect, $Row[8]) : "";
                    $phone = (isset($Row[9])) ?  mysqli_real_escape_string($connect, $Row[9]) : "";
                    $fax = (isset($Row[10])) ?  mysqli_real_escape_string($connect, $Row[10]) : "";
                    $maincontact = (isset($Row[11])) ?  mysqli_real_escape_string($connect, $Row[11]) : "";
                    $cell = (isset($Row[12])) ?  mysqli_real_escape_string($connect, $Row[12]) : "";
                    $preffer = (isset($Row[13])) ?  mysqli_real_escape_string($connect, $Row[13]) : "";


                    if (
                        !empty($dealerno) || !empty($dealership) ||
                        !empty($address) || !empty($city) ||
                        !empty($state) || !empty($zip) ||
                        !empty($miles) || !empty($traveltime) ||
                        !empty($roundtrip) || !empty($phone) ||
                        !empty($fax) || !empty($maincontact) ||
                        !empty($cell) || !empty($preffer)
                    ) {

                        $location = ($_SESSION['userLoc'] !== '') ? $_SESSION['userLoc'] : '1';
                        $sql = "INSERT INTO `locations`(`dealer_no`, `dealership`, `address`, `city`, `state`, `zip`, `miles`, `travel_time`, `round_trip`, `phone`, `fax`, `main_contact`, `cell`, `preffer`, `status` , `location`)
                        VALUES (
                            '$dealerno',
                            '$dealership',
                            '$address',
                            '$city',
                            '$state',
                            '$zip',
                            '$miles',
                            '$traveltime',
                            '$roundtrip',
                            '$phone',
                            '$fax',
                            '$maincontact',
                            '$cell',
                            '$preffer',
                            1,
                            '$location' )";



                        if ($connect->query($sql) === true) {
                            $valid['success'] = true;
                            $valid['messages'] =  "Successfully Added";
                        } else {
                            $valid['success'] = true;
                            $valid['erorStock'][] = $connect->error . ' stock no = ' . $dealerno;
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
