"use strict";
var manageRoleTable;
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});

$(function () {
    var divRequest = $(".div-request").text();

    if (divRequest == 'add') {

        $.validator.addMethod("valueNotEquals", function (value, element, arg) {
            // I use element.value instead value here, value parameter was always null
            return arg != element.value;
        }, "Value must not equal arg.");
        $("#createRoleForm").validate({
            ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
            rules: {
                roleName: {
                    required: !0,
                },
                location: {
                    valueNotEquals: "0"
                },
            },
            messages: {
                roleName: {
                    required: "Please Enter Role Name",
                },
                location: {
                    valueNotEquals: "Please select role",
                }
            },
            submitHandler: function (form, e) {
                // return true;
                e.preventDefault();
                var c = confirm("do you really want to save?");
                if (c) {
                    var form = $('#createRoleForm');
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
                                    timer: 1500
                                })
                                form[0].reset();

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
                }

                return false;

            }

        })




    } else if (divRequest == 'man') {
        manageRoleTable = $("#datatable-1").DataTable({
            "pageLength": 25,
            responsive: !0,
            'ajax': '../php_action/fetchRoles.php',
            // data: [],
        })


    } else if (divRequest == 'edit') {

        $("#eidtRoleForm").validate({
            rules: {
                editRoleName: {
                    required: !0,
                },
            },
            messages: {
                editRoleName: {
                    required: "Please Enter Role Name",
                }
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                var form = $('#eidtRoleForm');
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
                                timer: 1500
                            })
                            // form[0].reset();

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
    }

});


$('#checkALL').change(function () {
    // $('#checkALL').prop("checked", this.checked)
    $('.custom-control-input').prop("checked", this.checked);
});



function removeRole(roleId) {
    e1.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(function (t) {
        console.log(t);
        if (t.isConfirmed == true) {

            $.ajax({
                url: '../php_action/removeRole.php',
                type: 'post',
                data: { roleId: roleId },
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        Swal.fire("Deleted!", "Your file has been deleted.", "success")
                        manageRoleTable.ajax.reload(null, false);
                    } // /response messages
                }
            }); // /ajax function to remove the brand

        }
        // t.value && Swal.fire("Deleted!", "Your file has been deleted.", "success")
    });
}
