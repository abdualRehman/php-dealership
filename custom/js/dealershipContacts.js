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
        'ajax': '../php_action/fetchDealershipContacts.php',
        "pageLength": 25,
        dom: `
        <'row'<'col-sm-12 text-sm-left col-md-4 mb-2'<'#statusFilterDiv'> > <'col-sm-12 col-md-4 text-center'B> <'col-sm-12 col-md-4 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
       <'row'<'col-12'tr>>\n      
       <'row align-items-baseline'
       <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
       <'col-md-5'p>>\n`,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Company Dealership Contacts',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Company Dealership Contacts',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'print',
                title: 'Company Dealership Contacts',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
        ],

        columnDefs: [
            {
                visible: false,
                targets: [0],
            },
        ],
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editContact(" + data[0] + ")"
                });
            }
        },

        "order": [[1, "asc"],[2, "asc"]]
    });






    $("#addNewForm").validate({
        rules: {
            "brand": {
                required: !0,
            },
            "dealership": {
                required: !0,
            },

        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
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

            return false;

        }


    });

    $("#editForm").validate({
        rules: {
            "ebrand": {
                required: !0,
            },
            "edealership": {
                required: !0,
            },

        },

        submitHandler: function (form, e) {
            // return true
            e.preventDefault();
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

            return false;

        }

    })



})

function padLeadingZeros(num, size) {
    var s = num + "";
    while (s.length < size) s = "0" + s;
    return s;
}
$('.zipCode').on('blur', function () {
    let newV = padLeadingZeros($(this).val(), 5);
    if(newV != "00000"){
        $(this).val(newV);
    }
})


function editContact(dealershipId = null) {
    if (dealershipId) {

        $.ajax({
            url: '../php_action/fetchSelectDealershipContact.php',
            type: 'post',
            data: { id: dealershipId },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editForm')[0].reset();


                $('#dealershipId').val(response.id);

                $('#ebrand').val(response.brand);
                $('#edealership').val(response.dealership);
                $('#eaddress').val(response.address);
                $('#ecity').val(response.city);
                $('#estate').val(response.state);
                $('#ezip').val(response.zip);
                $('#etelephone').val(response.telephone);
                $('#efax').val(response.fax);

                $('#egeneralManager').val(response.gmanager);
                $('#egeneralManagerContact').val(response.gmanager_contact);

                $('#eusedcarManager').val(response.usedcmanager);
                $('#eusedcarManagerContact').val(response.usedcmanager_contact);


                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeContact(dealershipId = null) {
    if (dealershipId) {
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
                    url: '../php_action/removeDealershipContact.php',
                    type: 'post',
                    data: { id: dealershipId },
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

