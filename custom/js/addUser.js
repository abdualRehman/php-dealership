"use strict";
$(function () {

    // $('.timeInterval').timepicker({ 'timeFormat': 'H:i p', 'showDuration': true });
    $('.timeInterval').timepicker({
        dynamic: false,
        dropdown: true,
        'showDuration': false,
        scrollbar: true,
        show24Hours: false,
        interval: 60,
    });
    $('#color').wheelColorPicker({
        autoResize: false,
        sliders: null
    });

    var ccsID = Number(localStorage.getItem('ccsID'));

    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != element.value;
    }, "Value must not equal arg.");
    $("#addUserForm").validate({
        ignore: ":hidden:not(.selectpicker)",
        rules: {
            username: {
                required: !0,
            },
            mobile: {
                required: !0,
            },
            email: {
                required: !0,
                email: !0
            },
            // password: {
            //     required: !0,
            //     minlength: 6
            // },
            // conpassword: {
            //     required: !0,
            //     minlength: 6,
            //     equalTo: "#password"
            // },
            role: {
                valueNotEquals: "0"
            },
            color: {
                required: () => $('#role').val() == ccsID ? true : false,
            },
            monEnd: {
                required: () => $('#monStart').val() ? true : false,
            },
            tueEnd: {
                required: () => $('#tueStart').val() ? true : false,
            },
            wedEnd: {
                required: () => $('#wedStart').val() ? true : false,
            },
            thuEnd: {
                required: () => $('#thuStart').val() ? true : false,
            },
            friEnd: {
                required: () => $('#friStart').val() ? true : false,
            },
            satEnd: {
                required: () => $('#satStart').val() ? true : false,
            },
            sunEnd: {
                required: () => $('#sunStart').val() ? true : false,
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
                        $('.selectpicker').each(function () {
                            $(this).find('option:first').prop('selected', 'selected');
                            $(".selectpicker").selectpicker("refresh");
                        });
                        $("#color").removeAttr("style");
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
    });
});


function fetchUserRolesByLocation() {
    let location = $('#location').val();
    if (location != 0) {
        $.ajax({
            url: '../php_action/fetchUserRolesByLocation.php',
            type: 'post',
            data: { location: location },
            dataType: 'json',
            success: function (response) {
                let list = response.data;
                let _html = document.getElementById('roleList');
                _html.innerHTML = '';
                list.forEach(obj => {
                    _html.innerHTML += `<option value="${obj[0]}">${obj[1]}</option>`;
                });
                $('.selectpicker').selectpicker('refresh');
            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}