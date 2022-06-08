<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';
?>

<head>
    <link rel="stylesheet" href="../custom/css/customDatatable.css">
</head>

<style>
    .font-large {
        font-size: large;
    }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Matrix Files</h3>
                    </div>
                    <?php
                    $sql = "SELECT `file_type` , `file_name` FROM `settings`";
                    $result = $connect->query($sql);
                    // $result = array();
                    // $result = $resultData->fetch_array()
                    ?>
                    <div class="portlet-body">
                        <div class="rich-list rich-list-bordered">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="rich-list-item m-2">
                                        <div class="rich-list-content w-100">
                                            <div class="rich-list-prepend text-center m-2">
                                                <div class="avatar">
                                                    <div class="avatar-display">
                                                        <i class="fa fa-file"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center m-auto w-100">
                                                <h5 class="rich-list-title1 font-large text-center">Standard Retail Rates</h5>
                                                <div class="rich-list-subtitle text-center">
                                                    Please upload your documents only in 'pdf' format
                                                </div>
                                                <form class="mt-4 mb-2" id="form" autocomplete="off" method="post" action="../php_action/importMatrixFiles.php" enctype="multipart/form-data">
                                                    <input type="hidden" name="fileName" value="retailRates">

                                                    <?php
                                                    while ($row = $result->fetch_array()) {
                                                        $fileType = $row[0];
                                                        $fileName = $row[1];
                                                        if ($fileType == 'retailRates' && $fileName == '') {
                                                    ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <div class="form-group">
                                                                        <input type="file" name="retailRate" id="retailRate" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </div>
                                                        <?php

                                                        } else if ($fileType == 'retailRates' && $fileName != '') {

                                                        ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <a href="http://docs.google.com/gview?url=<?php echo $GLOBALS['siteurl']; ?>/assets/uploadMatrixRateFiles/<?php echo $fileName; ?>&embedded=true" target="_blank" class="btn btn-primary">
                                                                        <i class="fa fa-eye"></i> View
                                                                    </a>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="button" onclick="removeFile('<?php echo $fileType; ?>' , '<?php echo $fileName; ?>')" class="btn btn-danger"> <i class="fa fa-times"></i> Delete</button>
                                                                </div>
                                                            </div>

                                                    <?php

                                                        }
                                                    }

                                                    ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="rich-list-item m-2">
                                        <div class="rich-list-content w-100">
                                            <div class="rich-list-prepend text-center m-2">
                                                <div class="avatar">
                                                    <div class="avatar-display">
                                                        <i class="fa fa-file"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center m-auto w-100">
                                                <h4 class="rich-list-title1 font-large text-center">Programs</h4>
                                                <div class="rich-list-subtitle text-center">
                                                    Please upload your documents only in 'pdf' format
                                                </div>
                                                <form class="mt-4 mb-2" id="form1" autocomplete="off" method="post" action="../php_action/importMatrixFiles.php" enctype="multipart/form-data">
                                                    <input type="hidden" name="fileName" value="programs">
                                                    <?php
                                                    mysqli_data_seek($result, 0);
                                                    while ($row = $result->fetch_array()) {
                                                        $fileType = $row[0];
                                                        $fileName = $row[1];
                                                        if ($fileType == 'programs' &&  $fileName == '') {
                                                    ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <div class="form-group">
                                                                        <input type="file" name="retailRate" id="retailRate" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        } else if ($fileType == 'programs' && $fileName != '') {
                                                        ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <a href="http://docs.google.com/gview?url=<?php echo $GLOBALS['siteurl']; ?>/assets/uploadMatrixRateFiles/<?php echo $fileName; ?>&embedded=true" target="_blank" class="btn btn-primary">
                                                                        <i class="fa fa-eye"></i> View
                                                                    </a>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="button" onclick="removeFile('<?php echo $fileType; ?>' , '<?php echo $fileName; ?>')" class="btn btn-danger"> <i class="fa fa-times"></i> Delete</button>
                                                                </div>
                                                            </div>
                                                    <?php

                                                        }
                                                    }

                                                    ?>


                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="rich-list-item m-2">
                                        <div class="rich-list-content w-100">
                                            <div class="rich-list-prepend text-center m-2">
                                                <div class="avatar">
                                                    <div class="avatar-display">
                                                        <i class="fa fa-file"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center m-auto w-100">
                                                <h4 class="rich-list-title1 font-large text-center">Programs Incentives Summary</h4>
                                                <div class="rich-list-subtitle text-center">
                                                    Please upload your documents only in 'pdf' format
                                                </div>
                                                <form class="mt-4 mb-2" id="form2" autocomplete="off" method="post" action="../php_action/importMatrixFiles.php" enctype="multipart/form-data">
                                                    <input type="hidden" name="fileName" value="incentivesSummary">
                                                    <?php
                                                    mysqli_data_seek($result, 0);
                                                    while ($row = $result->fetch_array()) {
                                                        $fileType = $row[0];
                                                        $fileName = $row[1];
                                                        if ($fileType == 'incentivesSummary' &&  $fileName == '') {
                                                    ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <div class="form-group">
                                                                        <input type="file" name="retailRate" id="retailRate" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        } else if ($fileType == 'incentivesSummary' && $fileName != '') {
                                                        ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <a href="http://docs.google.com/gview?url=<?php echo $GLOBALS['siteurl']; ?>/assets/uploadMatrixRateFiles/<?php echo $fileName; ?>&embedded=true" target="_blank" class="btn btn-primary">
                                                                        <i class="fa fa-eye"></i> View
                                                                    </a>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="button" onclick="removeFile('<?php echo $fileType; ?>' , '<?php echo $fileName; ?>' )" class="btn btn-danger"> <i class="fa fa-times"></i> Delete</button>
                                                                </div>
                                                            </div>
                                                    <?php

                                                        }
                                                    }

                                                    ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="rich-list-item m-2">
                                        <div class="rich-list-content w-100">
                                            <div class="rich-list-prepend text-center m-2">
                                                <div class="avatar">
                                                    <div class="avatar-display">
                                                        <i class="fa fa-file"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center m-auto w-100">
                                                <h4 class="rich-list-title1 font-large text-center">Standard Lease Rates</h4>
                                                <div class="rich-list-subtitle text-center">
                                                    Please upload your documents only in 'pdf' format
                                                </div>
                                                <form class="mt-4 mb-2" id="form3" autocomplete="off" method="post" action="../php_action/importMatrixFiles.php" enctype="multipart/form-data">
                                                    <input type="hidden" name="fileName" value="leaseRate">
                                                    <?php
                                                    mysqli_data_seek($result, 0);
                                                    while ($row = $result->fetch_array()) {
                                                        $fileType = $row[0];
                                                        $fileName = $row[1];
                                                        if ($fileType == 'leaseRate' &&  $fileName == '') {
                                                    ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <div class="form-group">
                                                                        <input type="file" name="retailRate" id="retailRate" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        } else if ($fileType == 'leaseRate' && $fileName != '') {
                                                        ?>
                                                            <div class="form-row align-items-center justify-content-center">
                                                                <div class="col-auto">
                                                                    <a href="http://docs.google.com/gview?url=<?php echo $GLOBALS['siteurl']; ?>/assets/uploadMatrixRateFiles/<?php echo $fileName; ?>&embedded=true" target="_blank" class="btn btn-primary">
                                                                        <i class="fa fa-eye"></i> View
                                                                    </a>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="button" onclick="removeFile('<?php echo $fileType; ?>' , '<?php echo $fileName; ?>')" class="btn btn-danger"> <i class="fa fa-times"></i> Delete</button>
                                                                </div>
                                                            </div>
                                                    <?php

                                                        }
                                                    }

                                                    ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/matrixFiles.js"></script>