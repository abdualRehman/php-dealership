<?php
require_once './db/core.php';

$validate = array('success' => "false", 'messages' => array());

function updateManufacturePriceByRule($model, $year, $modelno)   // unlinked with create matrix file 
{

    global $connect;
    global $validate;

    $checkSql = "SELECT `id`, `destination`, `hb` , ex_modelno FROM `matrix_rule` WHERE model = '$model' 
    AND ( year = '$year' OR year = 'All' ) AND ( modelno = '$modelno' OR modelno = 'All' ) AND status = 1 AND
    ex_modelno NOT LIKE '% " . $modelno . " %' ORDER BY FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC LIMIT 1";

    // echo $checkSql .'<br />';
    $result = $connect->query($checkSql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $id = $row[0];
            $destination = $row[1];
            $hb = $row[2];
            $ex_modelno = explode(" ", trim($row[3]));

            // generating query 
            $filter = array();
            foreach ($ex_modelno as $value) {
                $filter[] = "model_code != " . "'{$value}'";
            }

            // echo $model . ' ex_modelno ' . json_encode($filter) . ' - ' . '<br />';

            // Generating Query
            $year1 = ($year === 'All') ? " year IS NOT NULL" : "'{$year}'";
            $modelno1 = ($modelno === 'All') ? " model_code IS NOT NULL AND (" . implode(' AND ', $filter) . " ) " : "'$modelno'";


            $sql = "SELECT id , dlr_inv , msrp FROM `manufature_price` WHERE model = '$model' AND year = " . $year1 . " 
            AND model_code = " . $modelno1 . " AND status = 1";
            // CV1F1NEW
            // echo $sql . '<br />';

            $mnpResult =  $connect->query($sql);
            if ($mnpResult && $mnpResult->num_rows > 0) {
                while ($row2 = $mnpResult->fetch_array()) {
                    $mnpId = $row2[0];
                    $dlr_inv = $row2[1];
                    $msrp = $row2[2];


                    // calculation
                    $Holdback = ($msrp * (int)$hb) / 100;
                    $Net =  $dlr_inv - $Holdback + $destination;
                    $invoice = $dlr_inv + $destination;
                    $MSRP = $msrp + $destination;

                    // echo $mnpId . ' - ' . $Net . ' - ' . $Holdback  . '<br />';

                    $updateSql = "UPDATE `manufature_price` SET `net`='$Net', `hb`='$Holdback', `invoice`='$invoice', `m.s.r.p`='$MSRP' WHERE id = '$mnpId'";
                    $connect->query($updateSql);

                    $validate['success'] = "true";
                    $validate['messages'] = "successfully updated";
                }
            } else {
                $validate['success'] = "false";
                $validate['messages'] = mysqli_error($connect);
                // echo mysqli_error($connect);
            }
        }
    } else {
        $validate['success'] = "false";
        $validate['messages'] = "No Rules Defined";
        // echo "No Rules Defined";
    }

    updateManufactureBDCByRule($model, $year, $modelno);

    return json_encode($validate);
}


function updateAllMaxtix()
{
    global $connect;
    global $validate;


    // if (!is_null($removeId)) {
    //     $checkSql = "SELECT `id`, `model` , `year` , `modelno` , `destination`, `hb` , ex_modelno FROM `matrix_rule` WHERE id = '$removeId'";
    //     $result = $connect->query($checkSql);
    //     if ($result && $result->num_rows > 0) {
    //         while ($row = $result->fetch_array()) {
    //             $model = $row[1];
    //             $year = $row[2];

    //             $year1 = ($year === 'All') ? " year IS NOT NULL" : "'{$year}'";
    //             // First Update all the models and years, Matrixes Values as Null or empty
    //             $updateSqlF = "UPDATE `manufature_price` SET `net`= '' , `hb`= '' , `invoice`= '' , `m.s.r.p`= '' WHERE model = '$model' AND year = " . $year1 . " AND status = 1";
    //             $connect->query($updateSqlF);
    //         }
    //     }
    // }

    // First Update all the Matrixes Values as Null or empty
    $updateSqlF = "UPDATE `manufature_price` SET `net`= '' , `hb`= '' , `invoice`= '' , `m.s.r.p`= '' WHERE status = 1";
    $connect->query($updateSqlF);

    $checkSql = "SELECT `id`, `model` , `year` , `modelno` , `destination`, `hb` , ex_modelno FROM `matrix_rule` WHERE status = 1";
    $result = $connect->query($checkSql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $id = $row[0];
            $model = $row[1];
            $year = $row[2];
            $modelno = $row[3];
            $destination = $row[4];
            $hb = $row[5];
            $ex_modelno = explode(" ", trim($row[6]));

            // echo $id . ' ex_modelno ' . json_encode($row[3]) . ' - ' . '<br />';

            // generating query 
            $filter = array();
            foreach ($ex_modelno as $value) {
                $filter[] = "model_code != " . "'$value'";
            }

            // Generating Query
            $year1 = ($year === 'All') ? " year IS NOT NULL" : "'$year'";
            $modelno1 = ($modelno === 'All') ? " model_code IS NOT NULL AND (" . implode(' AND ', $filter) . " ) " : "'$modelno'";


            $sql = "SELECT id , dlr_inv , msrp FROM `manufature_price` WHERE model = '$model' AND year = " . $year1 . " 
            AND model_code = " . $modelno1 . " AND status = 1";

            $mnpResult =  $connect->query($sql);
            if ($mnpResult && $mnpResult->num_rows > 0) {
                while ($row2 = $mnpResult->fetch_array()) {
                    $mnpId = $row2[0];
                    $dlr_inv = $row2[1];
                    $msrp = $row2[2];


                    // calculation
                    $Holdback = ($msrp * (int)$hb) / 100;
                    $Net =  $dlr_inv - $Holdback + $destination;
                    $invoice = $dlr_inv + $destination;
                    $MSRP = $msrp + $destination;

                    $Holdback = sprintf("%.2f", $Holdback);  //showing result upto 2 decimals 
                    $Net = sprintf("%.2f", $Net);  //showing result upto 2 decimals 
                    $invoice = sprintf("%.2f", $invoice);  //showing result upto 2 decimals 

                    // $MSRP = sprintf("%.2f", $MSRP);  //doesn't need

                    // $newNet = $dlr_inv - $hb + $destination
                    // Accord 2022 CIVIC SEDAN FE2F2NEW
                    // echo $mnpId . ' - ' . $Net . ' - ' . $Holdback  . '<br />';

                    $updateSql = "UPDATE `manufature_price` SET `net`='$Net', `hb`='$Holdback', `invoice`='$invoice', `m.s.r.p`='$MSRP' WHERE id = '$mnpId'";
                    $connect->query($updateSql);

                    $validate['success'] = "true";
                    $validate['messages'] = "successfully updated";
                }
            } else {
                $validate['success'] = "false";
                $validate['messages'] = mysqli_error($connect);
                // echo mysqli_error($connect);
            }
        }
    } else {
        $validate['success'] = "false";
        $validate['messages'] = "No Rules Defined";
        // echo "No Rules Defined";
    }

    updateAllBdc();
    updateAllLeaseRules();
    updateAllCashInventives();

    return json_encode($validate);
}



function updateManufactureBDCByRule($model, $year, $modelno)  // unlinked with create matrix file 
{

    global $connect;
    global $validate;

    // also working
    // $checkSql = "SELECT `id`, `calcfrom`, `calculation` , `num_to_calc` , ex_modelno FROM `bdc_rules` WHERE model = '$model' 
    // AND ( year = '$year' OR year = 'All' ) AND ( modelno = '$modelno' OR modelno = 'All' ) AND status = 1 AND
    // ex_modelno NOT LIKE '% " . $modelno . " %' ORDER BY IF(year = 'All', 1, 0) ASC, IF(modelno = 'All', 1, 0) ASC LIMIT 1";

    $checkSql = "SELECT `id`, `calcfrom`, `calculation` , `num_to_calc` , ex_modelno FROM `bdc_rules` WHERE model = '$model' 
    AND ( year = '$year' OR year = 'All' ) AND ( modelno = '$modelno' OR modelno = 'All' ) AND status = 1 AND
    ex_modelno NOT LIKE '% " . $modelno . " %' ORDER BY FIELD(year, '$year') DESC, FIELD(modelno, '$modelno') DESC LIMIT 1";

    // echo $checkSql . '<br />';
    $result = $connect->query($checkSql);

    if ($result && $result->num_rows > 0) {

        while ($row = $result->fetch_array()) {

            $id = $row[0];
            $calcfrom = ($row[1] === 'msrp') ? 'm.s.r.p' : $row[1];
            $calculation = $row[2];
            $num_to_calc = $row[3];

            $ex_modelno = explode(" ", trim($row[4]));



            // generating query 
            $filter = array();
            foreach ($ex_modelno as $value) {
                $filter[] = "model_code != " . "'{$value}'";
            }


            $year1 = ($year === 'All') ? " year IS NOT NULL" : "'{$year}'";
            $modelno1 = ($modelno === 'All') ? " model_code IS NOT NULL AND (" . implode(' AND ', $filter) . " ) " : "'$modelno'";


            $sql = "SELECT id , `" . $calcfrom . "` FROM `manufature_price` WHERE model = '$model' AND year = " . $year1 . " 
            AND model_code = " . $modelno1 . " AND status = 1 AND `" . $calcfrom . "` != '' ";

            // echo $sql ."<br />";

            $mnpResult =  $connect->query($sql);
            if ($mnpResult && $mnpResult->num_rows > 0) {
                while ($row2 = $mnpResult->fetch_array()) {
                    $mnpId = $row2[0];
                    $columnValue = $row2[1];


                    if ($calculation === 'plus') {
                        $bdc = $columnValue + $num_to_calc;
                    } else if ($calculation === 'minus') {
                        $bdc = $columnValue - $num_to_calc;
                    } else if ($calculation === 'percentage') {
                        $bdc = ($columnValue * $num_to_calc) / 100;
                    }
                    // echo $columnValue . ' - ' . $calculation . ' - ' . $num_to_calc . ' =  ' . $bdc .' <br /> ' ;

                    $updateSql = "UPDATE `manufature_price` SET `bdc` = '$bdc' WHERE id = '$mnpId'";
                    $connect->query($updateSql);

                    $validate['success'] = "true";
                    $validate['messages'] = "successfully updated";
                }
            } else {
                $validate['success'] = "false";
                $validate['messages'] = mysqli_error($connect);
            }
        }
    } else {
        $validate['success'] = "false";
        $validate['messages'] = "No BDC Rules Defined";
    }
    return json_encode($validate);
}


function updateAllBdc()
{
    global $connect;
    global $validate;


    // if (!is_null($removeId)) {
    //     $checkSql = "SELECT `id`, `model` , `year` FROM `bdc_rules` WHERE id = '$removeId'";
    //     $result = $connect->query($checkSql);
    //     if ($result && $result->num_rows > 0) {
    //         while ($row = $result->fetch_array()) {
    //             $model = $row[1];
    //             $year = $row[2];
    //             $year1 = ($year === 'All') ? " year IS NOT NULL" : "'$year'";
    //             // First Update all the models and years, Matrixes Values as Null or empty
    //             $updateSqlF = "UPDATE `manufature_price` SET `bdc`= '' WHERE model = '$model' AND year = " . $year1 . " AND status = 1";
    //             $connect->query($updateSqlF);
    //         }
    //     }
    // }

    // First Update all the models and years, Matrixes Values as Null or empty
    $updateSqlF = "UPDATE `manufature_price` SET `bdc`= '' WHERE status = 1";
    $connect->query($updateSqlF);

    $checkSql = "SELECT `id`, `model` , `year` , `modelno` , `ex_modelno` , `calcfrom`, `calculation`, `num_to_calc` FROM `bdc_rules` WHERE status = 1";
    $result = $connect->query($checkSql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $id = $row[0];
            $model = $row[1];
            $year = $row[2];
            $modelno = $row[3];
            $ex_modelno = explode(" ", trim($row[4]));

            $calcfrom = ($row[5] === 'msrp') ? 'm.s.r.p' : $row[5];
            $calculation = $row[6];
            $num_to_calc = $row[7];

            // echo $id . ' ex_modelno ' . json_encode($row[3]) . ' - ' . '<br />';

            // generating query 
            $filter = array();
            foreach ($ex_modelno as $value) {
                $filter[] = "model_code != " . "'$value'";
            }

            // Generating Query
            $year1 = ($year === 'All') ? " year IS NOT NULL" : "'$year'";
            $modelno1 = ($modelno === 'All') ? " model_code IS NOT NULL AND (" . implode(' AND ', $filter) . " ) " : "'$modelno'";


            $sql = "SELECT id , `" . $calcfrom . "` FROM `manufature_price` WHERE model = '$model' AND year = " . $year1 . " 
            AND model_code = " . $modelno1 . " AND status = 1 AND `" . $calcfrom . "` != '' ";

            // echo $sql;
            $mnpResult =  $connect->query($sql);
            if ($mnpResult && $mnpResult->num_rows > 0) {
                while ($row2 = $mnpResult->fetch_array()) {
                    $mnpId = $row2[0];
                    $columnValue = $row2[1];


                    if ($calculation === 'plus') {
                        $bdc = $columnValue + $num_to_calc;
                    } else if ($calculation === 'minus') {
                        $bdc = $columnValue - $num_to_calc;
                    } else if ($calculation === 'percentage') {
                        $bdc = ($columnValue * $num_to_calc) / 100;
                    }
                    // $bdc = sprintf("%.2f", $bdc);  // Doesn't need
                    // echo $columnValue . ' - ' . $calculation . ' - ' . $num_to_calc . ' =  ' . $bdc .' <br /> ' ;

                    $updateSql = "UPDATE `manufature_price` SET `bdc` = '$bdc' WHERE id = '$mnpId'";
                    $connect->query($updateSql);

                    $validate['success'] = "true";
                    $validate['messages'] = "successfully updated";
                }
            } else {
                $validate['success'] = "false";
                $validate['messages'] = mysqli_error($connect);
                // echo mysqli_error($connect);
            }
        }
    } else {
        $validate['success'] = "false";
        $validate['messages'] = "No BDC Rules Defined";
        // echo "No Rules Defined";
    }
    return json_encode($validate);
}


function updateALLRates()
{
    global $connect;
    global $validate;


    // First Update all the rate_rule_id Values as Null or empty
    $updateSqlF = "UPDATE `manufature_price` SET `rate_rule_id`= '' WHERE status = 1";
    $connect->query($updateSqlF);

    $checkSql = "SELECT `id`, `model` , `year` , `modelno` , `ex_modelno` FROM `rate_rule` WHERE status = 1";
    $result = $connect->query($checkSql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $rate_rule_id = $row[0];
            $model = $row[1];
            $year = $row[2];
            $modelno = $row[3];
            $ex_modelno = explode(" ", trim($row[4]));

            // echo $id . ' ex_modelno ' . json_encode($row[3]) . ' - ' . '<br />';

            // generating query
            $filter = array();
            foreach ($ex_modelno as $value) {
                $filter[] = "model_code != " . "'$value'";
            }

            // Generating Query
            $year1 = ($year === 'All') ? " year IS NOT NULL" : "'$year'";
            $modelno1 = ($modelno === 'All') ? " model_code IS NOT NULL AND (" . implode(' AND ', $filter) . " ) " : "'$modelno'";



            $sql = "SELECT id FROM `manufature_price` WHERE model = '$model' AND year = " . $year1 . " 
            AND model_code = " . $modelno1 . " AND status = 1";

            // echo $sql;
            $mnpResult =  $connect->query($sql);
            if ($mnpResult && $mnpResult->num_rows > 0) {
                while ($row2 = $mnpResult->fetch_array()) {
                    $mnpId = $row2[0];

                    $updateSql = "UPDATE `manufature_price` SET `rate_rule_id` = '$rate_rule_id' WHERE id = '$mnpId'";
                    $connect->query($updateSql);

                    $validate['success'] = "true";
                    $validate['messages'] = "successfully updated";
                }
            } else {
                $validate['success'] = "false";
                $validate['messages'] = mysqli_error($connect);
                // echo mysqli_error($connect);
            }
        }
    } else {
        $validate['success'] = "false";
        $validate['messages'] = "No BDC Rules Defined";
        // echo "No Rules Defined";
    }
    return json_encode($validate);
}
function updateAllLeaseRules()
{
    global $connect;
    global $validate;


    // First Update all the lease_rule_id Values as Null or empty
    $updateSqlF = "UPDATE `manufature_price` SET `lease_rule_id`= '' WHERE status = 1";
    $connect->query($updateSqlF);

    $checkSql = "SELECT `id`, `model` , `year` , `modelno` , `ex_modelno` FROM `lease_rule` WHERE status = 1";
    $result = $connect->query($checkSql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $lease_rule_id = $row[0];
            $model = $row[1];
            $year = $row[2];
            $modelno = $row[3];
            $ex_modelno = explode(" ", trim($row[4]));

            // echo $id . ' ex_modelno ' . json_encode($row[3]) . ' - ' . '<br />';

            // generating query
            $filter = array();
            foreach ($ex_modelno as $value) {
                $filter[] = "model_code != " . "'$value'";
            }

            // Generating Query
            $year1 = ($year === 'All') ? " year IS NOT NULL" : "'$year'";
            $modelno1 = ($modelno === 'All') ? " model_code IS NOT NULL AND (" . implode(' AND ', $filter) . " ) " : "'$modelno'";



            $sql = "SELECT id FROM `manufature_price` WHERE model = '$model' AND year = " . $year1 . " 
            AND model_code = " . $modelno1 . " AND status = 1";

            // echo $sql;
            $mnpResult =  $connect->query($sql);
            if ($mnpResult && $mnpResult->num_rows > 0) {
                while ($row2 = $mnpResult->fetch_array()) {
                    $mnpId = $row2[0];

                    $updateSql = "UPDATE `manufature_price` SET `lease_rule_id` = '$lease_rule_id' WHERE id = '$mnpId'";
                    $connect->query($updateSql);

                    $validate['success'] = "true";
                    $validate['messages'] = "successfully updated";
                }
            } else {
                $validate['success'] = "false";
                $validate['messages'] = mysqli_error($connect);
                // echo mysqli_error($connect);
            }
        }
    } else {
        $validate['success'] = "false";
        $validate['messages'] = "No BDC Rules Defined";
        // echo "No Rules Defined";
    }
    return json_encode($validate);
}


function updateAllCashInventives()
{
    global $connect;
    global $validate;

    // First Update all the models and years, Matrixes Values as Null or empty
    $updateSqlF = "UPDATE `manufature_price` SET `cash_incentive_rule_id`= '' WHERE status = 1";
    $connect->query($updateSqlF);

    $checkSql = "SELECT `id`, `model` , `year` , `modelno` , `ex_modelno` FROM `cash_incentive_rules` WHERE status = 1";
    $result = $connect->query($checkSql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $cash_incentive_id = $row[0];
            $model = $row[1];
            $year = $row[2];
            $modelno = $row[3];
            $ex_modelno = explode(" ", trim($row[4]));

            // echo $id . ' ex_modelno ' . json_encode($row[3]) . ' - ' . '<br />';

            // generating query 
            $filter = array();
            foreach ($ex_modelno as $value) {
                $filter[] = "model_code != " . "'$value'";
            }

            // Generating Query
            $year1 = ($year === 'All') ? " year IS NOT NULL" : "'$year'";
            $modelno1 = ($modelno === 'All') ? " model_code IS NOT NULL AND (" . implode(' AND ', $filter) . " ) " : "'$modelno'";


            $sql = "SELECT id  FROM `manufature_price` WHERE model = '$model' AND year = " . $year1 . " 
            AND model_code = " . $modelno1 . " AND status = 1 ";

            // echo $sql;
            $mnpResult =  $connect->query($sql);
            if ($mnpResult && $mnpResult->num_rows > 0) {
                while ($row2 = $mnpResult->fetch_array()) {
                    $mnpId = $row2[0];

                    // echo $columnValue . ' - ' . $calculation . ' - ' . $num_to_calc . ' =  ' . $bdc .' <br /> ' ;

                    $updateSql = "UPDATE `manufature_price` SET `cash_incentive_rule_id` = '$cash_incentive_id' WHERE id = '$mnpId'";
                    $connect->query($updateSql);

                    $validate['success'] = "true";
                    $validate['messages'] = "successfully updated";
                }
            } else {
                $validate['success'] = "false";
                $validate['messages'] = mysqli_error($connect);
                // echo mysqli_error($connect);
            }
        }
    } else {
        $validate['success'] = "false";
        $validate['messages'] = "No BDC Rules Defined";
        // echo "No Rules Defined";
    }
    return json_encode($validate);
}


// -------------------------- Add Multiple Exculde Model No ------------------------------
// $modelnoArry = array("YF8H5NJNW", "YF8H0MKNW");
// $array_data = implode(" ", $modelnoArry);
// $array_data = ' ' . $array_data . ' ';
// echo $array_data . '<br />';
// $query = "INSERT INTO my_tbl_name (id, array_data) VALUES(NULL,'" . $array_data . "');";
// search data // YF8H0MKNW
// $query = "SELECT * FROM my_tbl_name WHERE array_data LIKE '% value %'";
// use explode() function to convert "array_data" string to array
// $array = explode("array_separator", $array_data);
