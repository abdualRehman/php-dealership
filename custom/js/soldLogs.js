"use strict";
var manageSoldLogsTable, rowGroupSrc = 10; // status
var stockArray = [], deliveryCoordinatorArray = [], manageNotDoneTable;
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
        format: 'mm-dd-yyyy hh:ii',
        minView: 2,
        pickTime: false,
    });

    $("#reconcileDate").datepicker({
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
    });

    $(".scheduleDate").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        autoclose: true,
    });

    $('.scheduleTime').timepicker({
        dynamic: true,
        dropdown: true,
        'showDuration': false,
        disableTextInput: true,
        scrollbar: true,
        show24Hours: false,
        interval: 60,
    });

    autosize($(".autosize"));

    var deliveryCoordinatorID = Number(localStorage.getItem('deliveryCoordinatorID'));
    var divRequest = $(".div-request").text();

    if (divRequest == "man") {

        $('.nav-link').removeClass('active');
        $('#soldLogsPage').addClass('active');

        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        // alert($('#isConsultant').val());
        manageSoldLogsTable = $("#datatable-1").DataTable({
            responsive: !0,
            'ajax': '../php_action/fetchSoldLogs.php',
            dom: `\n     
            <'row'<'col-12'P>>\n      
            <'row'<'col-sm-12 text-sm-left col-md-3 mb-2'B> <'col-sm-12 col-md-6 text-center '<'#statusFilterDiv'>  > <'col-sm-12 col-md-3 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,

            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [3, 4, 5, 16]
            },
            "pageLength": 25,
            autoWidth: false,
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: 'Sold Logs',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Sold Logs',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'print',
                    title: 'Sold Logs',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
            ],
            columnDefs: [
                { width: 200, targets: 11 },
                {
                    targets: [16, 17, 18],
                    visible: false,
                },
                {
                    targets: [3],
                    visible: ($('#isConsultant').val() == "true") ? false : true,
                },
                {
                    targets: [9],
                    createdCell: function (td, cellData, rowData, row, col) {
                        if (rowData[22] == 'true') {
                            $(td).html(cellData + ' <span class="badge badge-danger badge-lg badge-pill">!</span>');
                        }
                    }
                },
                {
                    targets: [9, 12],
                    visible: ($('#vgb').val() == "true") ? true : false,
                },
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [3, 4, 5],

                },
                {
                    searchPanes: {
                        // show: true,
                        preSelect: ['NEW', 'USED']
                        // preSelect: ['OTHER']
                    },
                    targets: [16]
                },
                {
                    targets: 10,
                    createdCell: function (td, cellData, rowData, row, col) {
                        if (cellData == 'pending') {
                            $(td).html('<span class="badge badge-info badge-pill">Pending</span>');
                        } else if (cellData == 'delivered') {
                            $(td).html('<span class="badge badge-success badge-pill">Delivered</span>');
                        } else if (cellData == 'cancelled') {
                            $(td).html('<span class="badge badge-danger badge-pill">Cancelled</span>');
                        }

                    }
                },
                {
                    targets: [4, 5],
                    createdCell: function (td, cellData, rowData, row, col) {
                        if (col == 4 && rowData[21] == 'true') {
                            $(td).html(cellData + ' <span class="badge badge-danger badge-lg badge-pill">$</span>');
                        }
                        if (rowData[17] > 0) {
                            if (col == 4) {
                                $(td).addClass('dublicate_left');
                            }
                            if (col == 5) {
                                $(td).addClass('dublicate_right');
                            }
                        }
                    }
                },
                {
                    targets: [14],
                    createdCell: function (td, cellData, rowData, row, col) {
                        if (cellData == 'dealWritten') {
                            $(td).html('<span class="badge badge-lg badge-success badge-pill">Deal Written</span>');
                        } else if (cellData == 'gmdSubmit') {
                            $(td).html('<span class="badge badge-lg badge-success badge-pill">GMD Submit</span>');
                        } else if (cellData == 'contracted') {
                            $(td).html('<span class="badge badge-lg badge-success badge-pill">Contracted</span>');
                        } else if (cellData == 'cancelled') {
                            $(td).html('<span class="badge badge-lg badge-danger badge-pill">Cancelled</span>');
                        } else if (cellData == 'delivered') {
                            $(td).html('<span class="badge badge-lg badge-success badge-pill">Delivered</span>');
                        }
                    }
                }
            ],

            language: {
                "infoFiltered": "",
                searchPanes: {
                    count: "{total} found",
                    countFiltered: "{shown} / {total}"
                }
            },

            rowGroup: {
                dataSrc: 10,
                startRender: function (rows, group) {
                    var collapsed = !!collapsedGroups[group];

                    var filteredData = $('#datatable-1').DataTable()
                        .rows({ search: 'applied' })
                        .data()
                        .filter(function (data, index) {
                            return data[10] == group ? true : false;
                        });
                    // setting total numbers
                    $('#' + group + 'Count').html(filteredData.length);

                    return $('<tr/>')
                        .append('<td colspan="16">' + group + ' (' + filteredData.length + ')</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
                }
            },
            "drawCallback": function (settings, start, end, max, total, pre) {
                var json = this.fnSettings().json;
                if (json) {
                    var obj = json.data;
                    var todayCount = 0, yesterdayCount = 0, AllCount = obj.length, currentMonthCount = 0, pendingCount = 0, deliveredCount = 0, cancelledCount = 0, notDoneCount = 0;

                    var date = new Date();
                    var today = moment(date).format("MMM-DD-YYYY");

                    var date2 = moment(new Date(), "MMM-DD-YYYY").subtract(1, 'days');
                    var yesterday = moment(date2).format("MMM-DD-YYYY")

                    const startOfMonth = moment().startOf('month').format('MMM-DD-YYYY');
                    const endOfMonth = moment().endOf('month').format('MMM-DD-YYYY');

                    for (const [key, value] of Object.entries(obj)) {
                        // console.log(value[0]);
                        var rowDate = value[0]; // sold + reconcile date
                        var rowSoldDateOnly = value[20]; // sold only

                        let sale_status = value[10];
                        let thankyou = value[19];

                        if (sale_status == 'delivered' && thankyou != 'on') {
                            notDoneCount += 1;
                        }

                        if (today === rowSoldDateOnly) {
                            todayCount += 1;
                        }

                        if (yesterday === rowSoldDateOnly) {
                            yesterdayCount += 1;
                        }

                        var min = startOfMonth;
                        var max = endOfMonth;
                        if (
                            (min === null && max === null) ||
                            (min === null && rowDate <= max) ||
                            (min <= rowDate && max === null) ||
                            (min <= rowDate && rowDate <= max)
                        ) {
                            currentMonthCount += 1;
                        }

                    }


                    $('#datatable-1').DataTable()
                        .rows({ search: 'applied' })
                        .data()
                        .filter(function (data, index) {
                            if (data[10] == 'pending') {
                                pendingCount += 1;
                            } else if (data[10] == 'delivered') {
                                deliveredCount += 1;
                            } else if (data[10] == 'cancelled') {
                                cancelledCount += 1;
                            }
                            return true;
                        });

                    $(`#todayCount`).html(todayCount);
                    $(`#yesterdayCount`).html(yesterdayCount);
                    $(`#currentMonthCount`).html(currentMonthCount);
                    $('#notDoneCount').html(notDoneCount);
                    $(`#AllCount`).html(AllCount);
                    $('#pendingCount').html(pendingCount);
                    $('#deliveredCount').html(deliveredCount);
                    $('#cancelledCount').html(cancelledCount);


                }
            },
            createdRow: function (row, data, dataIndex) {
                // $(row).attr({
                //     "data-toggle": "modal",
                //     "data-target": "#showDetails",
                //     "onclick": "showDetails(" + data[18] + ")"
                // });
                if ($('#isEditAllowed').val() == "true") {
                    $(row).children().not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#editSaleModal",
                        "onclick": "editSale(" + data[18] + ")"
                    });
                }
            },
            "order": [[10, "desc"], [3, "asc"], [2, "asc"]]
        });


        writeStatusHTML();
        disabledManagerDiv();
        loadDeliveryCoordinator();

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const filter = urlParams.get('filter');

        if (filter == 'today') {
            $('#todayBtn').click();
            $('#searchStatusAll').click();
        } else if (filter == 'pending') {
            $('#modAll').click();
            $('#searchPending').click();
        } else if (filter == 'month') {
            $('#currentMonth').click();
            $('#searchStatusAll').click();
        } else {
            $('#currentMonth').click();
            $('#searchStatusAll').click();
        }


        // --------------------- checkboxes query --------------------------------------


        $.fn.dataTable.ext.search.push(
            function (settings, searchData, index, rowData, counter) {
                var tableNode = manageSoldLogsTable.table().node();


                var dateType = $('input:radio[name="radio-date"]:checked').map(function () {
                    if (this.value !== "") {
                        return this.value;
                    }
                }).get();

                if (dateType.length === 0) {
                    return true;
                }

                if (dateType == 'all') {
                    return true;
                } else if (dateType == 'today') {
                    var date2 = new Date();
                    var today = moment(date2).format("MMM-DD-YYYY");
                    if (today === rowData[20]) {  // here rowData[20] is sold date only
                        return true;
                    }

                } else if (dateType == 'yesterday') {
                    var date2 = moment(new Date(), "MMM-DD-YYYY").subtract(1, 'days');
                    var yesterday = moment(date2).format("MMM-DD-YYYY")
                    if (yesterday === rowData[20]) {
                        return true;
                    }
                } else if (dateType == 'currentMonth') {
                    const startOfMonth = moment().startOf('month').format('MMM-DD-YYYY');
                    const endOfMonth = moment().endOf('month').format('MMM-DD-YYYY');

                    var date = searchData[0];
                    var min = startOfMonth;
                    var max = endOfMonth;
                    if (
                        (min === null && max === null) ||
                        (min === null && date <= max) ||
                        (min <= date && max === null) ||
                        (min <= date && date <= max)
                    ) {
                        return true;
                    }
                }



                if (settings.nTable !== tableNode) {
                    return true;
                }

                return false;
            }
        );



        $.fn.dataTable.ext.search.push(
            function (settings, searchData, index, rowData, counter) {
                var tableNode = manageSoldLogsTable.table().node();

                var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                    if (this.value !== "") {
                        return this.value;
                    }
                }).get();

                if (searchStatus.length === 0) {
                    return true;
                }

                if (searchStatus.indexOf(searchData[10]) !== -1) {
                    return true;
                }
                if (settings.nTable !== tableNode) {
                    return true;
                }
                return false;
            }
        );


        $('input:radio[name="radio-date"]').on('change', function () {

            var currentElement = $(this).val();
            if (currentElement != "notDone") {
                $('.soldLogs').removeClass('d-none');
                $('.notDone').addClass('d-none');

                $('#datatable-1').block({
                    message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
                    timeout: 1e3
                });

                manageSoldLogsTable.draw();  // working
                manageSoldLogsTable.searchPanes.rebuildPane();

            } else if (currentElement == 'notDone') {
                $('.soldLogs').addClass('d-none');
                $('.notDone').removeClass('d-none');
                fetchNotDoneSoldLogs();
            }

        });




        $('input:radio[name="searchStatus"]').on('change', function () {
            $('#datatable-1').block({
                message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
                timeout: 1e3
            });
            manageSoldLogsTable.draw();  // working
            manageSoldLogsTable.searchPanes.rebuildPane();
        });




        $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MMM-DD-YYYY') + ' / ' + picker.endDate.format('MMM-DD-YYYY'));
            applyDateRageFilter(picker.startDate.format('MMM-DD-YYYY'), picker.endDate.format('MMM-DD-YYYY'));
            $('#datatable-1').block({
                message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
                timeout: 1e3
            });
            manageSoldLogsTable.draw();  // working
            manageSoldLogsTable.searchPanes.rebuildPane();
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            applyDateRageFilter();
            $('#datatable-1').block({
                message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
                timeout: 1e3
            });
            manageSoldLogsTable.draw();  // working
            manageSoldLogsTable.searchPanes.rebuildPane();
        });


        $("#editScheduleForm").validate({
            ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
            rules: {
                ecustomerName: {
                    required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
                },
                escheduleTime: {
                    required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
                },
                escheduleDate: {
                    required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
                },
                esale_id: {
                    required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
                },
                ecoordinator: {
                    required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
                },
                // eoverrideBy: {
                //     required: function (params) {
                //         if ($('#loggedInUserRole').val() != deliveryCoordinatorID) {
                //             return true;
                //         } else {
                //             $(params).removeClass('is-invalid');
                //             return false;
                //         }
                //     },
                // },
                'edelivery': {
                    required: function (params) {
                        var opt = $('input:radio[name="eadditionalServices"]:checked').val();
                        if (!opt && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                },
                'eadditionalServices': {
                    required: function (params) {
                        var opt = $('input:radio[name="edelivery"]:checked').val();
                        if (!opt && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                },
                'escheduleNotes': {
                    required: function (params) {
                        var opt = $('input:radio[name="eadditionalServices"]:checked').val();
                        if (opt == 'other' && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                }
            },
            submitHandler: function (form, e) {
                e.preventDefault();

                var time = $('#escheduleTime').val();
                const number = moment(time, ["h:mmA"]).format("HH:mm");
                $('#escheduleTime').val(number);

                var form = $('#editScheduleForm');
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
                            $('#editScheduleModel').modal('hide');
                            manageSoldLogsTable.ajax.reload(null, false);
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


    }
    $(function () {

        loadStock();
        loadSaleConsultant();
        loadSaleManager();
        loadFinanceManager();


        // ---------------------- Edit Sale---------------------------
        // validateState
        $("#editSaleForm").validate({
            ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
            rules: {
                saleDate: {
                    required: !0,
                },
                stockId: {
                    required: function (params) {
                        if (params.value == 0) {
                            params.classList.add('is-invalid');
                            $('#stockId').selectpicker('refresh');
                            params.classList.add('is-invalid');
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                salesPerson: {
                    required: function (params) {
                        if (params.value == 0) {
                            params.classList.add('is-invalid');
                            $('#salesPerson').selectpicker('refresh');
                            params.classList.add('is-invalid');
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                fname: {
                    required: !0,
                },
                lname: {
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
                    },
                },

            },
            messages: {
                fname: {
                    required: "",
                },
                lname: {
                    required: "",
                },

                state: {
                    required: "",
                },
            },
            submitHandler: function (form, event) {
                // return true;
                event.preventDefault();

                $('[disabled]').removeAttr('disabled');
                var form = $('#editSaleForm');
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
                            manageSoldLogsTable.ajax.reload(null, false);
                            $('#editSaleModal').modal('hide');
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

    $('.clear-selection-btn-group').on('click', function () {
        var targetElement = $(this).attr('data-targetElement');
        let elementId = $(this).attr("id");
        var prev = $(this).children('label.active').find(':radio:checked').val();
        setTimeout(() => {
            let current = $('#' + elementId + ' .active :radio:checked').val();
            if (prev == current) {
                $('#' + elementId + ' :radio').prop('checked', false);
                $('#' + elementId + ' .active').removeClass('active');
            }
            if (current != 'ok') {
                $('#' + targetElement).addClass('disabled-div');
                $('#' + targetElement + ' :radio').prop('checked', false);
                $('#' + targetElement + ' .active').removeClass('active');
            } else {
                $('#' + targetElement).removeClass('disabled-div');
            }
        }, 100);
    })



});
// ------------------------------------------------------------------------------------------------------------






function addNewSchedule(id = null) {
    if (id) {
        $.ajax({
            url: '../php_action/fetchSelectedAppointmentBySaleId.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editScheduleForm')[0].reset();

                $('#scheduleId').val(response.id);
                $('#ecallenderId').val(response.calender_id);
                $('#esale_id').val(response.sale_id);
                // echangeStockDetails({ value: response.sale_id }, false);

                $('#ecustomerName').val(response.fname + ' ' + response.lname);
                $('#evechicle').val(response.stocktype + ' ' + response.year + ' ' + response.make);
                $('#estockno').val(response.stock_id)
                $('#estocknoDisplay').val(response.stockno)
                $('#ehas_appointment').val(response.already_have);
                $('#esubmittedBy').val(response.submitted_by);
                $('#esubmittedByRole').val(response.submitted_by_role);
                $('#esubmittedById').val(response.submitted_by_id);
                $('#eoverrideBy').prop('checked', (response.manager_override != "" && response.manager_override != null) ? true : false);
                $('#eoverrideByName').val(response.manager_overrideName);
                $('#eoverrideById').val(response.eoverrideById);

                const number = response.appointment_time ? moment(response.appointment_time, ["HH:mm"]).format("h:mma") : "";
                $('#escheduleTime').val(number);
                var date = response.appointment_date ? moment(response.appointment_date, 'YYYY-MM-DD').format('MM-DD-YYYY') : "";
                $('#escheduleDate').val(date);
                $('#escheduleDate').datepicker('update', date);
                $('#edelivery :radio[name="edelivery"]').prop('checked', false);
                $('#edelivery .active').removeClass('active');
                (response.delivery) ? $('#e' + response.delivery).prop('checked', true).click() : null;
                $('#eadditionalServices :radio[name="eadditionalServices"]').prop('checked', false);
                $('#eadditionalServices .active').removeClass('active');
                (response.additional_services) ? $('#e' + response.additional_services).prop('checked', true).click() : null;
                $('#escheduleNotes').val(response.notes);
                $('#econfirmed :radio[name="econfirmed"]').prop('checked', false);
                $('#econfirmed .active').removeClass('active');
                (response.confirmed) ? $('#con' + response.confirmed).prop('checked', true).click() : null;
                $('#ecomplete :radio[name="ecomplete"]').prop('checked', false);
                $('#ecomplete .active').removeClass('active');
                (response.complete) ? $('#com' + response.complete).prop('checked', true).click() : null;

                if (response.confirmed != "ok") {
                    $('#ecomplete').addClass('disabled-div');
                } else {
                    $('#ecomplete').removeClass('disabled-div');
                }

                $('#ecoordinator').val(response.coordinator);
                var checkSelectValue = $('#ecoordinator').val();
                if (!checkSelectValue) {
                    var selectBox = document.getElementById('ecoordinatorList');
                    selectBox.innerHTML += `<option value="${response.coordinator}" title="${response.coordinator_name} - ${response.coordinator_email}">${response.coordinator_name} - ${response.coordinator_email} </option>`;
                    $('#ecoordinator').val(response.coordinator);
                }
                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}
$('#eoverrideBy').change(function () {
    if ($(this).prop('checked')) {
        var cN = $('#currentUser').val();
        var cId = $('#currentUserId').val();
        $('#eoverrideByName').val(cN);
        $('#eoverrideById').val(cId);
    } else {
        $('#eoverrideByName').val("");
        $('#eoverrideById').val("");
    }
})
function disabledManagerDiv() {
    let currentUser = $('#loggedInUserRole').val();
    var delivery_coordinator_id = Number(localStorage.getItem('deliveryCoordinatorID'));
    var sales_manager_id = Number(localStorage.getItem('salesManagerID'));
    var general_manager_id = Number(localStorage.getItem('generalManagerID'));
    if (currentUser != delivery_coordinator_id && currentUser != 'Admin') {
        $('.delivery_coordinator').addClass('disabled-div');
        $(".delivery_coordinator").find("*").prop("readonly", true);
    } else {
        if (currentUser == delivery_coordinator_id) {
            $('.appointment_div').addClass('disabled-div');
            $(".appointment_div").find("*").prop("readonly", true);
        } else {
            $('.appointment_div').removeClass('disabled-div');
            $(".appointment_div").find("*").prop("readonly", false);
        }
        $('.delivery_coordinator').removeClass('disabled-div');
        $(".delivery_coordinator").find("*").prop("readonly", false);
    }
    if (currentUser != 'Admin' && currentUser != sales_manager_id && currentUser != general_manager_id) {
        $('.manager_override_div').addClass('disabled-div');
        $(".manager_override_div").find("*").prop("readonly", true);
    } else {
        $('.manager_override_div').removeClass('disabled-div');
        $(".manager_override_div").find("*").prop("readonly", false);
    }

    $('#esubmittedBy , #eoverrideByName , #estocknoDisplay , #ecustomerName').addClass('disabled-div');
    $("#esubmittedBy , #eoverrideByName , #estocknoDisplay , #ecustomerName").find("*").prop("readonly", true);
}

function loadDeliveryCoordinator() {
    var id = Number(localStorage.getItem('deliveryCoordinatorID'));
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            deliveryCoordinatorArray = response.data;
        }
    });
}

$('.handleDateTime').on('change', function () {
    var date, time, selectBox;
    if ($(this).data('type') == 'add') {
        date = $('#scheduleDate').val();
        time = $('#scheduleTime').val();
        selectBox = document.getElementById('coordinatorList');
    } else {
        date = $('#escheduleDate').val();
        time = $('#escheduleTime').val();
        selectBox = document.getElementById('ecoordinatorList');
    }
    selectBox.innerHTML = "";
    $('.selectpicker').selectpicker('refresh');
    if ((date != '' && time != '') && moment(time, ["h:mmA"]).format("HH:mm") != 'Invalid date') {
        let dayname = moment(date).format('dddd').toLowerCase();
        deliveryCoordinatorArray.forEach(element => {
            let startTime = element[3][dayname][0];
            let endTime = element[3][dayname][1];
            let scheduledAppointments = element[4];
            if (startTime && endTime) {
                time = moment(moment(time, ["h:mmA"]).format("HH:mm"), 'hh:mm');
                startTime = moment(moment(startTime, ["h:mmA"]).format("HH:mm"), 'hh:mm');
                endTime = moment(moment(endTime, ["h:mmA"]).format("HH:mm"), 'hh:mm');
                if (time.isBetween(startTime, endTime, null, '[]')) {
                    let timeFormat = moment(time, ["h:mmA"]).format("HH:mm");
                    let dateFormat = moment(date, 'MM-DD-YYYY').format('YYYY-MM-DD');
                    let dateTime = moment(dateFormat + ' ' + timeFormat, 'YYYY-MM-DD hh:mm');
                    let allready_appointed = false;
                    scheduledAppointments.forEach(appointment => {
                        let schedule_start = moment(appointment.schedule_start, 'YYYY-MM-DD hh:mm');
                        let schedule_end = moment(appointment.schedule_end, 'YYYY-MM-DD hh:mm');
                        if (dateTime.isBetween(schedule_start, schedule_end, null, '[]')) {
                            allready_appointed = true;
                        }
                    });
                    if (allready_appointed == false) {
                        selectBox.innerHTML += `<option value="${element[0]}" title="${element[1]} - ${element[2]}">${element[1]} - ${element[2]} </option>`;
                        $('.selectpicker').selectpicker('refresh');
                    }
                }
            }
        });
    }
});
// ------------------------------------------------------------------------------------------------------------


function fetchNotDoneSoldLogs() {
    if ($.fn.dataTable.isDataTable('#datatable-2')) {

    }
    else {
        manageNotDoneTable = $("#datatable-2").DataTable({
            responsive: !0,
            'ajax': '../php_action/fetchNotDoneSoldLogs.php',
            "paging": true,
            "scrollX": true,
            "orderClasses": false,
            "deferRender": true,
            "pageLength": 25,
            autoWidth: false,
            "order": [[1, "desc"]],
            dom: `\n     
            <'row'<'col-12'P>>\n
           \n     
           <'row'<'col-sm-6 text-center text-sm-left pl-3'B>
                <'col-sm-6 text-right text-sm-right pl-3'f>>\n
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

            buttons: [
                {
                    extend: 'copyHtml5',
                    title: 'Not Done',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Not Done',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                {
                    extend: 'print',
                    title: 'Not Done',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
            ],
            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [3, 4, 5]
            },
            columnDefs: [
                {
                    targets: [0],
                    visible: false,
                },
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [3, 4, 5],
                },
                {
                    targets: 11,
                    createdCell: function (td, cellData, rowData, row, col) {
                        if (cellData == 'pending') {
                            $(td).html('<span class="badge badge-info badge-pill">Pending</span>');
                        } else if (cellData == 'delivered') {
                            $(td).html('<span class="badge badge-success badge-pill">Delivered</span>');
                        } else if (cellData == 'cancelled') {
                            $(td).html('<span class="badge badge-danger badge-pill">Cancelled</span>');
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
            "drawCallback": function (settings, start, end, max, total, pre) {
                var json = this.fnSettings().json;
                if (json) {
                    var obj = json.data;
                    $('#notDoneCount').html(obj.length);
                }
            },
            rowGroup: {
                dataSrc: 4,
                startRender: function (rows, group) {
                    var collapsed = !!collapsedGroups[group];

                    var filteredData = $('#datatable-2').DataTable()
                        .rows({ search: 'applied' })
                        .data()
                        .filter(function (data, index) {
                            console.log(data);
                            return data[4] == group ? true : false;
                        });
                    // setting total numbers
                    $('#' + group + 'Count').html(filteredData.length);

                    return $('<tr/>')
                        .append('<td colspan="13">' + group + ' (' + filteredData.length + ')</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
                }
            },
            createdRow: function (row, data, dataIndex) {
                if ($('#isEditAllowed').val() == "true") {
                    $(row).children().not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#showDetails",
                        "onclick": "showDetails(" + data[0] + ")"
                    });
                }
            },
            "order": [[4, "asc"]],
        })
    }
}



$('input[name="iscertified"]').on('change', function () {
    $('#iscertified').val($(this).val());
})














// ------------------------------------------------------------------------------------------------------------
function applyDateRageFilter(startOfMonth = "", endOfMonth = "") {

    // console.log(startOfMonth, endOfMonth);
    $.fn.dataTable.ext.search.pop();
    manageSoldLogsTable.search('').draw();
    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageSoldLogsTable.table().node();

            // var dateObject = $('input.dateRangePicker').map(function () {
            //     if (this.value !== "") {
            //         return this.value;
            //     }
            // }).get();
            // console.log(dateObject);

            if (startOfMonth == "" || endOfMonth == "") {
                return true;
            }


            // const startOfMonth = moment(dateObject[0]).format('MMM-DD-YYYY');
            // const endOfMonth = moment(dateObject[1]).format('MMM-DD-YYYY');

            // console.log(startOfMonth);
            // console.log(endOfMonth);

            var date = searchData[0];
            var min = startOfMonth;
            var max = endOfMonth;
            if (
                (min === null && max === null) ||
                (min === null && date <= max) ||
                (min <= date && max === null) ||
                (min <= date && date <= max)
            ) {
                return true;
            }

            if (settings.nTable !== tableNode) {
                return true;
            }
            return false;
        }
    );
}

function fetchSelectedInvForSearch(id = null) {
    $.ajax({
        url: '../php_action/fetchSelectedInvForSearch.php',
        type: 'post',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            stockArray.push(response.data);
            var item = response.data;
            var selectBox = document.getElementById('stockId');
            selectBox.innerHTML += `<option value="${item[0]}" selected title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} || Stock Deleted</option>`;
            $('.selectpicker').selectpicker('refresh');
            changeStockDetails({ value: item[0] }, true)
        }

    }); // /ajax function to remove the brand
}

function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div id="year">
                <div class="btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary">
                        <input type="radio" name="searchStatus" id="searchStatusAll" value=""> ALL
                    </label>
                    <label class="btn btn-outline-info">
                        <input type="radio" name="searchStatus" value="pending" id="searchPending" > Pending <span class="badge badge-lg p-1" id="pendingCount" ></span>
                    </label>
                    <label class="btn btn-outline-success">
                        <input type="radio" name="searchStatus" value="delivered"> Delivered <span class="badge badge-lg p-1" id="deliveredCount" ></span>
                    </label>
                    <label class="btn btn-outline-danger">
                        <input type="radio" name="searchStatus" value="cancelled"> Cancelled <span class="badge badge-lg p-0" id="cancelledCount" ></span>
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
function editSale(id = null) {

    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.eshowResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedSale.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                console.log(id);
                console.log(response);

                // modal loading
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.eshowResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                // modal footer
                $('#saleDate').datetimepicker('update', response.date);
                $('#saleId').val(id);

                if (response.reconcileDate != "") {
                    $('#reconcileDate').datepicker('update', moment(response.reconcileDate).format('MM-DD-YYYY'));
                }

                $('input[name="status"]').prop('checked', false);
                $('input[name="status"]').parent().removeClass('active')
                $('#' + response.sale_status).click();

                // $('#' + response.sale_status).attr("checked", "checked");
                // $('#' + response.sale_status).parent().addClass('active')


                $('#stockId').val(response.stock_id);

                $('input[name="iscertified"]').prop('checked', false);
                $('input[name="iscertified"]').parent().removeClass('active');
                if (response.certified == 'on') {
                    $('#yes').click();
                    $('#iscertified').val('on');
                } else if (response.certified == 'off') {
                    $('#no').click();
                    $('#iscertified').val('off');
                } else {
                    $('#yesOther').click();
                    $('#iscertified').val('yesOther');
                }

                // show/calclulate gross if stockTypes is used gross is shows otherwise hide 
                if ($('#vgb').val() == "true") {
                    $('#grossDiv').removeClass('v-none');
                } else {
                    $('#grossDiv').addClass('v-none');
                }
                $('#salesPerson').val(response.sales_consultant);

                $('#financeManager').val(response.finance_manager);

                $('input[name="dealType"]').prop('checked', false);
                $('input[name="dealType"]').parent().removeClass('active')
                if (response.deal_type) {
                    $('#' + response.deal_type).click();
                }

                $('#submittedBy').val(response.submittedBy);
                $('#consultantNote').val(response.consultant_notes);
                $('#thankyouCard').prop('checked', response.thankyou_cards == 'on' ? true : false);
                $('#dealNote').val(response.deal_notes);
                $('#fname').val(response.fname);
                $('#mname').val(response.mname);
                $('#lname').val(response.lname);
                $('#state').val(response.state);
                $('#address1').val(response.address1);
                $('#address2').val(response.address2);
                $('#city').val(response.city);
                $('#country').val(response.country);
                $('#zipCode').val(response.zipcode);
                $('#mobile').val(response.mobile);
                $('#altContact').val(response.altcontact);
                $('#email').val(response.email);

                $('#cbfname').val(response.cb_fname);
                $('#cbmname').val(response.cb_mname);
                $('#cblname').val(response.cb_lname);
                $('#cbstate').val(response.cb_state);
                $('#cbAddress1').val(response.cb_address1);
                $('#cbAddress2').val(response.cb_address2);
                $('#cbCity').val(response.cb_city);
                $('#cbCountry').val(response.cb_country);
                $('#cbZipCode').val(response.cb_zipcode);
                $('#cbMobile').val(response.cb_mobile);
                $('#cbAltContact').val(response.cb_altcontact);
                $('#cbEmail').val(response.cb_email);

                $('#profit').val(response.gross);

                // var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Vin: ${response.vin} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} \n Balance: ${response.balance}`;
                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Vin: ${response.vin} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} ${($('#vgb').val() == "true") ? `\n Balance: ${response.balance}` : ``}`;

                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');

                if (response.codp_warn == 'true') {
                    $('#codp_warn').removeClass('d-none');
                } else {
                    $('#codp_warn').addClass('d-none');
                }
                if (response.lwbn_warn == 'true') {
                    $('#lwbn_warn').removeClass('d-none');
                } else {
                    $('#lwbn_warn').addClass('d-none');
                }


                $('#college').val(response.college);
                $('#military').val(response.military);
                $('#loyalty').val(response.loyalty);
                $('#conquest').val(response.conquest);
                $('#misc1').val(response.misc1);
                $('#misc2').val(response.misc2);
                $('#leaseLoyalty').val(response.lease_loyalty);


                $('#vincheck').val(response.vin_check);
                $('#insurance').val(response.insurance);
                $('#tradeTitle').val(response.trade_title);
                $('#registration').val(response.registration);
                $('#inspection').val(response.inspection);
                $('#salePStatus').val(response.salesperson_status);
                $('#paid').val(response.paid);




                setTimeout(() => {
                    $('.selectpicker').selectpicker('refresh')
                    autosize.update($(".autosize"));
                }, 500);


                var checkValue = $('#stockId').val();
                if (!checkValue) {
                    // if Inventory item was deleted then search from deleted inv data
                    fetchSelectedInvForSearch(response.stock_id);
                } else {
                    changeRules();
                }


                setTimeout(() => {
                    chnageStyle({ id: 'vincheck', value: response.vin_check });
                    chnageStyle({ id: 'insurance', value: response.insurance });
                    chnageStyle({ id: 'tradeTitle', value: response.trade_title });
                    chnageStyle({ id: 'registration', value: response.registration });
                    chnageStyle({ id: 'inspection', value: response.inspection });
                    chnageStyle({ id: 'salePStatus', value: response.salesperson_status });
                    chnageStyle({ id: 'paid', value: response.paid });
                }, 1000);




            }, // /success
            error: function (err) {
                console.log(err);
            }



        }); // ajax function


    } else {
        alert('error!! Refresh the page again');
    }

}




function changeReconcile() {
    if (!$('#reconcileDate').attr('disabled')) {
        $('#reconcileDate').val('')
    }
    $('#reconcileDate').attr('disabled', function (_, attr) { return !attr });
}
function removeSale(saleId = null) {
    if (saleId) {
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
                    url: '../php_action/removeSale.php',
                    type: 'post',
                    data: { saleId: saleId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageSoldLogsTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });

    }
}

function loadStock() {
    var selectBox = document.getElementById('stockId');
    $.ajax({
        url: '../php_action/fetchInvForSearch.php',
        type: "POST",
        dataType: 'json',
        beforeSend: function () {
            // selectBox.setAttribute("disabled", true);
        },
        success: function (response) {
            stockArray = response.data;
            selectBox.innerHTML = `<option value="0" selected disabled>Stock No:</option>`;
            for (var i = 0; i < stockArray.length; i++) {
                var item = stockArray[i];
                selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
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
            var selectBox = document.getElementById('salesPerson');
            for (var i = 0; i < saleCnsltntArray.length; i++) {
                var item = saleCnsltntArray[i];
                selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function loadSaleManager() {
    var sales_manager_id = Number(localStorage.getItem('salesManagerID'));
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: sales_manager_id },
        success: function (response) {
            var array = response.data;
            var selectBoxs = document.getElementsByClassName('salesManagerList');

            selectBoxs.forEach(element => {
                for (var i = 0; i < array.length; i++) {
                    var item = array[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
                }
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}


function changeStockDetails(ele, fromEdit = false) {

    $('#detailsSection').removeClass('d-none');
    let obj = stockArray.find(data => data[0] === ele.value);

    console.log(obj);

    var retail = obj[12];
    retail = parseFloat(retail.replace(/\$|,/g, ''))
    var blnce = obj[11];
    blnce = parseFloat(blnce.replace(/\$|,/g, ''))
    var profit = retail - blnce;
    $('#profit').val(profit.toFixed(2));

    if (obj[13] == 'on') {
        $("#yes").prop("checked", true);
        $('#iscertified').val('on');
    } else {
        $("#no").prop("checked", true);
        $('#iscertified').val('off');
    }


    $('#selectedStockType').val(obj[14]); // setting up stockType for sales person Todo

    var detailsDiv = `${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]} \n Vin: ${obj[8]} \n Mileage: ${obj[31] == 1 ? obj[9] : ""} \n Age: ${obj[31] == 1 ? obj[10] : ""} \n Lot: ${obj[31] == 1 ? obj[7] : ""}  ${($('#vgb').val() == "true") ? `\n Balance: ${obj[31] == 1 ? obj[11] : ""} ${obj[31] == 2 ? "\n  Stock is Deleted" : ""}` : ``}`;
    $('#selectedDetails').html(detailsDiv);
    $('#selectedDetails').addClass('text-center');

    if (obj[32] == 'true') {
        $('#codp_warn').removeClass('d-none');
    } else {
        $('#codp_warn').addClass('d-none');
    }
    if (obj[33] == 'true') {
        $('#lwbn_warn').removeClass('d-none');
    } else {
        $('#lwbn_warn').addClass('d-none');
    }


    if ($('#vgb').val() == "true") {
        $('#grossDiv').removeClass('v-none'); // show gross field on both stock type new / used
    }

    if (fromEdit == false) {
        // for checking this stock is already in sale or not if it is in sale then and status is not cancelled then make it red
        if ((obj[16].length > 0) && obj[16].every(element => element != 'cancelled')) {
            $('#selectedDetails').addClass('text-center text-danger is-invalid');  // invalid selectarea section
            $('#saleDetailsDiv').addClass('is-invalid');  // invalid stock details div
            $('#grossDiv').addClass('v-none');  // hide gross div
            $('#stockId').parent().addClass('text-danger is-invalid'); // invalid stock input div
        } else {
            $('#selectedDetails').removeClass('text-danger is-invalid');  // valid selectarea section
            $('#saleDetailsDiv').removeClass('is-invalid');  // valid stock details div
            $('#stockId').parent().removeClass('text-danger is-invalid'); // valid stock input div
        }
    }

    autosize.update($("#selectedDetails"));

    if (fromEdit == false) {
        changeRules();
    }

}

function changeRules() {
    var eleV = $('#stockId').val();
    if (eleV) {
        let obj = stockArray.find(data => data[0] === eleV);
        // console.log(obj);

        chnageIncentiveStatus(obj[17], obj[18], 'college');
        chnageIncentiveStatus(obj[19], obj[20], 'military');
        chnageIncentiveStatus(obj[21], obj[22], 'loyalty');
        chnageIncentiveStatus(obj[23], obj[24], 'conquest');
        chnageIncentiveStatus(obj[25], obj[26], 'misc1');
        chnageIncentiveStatus(obj[27], obj[28], 'misc2');
        chnageIncentiveStatus(obj[29], obj[30], 'leaseLoyalty');

        $('.selectpicker').selectpicker('refresh');

        changeSalesPersonTodo();
    }

}

function chnageIncentiveStatus(value, date, element) {
    if (value != 'N/A') {
        var saleDate = $('#saleDate').val();
        saleDate = moment(saleDate).format('MM-DD-YYYY');

        var edate = moment(date);
        var cduration = moment.duration(edate.diff(saleDate));
        var cdays = cduration.asDays();
        cdays = Math.ceil(cdays);

        if (cdays >= 0) {
            $('#' + element).prop("disabled", false);
            $('#' + element + '_v').html('$' + value);
        } else {
            $('#' + element).prop("disabled", true);
            $('#' + element).val("No");
        }
    } else {
        $('#' + element).prop("disabled", true);
        $('#' + element).val("No");
        $('#' + element + '_v').html('');
    }
}

function changeSalesPersonTodo() {
    var eleV = $('#stockId').val();
    if (eleV) {
        let obj = stockArray.find(data => data[0] === eleV);

        var todoArray = obj['spTodoArray'];
        let state = $('#state').val();
        // console.log(todoArray);
        var saleDate = $('#saleDate').val();
        if (state && todoArray && todoArray.length > 0) {

            var spTodoRulesObj = todoArray.find(data => data[4] === state);

            if (spTodoRulesObj) {

                // console.log("Data found \n", spTodoRulesObj);
                changeSalesPersonTodoStyle("vincheck", spTodoRulesObj[5]);
                changeSalesPersonTodoStyle("insurance", spTodoRulesObj[6]);
                changeSalesPersonTodoStyle("tradeTitle", spTodoRulesObj[7]);
                changeSalesPersonTodoStyle("registration", spTodoRulesObj[8]);
                changeSalesPersonTodoStyle("inspection", spTodoRulesObj[9]);
                changeSalesPersonTodoStyle("salePStatus", spTodoRulesObj[10]);
                changeSalesPersonTodoStyle("paid", spTodoRulesObj[11]);

            } else {
                changeSalesPersonTodoStyle("vincheck", "N/A");
                changeSalesPersonTodoStyle("insurance", "N/A");
                changeSalesPersonTodoStyle("tradeTitle", "N/A");
                changeSalesPersonTodoStyle("registration", "N/A");
                changeSalesPersonTodoStyle("inspection", "N/A");
                changeSalesPersonTodoStyle("salePStatus", "N/A");
                changeSalesPersonTodoStyle("paid", "N/A");
            }

        } else {
            changeSalesPersonTodoStyle("vincheck", "N/A");
            changeSalesPersonTodoStyle("insurance", "N/A");
            changeSalesPersonTodoStyle("tradeTitle", "N/A");
            changeSalesPersonTodoStyle("registration", "N/A");
            changeSalesPersonTodoStyle("inspection", "N/A");
            changeSalesPersonTodoStyle("salePStatus", "N/A");
            changeSalesPersonTodoStyle("paid", "N/A");

        }

        $(".selectpicker").selectpicker("refresh");

    }
}

function changeSalesPersonTodoStyle(elementID, value) {
    if (value !== "N/A") {
        $('#' + elementID).val(value);
        $('#' + elementID).prop("disabled", false);
    } else {
        $(`#${elementID} option:eq(0)`).prop("selected", true);
        $('#' + elementID).prop("disabled", true);
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

function loadFinanceManager() {
    var finance_manager_id = Number(localStorage.getItem('financeManagerID'));
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: finance_manager_id },
        success: function (response) {
            var array = response.data;
            var selectBoxs = document.getElementById('financeManager');
            for (var i = 0; i < array.length; i++) {
                var item = array[i];
                selectBoxs.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}
