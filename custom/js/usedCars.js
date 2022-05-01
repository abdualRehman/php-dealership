
"use strict";
var manageInvTable, TableData, maxFileLimit = 10;
var manageCarDealersTable;
var collapsedGroups = {};
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})

$(function () {
    $('.nav-link').removeClass('active');
    $('#usedCars').addClass('active');

    $("#invDate").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,
    });

    $("#dateSent").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });
    $("#dateSold").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });

    autosize($(".autosize"));

    manageInvTable = $("#datatable-1").DataTable({

        responsive: !0,
        'ajax': '../php_action/fetchUsedCars.php',
        "paging": true,
        "scrollX": true,
        "orderClasses": false,
        "deferRender": true,
        "pageLength": 25,
        "order": [[1, "desc"]],

        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [2, 1, 3]
        },

        columnDefs: [
            {
                targets: [1],
                createdCell: function (td, cellData, rowData, row, col) {
                    var data = $(td).html();
                    if (data > 4) {
                        $(td).addClass('h5 font-weight-bolder text-danger');
                    } else {
                        $(td).addClass('font-weight-bold p');
                    }
                }
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [2, 1, 3]
            },
        ],
        dom: `\n     
                 <'row'<'col-12'P>>\n
                \n     
                <'row'<'col-sm-6 text-left text-sm-left pl-3'f>
                    <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'>>\n
                <'row'<'col-12'tr>>\n      
                <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {
                var obj = json.totalNumber;
                for (const [key, value] of Object.entries(obj)) {
                    $(`input[name='mod'][value='${key}']`).next().next().html(value)
                }
            }
        },


    })


    // --------------------- checkboxes query --------------------------------------

    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageInvTable.table().node();

            var activebtnvalue = $("#mods .btn.active input[name='mod']").val();

            if (activebtnvalue == 'addToSheet') {
                // var balance = rowData[9];
                var retail_status = rowData[19];
                var date_in = rowData[14];
                if ((date_in != '' && date_in != null) && retail_status != 'wholesale' ) {
                    return true;
                }
            }
            if (activebtnvalue == 'missingDate') {
                // console.log(rowData);
                var balance = rowData[9];
                var date_in = rowData[14];
                if ((date_in == '' || date_in == null) && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'titleIssue') {
                var balance = rowData[9];
                var title = rowData[15];
                if (title == 'false' && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'readyToShip') {
                var title = rowData[15];
                var retail_status = rowData[19];
                var key = rowData[16];
                if (title == 'true' && retail_status == 'wholesale' && key == 'false') {
                    return true;
                }
            }
            if (activebtnvalue == 'keysPulled') {
                var title = rowData[15];
                var retail_status = rowData[19];
                var key = rowData[16];
                if (title == 'true' && retail_status == 'wholesale' && key == 'true') {
                    return true;
                }
            }
            if (activebtnvalue == 'atAuction') {
                var date_sent = rowData[17];
                var date_sold = rowData[18];
                // if ((date_sent != "" || date_sent != null) && (date_sold == "" || date_sold == null)) {
                //     return true;
                // }
                if (date_sent && !date_sold) {
                    return true;
                }
            }
            if (activebtnvalue == 'soldAtAuction') {
                var date_sold = rowData[18];
                if (date_sold != "" && date_sold != null) {
                    return true;
                }
            }
            if (activebtnvalue == 'retail') {
                var balance = rowData[9];
                var wholesale = rowData[13];
                if (wholesale == 'No' && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'sold') {
                var balance = rowData[9];
                if (balance == "" || balance == null) {
                    return true;
                }
            }

            if (settings.nTable !== tableNode) {
                return true;
            }

            return false;
        }
    );




    $('#mods input:radio').on('change', function () {

        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        })

        setTimeout(() => {
            manageInvTable.draw();
            manageInvTable.searchPanes.rebuildPane();
        }, 500);

    });



    $("#updateUsedCarsForm").validate({
        // ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        rules: {
            // "bodyshop": {
            //     required: !0,
            // },

            "soldPrice": {
                number: !0,
            },
            "uciApproved": {
                number: !0,
            },
            "uciClosed": {
                number: !0,
            },
        },


        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
                var form = $('#updateUsedCarsForm');
                var fd = new FormData(document.getElementById("updateUsedCarsForm"));
                fd.append("CustomField", "This is some extra data");
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: fd,
                    contentType: false,
                    processData: false,
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
                            manageInvTable.ajax.reload(null, false);
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


    loadSaleConsultant();
});


function loadSaleConsultant() {
    // var sales_consultant_id = 38;
    var sales_consultant_id = 66;
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: sales_consultant_id },
        success: function (response) {
            var saleCnsltntArray = response.data;
            var selectBox = document.getElementById('salesConsultant');
            for (var i = 0; i < saleCnsltntArray.length; i++) {
                var item = saleCnsltntArray[i];
                selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}


function toggleFilterClass2() {
    $('.dtsp-panes').toggle();
}

function toggleInfo(id) {
    $('#' + id).toggleClass('d-none');
}

function editUsedCar(id) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedUsedCar.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                console.log(response);
                // stockno  stock and vin
                // selectedDetails   stock details
                $('#vehicleId').val(id);
                $('#stockno').val(response.stockno + " || " + response.vin);
                $('#submittedBy').val(response.submitted_by);
                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} \n Balance: ${response.balance}`;
                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');


                $('#invDate').val(response.date_in ? response.date_in : "");


                $('#retailStatus :radio[name="retailStatus"]').prop('checked', false);
                $('#retailStatus .active').removeClass('active');
                (response.retail_status) ? $('#' + response.retail_status).prop('checked', true).click() : null;


                $('#purchaseFrom :radio[name="purchaseFrom"]').prop('checked', false);
                $('#purchaseFrom .active').removeClass('active');
                (response.purchase_from) ? $('#' + response.purchase_from).prop('checked', true).click() : null;


                $('#certified').prop('checked', response.certified == "true" ? true : false);
                $('#title').prop('checked', response.title == "true" ? true : false);


                $('#titlePriority').val(response.title_priority ? response.title_priority : "");
                $('#salesConsultant').val(response.sales_consultant ? response.sales_consultant : "");
                $('#customerName').val(response.customer ? response.customer : "");


                $('#dateSent').val(response.date_sent ? response.date_sent : "");
                $('#dateSold').val(response.date_sold ? response.date_sold : "");
                $('#soldPrice').val(response.sold_price ? response.sold_price : "");
                // keys
                $('#keys').prop('checked', response.key == "true" ? true : false);


                $('#titleNotes').val(response.title_notes ? response.title_notes : "");
                $('#onlineDescription').val(response.online_description ? response.online_description : "");
                $('#roNotes').val(response.ro_online_notes ? response.ro_online_notes : "");

                // uci
                $('#uci').val(response.uci ? response.uci : "");
                $('#uciRo').val(response.uci_ro ? response.uci_ro : "");
                $('#uciApproved').val(response.uci_approved ? response.uci_approved : "");
                $('#uciClosed').val(response.uci_close ? response.uci_close : "");
                $('#uciClosed').val(response.uci_close ? response.uci_close : "");

                $('#oci').prop('checked', response.oci_ok == "true" ? true : false);

                setTimeout(() => {
                    autosize.update($(".autosize"));
                }, 500);
                $('.selectpicker').selectpicker('refresh');




            }, // /success
            error: function (err) {
                console.log(err);
            }

        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }
}
