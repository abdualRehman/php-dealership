"use strict";
var manageDataTable;
var stockArray = [];
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


    $('#travelTime').timepicker({ 'timeFormat': 'H:i', 'showDuration': true });
    $('#roundTrip').timepicker({ 'timeFormat': 'H:i', 'showDuration': true });
    autosize($(".autosize"));

    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchSwaps.php',
        dom: "Pfrtip",
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [0, 1, 2, 3],
        },
        "pageLength": 25,
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3],
            },
            // {
            //     targets: [9],
            //     visible: false,
            // },
            {
                targets: [5],
                createdCell: function (td, cellData, rowData, row, col) {
                    if (cellData == 'Yes') {
                        $(td).html('<h2 class="badge badge-xl h1 badge-success">Yes</h2>');
                    } else if (cellData == 'No') {
                        let celld = (rowData[1]).replace(/<[^>]*>?/gm, '');
                        if (celld != "") {
                            $(td).html('<h2 class="badge badge-xl h1 badge-danger">No</h2>');
                        } else {
                            $(td).html(cellData);
                        }
                    }
                }
            },
            {
                targets: [6],
                createdCell: function (td, cellData, rowData, row, col) {
                    if (cellData == 'Yes') {
                        $(td).html('<h2 class="badge badge-xl h1 badge-success">Yes</h2>');
                    } else if (cellData == 'No') {
                        let celld = (rowData[2]).replace(/<[^>]*>?/gm, '');
                        if (celld != "") {
                            $(td).html('<h2 class="badge badge-xl h1 badge-danger">No</h2>');
                        } else {
                            $(td).html(cellData);
                        }
                    }
                }
            },
            {
                targets: [7],
                createdCell: function (td, cellData, rowData, row, col) {
                    if (cellData == 'Pending') {
                        $(td).html('<h2 class="badge badge-xl h1 badge-warning">Pending</h2>');
                    } else if (cellData == 'Paperwork Done') {
                        $(td).html('<h2 class="badge badge-xl h1 badge-primary">Paperwork Done</h2>');
                    } else if (cellData == 'Completed') {
                        $(td).html('<h2 class="badge badge-xl h1 badge-success">Completed</h2>');
                    }
                }
            },
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                // $(row).attr({
                //     "data-toggle": "modal",
                //     "data-target": "#editDetails",
                //     "onclick": "editDetails(" + data[9] + ")"
                // });
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#editDetails",
                    "onclick": "editDetails(" + data[9] + ")"
                });
            }
        },
        "order": [[7, "desc"]],
    });

    // --------------------- checkboxes query --------------------------------------

    $('#pending').click();
    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageDataTable.table().node();


            var dateType = $('input:radio[name="radio-status"]:checked').map(function () {
                if (this.value !== "") {
                    return this.value;
                }
            }).get();

            if (dateType.length === 0) {
                return true;
            }

            if (dateType == 'all') {
                return true;
            } else if (dateType == 'pending') {
                if (searchData[7] == 'Pending') {
                    return true;
                }
            } else if (dateType == 'paperWork') {
                if (searchData[7] == 'Paperwork Done') {
                    return true;
                }
            } else if (dateType == 'completed') {
                if (searchData[7] == 'Completed') {
                    return true;
                }
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
        manageDataTable.searchPanes.rebuildPane();
    });



    function requireSelectBox(params) {
        var id = params.id;
        if (params.value == 0 || params.value == null) {
            params.classList.add('is-invalid');
            params.classList.remove('is-valid');
            $('#' + id).selectpicker('refresh');
            return true;
        } else {
            params.classList.remove('is-invalid');
            params.classList.add('is-valid');
            $('#' + id).selectpicker('refresh');
            return false;
        }
    }

    $("#addNewSwap").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "inpercentage": {
                required: false,
                number: !0,
            },
            "outpercentage": {
                required: false,
                number: !0,
            },
            "fromDealer": {
                required: requireSelectBox,
            },
            // "vehicleIn": {
            //     required: !0
            // },
            // "stockIn": {
            //     required: !0,
            // },
            // "vehicleOut": {
            //     required: !0
            // },
            // "salesPerson": {
            //     required: requireSelectBox,
            // },
            "invIn": {
                number: !0,
            },
            "hbIn": {
                number: !0,
            },
            "msrpIn": {
                number: !0,
            },
            "hdagIn": {
                number: !0,
            },
            "addsIn": {
                number: !0,
            },
            "invOut": {
                number: !0,
            },
            "hbOut": {
                number: !0,
            },
            "msrpOut": {
                number: !0,
            },
            "hdagOut": {
                number: !0,
            },
            "addsOut": {
                number: !0,
            },




        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();

            var form = $('#addNewSwap');
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
                        });

                        form[0].reset();
                        resetForm();
                        $('#addNew').modal('hide');
                        $("#createBtnPrint").attr("onclick", `printDetails(${response.id})`);
                        manageDataTable.ajax.reload(null, false);
                    } else {
                        e1.fire({
                            // position: "center",
                            icon: "error",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500
                        })

                        form[0].reset();
                    }


                }
            });

            return false;

        }


    });

    $("#editSwapForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "einpercentage": {
                required: false,
                number: !0,
            },
            "eoutpercentage": {
                required: false,
                number: !0,
            },
            "efromDealer": {
                required: requireSelectBox,
            },
            // "evehicleIn": {
            //     required: !0
            // },
            // "estockIn": {
            //     required: !0,
            // },
            // "evehicleOut": {
            //     required: !0
            // },
            // "esalesPerson": {
            //     required: requireSelectBox,
            // },
            "einvIn": {
                number: !0,
            },
            "ehbIn": {
                number: !0,
            },
            "emsrpIn": {
                number: !0,
            },
            "ehdagIn": {
                number: !0,
            },
            "eaddsIn": {
                number: !0,
            },
            "einvOut": {
                number: !0,
            },
            "ehbOut": {
                number: !0,
            },
            "emsrpOut": {
                number: !0,
            },
            "ehdagOut": {
                number: !0,
            },
            "eaddsOut": {
                number: !0,
            },
        },

        submitHandler: function (form, e) {
            // return true
            e.preventDefault();

            var form = $('#editSwapForm');
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
                        $('#editDetails').modal('hide');
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

    $("#addNewSwapLocation").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            dealerno: {
                required: !0,
            },
            dealership: {
                required: !0,
            },
            address: {
                required: !0,
            },
            city: {
                required: !0,
            },
            state: {
                required: function (params) {
                    if (params.value == 0) {
                        params.classList.add('is-invalid');
                        $('#state').selectpicker('refresh');
                        params.classList.add('is-invalid');
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            zip: {
                required: !0,
            },
            phone: {
                required: !0,
            },

        },

        submitHandler: function (form, event) {
            event.preventDefault();

            var form = $('#addNewSwapLocation');
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

                        form[0].reset();
                        loadLocations();

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

    loadStock();
    loadLocations();
    loadSaleConsultant();

});


function resetForm() {
    $('.typeahead').val('');
    $('.typeahead').trigger('change');
    $('.selectpicker').each(function () {
        $(this).find('option:first').prop('selected', 'selected');
        $(".selectpicker").selectpicker("refresh");
    });
    $(".tags").empty();
}


// $('.vehicleDetails').on('change', function () {
//     if ($('.vehicleDetails:checked').length == $('.vehicleDetails').length) {
//         var selectBox = document.getElementById('status');
//         selectBox.innerHTML += `<option value="completed">Completed</option>`;
//     } else {
//         $("#status option[value='completed']").remove();
//     }
//     $('.selectpicker').selectpicker('refresh');
// });

function updateStatus() {
    var selectBox = document.getElementById('status');
    var value = selectBox.value;
    if (($('.vehicleIn:checked').length == $('.vehicleIn').length) || ($('.vehicleOut:checked').length == $('.vehicleOut').length)) {
        $("#status option[value='completed']").remove();
        selectBox.innerHTML += `<option value="completed">Completed</option>`;
    } else {
        $("#status option[value='completed']").remove();
    }

    $('select[name=status]').val(value);
    $('.selectpicker').selectpicker('refresh');
}
function updateEditStatus() {
    var selectBox = document.getElementById('estatus');
    var value = selectBox.value;

    if (($('.evehicleIn:checked').length == $('.evehicleIn').length) || ($('.evehicleOut:checked').length == $('.evehicleOut').length)) {
        $("#estatus option[value='completed']").remove();
        selectBox.innerHTML += `<option value="completed">Completed</option>`;
    } else {
        $("#estatus option[value='completed']").remove();
    }
    $('select[name=estatus]').val(value);
    $('.selectpicker').selectpicker('refresh');
}


$('.vehicleIn').on('change', function () {
    if ($('#invReceived:checked').val()) {
        $('#inDetails').removeClass('d-none');
    } else {
        $('#inDetails').addClass('d-none');
    }
    updateStatus();
});

$('.vehicleOut').on('change', function () {
    if ($('#invSent:checked').val()) {
        $('#outDetails').removeClass('d-none');
    } else {
        $('#outDetails').addClass('d-none');
    }
    updateStatus();
});


// $('.evehicleDetails').on('change', function () {
//     var selectBox = document.getElementById('estatus');
//     var value = selectBox.value;
//     if ($('.evehicleDetails:checked').length == $('.evehicleDetails').length) {
//         $("#estatus option[value='completed']").remove();
//         selectBox.innerHTML += `<option value="completed">Completed</option>`;
//     } else {
//         $("#estatus option[value='completed']").remove();
//     }
//     $('select[name=estatus]').val(value);
//     $('.selectpicker').selectpicker('refresh');
// });

$('.evehicleIn').on('change', function () {

    if ($('#einvReceived:checked').val()) {
        $('#einDetails').removeClass('d-none');
    } else {
        $('#einDetails').addClass('d-none');
    }
    updateEditStatus();
});

$('.evehicleOut').on('change', function () {
    if ($('#einvSent:checked').val()) {
        $('#eoutDetails').removeClass('d-none');
    } else {
        $('#eoutDetails').addClass('d-none');
    }
    updateEditStatus();
});


function calculateHTB(currentElement, target, percentage) {
    var msrp = $('#' + currentElement).val();
    var prcntge = $('#' + percentage).val();
    var calcHbt = (msrp * prcntge) / 100;
    // calcHbt = (calcHbt).toFixed(2);
    calcHbt = Math.round((calcHbt + Number.EPSILON) * 100) / 100;   //  to set a limit of decimal 

    $('#' + target).val(calcHbt);
    $('#' + target).click();
}

function calculateCost(inv, hb, HBT, hdag, adds, target) {
    var invVal = $('#' + inv).val();
    invVal = invVal ? invVal : 0;
    var hbVal = $('#' + hb).val();
    hbVal = hbVal ? hbVal : 0;
    var HBTVal = $('#' + HBT).val();
    HBTVal = HBTVal ? HBTVal : 0;
    var hdagVal = $('#' + hdag).val();
    hdagVal = hdagVal ? hdagVal : 0;
    var addsVal = $('#' + adds).val();
    addsVal = addsVal ? addsVal : 0;

    var newCost = (Number(invVal - hbVal - HBTVal - hdagVal) + Number(addsVal));

    // newCost = newCost.toFixed(2);  // to fix limit
    newCost = Math.round((newCost + Number.EPSILON) * 100) / 100;   //  to set a limit of decimal 
    // newCost = (Math.round((newCost + Number.EPSILON) * 1000) / 1000).toFixed(3);   //  to set a limit of decimal 


    $('#' + target).val(newCost);

}

function loadSaleConsultant() {
    var sales_consultant_id = Number(localStorage.getItem('salesConsultantID'));
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: sales_consultant_id },
        success: function (response) {
            var saleCnsltntArray = response.data;
            var selectBox = document.getElementsByClassName('salesPerson');
            selectBox.forEach(element => {
                for (var i = 0; i < saleCnsltntArray.length; i++) {
                    var item = saleCnsltntArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
                }

            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function loadLocations() {
    $.ajax({
        url: '../php_action/fetchLocationsForSearch.php',
        type: "POST",
        dataType: 'json',
        success: function (response) {
            var locationArray = response.data;

            var selectBoxes = document.getElementsByClassName('fromDealer');

            selectBoxes.forEach(element => {
                element.innerHTML = `<option value="0" selected disabled>From Dealer</option>`;
                for (var i = 0; i < locationArray.length; i++) {
                    var item = locationArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[2]}">${item[1]} || ${item[2]} ||  ${item[3]} </option>`;
                }
            });

            $('.selectpicker').selectpicker('refresh');
        },
        error: function (params) {
            console.log(params);

        }
    });
}

function resetDealerFrom() {
    $("#fromDealer").val('default').selectpicker("refresh");
    $("#efromDealer").val('default').selectpicker("refresh");
}

function changeStockDetails(ele, vehicle, color, vin) {

    $('#detailsSection').removeClass('d-none');
    let obj = stockArray.find(data => data[0] === ele.value);

    $('#' + vehicle).val(obj[2] + ' ' + obj[3] + ' ' + obj[4]);
    $('#' + color).val(obj[6]);
    $('#' + vin).val(obj[8]);
}



function editDetails(id = null) {
    if (id) {

        $.ajax({
            url: '../php_action/fetchSelectedSwap.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: async function (response) {
                // console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editSwapForm')[0].reset();


                $('#swapId').val(response.id);

                $("#editBtnPrint").attr("onclick", `printDetails(${response.id})`);

                $('#efromDealer').val(response.from_dealer);
                $('#esalesPerson').val(response.sales_consultant);
                $('#submittedBy').html(response.submitted_by);


                $('#estockIn').val(response.stock_in);
                $('#evehicleIn').val(response.vehicle_in);
                $('#ecolorIn').val(response.color_in);


                $("#einvReceived").prop("checked", (response.inv_received == 'on') ? true : false);
                $("#etransferredIn").prop("checked", (response.transferred_in == 'on') ? true : false);
                if (response.inv_received == 'on') {
                    $('.evehicleIn').change();
                }


                $('#evinIn').val(response.vin_in);
                $('#einvIn').val(response.inv_in);
                $('#ehbIn').val(response.hb_in);

                $('#emsrpIn').val(response.msrp_in);

                $('#ehdagIn').val(response.hdag_in);
                $('#eaddsIn').val(response.adds_in);
                $('#eaddsInNotes').val(response.adds_in_notes);

                $('#ehbtIn').val(response.hbt_in);


                $('#einpercentage').val((response.hbt_in && response.msrp_in) ? ((response.hbt_in * 100) / response.msrp_in).toFixed(2) : "1.5");


                $('#enetcostIn').val(response.net_cost_in);


                // $('#estockOut').val(response.stock_out);

                console.log("response.stock_out", response.stock_out);

                if (response.stock_out !== "") {
                    const foundStock = stockArray.find((obj) => obj[0] === response.stock_out);
                    console.log("foundStock", foundStock, stockArray);
                    if (foundStock) {
                        var element = document.getElementById('stockOutEdit');
                        var options = element.querySelectorAll('option');
                        // Check if any option has the specific value
                        var hasSpecificValueExist = Array.from(options).some(option => option.value === response.stock_out);
                        if (!hasSpecificValueExist) {
                            writeHTMLOptions(element, [foundStock])
                        }
                    } else {
                        await fetchSelectedInvForSearch(response.stock_out)
                    }

                }


                $('#estockOut').val(response.stock_out);



                $('#evehicleOut').val(response.vehicle_out);
                $('#ecolorOut').val(response.color_out);


                $("#einvSent").prop("checked", (response.inv_sent == 'on') ? true : false);
                $("#etransferredOut").prop("checked", (response.transferred_out == 'on') ? true : false);
                $("#etagged").prop("checked", (response.tagged == 'on') ? true : false);

                if (response.inv_sent == 'on') {
                    $('.evehicleOut').change();
                }



                $('#evinOut').val(response.vin_out);
                $('#einvOut').val(response.inv_out);
                $('#ehbOut').val(response.hb_out);
                $('#emsrpOut').val(response.msrp_out);
                $('#ehdagOut').val(response.hdag_out);
                $('#eaddsOut').val(response.adds_out);
                $('#eaddsOutNotes').val(response.adds_out_notes);
                $('#ehbtOut').val(response.hbt_out);

                $('#eoutpercentage').val((response.hbt_out && response.msrp_out) ? ((response.hbt_out * 100) / response.msrp_out).toFixed(2) : "1.5");

                $('#enetcostOut').val(response.net_cost_out);

                $('#edealNote').val(response.notes);

                // chnage status
                // $('.evehicleDetails').change();
                updateEditStatus();
                $('#estatus').val(response.swap_status);

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
            console.log(t);
            if (t.isConfirmed == true) {

                $.ajax({
                    url: '../php_action/removeSwap.php',
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




// print order function
function printDetails(id = null) {
    if (id) {
        $.ajax({
            url: '../php_action/printSwap.php',
            type: 'post',
            data: { id: id },
            dataType: 'text',
            success: function (response) {
                if (response) {
                    var mywindow = window.open('', 'Swaps', 'height=400,width=600');
                    mywindow.document.write('<html><head><title></title>');
                    mywindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">');

                    // mywindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">');
                    // mywindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&amp;family=Roboto+Mono&amp;display=swap" rel="stylesheet">');
                    // mywindow.document.write('<link href="../assets/build/styles/ltr-core.css" rel="stylesheet">');
                    // mywindow.document.write('<link href="../assets/build/styles/ltr-vendor.css" rel="stylesheet">');
                    mywindow.document.write('</head><body>');
                    mywindow.document.write(response);
                    mywindow.document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>');
                    mywindow.document.write('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>');
                    mywindow.document.write('</body>');
                    mywindow.document.write('</html>');
                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10
                    $(mywindow).on("load", function (e) {
                        setTimeout(() => {
                            mywindow.print();
                        }, 100);
                    });
                }
                // mywindow.close();

            } // /success function
        }); // /ajax function to fetch the printable order

    } else {
        Swal.fire("Error!", "You must have to save first", "error")
    }
} // /print order function

function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}



// Previous code

// function loadStock() {
//     $.ajax({
//         url: '../php_action/fetchInvForSearch.php',
//         type: "POST",
//         data: { type: 'NEW' },
//         dataType: 'json',
//         success: function (response) {
//             stockArray = response.data;
//             // console.log(stockArray);
//             // console.log(selectBox);

//             var selectBoxes = document.getElementsByClassName('stockOut');
//             selectBoxes.forEach(element => {
//                 for (var i = 0; i < stockArray.length; i++) {
//                     var item = stockArray[i];
//                     element.innerHTML += `<option value="${item[0]}" title="${item[1]}" >${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
//                 }
//             });
//             // selectBox.removeAttribute("disabled");
//             $('.selectpicker').selectpicker('refresh');
//         }
//     });
// }

function loadStock() {
    var selectBoxes = document.getElementsByClassName('stockOut');
    selectBoxes.forEach(element => {
        element.innerHTML = ``;
    });
    $('.selectpicker').selectpicker('refresh');
    $.ajax({
        url: '../php_action/fetchInvForSearch.php',
        type: "POST",
        data: { type: 'NEW' },
        dataType: 'json',
        success: function (response) {

            stockArray = response.data;
            // console.log(stockArray);
            selectBoxes.forEach((element, index) => {
                // for (var i = 0; i < stockArray.length; i++) {
                writeHTMLOptions(element, stockArray);

                const scroller = new InfiniteScroller(element, stockArray);
                scroller.setScroller(element, stockArray);

            });

            // selectBox.removeAttribute("disabled");
            $('.selectpicker').selectpicker('refresh');

            $('#stockId').selectpicker('toggle');
            $('#stockId').selectpicker('refresh');
            $("#_").focus().val('').val("_");
        }
    });
}

function writeHTMLOptions(element, stockArray) {
    for (var i = 0; i < (stockArray.length < 10 ? stockArray.length : 10); i++) {
        var item = stockArray[i];
        $(element).append(`<option value="${item[0]}" title="${item[1]}" >${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
    }
}


function fetchSelectedInvForSearch(id = null) {
    $.ajax({
        url: '../php_action/fetchSelectedInvForSearch.php',
        type: 'post',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            stockArray.push(response.data);
            var item = response.data;
            var selectBox = document.getElementById('stockOutEdit');
            selectBox.innerHTML += `<option value="${item[0]}" selected title="${item[1]}" >${item[1]} || ${item[4]} ||  ${item[8]}  || Stock Deleted</option>`;
            $('#estockOut').val(item[0]);
            $('.selectpicker').selectpicker('refresh');
        }

    }); // /ajax function to remove the brand
}

class InfiniteScroller {
    element;
    stockArray;
    constructor(element, stockArray) {
        this.element = element;
        this.stockArray = stockArray;
    }
    setScroller(element, array) {
        var p = element.parentElement.id;

        var Selectpicker = $('#' + p).data('selectpicker');

        Selectpicker?.$menuInner?.on('scroll', function () {
            if (Selectpicker?.$searchbox?.val() == '') {

                var scrollTop = Selectpicker?.selectpicker?.view?.scrollTop;
                // if within 100px of the bottom of the menu, load more options
                if ($(this)[0].scrollHeight - Selectpicker.sizeInfo.menuInnerHeight - scrollTop < 10) {

                    var optionDiv = Selectpicker?.$element[0];
                    var targetChildElement = $(optionDiv).children('optgroup')[0].lastChild;
                    var lastArrayIndex = $(targetChildElement).data('scroll-index') + 1;
                    // console.log(lastArrayIndex);
                    if (array.length > lastArrayIndex) {
                        for (let j = lastArrayIndex; j < lastArrayIndex + 10; j++) {
                            var item = array[j];
                            if (item) {
                                // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${j}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
                                $(element).append(`<option value="${item[0]}" data-scroll-index="${j}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);

                            }
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                }
            }
        });

        Selectpicker?.$searchbox.on('input', function () {
            var search = this.value;

            if (search != '' && search.length > 3) {
                element.innerHTML = '';
                $('.dropdown-menu.show').block();
                const results = array.filter(element => {
                    var stock = (element[1]).toLowerCase();
                    var model = (element[4]).toLowerCase();
                    var vin = (element[8]).toLowerCase();
                    return stock.indexOf(search.toLowerCase()) >= 0 || vin.indexOf(search.toLowerCase()) >= 0 || model.indexOf(search.toLowerCase()) >= 0;
                });
                results.forEach((item, i) => {
                    // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
                    $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
                });
                $('.dropdown-menu.show').unblock();
                $('.selectpicker').selectpicker('refresh');
            } else if (search == '') {
                element.innerHTML = '';
                $('.dropdown-menu.show').block();
                for (var i = 0; i < 5; i++) {
                    var item = array[i];
                    $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
                }
                $('.dropdown-menu.show').unblock();
                $('.selectpicker').selectpicker('refresh');
            }
            $('.dropdown-menu.show').unblock();

        });
    }
}
