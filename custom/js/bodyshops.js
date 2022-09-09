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
        dom: `
        <'row'<'col-sm-12 text-sm-left col-md-4 mb-2'<'#statusFilterDiv'> > <'col-sm-12 col-md-4 text-center'B> <'col-sm-12 col-md-4 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
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
                targets: [9, 10],
            },
        ],

        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {
                var obj = json.data;
                var activeCount = 0, inactiveCount = 0;

                for (const [key, value] of Object.entries(obj)) {
                    var rowStatus = value[9];
                    if (rowStatus == '0') {
                        inactiveCount += 1;
                    } else if (rowStatus == '1') {
                        activeCount += 1;
                    }
                }
                $(`#activeCount`).html(activeCount);
                $(`#inactiveCount`).html(inactiveCount);
            }
            $('.editCheckbox').on('change', function () {
                changeStatus($(this));
            });
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editShop(" + data[10] + ")"
                });
            }
        },

        "order": [[10, "asc"]]
    });

    writeStatusHTML();
    $('#searchStatusActive').click();



    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageDataTable.table().node();

            var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                if (this.value !== "") {
                    return this.value;
                }
            }).get();

            if (searchStatus.length === 0) {
                return true;
            }

            if (searchStatus.indexOf(searchData[9]) !== -1) {
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
        manageDataTable.draw();  // working
    });






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

function changeStatus(obj = null) {
    var probId = obj[0].id;
    console.log(probId);
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
                    url: '../php_action/changeBodyshopStatus.php',
                    type: 'post',
                    data: { id: probId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Changed!", "Status has been changes.", "success")
                            manageDataTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
    }
}
function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusInactive" value="0" >Inactive<span class="badge badge-lg p-1" id="inactiveCount" ></span>
            </label>
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusActive" value="1">Active <span class="badge badge-lg p-1" id="activeCount" ></span>
            </label> 
            
        </div>`;
    }
}

function padLeadingZeros(num, size) {
    var s = num + "";
    while (s.length < size) s = "0" + s;
    return s;
}
$('.zipCode').on('change', function () {
    let newV = padLeadingZeros($(this).val(), 5);
    $(this).val(newV);
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

