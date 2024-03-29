"use strict";
var manageUserTable;
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});

$(function () {

    // $('.timeInterval').timepicker({ 'timeFormat': 'H:i', 'showDuration': true });
    $('.timeInterval').timepicker({
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        show24Hours: false,
        interval: 60,
    });

    $('#color').wheelColorPicker({
        autoResize: false,
        sliders: null
    })

    var ccsID = Number(localStorage.getItem('ccsID'));

    manageUserTable = $("#datatable-1").DataTable({
        responsive: !0,
        "pageLength": 25,
        'ajax': '../php_action/fetchUsers.php',
        dom: `<'row'<'col-12'P>>
        <'row' 
        <'col-sm-4 text-left text-sm-left pl-3'<'#statusFilterDiv'>>
            <'col-sm-4 text-sm-center pl-3'B>
            <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
        <'row'<'col-12'tr>>\n      
        <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Users List',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Users List',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'print',
                title: 'Users List',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
            },
        ],
        columnDefs: [
            {
                targets: $('#isEditAllowed').val() == "false" ? [5] : [],
                visible: false,
            },
        ],
        "drawCallback": function (settings, start, end, max, total, pre) {
            $('.editCheckbox').on('change', function () {
                changeUserStatus($(this));
            });
        },
    });

    writeStatusHTML();
    $('#searchStatusActive').click();


    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        // I use element.value instead value here, value parameter was always null
        return arg != element.value;
    }, "Value must not equal arg.");

    $("#editUserForm").validate({
        rules: {
            editusername: {
                required: !0,
            },
            mobile: {
                required: !0,
            },
            editemail: {
                required: !0,
                email: !0
            },
            editrole: {
                valueNotEquals: "0"
            },
            color: {
                required: () => $('#editrole').val() == ccsID ? true : false,
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
            editusername: {
                required: "Please enter username",
            },
            editemail: {
                required: "Please enter your email",
                email: "Your email is not valid"
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
    $("#editPasswordForm").validate({
        rules: {
            // oldpassword: {
            //     required: !0,
            // },
            editpassword: {
                required: !0,
                minlength: 6
            },
            editconpassword: {
                required: !0,
                minlength: 6,
                equalTo: "#editpassword"
            },
        },
        messages: {
            editpassword: {
                required: "Please provide your password",
                minlength: $.validator.format("Please enter at least {0} characters")
            },
            editconpassword: {
                required: "Please reenter your password",
                minlength: $.validator.format("Please enter at least {0} characters"),
                equalTo: $.validator.format("Password is not Match"),
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var form = $('#editPasswordForm');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
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
                        manageUserTable.ajax.reload(null, false);

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
    })


    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageUserTable.table().node();
            var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                if (this.value !== "") {
                    return this.value;
                }
            }).get();
            if (searchStatus.length === 0) {
                return true;
            }

            if (searchStatus.indexOf(rowData[6]) !== -1) {
                return true;
            }

            if (settings.nTable !== tableNode) {
                return true;
            }
            return false;
        }
    );


    $('input:radio').on('change', function () {
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageUserTable.draw();  // working
    });

});



function changeUserStatus(obj = null) {
    var probId = obj[0].id;
    if (probId) {
        e1.fire({
            title: "Are you sure?",
            text: "Do you really want to change status?",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Change it!"
        }).then(function (t) {
            if (t.isConfirmed == true) {
                $.ajax({
                    url: '../php_action/changeUserStatus.php',
                    type: 'post',
                    data: { id: probId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Changed!", "Status has been changes.", "success")
                            manageUserTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            } else {
                // $(obj[0]).change();
            }
        });
    }
}

function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        // checked="checked"
        element.innerHTML = `
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusActive" value="1" >Active<span class="badge badge-lg p-1" id="ativeCount" ></span>
            </label>
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusNotActive" value="0">Not Active <span class="badge badge-lg p-1" id="notAtiveCount" ></span>
            </label> 
        </div>`;
    }
}


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

                $('#editusername').val(response.username);
                $('#editemail').val(response.email);
                $('#location').val(response.location);
                $('#extention').val(response.extention);
                $('#mobile').val(response.mobile);
                $('#color').val(response.color);
                $('#color').wheelColorPicker('color', '#' + response.color);

                $('#editrole').val(response.role);
                $('.selectpicker').selectpicker('refresh');


                $('#monStart').val(response.mon_start);
                $('#monEnd').val(response.mon_end);
                $('#tueStart').val(response.tue_start);
                $('#tueEnd').val(response.tue_end);
                $('#wedStart').val(response.wed_start);
                $('#wedEnd').val(response.wed_end);
                $('#thuStart').val(response.thu_start);
                $('#thuEnd').val(response.thu_end);
                $('#friStart').val(response.fri_start);
                $('#friEnd').val(response.fri_end);
                $('#satStart').val(response.sat_start);
                $('#satEnd').val(response.sat_end);
                $('#sunStart').val(response.sun_end);
                $('#sunEnd').val(response.sun_start);



                $('#editpassword').val('');
                $('#editconpassword').val('');

                $("#userId").remove();
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


function editPasswords(userId = null) {
    $('#oldpassword').val('');
    $('#editpassword').val('');
    $('#editconpassword').val('');
    $("#userpasswordId").remove();
    $(".editUserPassword-result").after('<input type="hidden" name="userpasswordId" id="userpasswordId" value="' + userId + '" />');
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