"use strict";
var manageSoldLogsTable;
var stockArray = [];

var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})


$(function () {
    $("#saleDate").datetimepicker({
        language: 'pt-BR',
        format: 'M-dd-yyyy',
        minView: 2,
        pickTime: false,
    });

    function loadOpened() {
        $.fn.dataTable.ext.search.pop();
        manageSoldLogsTable.search('').draw();
        // var tableNode = this.table(0).node();
        var tableNode = $('#datatable-1')[0];
        // console.log(tableNode);
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                if (settings.nTable !== tableNode) {
                    return true;
                }
                var vin_check = data[5];
                var insurance = data[6];
                var trade_title = data[7];
                var registration = data[8];
                var inspection = data[9];
                var salesperson_status = data[10];
                var paid = data[11];
                if (
                    salesperson_status !== 'cancelled' && salesperson_status !== 'delivered'
                ) {

                    return true;
                }
                return false
            }
        )
        manageSoldLogsTable.draw();  // working
        manageSoldLogsTable.button(0).active(true);
        manageSoldLogsTable.button(1).active(false);
    }


    manageSoldLogsTable = $("#datatable-1").DataTable({



        // responsive: !0,
        // responsive: {
        //     details: {
        //         type: 'column',
        //         target: 1
        //     }
        // },
        // scrollY: 500,
        scrollX: !0,
        scrollCollapse: !0,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 1
        },

        'ajax': '../php_action/fetchSoldTodo.php',

        // working.... with both
        // dom: "Pfrtip",
        dom: `\n     
             <'row'<'col-12'P>>\n      
             <'row'<'col-sm-12 col-md-6'l>>\n  
            \n     
            <'row'<'col-sm-6 text-center text-sm-left p-3'B>
                <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
            <'row'<'col-12'tr>>\n      
            <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

        "pageLength": 25,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [1, 2, 3, 4],
            stateSave: true,
        },
        buttons: [
            {
                text: 'Opened',
                action: function (e, dt, node, config) {
                    loadOpened();
                },

            },
            {
                text: 'Completed',
                action: function (e, dt, node, config) {
                    manageSoldLogsTable.button(0).active(false);
                    manageSoldLogsTable.button(1).active(true);

                    $.fn.dataTable.ext.search.pop();
                    manageSoldLogsTable.search('').draw();
                    var tableNode = this.table(0).node();
                    // console.log(tableNode);
                    $.fn.dataTable.ext.search.push(
                        function (settings, data, dataIndex) {
                            if (settings.nTable !== tableNode) {
                                return true;
                            }
                            var vin_check = data[5];
                            var insurance = data[6];
                            var trade_title = data[7];
                            var registration = data[8];
                            var inspection = data[9];
                            var salesperson_status = data[10];
                            var paid = data[11];
                            if (
                                salesperson_status === 'cancelled' || salesperson_status === 'delivered'
                            ) {
                                return true;
                            }
                            return false
                        }
                    )
                    manageSoldLogsTable.draw();  // working


                },

            }
        ],

        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [1, 2, 3, 4],

            },
            {
                targets: 5,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(changePillCSS(cellData, 'vinCheck'));
                }
            },
            {
                targets: 6,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(changePillCSS(cellData, 'insurance'));
                }
            },
            {
                targets: 7,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(changePillCSS(cellData, 'tradeTitle'));
                }
            },
            {
                targets: 8,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(changePillCSS(cellData, 'registration'));
                }
            },
            {
                targets: 9,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(changePillCSS(cellData, 'inspection'));
                }
            },
            {
                targets: 10,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(changePillCSS(cellData, 'salePStatus'));
                }
            },
            {
                targets: 11,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(changePillCSS(cellData, 'paid'));
                }
            },
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
    });


    loadOpened();



    // ---------------------- Edit Sale---------------------------

    // validateState
    $("#editSoldTodoForm").validate({

        submitHandler: function (form, event) {
            // return true;
            event.preventDefault();

            var c = confirm('Do you really want to save this?');
            if (c == true) {
                $('[disabled]').removeAttr('disabled');
                var form = $('#editSoldTodoForm');
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

                            manageSoldLogsTable.ajax.reload();
                            manageSoldLogsTable.ajax.reload(null, false);
                            manageSoldLogsTable.searchPanes.rebuildPane();

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
    });



});



function changePillCSS(data, title) {
    switch (title) {
        case 'vinCheck':
            if (data == 'checkTitle') {
                return '<span class="badge badge-lg badge-danger badge-pill">Check Title</span>';
            } else if (data == 'need') {
                return '<span class="badge badge-lg badge-danger badge-pill">Need</span>';
            } else if (data == 'notNeed') {
                return '<span class="badge badge-lg badge-success badge-pill">Not Need</span>';
            } else if (data == 'n/a') {
                return '<span class="badge badge-lg badge-success badge-pill">N/A</span>';
            } else if (data == 'onHold') {
                return '<span class="badge badge-lg badge-success badge-pill">On Hold</span>';
            } else if (data == 'done') {
                return '<span class="badge badge-lg badge-success badge-pill">Done</span>';
            }
            break;
        case 'insurance':
            if (data == 'need') {
                return '<span class="badge badge-lg badge-danger badge-pill">Need</span>';
            } else if (data == 'inHouse') {
                return '<span class="badge badge-lg badge-success badge-pill">In House</span>';
            } else if (data == 'n/a') {
                return '<span class="badge badge-lg badge-success badge-pill">N/A</span>';
            }
            break;
        case 'tradeTitle':
            if (data == 'need') {
                return '<span class="badge badge-lg badge-danger badge-pill">Need</span>';
            } else if (data == 'payoff') {
                return '<span class="badge badge-lg badge-success badge-pill">Payoff</span>';
            } else if (data == 'noTrade') {
                return '<span class="badge badge-lg badge-success badge-pill">No Trade</span>';
            } else if (data == 'inHouse') {
                return '<span class="badge badge-lg badge-success badge-pill">In House</span>';
            }
            break;
        case 'registration':
            if (data == 'pending') {
                return '<span class="badge badge-lg badge-danger badge-pill">Pending</span>';
            } else if (data == 'done') {
                return '<span class="badge badge-lg badge-success badge-pill">Done</span>';
            } else if (data == 'customerHas') {
                return '<span class="badge badge-lg badge-success badge-pill">Customer Has</span>';
            } else if (data == 'mailed') {
                return '<span class="badge badge-lg badge-success badge-pill">Mailed</span>';
            } else if (data == 'n/a') {
                return '<span class="badge badge-lg badge-success badge-pill">N/A</span>';
            }
            break;
        case 'inspection':
            if (data == 'need') {
                return '<span class="badge badge-lg badge-danger badge-pill">Need</span>';
            } else if (data == 'notNeed') {
                return '<span class="badge badge-lg badge-success badge-pill">Not Need</span>';
            } else if (data == 'done') {
                return '<span class="badge badge-lg badge-success badge-pill">Done</span>';
            } else if (data == 'n/a') {
                return '<span class="badge badge-lg badge-success badge-pill">N/A</span>';
            }
            break;
        case 'salePStatus':
            if (data == 'dealWritten') {
                return '<span class="badge badge-lg badge-success badge-pill">Deal Written</span>';
            } else if (data == 'gmdSubmit') {
                return '<span class="badge badge-lg badge-success badge-pill">GMD Submit</span>';
            } else if (data == 'contracted') {
                return '<span class="badge badge-lg badge-success badge-pill">Contracted</span>';
            } else if (data == 'cancelled') {
                return '<span class="badge badge-lg badge-danger badge-pill">Cancelled</span>';
            } else if (data == 'delivered') {
                return '<span class="badge badge-lg badge-success badge-pill">Delivered</span>';
            }
            break;
        case 'paid':
            if (data == 'no') {
                return '<span class="badge badge-lg badge-danger badge-pill">No</span>';
            } else if (data == 'yes') {
                return '<span class="badge badge-lg badge-success badge-pill">Yes</span>';
            }
            break;

        default:
            break;
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
            url: '../php_action/fetchSelectedSoldTodo.php',
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



                $('#soldTodoId').val(id);

                $('#customerName').val(response.sale_consultant);
                $('#stockNo').val(response.stockno);
                $('#vehicle').val(`${response.stocktype} ${response.year} ${response.make} ${response.model}`);
                $('#state').val(response.state);
                $('#saleDate').datetimepicker('update', response.date);



                $('#vincheck').val(response.vin_check);
                $('#insurance').val(response.insurance);
                $('#tradeTitle').val(response.trade_title);
                $('#registration').val(response.registration);
                $('#inspection').val(response.inspection);
                $('#salePStatus').val(response.salesperson_status);
                $('#paid').val(response.paid);

                $('.selectpicker').selectpicker('refresh');

                chnageStyle({ id: 'vincheck', value: response.vin_check });
                chnageStyle({ id: 'insurance', value: response.insurance });
                chnageStyle({ id: 'tradeTitle', value: response.trade_title });
                chnageStyle({ id: 'registration', value: response.registration });
                chnageStyle({ id: 'inspection', value: response.inspection });
                chnageStyle({ id: 'salePStatus', value: response.salesperson_status });
                chnageStyle({ id: 'paid', value: response.paid });

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }

}

function removeTodo(id = null) {
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
                    url: '../php_action/removeSoldTodo.php',
                    type: 'post',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageSoldLogsTable.ajax.reload(null, false);
                            manageSoldLogsTable.searchPanes.rebuildPane();
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });

    }
}



function toggleInfo(className) {
    $('.' + className).toggleClass('hidden')
}


function chnageStyle(field) {

    var ele = $(`button[data-id="${field.id}"]`);

    switch (field.id) {

        case 'vincheck':
            if (field.value == 'checkTitle' || field.value == 'need') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;

        case 'insurance':
        case 'tradeTitle':
        case 'inspection':
            if (field.value == 'need') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        case 'registration':
            if (field.value == 'pending') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        case 'salePStatus':
            if (field.value == 'dealWritten') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        case 'paid':
            if (field.value == 'no') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        default:
            break;
    }


}
