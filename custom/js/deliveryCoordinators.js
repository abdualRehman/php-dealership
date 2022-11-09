"use strict";
var manageAppointmentsTable, apptexistStatusValue = false;
var deliveryCoordinatorArray = [];
var o, vehicleArray = ["All"];
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


    var siteURL = localStorage.getItem('siteURL');
    var deliveryCoordinatorID = Number(localStorage.getItem('deliveryCoordinatorID'));


    $(".scheduleDate").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        autoclose: true,
    });

    // $(".scheduleTime").datetimepicker({
    //     todayHighlight: !0,
    //     minView: 1,
    //     startView: 1,
    //     maxView: 1,
    //     showMeridian: !0,
    //     format: "hh:00",
    //     autoclose: true,
    //     forceParse: false,
    // });
    $('.scheduleTime').timepicker({
        dynamic: true,
        dropdown: true,
        'showDuration': false,
        disableTextInput: true,
        scrollbar: true,
        show24Hours: false,
        interval: 60,
    });



    function assignKey(obj, key) {
        // console.log(key);
        typeof obj[key] === 'undefined' ? obj[key] = 1 : obj[key]++;
    }

    manageAppointmentsTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': `${siteURL}/php_action/fetchSchedules.php`,
        dom: `<'row'<'col-12'P>>
        <'row' 
        <'col-sm-4 text-left text-sm-left pl-3'<'#statusFilterDiv'>>
            <'col-sm-4 text-center pl-3'B>
            <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
        <'row'<'col-12'tr>>\n      
        <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [6, 10, 7, 15],
        },
        "pageLength": 25,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Appointments',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Appointments',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'print',
                title: 'Appointments',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
        ],
        columnDefs: [
            { width: 400, targets: [13] },
            { visible: false, targets: [0, 12, 15] },
            {
                targets: [0],
                data: 0,
            },
            {
                targets: [1],
                data: 6,
                render: function (data, type, row) {
                    // var d1 = data.split(/(?=[A-Z])/).join(' ').toLowerCase();
                    if (data == 'ok') {
                        data = "Yes";
                    } else if (data == 'showVerified') {
                        data = "No";
                    }
                    return data;
                }
            },
            {
                targets: [2],
                data: 7,
                render: function (data, type, row) {
                    // var d1 = data.split(/(?=[A-Z])/).join(' ').toLowerCase();
                    if (data == 'ok') {
                        data = "Yes";
                    } else if (data == 'showVerified') {
                        data = "No";
                    }
                    return data;
                }
            },
            {
                targets: [3],
                data: 8,
            },
            {
                targets: [4],
                data: 9,
                render: function (data, type, row) {
                    data = moment(data, 'YYYY-MM-DD').format('MM-DD-YYYY')
                    return data;
                },
            },
            {
                targets: [5],
                data: 10,
                render: function (data, type, row) {
                    data = moment(data, "HH:mm").format("h:mm A")
                    return data;
                },
            },
            {
                targets: [6],
                data: 11,
            },
            {
                targets: [7],
                data: 12,
            },
            {
                targets: [8],
                data: 13,
            },
            {
                targets: [9],
                data: 22,
            },
            {
                targets: [10],
                data: 14,
            },
            {
                targets: [11],
                data: 23,
            },
            {
                targets: [12],
                data: 19,
            },
            {
                targets: [13],
                data: 15,
            },
            {
                targets: [14],
                data: 16,
            },
            {
                targets: [15],
                data: 18,
                render: function (data, type, row) {
                    if (data == '') {
                        data = "";
                    } else {
                        data = "Additional Services";
                    }
                    return data;
                }
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [6, 10, 7, 15],
            },
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
        createdRow: function (row, data, dataIndex) {
            if (data[17] && $('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#editScheduleModel",
                    "onclick": "editShedule(" + data[0] + ")"
                });
            }
        },
        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {

                var obj = json.data;
                var lmconfirmed = 0, lmcomplete = 0, tmconfirmed = 0, tmcomplete = 0;

                const startOfMonth = moment().startOf('month').format('MM-DD-YYYY');
                const endOfMonth = moment().endOf('month').format('MM-DD-YYYY');

                const todayDate = moment(new Date()).format("MM-DD-YYYY");
                const startDayOfPrevMonth = moment(todayDate).subtract(1, 'month').startOf('month').format('MM-DD-YYYY')
                const lastDayOfPrevMonth = moment(todayDate).subtract(1, 'month').endOf('month').format('MM-DD-YYYY')


                var bool1 = moment(date).isBetween
                    (startDayOfPrevMonth, lastDayOfPrevMonth, null, '[]');
                if (bool1) {
                    return true;
                }

                for (const [key, value] of Object.entries(obj)) {
                    // console.log(value[0]);
                    var date = value[4];
                    let confirm = value[6];
                    let complete = value[7];
                    let additional_services = value[18];
                    var bool1 = moment(date).isBetween
                        (startDayOfPrevMonth, lastDayOfPrevMonth, null, '[]');
                    if (bool1) {
                        // if (confirm == 'ok' && complete == 'ok' && additional_services != '') {
                        if (confirm == 'ok' && complete == 'ok' && additional_services != '') {
                            lmconfirmed += 1;
                        }
                    }
                    var bool1 = moment(date).isBetween(startOfMonth, endOfMonth, null, '[]');
                    if (bool1) {
                        // if (confirm == 'ok' && complete == 'ok' && additional_services != '') {
                        if (confirm == 'ok' && complete == 'ok') {
                            tmconfirmed += 1;
                        }
                    }
                }
                $(`#lmconfirmed`).html(lmconfirmed);
                $(`#tmconfirmed`).html(tmconfirmed);
            }
        },
        "order": [[0, "desc"]]
    })

    writeStatusHTML();

    let filterById = null;
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const filter = urlParams.get('filter');
    const id = urlParams.get('id');
    if (filter != null) {
        filterById = filter;
        console.log(id);
        handle_notification(id);
    } else {
        $('#thisMonth').click();
    }



    loadSoldLogs();
    loadDeliveryCoordinator();
    disabledManagerDiv();


    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageAppointmentsTable.table().node();

            if (filterById == null) {

                var dateType = $('input:radio[name="searchStatus"]:checked').map(function () {
                    if (this.value !== "") {
                        return this.value;
                    }
                }).get();

                if (dateType.length === 0) {
                    return true;
                }

                if (dateType == 'all') {
                    return true;
                }
                else if (dateType == 'lastMonth') {

                    const todayDate = moment(new Date()).format("MM-DD-YYYY");
                    var date = searchData[4];
                    const startDayOfPrevMonth = moment(todayDate).subtract(1, 'month').startOf('month').format('MM-DD-YYYY')

                    const lastDayOfPrevMonth = moment(todayDate).subtract(1, 'month').endOf('month').format('MM-DD-YYYY')


                    var bool1 = moment(date).isBetween
                        (startDayOfPrevMonth, lastDayOfPrevMonth, null, '[]');
                    if (bool1) {
                        return true;
                    }

                } else if (dateType == 'thisMonth') {
                    const startOfMonth = moment().startOf('month').format('MM-DD-YYYY');
                    const endOfMonth = moment().endOf('month').format('MM-DD-YYYY');

                    var date = searchData[4];
                    var bool1 = moment(date).isBetween
                        (startOfMonth, endOfMonth, null, '[]');
                    if (bool1) {
                        return true;
                    }
                } else if (dateType == 'approvals') {
                    var appointed = rowData[19];
                    var manager_override_id = rowData[20];
                    var delivery = rowData[23];
                    if (appointed == 'true' && !manager_override_id && delivery != '') {
                        return true;
                    }
                }


            } else {
                let rowId = searchData[0];
                if (rowId == filter) {
                    return true;
                }
            }


            if (settings.nTable !== tableNode) {
                return true;
            }

            return false;
        }
    );


    $('input[name="searchStatus"]:radio').on('change', function () {
        filterById = null;
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageAppointmentsTable.draw();  // working
        manageAppointmentsTable.searchPanes.rebuildPane();
    });


    $('.clear-selection-btn-group').on('click', function () {
        var targetElement = $(this).attr('data-targetElement');
        let elementId = $(this).attr("id");
        var prev = $(this).children('label.active').find(':radio:checked').val();
        var current_e = $('#' + elementId + ' .active :radio:checked').val();
        setTimeout(() => {
            let current = $('#' + elementId + ' .active :radio:checked').val();
            if (prev == current) {
                $('#' + elementId + ' :radio').prop('checked', false);
                $('#' + elementId + ' .active').removeClass('active');
            }
            if (current != 'ok' || current_e == 'ok') {
                $('#' + targetElement).addClass('disabled-div');
                $('#' + targetElement + ' :radio').prop('checked', false);
                $('#' + targetElement + ' .active').removeClass('active');
            } else {
                $('#' + targetElement).removeClass('disabled-div');
            }
        }, 100);
    })



    $("#addNewSchedule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            customerName: {
                required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
            },
            scheduleTime: {
                required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
            },
            scheduleDate: {
                required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
            },
            sale_id: {
                required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
            },
            coordinator: {
                required: $('#loggedInUserRole').val() == deliveryCoordinatorID ? false : true,
            },
            // overrideBy: {
            //     required: function (params) {
            //         // if ($('#loggedInUserRole').val() != deliveryCoordinatorID) {
            //         if (apptexistStatusValue == true) {
            //             $(params).addClass('is-invalid');
            //             return true;
            //         } else {
            //             $(params).removeClass('is-invalid');
            //             return false;
            //         }
            //     },
            // },
            'delivery': {
                required: function (params) {
                    var opt = $('input:radio[name="additionalServices"]:checked').val();
                    if (!opt && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            'additionalServices': {
                required: function (params) {
                    var opt = $('input:radio[name="delivery"]:checked').val();
                    if (!opt && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            'scheduleNotes': {
                required: function (params) {
                    var opt = $('input:radio[name="additionalServices"]:checked').val();
                    if (opt == 'other' && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
                        return true;
                    } else {
                        return false;
                    }
                },
            }
        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();

            var time = $('#scheduleTime').val();
            const number = moment(time, ["h:mmA"]).format("HH:mm");
            $('#scheduleTime').val(number);

            var form = $('#addNewSchedule');
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
                        $('#addNewSchedule')[0].reset();
                        $('#addNew').modal('hide');
                        loadSoldLogs();
                        manageAppointmentsTable.ajax.reload(null, false);
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
            //         // if ($('#loggedInUserRole').val() != deliveryCoordinatorID) {
            //         if (apptexistStatusValue == true) {
            //             $(params).addClass('is-invalid');
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
            // return true;
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
                        manageAppointmentsTable.ajax.reload(null, false);
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


})

function setNavLink(id = 'more') {
    $('.nav-link').removeClass('active');
    $('#' + id).addClass('active');
}

function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="searchStatusAll" value="all" > ALL <span class="badge badge-lg p-1" id="allCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" value="thisMonth" id="thisMonth" > This Month <span class="badge badge-lg p-1" id="thisMonthCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" value="lastMonth"> Last Month <span class="badge badge-lg p-1" id="lastMonthCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" value="approvals"> Manager Approvals <span class="badge badge-lg p-1" id="approvalsCount" ></span>
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
}






function removeSchedule(scheduleId = null) {
    if (scheduleId) {
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
                    url: `${siteURL}/php_action/removeScheduleAppointment.php`,
                    type: 'post',
                    data: { id: scheduleId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageAppointmentsTable.ajax.reload(null, false);
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

function disabledManagerDiv() {
    let currentUser = $('#loggedInUserRole').val();
    var delivery_coordinator_id = Number(localStorage.getItem('deliveryCoordinatorID'));
    var sales_manager_id = Number(localStorage.getItem('salesManagerID'));
    var general_manager_id = Number(localStorage.getItem('generalManagerID'));
    var branchAdmin_id = Number(localStorage.getItem('branchAdmin'));

    if (currentUser != delivery_coordinator_id && currentUser != 'Admin' && currentUser != branchAdmin_id) {
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
    if (currentUser != 'Admin' && currentUser != branchAdmin_id && currentUser != sales_manager_id && currentUser != general_manager_id) {
        $('.manager_override_div').addClass('disabled-div');
        $(".manager_override_div").find("*").prop("readonly", true);
    } else {
        $('.manager_override_div').removeClass('disabled-div');
        $(".manager_override_div").find("*").prop("readonly", false);
    }

    $('#esubmittedBy , #submittedBy , #eoverrideByName , #overrideByName , #customerName , #ecustomerName').addClass('disabled-div');
    $("#esubmittedBy , #submittedBy , #eoverrideByName , #overrideByName , #customerName , #ecustomerName").find("*").prop("readonly", true);
}
function loadSoldLogs() {
    var selectBoxes = document.getElementsByClassName('stockno');
    selectBoxes.forEach(element => {
        element.innerHTML = ``;
    });
    $('.selectpicker').selectpicker('refresh');

    $.ajax({
        url: `${siteURL}/php_action/fetchSoldLogsForAppointmenst.php`,
        type: "GET",
        dataType: 'json',
        success: function (response) {
            stockArray = response.data;
            selectBoxes.forEach(element => {
                for (var i = 0; i < stockArray.length; i++) {
                    var item = stockArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[4]}">${item[4]} - ${item[5]} </option>`;
                }
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}
function loadDeliveryCoordinator() {
    var id = Number(localStorage.getItem('deliveryCoordinatorID'));
    $.ajax({
        url: `${siteURL}/php_action/fetchUsersWithRoleForSearch.php`,
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



$('.clear-selection').click(function () {
    let id = $(this).data('id');
    $(`#${id} :radio`).prop('checked', false);
    $(`#${id} .active`).removeClass('active');
})



function editShedule(id = null) {

    if (id) {
        $.ajax({
            url: `${siteURL}/php_action/fetchSelectedAppointment.php`,
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

                $('#editScheduleForm')[0].reset();


                $('#edelivery :radio[name="edelivery"]').prop('checked', false);
                $('#edelivery .active').removeClass('active');
                (response.delivery) ? $('#e' + response.delivery).prop('checked', true).click() : null;

                $('#eadditionalServices :radio[name="eadditionalServices"]').prop('checked', false);
                $('#eadditionalServices .active').removeClass('active');
                (response.additional_services) ? $('#e' + response.additional_services).prop('checked', true).click() : null;


                $('#scheduleId').val(response.id);
                $('#ecallenderId').val(response.calender_id);

                $('#esale_id').val(response.sale_id);
                echangeStockDetails({ value: response.sale_id }, false);
                $('#ehas_appointment').val(response.already_have);

                $('#esubmittedBy').val(response.submitted_by);
                $('#esubmittedByRole').val(response.submitted_by_role);
                $('#esubmittedById').val(response.submitted_by_id);

                $('#eoverrideBy').prop('checked', response.manager_override != "" ? true : false);
                $('#eoverrideByName').val(response.manager_overrideName);
                $('#eoverrideById').val(response.eoverrideById);


                const number = moment(response.appointment_time, ["HH:mm"]).format("h:mma");
                $('#escheduleTime').val(number);

                var date = moment(response.appointment_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
                $('#escheduleDate').val(date);

                $('#escheduleDate').datepicker('update', date);





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

                if (response.allowDeliveryCoordinator == true) {
                    $('.delivery_coordinator').addClass('disabled-div');
                    $(".delivery_coordinator").find("*").prop("readonly", true);
                }else {
                    disabledManagerDiv();
                }
                

                $('#ecoordinator').val(response.coordinator);
                var checkSelectValue = $('#ecoordinator').val();
                if (!checkSelectValue) {
                    var selectBox = document.getElementById('ecoordinatorList');
                    selectBox.innerHTML += `<option value="${response.coordinator}" title="${response.coordinator_name} - ${response.coordinator_email}">${response.coordinator_name} - ${response.coordinator_email} </option>`;
                    $('#ecoordinator').val(response.coordinator);
                }
                $('.selectpicker').selectpicker('refresh');
                apptexistStatusValue = false;


            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }

}

function changeStockDetails(ele) {
    let obj = stockArray.find(data => data[0] === ele.value);
    $('#customerName').val("");
    $('#vechicle').val("");
    $('#stockno').val("");
    $('#has_appointment').val("null");
    if (obj) {
        let currentUser = $('#loggedInUserRole').val();
        var apptStatus = obj[10];
        var sales_manager_id = Number(localStorage.getItem('salesManagerID'));
        var general_manager_id = Number(localStorage.getItem('generalManagerID'));
        var branchAdmin_id = Number(localStorage.getItem('branchAdmin'));

        if (apptStatus != null && currentUser != sales_manager_id && currentUser != general_manager_id && currentUser != 'Admin' && currentUser != branchAdmin_id) {
            toastr.error('Error! - Appointment Allready Exist');

            $('#sale_id').val('');
            $('.selectpicker').selectpicker('refresh');
            return false;
        }

        changeExistStatus();

        $('#customerName').val(obj[2] + ' ' + obj[3]);
        $('#vechicle').val(obj[6] + ' ' + obj[7] + ' ' + obj[9]);
        // $('#has_appointment').val(obj[10])
        $('#has_appointment').val(obj[11])
        $('#stockno').val(obj[1])
    }

}


function changeExistStatus(editStatus = false) {
    let eleValue = $(`#${editStatus ? 'e' : ''}sale_id`).val();
    let obj = stockArray.find(data => data[0] === eleValue);
    let deliveryStatus = $(`input[name="${editStatus ? 'e' : ''}delivery"]:checked`).val();
    if (obj && deliveryStatus) {
        var apptexistStatus = obj[11];
        if (apptexistStatus != null && deliveryStatus != '') {
            apptexistStatusValue = true;
        } else {
            apptexistStatusValue = false;
        }
    } else {
        apptexistStatusValue = false;
    }
    if (apptexistStatusValue == true) {
        toastr.error(`Error! - Stock No: ${obj[4]} has already been scheduled for a delivery`);
    }
    $(`#${editStatus ? 'e' : ''}overrideBy`).prop('checked', false);
    $(`#${editStatus ? 'e' : ''}overrideByName`).val("");
    $(`#${editStatus ? 'e' : ''}overrideById`).val("");
}


function echangeStockDetails(ele, checkAppt = true) {

    let obj = stockArray.find(data => data[0] === ele.value);
    $('#ecustomerName').val("");
    $('#evechicle').val("");
    $('#estockno').val("");
    $('#ehas_appointment').val("null");
    if (obj) {
        if (checkAppt && checkAppt == true) {
            let currentUser = $('#loggedInUserRole').val();
            var apptStatus = obj[10];
            var sales_manager_id = Number(localStorage.getItem('salesManagerID'));
            var general_manager_id = Number(localStorage.getItem('generalManagerID'));
            var branchAdmin_id = Number(localStorage.getItem('branchAdmin'));

            if (apptStatus != null && currentUser != sales_manager_id && currentUser != general_manager_id && currentUser != 'Admin' && currentUser != branchAdmin_id) {
                toastr.error('Error! - Appointment Allready Exist');
                $('#esale_id').val('');
                $('.selectpicker').selectpicker('refresh');
                return false;
            }
            changeExistStatus();
        }
        $('#ecustomerName').val(obj[2] + ' ' + obj[3]);
        $('#evechicle').val(obj[6] + ' ' + obj[7] + ' ' + obj[9]);
        // $('#ehas_appointment').val(obj[10])
        $('#ehas_appointment').val(obj[11])
        $('#estockno').val(obj[1])
    }

}



$('input[name=delivery] , input[name=edelivery]').change(function () {
    if ($(this).prop('name') == 'delivery') {
        changeExistStatus();
    } else {
        changeExistStatus(true);
    }
})

$('#overrideBy').change(function () {
    if ($(this).prop('checked')) {
        var cN = $('#currentUser').val();
        var cId = $('#currentUserId').val();
        $('#overrideByName').val(cN);
        $('#overrideById').val(cId);
    } else {
        $('#overrideByName').val("");
        $('#overrideById').val("");
    }
})
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
