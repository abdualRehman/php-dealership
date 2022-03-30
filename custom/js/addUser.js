"use strict";
$(function () {
    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        // I use element.value instead value here, value parameter was always null
        return arg != element.value;
    }, "Value must not equal arg.");

    $("#addUserForm").validate({
        rules: {
            username: {
                required: !0,

            },
            email: {
                required: !0,
                email: !0
            },
            password: {
                required: !0,
                minlength: 6
            },
            conpassword: {
                required: !0,
                minlength: 6,
                equalTo: "#password"
            },
            role: {
                valueNotEquals: "0"
            },
        },
        messages: {
            username: {
                required: "Please enter username",
            },
            email: {
                required: "Please enter your email",
                email: "Your email is not valid"
            },
            password: {
                required: "Please provide your password",
                minlength: $.validator.format("Please enter at least {0} characters")
            },
            conpassword: {
                required: "Please reenter your password",
                minlength: $.validator.format("Please enter at least {0} characters"),
                equalTo: $.validator.format("Password is not Match"),
            },
            role: {
                valueNotEquals: "Please select role",
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var form = $('#addUserForm');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log(response);

                    if (response.success == true) {
                        $('#add-messages').html('<div class="alert alert-label-success mb-0 fade show">' +
                            '<div class="alert-icon"> <i class="fa fa-check"></i> </div>' +
                            '<div class="alert-content">' + response.messages + '</div>' +
                            '<button type="button" class="btn btn-text-danger btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>' +
                            '</div>');
                        form[0].reset();
                        $(".alert-label-success").delay(500).show(10, function () {
                            $(this).delay(3000).hide(10, function () {
                                $(this).remove();
                            });
                        }); // /.alert
                    } else {
                        $('#add-messages').html('<div class="alert alert-label-danger mb-0 fade show">' +
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