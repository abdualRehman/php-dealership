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
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
    })

    function requireSelectBox(params) {
        var id = params.id;
        if (params.value == 0) {
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
            "inpercentage":{
                required:false,
                number: !0,
            },
            "outpercentage":{
                required:false,
                number: !0,
            },
            "fromDealer": {
                required: requireSelectBox,
            },
            "vehicleIn": {
                required: !0
            },
            "stockIn": {
                required: !0,
            },
            "vehicleOut": {
                required: !0
            },
            "stockOut": {
                required: requireSelectBox,
            },

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
            var c = confirm('Do you really want to save this?');
            if (c) {
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
                            })
                            manageDataTable.ajax.reload(null, false);
                        } else {
                            e1.fire({
                                // position: "center",
                                icon: "error",
                                title: response.messages,
                                showConfirmButton: !1,
                                timer: 2500
                            })

                            // form[0].reset();
                        }


                    }
                });
            }
            return false;

        }


    });

    $("#editSwapForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "einpercentage":{
                required:false,
                number: !0,
            },
            "eoutpercentage":{
                required:false,
                number: !0,
            },
            "efromDealer": {
                required: requireSelectBox,
            },
            "evehicleIn": {
                required: !0
            },
            "estockIn": {
                required: !0,
            },
            "evehicleOut": {
                required: !0
            },
            "estockOut": {
                required: requireSelectBox,
            },
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
            var c = confirm('Do you really want to save this?');
            if (c) {
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

    loadStock();
    loadLocations();

})


$('.vehicleDetails').on('change', function () {
    if ($('.vehicleDetails:checked').length == $('.vehicleDetails').length) {
        var selectBox = document.getElementById('status');
        selectBox.innerHTML += `<option value="completed">Completed</option>`;
    } else {
        $("#status option[value='completed']").remove();
    }
    $('.selectpicker').selectpicker('refresh');
});

$('.vehicleIn').on('change', function () {
    if ($('.vehicleIn:checked').length == $('.vehicleIn').length) {
        $('#inDetails').removeClass('d-none');
    } else {
        $('#inDetails').addClass('d-none');
    }
});

$('.vehicleOut').on('change', function () {
    if ($('.vehicleOut:checked').length == $('.vehicleOut').length) {
        $('#outDetails').removeClass('d-none');
    } else {
        $('#outDetails').addClass('d-none');
    }
});


$('.evehicleDetails').on('change', function () {
    if ($('.evehicleDetails:checked').length == $('.evehicleDetails').length) {
        var selectBox = document.getElementById('estatus');
        selectBox.innerHTML += `<option value="completed">Completed</option>`;
    } else {
        $("#estatus option[value='completed']").remove();
    }
    $('.selectpicker').selectpicker('refresh');
});
$('.evehicleIn').on('change', function () {
    if ($('.evehicleIn:checked').length == $('.evehicleIn').length) {
        $('#einDetails').removeClass('d-none');
    } else {
        $('#einDetails').addClass('d-none');
    }
});

$('.evehicleOut').on('change', function () {
    if ($('.evehicleOut:checked').length == $('.evehicleOut').length) {
        $('#eoutDetails').removeClass('d-none');
    } else {
        $('#eoutDetails').addClass('d-none');
    }
});


function calculateHTB(currentElement, target , percentage) {
    var msrp = $('#' + currentElement).val();
    var prcntge = $('#' + percentage).val();
    var calcHbt = (msrp * prcntge) / 100;
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


    $('#' + target).val(newCost);

}

function loadStock() {

    $.ajax({
        url: '../php_action/fetchInvForSearch.php',
        type: "POST",
        dataType: 'json',
        beforeSend: function () {
            // selectBox.setAttribute("disabled", true);
        },
        success: function (response) {
            stockArray = response.data;
            // console.log(stockArray);
            // console.log(selectBox);

            var selectBoxes = document.getElementsByClassName('stockOut');
            selectBoxes.forEach(element => {
                for (var i = 0; i < stockArray.length; i++) {
                    var item = stockArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]}" >${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
                }
            });
            // selectBox.removeAttribute("disabled");
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
                for (var i = 0; i < locationArray.length; i++) {
                    var item = locationArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[2]}">${item[1]} || ${item[2]} ||  ${item[3]} </option>`;
                }
            });

            // $('.selectpicker').selectpicker('refresh');
        },
        error: function (params) {
            console.log(params);

        }
    });
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
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editSwapForm')[0].reset();


                $('#swapId').val(response.id);
                $('#efromDealer').val(response.from_dealer);


                $('#estockIn').val(response.stock_in);
                $('#evehicleIn').val(response.vehicle_in);
                $('#ecolorIn').val(response.color_in);


                $("#einvReceived").prop("checked", (response.inv_received == 'on') ? true : false);
                $("#etransferredIn").prop("checked", (response.transferred_in == 'on') ? true : false);
                $('.evehicleIn').change();


                $('#evinIn').val(response.vin_in);
                $('#einvIn').val(response.inv_in);
                $('#ehbIn').val(response.hb_in);
                
                $('#emsrpIn').val(response.msrp_in);
                
                $('#ehdagIn').val(response.hdag_in);
                $('#eaddsIn').val(response.adds_in);
                $('#eaddsInNotes').val(response.adds_in_notes);
                
                $('#ehbtIn').val(response.hbt_in);
                
                
                $('#einpercentage').val(( response.hbt_in && response.msrp_in )  ?  (( response.hbt_in * 100 ) / response.msrp_in) : "1.5" );


                $('#enetcostIn').val(response.net_cost_in);

                $('#estockOut').val(response.stock_out);
                $('#evehicleOut').val(response.vehicle_out);
                $('#ecolorOut').val(response.color_out);


                $("#einvSent").prop("checked", (response.inv_sent == 'on') ? true : false);
                $("#etransferredOut").prop("checked", (response.transferred_out == 'on') ? true : false);
                $('.evehicleOut').change();



                $('#evinOut').val(response.vin_out);
                $('#einvOut').val(response.inv_out);
                $('#ehbOut').val(response.hb_out);
                $('#emsrpOut').val(response.msrp_out);
                $('#ehdagOut').val(response.hdag_out);
                $('#eaddsOut').val(response.adds_out);
                $('#eaddsOutNotes').val(response.adds_out_notes);
                $('#ehbtOut').val(response.hbt_out);

                $('#eoutpercentage').val(( response.hbt_out && response.msrp_out )  ?  (( response.hbt_out * 100 ) / response.msrp_out) : "1.5" );

                $('#enetcostOut').val(response.net_cost_out);

                $('#edealNote').val(response.notes);

                // chnage status
                $('.evehicleDetails').change();
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
                var mywindow = window.open('', 'Swaps', 'height=400,width=600');
                mywindow.document.write('<html><head><title></title>');
                mywindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">');
                mywindow.document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>');
                mywindow.document.write('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>');
                // mywindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">');
                // mywindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&amp;family=Roboto+Mono&amp;display=swap" rel="stylesheet">');
                // mywindow.document.write('<link href="../assets/build/styles/ltr-core.css" rel="stylesheet">');
                // mywindow.document.write('<link href="../assets/build/styles/ltr-vendor.css" rel="stylesheet">');
                mywindow.document.write('</head><body>');
                mywindow.document.write(response);
                mywindow.document.write('</body>');
                mywindow.document.write('</html>');
                mywindow.document.close(); // necessary for IE >= 10
                // mywindow.focus(); // necessary for IE >= 10
                mywindow.print();
                // mywindow.close();

            } // /success function
        }); // /ajax function to fetch the printable order
    } // /if orderId
} // /print order function

function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}

