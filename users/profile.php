<?php
include_once '../php_action/db/core.php';
include_once '../includes/header.php';
?>

<style>
    /*Profile Pic Start*/
    .picture-container {
        position: relative;
        text-align: center;
    }

    .picture {
        width: 150px;
        height: 150px;
        background-color: #999999;
        border: 4px solid #CCCCCC;
        color: #FFFFFF;
        border-radius: 50%;
        margin: 0px auto;
        overflow: hidden;
        transition: all 0.2s;
        -webkit-transition: all 0.2s;
        margin-bottom: 2rem;
    }

    .picture:hover {
        border-color: #2ca8ff;
    }

    .content.ct-wizard-green .picture:hover {
        border-color: #05ae0e;
    }

    .content.ct-wizard-blue .picture:hover {
        border-color: #3472f7;
    }

    .content.ct-wizard-orange .picture:hover {
        border-color: #ff9500;
    }

    .content.ct-wizard-red .picture:hover {
        border-color: #ff3b30;
    }

    .picture input[type="file"] {
        /* cursor: pointer;
        display: block;
        height: 100%;
        left: 0;
        opacity: 0 !important;
        position: absolute;
        top: 0;
        width: 100%; */
        cursor: pointer;
        display: block;
        height: 70%;
        left: 15%;
        opacity: 0 !important;
        position: absolute;
        top: 0;
        width: 70%;
        text-align: center;
        margin: auto;
    }

    .picture-src {
        width: 100%;
        height: inherit;
    }

    /*Profile Pic End*/
</style>

<?php

$id = $_SESSION['userId'];
$mainSql = "SELECT users.* FROM users WHERE users.id = '$id'";
$mainResult = $connect->query($mainSql);

if ($mainResult->num_rows == 1) {
    $value = $mainResult->fetch_assoc();

    $userName = $value['username'];
    $userEmail = $value['email'];
    $userProfile = $value['profile'];
}

?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="portlet">
                    <div class="portlet-header portlet-header-bordered">
                        <h3 class="portlet-title">Edit Profile</h3>
                    </div>
                    <div class="portlet-body">

                        <div class="form-row d-flex flex-md-row flex-column-reverse">

                            <div class="col-md-8">
                                <div class="card text-center m-3">
                                    <form id="updateDetails" autocomplete="off" method="post" action="../php_action/updateProfile.php">
                                        <div class="card-body">
                                            <h3 class="card-title1">Personal Details</h3>
                                            <div class="row justify-content-center">
                                                <div class="form-group form-group col-sm-10" id="add-messages">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="username" class="col-sm-3 offset-sm-1 col-form-label text-right">User Name</label>
                                                <div class="form-group col-sm-6">
                                                    <input type="text" class="form-control" value="<?php echo $userName; ?>" name="username" id="username" autocomplete="false" autofill="off" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="email" class="col-sm-3 offset-sm-1 col-form-label text-right">Email</label>
                                                <div class="form-group col-sm-6">
                                                    <input type="email" class="form-control" value="<?php echo $userEmail; ?>" name="email" id="email" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6 m-auto d-flex justify-content-around mb-0">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card text-center m-3">
                                    <div class="card-body">
                                        <form id="updatePasswords" autocomplete="off" method="post" action="../php_action/updateProfile.php">
                                            <h3 class="card-title1">Password Details</h3>
                                            <div class="row"><label for="password" class="col-sm-3 offset-sm-1 col-form-label text-right">Current Password</label>
                                                <div class="form-group col-sm-6"><input type="password" class="form-control" name="password" id="password" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="row"><label for="npassword" class="col-sm-3 offset-sm-1 col-form-label text-right">New Password</label>
                                                <div class="form-group col-sm-6"><input type="password" class="form-control" name="npassword" id="npassword" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="row"><label for="conpassword" class="col-sm-3 offset-sm-1 col-form-label text-right">Confirm Password</label>
                                                <div class="form-group col-sm-6"><input type="password" class="form-control" name="conpassword" id="conpassword" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6 m-auto d-flex justify-content-around mb-0">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 pb-3">
                                <div class="card text-center m-3" style="height:97%;">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <h3 class="card-title1">Upload Profile</h3>
                                        <form id="updateProfile" autocomplete="off" method="post" action="../php_action/updateProfile.php" enctype="multipart/form-data">
                                            <div class="container">
                                                <div class="picture-container">
                                                    <div class="picture">

                                                        <?php
                                                        if (!isset($userProfile) || $userProfile == '') {
                                                            echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/default.jpg" class="picture-src" id="wizardPicturePreview" title="">';
                                                            echo '<input type="hidden" name="uploadedImg" id="uploadedImg" value="default.jpg" />';
                                                        } else {
                                                            echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/' . $userProfile . '" class="picture-src" id="wizardPicturePreview" title="">';
                                                            echo '<input type="hidden" name="uploadedImg" id="uploadedImg" value="' . $userProfile . '" />';
                                                        }
                                                        ?>
                                                        <input type="file" id="wizard-picture" name="wizard-picture" class="" accept="image/*" data-type='image' />
                                                    </div>
                                                    <h6 class="">Choose Picture</h6>
                                                    <div class="row">
                                                        <div class="form-group col-sm-6 m-auto d-flex justify-content-around mb-0">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary" onclick="removeProfile()">Remove</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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


<?php require_once('../includes/footer.php') ?>
<script type="text/javascript" src="../custom/js/profile.js"></script>

<script>
    $(document).ready(function() {
        // Prepare the preview for profile picture
        $("#wizard-picture").change(function() {
            // readURL(this);
            if (this.files[0].type.split('/')[0] == 'image') {
                readURL(this);
                // console.log(this.files[0].type);
            } else {
                removeProfile();
                return false;
            }
        });
    });

    // $(":file").on("change", function(e) {
    //     if (this.files[0].type.split('/')[0] == 'image') {
    //         readURL(this);
    //         // console.log(this.files[0].type);
    //     } else {
    //         removeProfile();
    //         // return false;
    //     }
    // })

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeProfile(siteURL = '<?php echo $GLOBALS['siteurl']; ?>') {
        var defaultURL = siteURL + "/assets/profiles/default.jpg";
        $('#wizardPicturePreview').attr('src', defaultURL).fadeIn('slow');
        $('#uploadedImg').val('default.jpg')
    }
</script>