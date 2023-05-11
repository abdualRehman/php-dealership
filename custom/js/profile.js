"use strict";
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})


$(function () {

    $("#updateDetails").validate({
        rules: {

            username: {
                required: !0,
            },
            email: {
                required: !0,
                email: !0
            },
        },
        messages: {
            email: {
                required: "Please enter your email",
                email: "Your email is not valid"
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var form = $('#updateDetails');
            var fd = new FormData(document.getElementById("updateDetails"));
            fd.append("formType", "updateDetails");
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                // data: form.serialize(),
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    if (response.success == true) {
                        e1.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500,
                        })


                    } else {
                        e1.fire({
                            position: "top-end",
                            icon: "error",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500
                        })
                    }
                }
            });
            return false;
        }
    });
    $("#updatePasswords").validate({
        rules: {
            password: {
                required: !0,
                minlength: 6
            },
            npassword: {
                required: !0,
                minlength: 6
            },
            conpassword: {
                required: !0,
                minlength: 6,
                equalTo: "#npassword"
            },
        },
        messages: {
            password: {
                required: "Please provide your password",
                minlength: $.validator.format("Please enter at least {0} characters")
            },
            npassword: {
                required: "Please provide your password",
                minlength: $.validator.format("Please enter at least {0} characters")
            },
            conpassword: {
                required: "Please reenter your password",
                minlength: $.validator.format("Please enter at least {0} characters"),
                equalTo: $.validator.format("Password is not Match"),
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var form = $('#updatePasswords');
            var fd = new FormData(document.getElementById("updatePasswords"));
            fd.append("formType", "updatePasswords");
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                // data: form.serialize(),
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    if (response.success == true) {
                        e1.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500,
                        })


                    } else {
                        e1.fire({
                            position: "top-end",
                            icon: "error",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500
                        })
                    }
                }
            });
            return false;
        }
    });
    $("#updateProfile").validate({

        submitHandler: function (form, e) {
            e.preventDefault();
            var form = $('#updateProfile');
            var fd = new FormData(document.getElementById("updateProfile"));
            fd.append("formType", "updateProfile");
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success == true) {
                        e1.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500,
                        })


                    } else {
                        e1.fire({
                            position: "top-end",
                            icon: "error",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500
                        })
                    }
                }
            });
            return false;
        }
    });

    $.validator.addMethod("usPhoneFormat", function (value, element) {
        return this.optional(element) || /^\d{3}-\d{3}-\d{4}$/.test(value);
    }, "Please enter a valid US phone number in the format XXX-XXX-XXXX.");

    $("#otpDetails").validate({
        rules: {
            mobile: {
                usPhoneFormat: true,
            },
        },
        messages: {
            email: {
                usPhoneFormat: "Please enter a valid US phone number in the format XXX-XXX-XXXX."
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            var form = $('#otpDetails');
            var fd = new FormData(document.getElementById("otpDetails"));
            fd.append("formType", "changeOTP");
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                // data: form.serialize(),
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    if (response.success == true) {
                        e1.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500,
                        })


                    } else {
                        e1.fire({
                            position: "top-end",
                            icon: "error",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500
                        })
                    }
                }
            });
            return false;
        }
    });
});