<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';

if ($_GET['r'] == 'man') {
    echo "<div class='div-request d-none'>man</div>";
} else if ($_GET['r'] == 'add') {
    echo "<div class='div-request d-none'>add</div>";
} // /else manage order

?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>

<style>
    #showDetails .h5 {
        text-align: center;
        text-decoration: underline;
    }

    .firstRow {
        border: 1px solid #bdbdbd;
        border-radius: 15px;
        margin-bottom: 10px;
    }

    #showDetails .well {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        flex-wrap: nowrap;
    }

    #showDetails .well p {
        margin-bottom: 2px;
        /* color: #424248; */
        font-weight: 700;
    }

    #datatable-1 tbody tr{
        font-size: 14px;
    }
    #dealer,
    #other,
    #lease {
        font-weight: 700;
    }


    #datatable-1 tbody tr>:nth-child(5)::first-letter,
    #datatable-1 tbody tr>:nth-child(6)::first-letter,
    #datatable-1 tbody tr>:nth-child(7)::first-letter,
    #datatable-1 tbody tr>:nth-child(8)::first-letter,
    #datatable-1 tbody tr>:nth-child(9)::first-letter,
    #net::first-letter,
    #hb::first-letter,
    #invoice::first-letter,
    #msrp::first-letter,
    #bdc::first-letter,
    #dealer::first-letter,
    #other::first-letter,
    #lease::first-letter
    {
        font-size: medium;
    }

    /* buttons groups */
    #modal label,
    #year label {
        margin: auto 1px;
        border: 1px solid #bdbdbd;
    }

    #modal label {
        margin-top: 5px;
    }

    #modal {
        text-align: center;
    }

    #year {
        margin-top: 5px;
        text-align: center;
    }

    /* table css */
   
</style>

<?php

if ($_GET['r'] == 'man') {

?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <div class="row w-100 p-0">
                                <div class="col-md-2 d-flex align-items-center">
                                    <h3 class="portlet-title">Manage Matrix</h3>
                                </div>
                                <div class="col-md-7">
                                    <div class="row d-flex justify-content-center">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="modal">
                                                    <div class="btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts=""> ALL
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="Accord,Accord Hybrid,ACCORD 4DR"> Accord
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="Civic,CIVIC HATCH,CIVIC SEDAN,Civic SI,CIVIC 5DR"> Civic
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="CR-V,CR-V Hybrid,CRV,CLARITY PLUG-IN HYBRID"> CR-V
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="HR-V,HRV"> HRV
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="INSIGHT"> INSIGHT
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="ODYSSEY"> ODYSSEY
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="Passport,Passport  Package,Passport Package"> Passport
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="Pilot,PILOT"> Pilot
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="mod" data-texts="Ridgeline,Ridgeline Packages,RIDGELINE"> Ridgeline
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="year">
                                                    <div class="btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="year" value=""> ALL
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="year" value="2021"> 2021
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="year" value="2022"> 2022
                                                        </label>
                                                        <label class="btn btn-outline-primary">
                                                            <input type="radio" name="year" value="2023"> 2023
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 p-1 d-flex justify-content-center align-items-center">
                                    <div class="d-inline-flex">
                                        <button class="btn btn-info mr-2 p-2" onclick="toggleFilterClass()">
                                            <i class="fa fa-align-center ml-1 mr-2"></i> Filter
                                        </button>
                                        <!-- <a href="<?php // echo $GLOBALS['siteurl']; 
                                                        ?>/matrix/manMatrix.php?r=add" class="btn btn-primary mr-2 p-2">
                                                <i class="fa fa-plus ml-1 mr-2"></i> Import New File
                                            </a> -->
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/manufaturePrice.php?r=man" class="btn btn-success mr-2 p-2">
                                            <i class="fa fa-plus ml-1"></i> See Manufacture Price
                                        </a>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="portlet-body">

                            <table id="datatable-1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Year</th>
                                        <th style="width: 10%;">Model</th>
                                        <th style="width: 15%;">Trim</th>
                                        <th style="width: 10%;">Model No</th>
                                        <th style="width: 10%;">Net</th>
                                        <th style="width: 10%;">HB</th>
                                        <th style="width: 10%;">Invoice</th>
                                        <th style="width: 10%;">MSRP</th>
                                        <th style="width: 10%;">BDC</th>
                                        <th style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showDetails">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bordered">
                    <h5 class="modal-title">Matrix Details</h5>
                    <button type="button" class="btn btn-label-danger btn-icon" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="showResult d-none">
                        <div class="form-row text-center align-items-center firstRow">
                            <div class="col-md-8">
                                <!-- <h3 id="title" class="h3 text-primary">2022 Honda Accord Sadan Support 1.5T CVIF3NEW</h3> -->
                                <h3 id="title" class="h3 text-primary"></h3>
                            </div>
                            <div class="col-md-4 mt-3 mb-3">
                                <h5 class="h5 text-primary">COST</h5>
                                <div class="details">
                                    <div class="well">
                                        <p>NET</p>
                                        <p id="net"></p>
                                    </div>
                                    <div class="well">
                                        <p>HB</p>
                                        <p id="hb"></p>
                                    </div>
                                    <div class="well">
                                        <p>INVOICE</p>
                                        <p id="invoice"></p>
                                    </div>
                                    <div class="well">
                                        <p>MSRP</p>
                                        <p id="msrp"></p>
                                    </div>
                                    <div class="well">
                                        <p>BDC</p>
                                        <p id="bdc"></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 text-center">
                                <!-- <label for="dealer">Dealer</label> -->
                                <h5 class="h5 text-primary">Dealer</h5>
                                <p id="dealer"></p>
                            </div>
                            <div class="col-md-4 text-center">
                                <h5 class="h5 text-primary">Other</h5>
                                <p id="other"></p>
                            </div>
                            <div class="col-md-4 text-center">
                                <h5 class="h5 text-primary">Lease</h5>
                                <p id="lease"></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">
                                <h5 class="h5 text-primary">FINANCE RATES 660+</h5>
                                <div class="well">
                                    <p>24-36 Months <strong id="f_24_36"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>37-48 Months <strong id="f_37_48"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>49-60 Months <strong id="f_49_60"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>61-72 Months <strong id="f_61_72"></strong> </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="h5 text-primary">FINANCE RATES 610-659</h5>
                                <div class="well">
                                    <p>24-36 Months <strong id="f_610_24_36"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>37-60 Months <strong id="f_610_37_60"></strong> </p>
                                </div>

                                <div class="well">
                                    <p>61-72 Months <strong id="f_610_61_72"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>Expire In <strong id="f_expire"></strong> </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="h5 text-primary">LEASE RATES 660+</h5>
                                <div class="well">
                                    <p>One Pay <strong id="l_onePay"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>24-36 Months <strong id="l_24_36"></strong> </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="h5 text-primary">LEASE RATES 610-659</h5>
                                <div class="well">
                                    <p>One Pay <strong id="l_610_onePay"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>24-36 Months <strong id="l_610_24_36"></strong> </p>
                                </div>
                                <div class="well">
                                    <p>Expire In <strong id="l_expire"></strong> </p>
                                </div>
                            </div>
                        </div>
                        <h3 class="h3 text-center text-primary m-3"><strong>RESIDUALS</strong></h3>
                        <div class="container">


                            <div class="form-row">
                                <div class="col-md-4">
                                    <h5 class="h5 text-primary">10,000 Miles Per Year</h5>
                                    <div class="well">
                                        <p>24 - <strong id="10_24"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>27 - <strong id="10_27"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>30 - <strong id="10_30"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>33 - <strong id="10_33"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>36 - <strong id="10_36"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>39 - <strong id="10_39"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>42 - <strong id="10_42"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>45 - <strong id="10_45"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>48 - <strong id="10_48"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>51 - <strong id="10_51"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>54 - <strong id="10_54"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>57 - <strong id="10_57"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>60 - <strong id="10_60"></strong> </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="h5 text-primary">12,000 Miles Per Year</h5>
                                    <div class="well">
                                        <p>24 - <strong id="12_24"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>27 - <strong id="12_27"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>30 - <strong id="12_30"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>33 - <strong id="12_33"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>36 - <strong id="12_36"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>39 - <strong id="12_39"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>42 - <strong id="12_42"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>45 - <strong id="12_45"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>48 - <strong id="12_48"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>51 - <strong id="12_51"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>54 - <strong id="12_54"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>57 - <strong id="12_57"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>60 - <strong id="12_60"></strong> </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="h5 text-primary">15,000 Miles Per Year</h5>
                                    <div class="well">
                                        <p>24 - <strong id="15_24"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>27 - <strong id="15_27"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>30 - <strong id="15_30"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>33 - <strong id="15_33"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>36 - <strong id="15_36"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>39 - <strong id="15_39"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>42 - <strong id="15_42"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>45 - <strong id="15_45"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>48 - <strong id="15_48"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>51 - <strong id="15_51"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>54 - <strong id="15_54"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>57 - <strong id="15_57"></strong> </p>
                                    </div>
                                    <div class="well">
                                        <p>60 - <strong id="15_60"></strong> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-bordered">
                    <button class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<?php
}

// else if ($_GET['r'] == 'add') {
?>


<!-- <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="portlet">
                        <div class="portlet-header portlet-header-bordered">
                            <h3 class="portlet-title">Add Matrix Data</h3>
                            <a href="<?php // echo $GLOBALS['siteurl']; 
                                        ?>/matrix/manMatrix.php?r=man" class="btn btn-info">
                                <i class="fa fa-arrow-right"></i>
                                Manage</a>
                        </div>
                        <div class="portlet-body">

                            <div class="form-row p-3">
                                <div class="col-md-3">
                                    <p class="h4">Import Excel File here!</p>
                                </div>
                            </div>
                            <form id="importMatrixForm" autocomplete="off" method="post" action="../php_action/importMatrix.php" enctype="multipart/form-data">
                                <div class="form-row pl-3">
                                    <div class="form-group mb-0">
                                        <input type="file" class="form-control-file" id="excelFile" name="excelFile" />
                                    </div>

                                    <div class="col-md-6">
                                        <button class="btn btn-primary" type="submit">Submit form</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <div class="row p-3 d-none" id="errorDiv">
                                <div class="col-md-12 mb-3">
                                    <button class="btn btn-danger float-right" onclick="clearErrorsList()">Clear Logs</button>
                                </div>
                                <div class="col-md-12">
                                    <div class="list-group list-group-action" id="errorList">

                                    </div>
                                </div>
                            </div>

                            <div class="row p-3">
                                <div class="col-md-12">
                                    <div class="alert alert-outline-info fade show mb-0">
                                        <div class="alert-icon"><i class="fa fa-info"></i></div>
                                        
                                        <div class="alert-content">
                                            <h4 class="alert-heading">Please Note!</h4>
                                            <a href="./MATRIX.xlsx" download class="btn btn-success float-right" >Download Format File</a>
                                            <p>The following Excel File column sequence should match the image below.</p>
                                            <code>"Year." "Model" "Trim" "Model #" "NET" , "HP" ...</code>
                                            <hr>
                                            <p class="mb-0">
                                                <img src="excelformat.PNG" alt="format" class="img card-img-top">
                                            </p>
                                        </div>
                                        <button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                        
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


<?php
// }
?>




<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/manageMatrix.js"></script>