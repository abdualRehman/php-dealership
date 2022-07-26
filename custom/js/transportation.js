"use strict";
var manageDataTable, stockArray;
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
        'ajax': '../php_action/fetchTransportations.php',
        dom: `\n     
            <'row'<'col-12'P>>\n      
            <'row'<'col-sm-12 text-sm-left col-md-3 mb-2 '<'#statusFilterDiv'>> <'col-sm-12 col-md-6 text-center text-sm-left 'B> <'col-sm-12 col-md-3 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,
        "pageLength": 25,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [1, 2, 3],
        },
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Transportation Damager',
                exportOptions: {
                    columns: [1, 2, 3, 4]
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Transportation Damager',
                exportOptions: {
                    columns: [1, 2, 3, 4]
                }
            },
            {
                extend: 'print',
                title: 'Transportation Damager',
                exportOptions: {
                    columns: [1, 2, 3, 4]
                }
            },
        ],
        columnDefs: [
            {
                targets: 0,
                visible: false,
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [1, 2, 3],
            },
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
        createdRow: function (row, data, dataIndex) {
            // if ($('#isAllowed').val() == 'true') {
            //     $(row).children().not(':last-child').attr({
            //         "data-toggle": "modal",
            //         "data-target": "#modal8",
            //         "onclick": "editFun(" + data[19] + ")"
            //     });
            // }
            $(row).children().not(':last-child').attr({
                "data-toggle": "modal",
                "data-target": "#modal8",
                "onclick": "editFun(" + data[0] + ")"
            });

        },
        "order": [[0, "asc"]]
    })


    writeStatusHTML();
    $('#pending').click();


    loadStock();
    loadDefaultOptions();


    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index) {
            var tableNode = manageDataTable.table().node();

            var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                if (this.value !== "") {
                    return this.value;
                }
            }).get();

            if (searchStatus.length === 0) {
                return true;
            }

            if (settings.nTable !== tableNode) {
                return true;
            }

            if (searchStatus[0] === 'pending') {
                var status = searchData[4];
                if (status == 'Pending') {
                    return true;
                }
            }
            if (searchStatus[0] === 'done') {
                var status = searchData[4];
                if (status != 'Pending') {
                    return true;
                }
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
        manageDataTable.searchPanes.rebuildPane();

    });


    $("#addNewFrom").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        submitHandler: function (form, e) {
            // return true;
            // e.preventDefault();
            var form = $('#addNewFrom');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {

                    if ((response.errorMessages) && response.errorMessages.length > 0) {
                        response.errorMessages.forEach(message => {
                            toastr.error(message, 'Error while Adding');
                        });
                    }
                    if (response.success == true) {
                        e1.fire({
                            position: "center",
                            icon: "success",
                            title: response.messages.length > 0 ? response.messages[0] : "Successfully Added",
                            showConfirmButton: !1,
                            timer: 1500
                        })
                        manageDataTable.ajax.reload(null, false);
                    } else {
                        e1.fire({
                            // position: "center",
                            icon: "error",
                            title: response.messages.length > 0 ? response.messages[0] : "Error while Adding",
                            showConfirmButton: !1,
                            timer: 2500
                        })

                        // form[0].reset();
                    }


                }
            });

            return false;

        }


    });

    $("#editForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is

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

    });

});



function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div id="sort">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="pending" value="pending"> Pending
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="done" value="done"> Done
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
}

function loadStock() {
    $.ajax({
        url: '../php_action/fetchInvForSearch.php',
        type: "POST",
        dataType: 'json',
        success: function (response) {
            stockArray = response.data;
            var selectBoxes = document.getElementsByClassName('stockId');
            selectBoxes.forEach(selectBox => {
                // selectBox.innerHTML = `<option value="0" selected disabled>Stock No:</option>`;
                for (var i = 0; i < stockArray.length; i++) {
                    // for (var i = 0; i < 3; i++) {
                    var item = stockArray[i];
                    
                    selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]} - ${item[8]}">${item[1]} - ${item[8]} </option>`;
                }
            });
            // selectBox.removeAttribute("disabled");
            $('.selectpicker').selectpicker('refresh');
        }
    });
}
function loadDefaultOptions() {

    var defaultOptions = document.getElementsByClassName('defaultOptions');
    $.ajax({
        url: '../php_action/fetchDefaultOptions.php',
        type: "POST",
        dataType: 'json',
        success: function (response) {
            let data = response.data;

            defaultOptions.forEach(element => {

                for (var i = 0; i < data.length; i++) {

                    var item = data[i];

                    if (item[0] != "") {
                        if ($(element).hasClass("locNum")) {
                            element.innerHTML += `<option>${item[0]} </option>`;
                        }
                    }
                    if (item[1] != "") {
                        if ($(element).hasClass("damageType")) {
                            element.innerHTML += `<option>${item[1]} </option>`;
                        }
                    }
                    if (item[2] != "") {
                        if ($(element).hasClass("damageSeverity")) {
                            element.innerHTML += `<option>${item[2]} </option>`;
                        }
                    }
                    if (item[3] != "") {
                        if ($(element).hasClass("damageGrid")) {
                            element.innerHTML += `<option>${item[3]} </option>`;
                        }
                    }

                }

            });
            // selectBox.removeAttribute("disabled");
            $('.selectpicker').selectpicker('refresh');
        }
    });
}



function changeStockDetails(ele) {
    let obj = stockArray.find(data => data[0] === ele.value);
    $('#stockDetails').val(`${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]}`);
}


function echangeStockDetails(ele) {
    let obj = stockArray.find(data => data[0] === ele.value);
    $('#estockDetails').val(`${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]}`);
}


function editFun(id = null) {
    if (id) {

        $.ajax({
            url: '../php_action/fetchSelectedtransportation.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editForm')[0].reset();


                $('#transId').val(response.id);

                $('#estockId').val(response.stock_id);
                echangeStockDetails({ value: response.stock_id });

                $('#estatus').val(response.transport_status);
                $('#elocNum').val(response.loc_num);
                $('#edamageType').val(response.damage_type);
                $('#edamageSeverity').val(response.damage_severity);
                $('#edamageGrid').val(response.damage_grid);

                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeDetails(id = null) {
    if (id) {
        e1.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(function (t) {
            if (t.isConfirmed == true) {

                $.ajax({
                    url: '../php_action/removeTransportation.php',
                    type: 'post',
                    data: { id: id },
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



function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
