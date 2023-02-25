"use strict";
var manageSoldLogsTable;
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
    $("#saleDate").datetimepicker({
        language: 'pt-BR',
        format: 'M-dd-yyyy',
        minView: 2,
        pickTime: false,
    });


    manageSoldLogsTable = $("#datatable-1").DataTable({



        responsive: !0,
        // responsive: {
        //     details: {
        //         type: 'column',
        //         target: 1
        //     }
        // },

        'ajax': '../php_action/fetchSoldTodo.php',

        // working.... with both
        // dom: "Pfrtip",
        // dom: `\n     
        //      <'row'<'col-12'P>>\n      
        //      <'row'<'col-sm-12 col-md-6'l>>\n  
        //     \n     
        //     <'row'<'col-sm-6 text-center text-sm-left p-3'B>
        //         <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
        //     <'row'<'col-12'tr>>\n      
        //     <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,
        dom: `\n     
            <'row'<'col-12'P>>\n      
            <'row'<'col-sm-12 text-sm-left col-md-3 mb-2 '<'#statusFilterDiv'>> <'col-sm-12 col-md-6 text-center'B> <'col-sm-12 col-md-3 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,

        "pageLength": 150,
        searchPanes: {
            columns: [13, 2, 3, 4],
        },
        buttons: [
            {
                text: '&nbsp Expand/Collapse All',
                action: function () {
                    $('#datatable-1 tbody tr.dtrg-group.dtrg-start').each(function () {
                        var name = $(this).data('name');
                        collapsedGroups[name] = !collapsedGroups[name];
                        manageSoldLogsTable.draw(false);
                    });
                }
            },
            {
                extend: 'copyHtml5',
                title: 'Sold Todo',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Sold Todo',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
            {
                extend: 'print',
                title: 'Sold Todo',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
        ],
        // buttons: [
        //     {
        //         text: 'Opened',
        //         action: function (e, dt, node, config) {
        //             loadOpened();
        //         },

        //     },
        //     {
        //         text: 'Completed',
        //         action: function (e, dt, node, config) {
        //             manageSoldLogsTable.button(0).active(false);
        //             manageSoldLogsTable.button(1).active(true);

        //             $.fn.dataTable.ext.search.pop();
        //             manageSoldLogsTable.search('').draw();
        //             var tableNode = this.table(0).node();
        //             // console.log(tableNode);
        //             $.fn.dataTable.ext.search.push(
        //                 function (settings, data, dataIndex) {
        //                     if (settings.nTable !== tableNode) {
        //                         return true;
        //                     }
        //                     var vin_check = data[5];
        //                     var insurance = data[6];
        //                     var trade_title = data[7];
        //                     var registration = data[8];
        //                     var inspection = data[9];
        //                     var salesperson_status = data[10];
        //                     var paid = data[11];
        //                     if (
        //                         salesperson_status === 'cancelled' || salesperson_status === 'delivered'
        //                     ) {
        //                         return true;
        //                     }
        //                     return false
        //                 }
        //             )
        //             manageSoldLogsTable.draw();  // working


        //         },

        //     }
        // ],

        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [13, 2, 3, 4],
            },
            {
                targets: [12, 13],
                visible: false,
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
            "infoFiltered": "",
            // searchPanes: {
            //     count: "{total} found",
            //     countFiltered: "{shown} / {total}"
            // }
        },

        rowGroup: {
            enable: $('#isConsultant').val() == 'false' ? true : false,
            dataSrc: 13,
            startRender: function (rows, group) {
                var collapsed = !!collapsedGroups[group];

                rows.nodes().each(function (r) {
                    r.style.display = 'none';
                    if (collapsed) {
                        r.style.display = '';
                    }
                });

                var filteredData = $('#datatable-1').DataTable()
                    .rows({ search: 'applied' })
                    .data()
                    .filter(function (data, index) {
                        return data[13] == group ? true : false;
                    });

                return $('<tr/>')
                    .append('<td style="text-align:left!important" colspan="14">' + group + ' (' + filteredData.length + ')</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            },
        },
        initComplete: function () {
            // Start with closed groups
            $('#datatable-1 tbody tr.dtrg-start').each(function () {
                var name = $(this).data('name');
                collapsedGroups[name] = !!collapsedGroups[name];
            });
            manageSoldLogsTable.draw(false);
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).attr({
                    "data-toggle": "modal",
                    "data-target": "#editDetails",
                    "onclick": "editDetails(" + data[12] + ")"
                });
            }
        },
        "order": [[13, "asc"], [0, "asc"]],

    });

    writeStatusHTML();
    $('#opened').click();

    // Collapse Groups
    $('#datatable-1 tbody').on('click', 'tr.dtrg-start', function () {
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        manageSoldLogsTable.draw(false);
    });



    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index) {
            var tableNode = manageSoldLogsTable.table().node();
            // console.log(settings.preSelect);
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

            if (searchStatus[0] === 'opened') {
                var salesperson_status = searchData[10];
                if (salesperson_status === 'cancelled') {
                    return false;
                } else {
                    // Delivered AND all others to do should stay in Opened status if anything else is in the RED…except paid
                    var vin_check = searchData[5];
                    var insurance = searchData[6];
                    var trade_title = searchData[7];
                    var registration = searchData[8];
                    var inspection = searchData[9];
                    // if (
                    //     (vin_check !== 'checkTitle' && vin_check !== 'need') &&
                    //     insurance !== 'need' && trade_title !== 'need' &&
                    //     registration !== 'pending' && inspection !== 'need') {
                    //     return false;
                    // } else {
                    //     return true;
                    // }
                    if (
                        (vin_check !== 'checkTitle' && vin_check !== 'need') &&
                        insurance !== 'need' && trade_title !== 'need' &&
                        (registration !== 'pending' && registration !== 'done') && inspection !== 'need' && (salesperson_status === 'cancelled' || salesperson_status === 'delivered')) {
                        return false;
                    } else {


                        let houseDealFilter = $('#houseDealFilter:checked').is(':checked');
                        if (houseDealFilter == true) {
                            return true;
                        } else {
                            if (searchData[13] != 'House Deal') {
                                return true;
                            }
                        }
                    }
                }
            }
            if (searchStatus[0] === 'completed') {
                var salesperson_status = searchData[10];

                if (salesperson_status === 'cancelled' || salesperson_status === 'delivered') {

                    // return true;
                    // Delivered to do should stay in Opened status if anything else is in the RED…except paid
                    if (salesperson_status === 'delivered') {
                        var vin_check = searchData[5];
                        var insurance = searchData[6];
                        var trade_title = searchData[7];
                        var registration = searchData[8];
                        var inspection = searchData[9];
                        if (
                            (vin_check === 'checkTitle' || vin_check === 'need') ||
                            insurance === 'need' || trade_title === 'need' ||
                            (registration === 'pending' || registration === 'done') || inspection === 'need'
                        ) {
                            return false;
                        } else {
                            // return true;
                            let houseDealFilter = $('#houseDealFilter:checked').is(':checked');
                            if (houseDealFilter == true) {
                                return true;
                            } else {
                                if (searchData[13] != 'House Deal') {
                                    return true;
                                }
                            }
                        }
                    } else {
                        // return true;
                        let houseDealFilter = $('#houseDealFilter:checked').is(':checked');
                        if (houseDealFilter == true) {
                            return true;
                        } else {
                            if (searchData[13] != 'House Deal') {
                                return true;
                            }
                        }
                    }
                }

                // var vin_check = searchData[5];
                // var insurance = searchData[6];
                // var trade_title = searchData[7];
                // var registration = searchData[8];
                // var inspection = searchData[9];
                // if (
                //     (vin_check === 'checkTitle' || vin_check === 'need') &&
                //     insurance === 'need' && trade_title === 'need' &&
                //     registration === 'pending' && inspection === 'need' &&
                //     salesperson_status === 'dealwritten'
                // ) {
                //     return false;
                // } else {
                //     return true;
                // }


            }


            return false;
        }
    );


    $('input:radio , #houseDealFilter:checkbox').on('change', function () {

        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });

        manageSoldLogsTable.draw();  // working
        manageSoldLogsTable.searchPanes.rebuildPane();

    });


    // ---------------------- Edit Sale---------------------------

    // validateState
    $("#editSoldTodoForm").validate({

        submitHandler: function (form, event) {
            // return true;
            event.preventDefault();

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
        <div class="col-md-12 d-flex align-items-center justify-content-between">
            <div id="sort">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="opened" value="opened"> Opened
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="completed" value="completed"> Completed
                    </label>
                </div>
            </div>
            <div class="custom-control custom-control-lg custom-checkbox pr-2">
                <input type="checkbox" class="custom-control-input" id="houseDealFilter">
                <label class="custom-control-label" for="houseDealFilter">House Deal</label>
            </div>
        </div>
    </div>`;
    }
}

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
                return '<span class="badge badge-lg badge-primary badge-pill">Done</span>';
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
            } else if (data == 'donebycustomer') {
                return '<span class="badge badge-lg badge-success badge-pill">Done by Customer</span>';
            } else if (data == 'n/a') {
                return '<span class="badge badge-lg badge-success badge-pill">N/A</span>';
            }
            break;
        case 'salePStatus':
            if (data == 'dealWritten') {
                return '<span class="badge badge-lg badge-primary badge-pill">Deal Written</span>';
            } else if (data == 'gmdSubmit') {
                return '<span class="badge badge-lg badge-primary badge-pill">GMD Submit</span>';
            } else if (data == 'contracted') {
                return '<span class="badge badge-lg badge-primary badge-pill">Contracted</span>';
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

                $('#customerName').val(response.fname + ' ' + response.lname);
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
                ele.removeClass('btn-outline-primary');
            } else if (field.value == 'done') {
                ele.addClass('btn-outline-primary');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-primary');
            }
            break;
        case 'salePStatus':
            if (field.value == 'cancelled') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
                ele.removeClass('btn-outline-primary');
            } else if (field.value == 'dealWritten' || field.value == 'contracted' || field.value == 'gmdSubmit') {
                ele.addClass('btn-outline-primary');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-primary');
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
