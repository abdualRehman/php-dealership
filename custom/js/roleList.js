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

    if (divRequest == 'add' || divRequest == 'edit') {
        new Sortable(document.querySelector("#users-left"), {
            group: "shared",
            ghostClass: 'blue-background-class',
            animation: 150,
            // Element dragging ended
            onAdd: function (/**Event*/evt) {
                var itemEl = evt.item;  // dragged HTMLElement
                var input = $(itemEl).find('input[type="hidden"]')
                input.val(false)
            },

        })
        new Sortable(document.querySelector("#users-right"), {
            group: "shared",
            ghostClass: 'blue-background-class',
            animation: 150,
            // Element dragging ended
            onAdd: function (/**Event*/evt) {
                var itemEl = evt.item;  // dragged HTMLElement
                var input = $(itemEl).find('input[type="hidden"]')
                input.val(true)
            },

        })
        new Sortable(document.querySelector("#inv-left"), {
            group: "shared1",
            ghostClass: 'blue-background-class',
            animation: 150,
            onAdd: function (evt) {
                var itemEl = evt.item;  // dragged HTMLElement
                var input = $(itemEl).find('input[type="hidden"]')
                input.val(false)
            },
        })
        new Sortable(document.querySelector("#inv-right"), {
            group: "shared1",
            ghostClass: 'blue-background-class',
            animation: 150,
            onAdd: function (evt) {
                var itemEl = evt.item;  // dragged HTMLElement
                var input = $(itemEl).find('input[type="hidden"]')
                input.val(true)
            },
        })
    }

    if (divRequest == 'add') {

        $("#createRoleForm").validate({
            rules: {
                roleName: {
                    required: !0,

                },
            },
            messages: {
                roleName: {
                    required: "Please Enter Role Name",
                }
            },
            submitHandler: function (form, e) {
                e.preventDefault();
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
                return false;

            }

        })




    } else if (divRequest == 'man') {
        manageRoleTable = $("#datatable-1").DataTable({
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
                return false;

            }

        })
    }

})
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
