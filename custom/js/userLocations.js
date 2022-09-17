"use strict";
var manageDataTable;
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});

$(function () {
    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchUserLocations.php',
        "pageLength": 25,
        dom: `
        <'row'<'col-sm-12 text-sm-left col-md-4 mb-2'B><'col-sm-12 col-md-4 text-center'> <'col-sm-12 col-md-4 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
       <'row'<'col-12'tr>>\n      
       <'row align-items-baseline'
       <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
       <'col-md-5'p>>\n`,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Bodyshops',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Bodyshops',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'print',
                title: 'Bodyshops',
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
            $(row).children().not(':last-child').attr({
                "data-toggle": "modal",
                "data-target": "#modal8",
                "onclick": `editLoc(${JSON.stringify(data[0])} , ${JSON.stringify(data[1])})`
            });
        },
        "order": [[1, "asc"]]
    });

    $("#addNewForm").validate({
        rules: {
            "locName": {
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
            "elocName": {
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


function editLoc(locId = null, locName = "") {
    if (locId) {
        $('.spinner-grow').addClass('d-none');
        // modal result
        $('.showResult').removeClass('d-none');
        // modal footer
        $('.modal-footer').removeClass('d-none');

        $('#editForm')[0].reset();
        $('#locId').val(locId);
        $('#elocName').val(locName);
    }
}

function removeLoc(locId = null) {
    if (locId) {
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
                    url: '../php_action/removeUserLocation.php',
                    type: 'post',
                    data: { id: locId },
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

