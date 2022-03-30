"use strict";
var manageUserTable
$(function () {


    manageUserTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchUsers.php',
        // data: [],
    })






    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        // I use element.value instead value here, value parameter was always null
        return arg != element.value;
    }, "Value must not equal arg.");

    $("#editUserForm").validate({
        rules: {
            editusername: {
                required: !0,

            },
            editemail: {
                required: !0,
                email: !0
            },
            editpassword: {
                required: !0,
                minlength: 6
            },
            editconpassword: {
                required: !0,
                minlength: 6,
                equalTo: "#editpassword"
            },
            editrole: {
                valueNotEquals: "0"
            },
        },
        messages: {
            editusername: {
                required: "Please enter username",
            },
            editemail: {
                required: "Please enter your email",
                email: "Your email is not valid"
            },
            editpassword: {
                required: "Please provide your password",
                minlength: $.validator.format("Please enter at least {0} characters")
            },
            editconpassword: {
                required: "Please reenter your password",
                minlength: $.validator.format("Please enter at least {0} characters"),
                equalTo: $.validator.format("Password is not Match"),
            },
            editrole: {
                valueNotEquals: "Please select role",
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var form = $('#editUserForm');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log(response);

                    if (response.success == true) {
                        $('#edit-messages').html('<div class="alert alert-label-success mb-0 fade show">' +
                            '<div class="alert-icon"> <i class="fa fa-check"></i> </div>' +
                            '<div class="alert-content">' + response.messages + '</div>' +
                            '<button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>' +
                            '</div>');
                        manageUserTable.ajax.reload(null, false);

                        $(".alert-label-success").delay(500).show(10, function () {
                            $(this).delay(3000).hide(10, function () {
                                $(this).remove();
                            });
                        }); // /.alert
                    } else {
                        $('#edit-messages').html('<div class="alert alert-label-danger mb-0 fade show">' +
                            '<div class="alert-icon"> <i class="fa fa-exclamation"></i> </div>' +
                            '<div class="alert-content">' + response.messages + '</div>' +
                            '<button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>' +
                            '</div>');

                        $(".alert-label-danger").delay(500).show(10, function () {
                            $(this).delay(3000).hide(10, function () {
                                $(this).remove();
                            });
                        }); // /.alert
                    }


                }
            });
            return false;

        }
    })


});

function editUser(userId = null) {
    if (userId) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.editUser-result').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedUser.php',
            type: 'post',
            data: { userId: userId },
            dataType: 'json',
            success: function (response) {

                console.log(response);
                // modal loading
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.editUser-result').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                // setting the brand name value 
                $('#editusername').val(response.username);
                // setting the brand status value
                $('#editemail').val(response.email);
                $('#editrole').val(response.role);
                $('#editpassword').val('');
                $('#editconpassword').val('');
                // brand id 
                $(".editUser-result").after('<input type="hidden" name="userId" id="userId" value="' + userId + '" />');


            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }
}
function removeUser(userId = null) {
    if (userId) {
        $('.removeUserFooter').after('<input type="hidden" name="userId" id="userId" value="' + userId + '" /> ');

        $("#removeUserBtn").unbind('click').bind('click', function () {
            // button loading
            $("#removeUserBtn").addClass('disabled');
            $("#removeUserBtn").html('Loading...');
            $.ajax({
                url: '../php_action/removeUser.php',
                type: 'post',
                data: { userId: userId },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    // button loading
                    $("#removeUserBtn").button('reset');
                    $("#removeUserBtn").removeClass('disabled');
                    $("#removeUserBtn").html('Confirm');
                    if (response.success == true) {
                        //     // hide the remove modal 
                        $('#modal7').modal('hide');
                        //     // reload the brand table 
                        manageUserTable.ajax.reload(null, false);

                        $('.remove-messages').html('<div class="alert alert-label-success mb-0 fade show">' +
                            '<div class="alert-icon"> <i class="fa fa-check"></i> </div>' +
                            '<div class="alert-content">' + response.messages + '</div>' +
                            '<button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>' +
                            '</div>');

                        $(".alert-label-success").delay(500).show(10, function () {
                            $(this).delay(3000).hide(10, function () {
                                $(this).remove();
                            });
                        }); 
                    } // /response messages
                }
            }); // /ajax function to remove the brand

        }); // /click on remove button to remove the brand
    }
}