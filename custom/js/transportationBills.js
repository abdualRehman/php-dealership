"use strict";
var manageBillsTable;
var stockArray = [];
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
    $('#more').addClass('active');

    $("#date_in_paid").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,
    });
    $("#date_out_paid").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });


    manageBillsTable = $("#datatable-1").DataTable({

        responsive: !0,
        'ajax': '../php_action/fetchTransportationBills.php',
        dom: `\n     
            <'row'<'col-12'P>>\n      
            <'row'<'col-sm-12 text-sm-left col-md-3 mb-2 '<'#statusFilterDiv'>> <'col-sm-12 col-md-6 text-center 'B> <'col-sm-12 col-md-3 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,

        "pageLength": 50,
        searchPanes: {
            columns: [1, 2, 3],
        },
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Transportation Bills',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Transportation Bills',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'print',
                title: 'Transportation Bills',
                exportOptions: {
                    columns: [':visible']
                }
            },
        ],
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [1, 2, 3],
            },
            {
                targets: [0],
                visible: false,
            },

            {
                targets: [7],
                data: 7, // repairs 
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(cellData);
                    $(td).css('textTransform', 'capitalize');
                }
            },
        ],

        language: {
            "infoFiltered": "",
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#editDetails",
                    "onclick": "editDetails(" + data[0] + ")"
                });
            }
        },
        "order": [[0, "asc"]],

    });

    writeStatusHTML();
    $('#notPaid').click();




    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index) {
            var tableNode = manageBillsTable.table().node();

            var purchase_from = searchData[7];
            var date_in_paid = searchData[8];
            var date_sent = searchData[9];
            var date_out_paid = searchData[10];


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

            if (searchStatus[0] === 'notPaid') {
                if ((date_in_paid == '') || (date_sent != '' && date_out_paid == '')) {
                    return true;
                }
            }
            if (searchStatus[0] === 'paid') {
                if ((date_in_paid != '') || (date_sent != '' && date_out_paid != '')) {
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

        manageBillsTable.draw();  // working
        manageBillsTable.searchPanes.rebuildPane();

    });


    // ---------------------- Edit Sale---------------------------

    // validateState
    $("#editTransportationForm").validate({

        submitHandler: function (form, event) {
            // return true;
            event.preventDefault();

            var form = $('#editTransportationForm');
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

                        manageBillsTable.ajax.reload();
                        manageBillsTable.ajax.reload(null, false);
                        manageBillsTable.searchPanes.rebuildPane();
                        $('#editDetails').modal('hide');

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
                        <input type="radio" name="searchStatus" id="notPaid" value="notPaid"> Not Paid
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="paid" value="paid"> Paid
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
}


function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
function editDetails(id = null) {

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


                $('#usedCarId').val(id);
                $('#dateIn').val(response.date_in);
                $('#stockno').val(response.stockno + ' ' + response.vin);
                $('#vehicle').val(response.year + ' ' + response.make + ' ' + response.model);
                $('#purchaseFrom').val(response.purchase_from);
                $('#purchaseFrom').css('textTransform', 'capitalize');
                $('#date_in_paid').val(response.date_in_paid);
                $('#date_in_paid').attr("disabled", (response.purchase_from == "auction" ? false : true));
                $('#dateSent').val(response.date_sent);
                $('#date_out_paid').val(response.date_out_paid);
                $('#date_out_paid').attr("disabled", (response.date_sent != "" && response.date_sent != null ? false : true));

                $('#notes').val(response.notes);


                // $('#saleDate').datetimepicker('update', response.date);
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


function toggleInfo(className) {
    $('.' + className).toggleClass('hidden')
}


