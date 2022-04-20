<?php

require_once 'db/core.php';

$id = $_POST['id'];
// $id = 4;

$sql = "SELECT `from_dealer`, `swap_status`, `stock_in`, `vehicle_in`, `color_in`, `inv_received`, `transferred_in`, `vin_in`, `inv_in`, `hb_in`, `msrp_in`, `hdag_in`, `adds_in`, `adds_in_notes`, `hbt_in`, `net_cost_in`, `stock_out`, `vehicle_out`, `color_out`, `inv_sent`, `transferred_out`, `vin_out`, `inv_out`, `hb_out`, `msrp_out`, `hdag_out`, `adds_out`, `adds_out_notes`, `hbt_out`, `net_cost_out`, `notes`, `sales_consultant` , users.username , locations.dealership 
FROM `swaps` LEFT JOIN locations ON locations.id = swaps.from_dealer LEFT JOIN users ON users.id = swaps.sales_consultant WHERE swaps.id = '$id'";

$result = $connect->query($sql);
$row = $result->fetch_array();
$connect->close();
if ($row) {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Print Swap Title</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&amp;family=Roboto+Mono&amp;display=swap" rel="stylesheet">
        <link href="<?php echo $GLOBALS['siteurl']; ?>/assets/build/styles/ltr-core.css" rel="stylesheet">
        <link href="<?php echo $GLOBALS['siteurl']; ?>/assets/build/styles/ltr-vendor.css" rel="stylesheet">

        <style>
            @media print {

                body,
                .theme-dark .form-control,
                ::placeholder {
                    color: #000 !important;
                    background-color: #fff !important;
                }
            }

            @page {
                margin: 10px;
            }

            @page :first {

                margin-top: 10px;
            }

            @page :left {

                margin-right: 10px;
            }

            @page :right {

                margin-left: 10px;
            }

            @media print {

                .col-md-1,
                .col-md-2,
                .col-md-3,
                .col-md-4,
                .col-md-5,
                .col-md-6,
                .col-md-7,
                .col-md-8,
                .col-md-9,
                .col-md-10,
                .col-md-11,
                .col-md-12 {
                    float: left;
                }

                .col-md-1 {
                    width: 8%;
                }

                .col-md-2 {
                    width: 16%;
                }

                .col-md-3 {
                    width: 25%;
                }

                .col-md-4 {
                    width: 33%;
                }

                .col-md-5 {
                    width: 42%;
                }

                .col-md-6 {
                    width: 50%;
                }

                .col-md-7 {
                    width: 58%;
                }

                .col-md-8 {
                    width: 66%;
                }

                .col-md-9 {
                    width: 75%;
                }

                .col-md-10 {
                    width: 83%;
                }

                .col-md-11 {
                    width: 92%;
                }

                .col-md-12 {
                    width: 100%;
                }
            }
        </style>

        <style>
            body {
                margin: 2em;
            }

            body,
            .theme-dark .form-control,
            ::placeholder {
                color: #000 !important;
                background-color: #fff !important;
            }

            .form-label {
                width: -webkit-fill-available;
                max-width: 30%;
            }

            .container1 {
                background-color: #bdbdbd82;
                padding: 20px;
                border-radius: 20px;
                margin: 10px;
                margin-bottom: 0px;
                padding-bottom: 0px;
            }

            .container2 {
                padding: 20px;
                margin: 10px;
                padding-top: 5px;
            }

            .container2 h3 {
                text-transform: uppercase;
                text-decoration: underline;
                font-weight: bolder;
                font-size: large;
                text-align: center;
                letter-spacing: 2.2px;
            }

            .form-control {
                background-color: white;
            }

            .notesDiv {
                position: relative;
                top: 2rem;
            }

            .custom-checkbox {
                display: inline-grid !important;
                margin: auto;
                align-items: center;
            }

            .list-group-item {
                padding: 5px 20px !important;
            }

            .list-group-item.rounded-0 h5 {
                text-transform: uppercase;
                text-decoration: underline;
                font-weight: bolder;
                font-size: large;
                letter-spacing: 2.2px;
            }

            .list-group-item.rounded-0 .custom-control-label {
                text-transform: uppercase;
                font-weight: 300;
                font-size: initial;
                letter-spacing: 1px;
            }

            .signature {
                border-top: 3px dashed black !important;
                margin-top: 5rem !important;
                margin-bottom: 0px !important;
            }

            .my-5 {
                border-top: 3px dashed black !important;
            }

            @media print {

                .custom-checkbox .custom-control-input:checked~.custom-control-label::after {
                    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3E%3C/svg%3E") !important;
                }

                .custom-control-label::after {
                    content: "";
                    position: absolute !important;
                    display: block !important;
                    width: 1.35rem !important;
                    height: 1.35rem !important;
                    /* background-repeat: no-repeat!important;
                    background-position: center!important;
                    background-size: 60%!important; */
                    left: -1.75rem !important;
                    top: 50% !important;
                    transform: translateY(-50%) !important;
                    border: 1px solid gray;
                    border-radius: 0px;
                }

                .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
                    background-color: #007bff !important;
                    opacity: 1 !important;

                    border: 1px solid !important;
                    border-radius: 5px !important;
                }

                .container1 {
                    background-color: #bdbdbd82 !important;
                    padding: 20px !important;
                    border-radius: 20px !important;
                    margin: 10px !important;
                    margin-bottom: 0px !important;
                    padding-bottom: 0px !important;
                }

                .container1 .form-label,.container1 .form-control,textarea,footer .h5 {
                    font-size: 15px !important;
                    font-family: sans-serif;
                    line-height: 20px;
                }
                footer .h5{
                    font-weight: 600!important;
                    font-size: large!important;
                    letter-spacing: 1px!important;
                }

                .form-control {
                    background-color: white !important;
                }

                .notesDiv {
                    position: relative !important;
                    bottom: 0.5rem !important;
                }

                .notesDiv textarea {
                    max-height: 2cm;
                    min-height: 2cm;
                }

                .list-group-item {
                    padding: 5px 20px !important;
                }

                .list-group-item.rounded-0 h5 {
                    text-transform: uppercase !important;
                    text-decoration: underline !important;
                    font-weight: bolder !important;
                    font-size: large !important;
                    letter-spacing: 2.2px !important;
                }

                .list-group-item.rounded-0 .custom-control-label {
                    text-transform: uppercase !important;
                    font-weight: 300 !important;
                    font-size: initial !important;
                    letter-spacing: 1px !important;
                }

                footer {
                    display: block;
                    position: fixed;
                    bottom: 0;
                    width: 100% !important;
                    margin: auto !important;
                    margin-bottom: 1cm !important;
                    text-align: center !important;
                }

                footer p {
                    text-align: center !important;
                }

                .signature {
                    border-top: 3px dashed black !important;
                    margin-top: 5rem !important;
                    margin-bottom: 0px !important;
                }

            }
        </style>
    </head>

    <body>
        <div class="container1">


            <div class="row mb-4">
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6 text-center">
                    <h3 class="h3">Vehicle In</h3>
                </div>
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6 text-center">
                    <h3 class="h3">Vehicle Out</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6 text-center">
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">From Dealer</label>
                        <label class="form-control" id="certified"> <?php echo $row['dealership']; ?> </label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Vehicle In</label>
                        <label class="form-control" id="certified"><?php echo $row['vehicle_in']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Color In</label>
                        <label class="form-control" id="certified"><?php echo $row['color_in']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Stock In</label>
                        <label class="form-control" id="certified"><?php echo $row['stock_in']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Invoice Received</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['inv_received'] == 'on') ? "YES" : "NO"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Transferred In</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['transferred_in'] == 'on') ? "YES" : "NO"; ?></label>
                    </div>
                </div>
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6 text-center">
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Sales Consultant</label>
                        <label class="form-control" id="certified"><?php echo $row['username']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Vehicle Out</label>
                        <label class="form-control" id="certified"><?php echo $row['vehicle_out']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Color Out</label>
                        <label class="form-control" id="certified"><?php echo $row['color_out']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Stock Out</label>
                        <label class="form-control" id="certified"><?php echo $row['stock_out']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Invoice Sent</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['inv_sent'] == 'on') ? "YES" : "NO"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Transferred Out</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['transferred_out'] == 'on') ? "YES" : "NO"; ?></label>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border: 0.1px solid #bdbdbd;">

            <div class="row">
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6 text-center">
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Vin In</label>
                        <label class="form-control" id="certified"><?php echo $row['vin_in'] ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Invoice In</label>
                        <label class="form-control" id="certified"><?php echo ($row['inv_in']) ? "$".number_format($row['inv_in'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Holdback In</label>
                        <label class="form-control" id="certified"><?php echo ($row['inv_in']) ? "$".number_format($row['hb_in'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">MSRP -dest In</label>
                        <label class="form-control" id="certified"><?php echo ($row['msrp_in']) ? "$".number_format($row['msrp_in'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Hdag In</label>
                        <label class="form-control" id="certified"><?php echo ($row['hdag_in']) ?  "$".number_format($row['hdag_in'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Adds In</label>
                        <label class="form-control" id="certified"><?php echo ($row['adds_in']) ?  "$".number_format($row['adds_in'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Adds In Notes</label>
                        <label class="form-control" id="certified"><?php echo $row['adds_in_notes']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">HBT</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['hbt_in']) ?  "$".number_format($row['hbt_in'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Net Cost In</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['net_cost_in']) ?  "$".number_format($row['net_cost_in'], 2, '.', ',') : "0"; ?></label>
                    </div>
                </div>

                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6 text-center">
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Vin Out</label>
                        <label class="form-control" id="certified"><?php echo $row['vin_out']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Invoice Out</label>
                        <label class="form-control" id="certified"><?php echo ($row['inv_out']) ?  "$".number_format($row['inv_out'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Holdback Out</label>
                        <label class="form-control" id="certified"><?php echo ($row['hb_out']) ?  "$".number_format($row['hb_out'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">MSRP -dest Out</label>
                        <label class="form-control" id="certified"><?php echo ($row['msrp_out']) ?  "$".number_format($row['msrp_out'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Hdag Out</label>
                        <label class="form-control" id="certified"><?php echo ($row['hdag_out']) ?  "$".number_format($row['hdag_out'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Adds Out</label>
                        <label class="form-control" id="certified"><?php echo ($row['adds_out']) ?  "$".number_format($row['adds_out'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Adds Out Notes</label>
                        <label class="form-control" id="certified"><?php echo $row['adds_out_notes']; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">HBT</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['hbt_out']) ?  "$".number_format($row['hbt_out'], 2, '.', ',') : "0"; ?></label>
                    </div>
                    <div class="d-flex align-items-center m-auto">
                        <label class="form-label" for="certified">Net Cost Out</label>
                        <label class="form-label m-auto" id="certified"><?php echo ($row['net_cost_out']) ?  "$".number_format($row['net_cost_out'], 2, '.', ',') : "0"; ?></label>
                    </div>
                </div>
            </div>
            <!-- <hr class="my-4" style="border: 0.1px solid #bdbdbd;"> -->

            <div class="form-row mt-3 justify-content-center notesDiv">
                <div class="col-md-10 form-group">
                    <textarea class="form-control autosize" name="dealNote" id="dealNote" placeholder="Notes..."><?php echo $row['notes']; ?></textarea>
                </div>
            </div>
        </div>
        <div class="container2">
            <h3 class="h3">Runners checklist</h3>
            <div class="row">
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item rounded-0">
                            <h5 class="h5">Peperwork</h5>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inSign" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="inSign">Co signed-notorized if not mass</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inMileage" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="inMileage">Mileage statement</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inSnV" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="inSnV">Check Signed & Verified</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inInv" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="inInv">Invoice</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inBill" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="inBill">Bill of Sale</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item rounded-0">
                            <h5 class="h5">Peperwork</h5>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outSign" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="outSign">Co signed-notorized if not mass</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outMileage" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="outMileage">Mileage statement</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outSnV" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="outSnV">Check Signed & Verified</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outInv" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="outInv">Invoice</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outBill" type="checkbox">
                                <label class="cursor-pointer custom-control-label" for="outBill">Bill of Sale</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item rounded-0">
                            <h5 class="h5">Vehicle</h5>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inRMP" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inRMP">Remove Plastic</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inDmg" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inDmg">Check for Damage</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inKeys" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inKeys">3 Keys Or 2 keyfobs (car with Start Button)</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inPDI" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inPDI">Check if vehicle is PDI</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inBooks" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inBooks">Books & PDI</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inRadio" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inRadio">Radio Code & Nav Code (if applicable)</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inCRV" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inCRV">Cargo cover - CRV only Except LX</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="InFlrMts" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="InFlrMts">Floormats</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="inWheel" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="inWheel">Key to wheel looks (if applicable)</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item rounded-0">
                            <h5 class="h5">Vehicle</h5>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outRMP" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outRMP">Remove Plastic</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outDmg" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outDmg">Check for Damage</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outKeys" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outKeys">3 Keys Or 2 keyfobs (car with Start Button)</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outPDI" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outPDI">Check if vehicle is PDI</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outBooks" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outBooks">Books & PDI</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outRadio" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outRadio">Radio Code & Nav Code (if applicable)</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outCRV" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outCRV">Cargo cover - CRV only Except LX</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outFlrMts" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outFlrMts">Floormats</label>
                            </div>
                        </li>
                        <li class="list-group-item rounded-0">
                            <div class="custom-control-lg custom-checkbox position-static">
                                <input class="custom-control-input" id="outWheel" type="checkbox">
                                <label class="cursor-pointer  custom-control-label" for="outWheel">Key to wheel looks (if applicable)</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-ms-6 col-sm-6 col-md-6">
                                <p class="h5">Runners Signature</p>
                            </div>
                            <div class="col-xs-12 col-ms-6 col-sm-6 col-md-6">
                                <p class="h5">Date</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-ms-6 col-sm-6 col-md-6">
                                <hr class="my-5 signature">
                            </div>
                            <div class="col-xs-12 col-ms-6 col-sm-6 col-md-6">
                                <hr class="my-5 signature">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                                <p class="h5">Runners Signature</p>
                            </div>
                            <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                                <p class="h5">Date</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                                <hr class="my-5 signature">
                            </div>
                            <div class="col-xs-12 col-ms-3 col-sm-6 col-md-6">
                                <hr class="my-5 signature">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/assets/build/scripts/mandatory.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/assets/build/scripts/core.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['siteurl']; ?>/assets/build/scripts/vendor1.js"></script>
        <script>
            autosize($(".autosize"));
        </script>
    </body>

    </html>
<?php
}
?>