"use strict";
var manageDataTable;
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});
toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": 0,
    "extendedTimeOut": 0,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "tapToDismiss": true
};

$(function () {


    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchBodyshops.php',
        "pageLength": 25,
        // dom: "Pfrtip",
        // searchPanes: {
        //     cascadePanes: !0,
        //     viewTotal: !0,
        //     columns: [0, 1, 2, 3],
        // },
        // columnDefs: [
        //     {
        //         searchPanes: {
        //             show: true
        //         },
        //         targets: [0, 1, 2, 3],
        //     },
        // ],

        // language: {
        //     searchPanes: {
        //         count: "{total} found",
        //         countFiltered: "{shown} / {total}"
        //     }
        // },
        // "order": [[0, "asc"]]
    })



    $("#addNewForm").validate({
        rules: {
            "bName": {
                required: !0,
            },
            "shop": {
                required: !0,
            },

        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
                var form = $('#addNewForm');
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);


                        if (response.success == true) {
                            e1.fire({
                                position: "center",
                                icon: "success",
                                title: response.messages,
                                showConfirmButton: !1,
                                timer: 1500
                            })
                            manageDataTable.ajax.reload(null, false);
                            form[0].reset();
                        } else {
                            e1.fire({
                                // position: "center",
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


    });

    $("#editForm").validate({
        rules: {
            "ebName": {
                required: !0,
            },
            "eshop": {
                required: !0,
            },

        },

        submitHandler: function (form, e) {
            // return true
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
                var form = $('#editForm');
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
                            manageDataTable.ajax.reload(null, false);

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



})




function editShop(shopId = null) {
    if (shopId) {

        $.ajax({
            url: '../php_action/fetchSelectBodyshop.php',
            type: 'post',
            data: { id: shopId },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editForm')[0].reset();


                $('#shopId').val(response.id);

                $('#ebName').val(response.business_name);
                $('#eshop').val(response.shop);
                $('#eaddress').val(response.address);
                $('#ecity').val(response.city);
                $('#estate').val(response.state);
                $('#ezip').val(response.zip);
                $('#econtatperson').val(response.contact_person);
                $('#econtatnumber').val(response.contact_number);


                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeShop(shopId = null) {
    if (shopId) {
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
                    url: '../php_action/removeBodyshop.php',
                    type: 'post',
                    data: { id: shopId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageDataTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
    }
}

