"use strict";
var manageAppointmentsTable;
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

    $('.nav-link').removeClass('active');
    $('#more').addClass('active');



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
        'ajax': '../php_action/fetchSchedules.php',
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
            columns: [6, 9, 7],
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
            {
                targets: [0],
                data: 0,
            },
            {
                targets: [1],
                data: 6,
                render: function (data, type, row) {
                    var d1 = data.split(/(?=[A-Z])/).join(' ').toLowerCase();
                    return d1;
                }
            },
            {
                targets: [2],
                data: 7,
                render: function (data, type, row) {
                    var d1 = data.split(/(?=[A-Z])/).join(' ').toLowerCase();
                    return d1;
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
                data: 14,
            },
            {
                targets: [10],
                data: 15,
            },
            {
                targets: [11],
                data: 16,
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [6, 9, 7],
            },
            {
                targets: [0],
                visible: false,
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
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#editScheduleModel",
                    "onclick": "editShedule(" + data[0] + ")"
                });
            }
        },
        "order": [[0, "desc"]]
    })

    writeStatusHTML();
    $('#thisMonth').click();
    loadSoldLogs();
    loadDeliveryCoordinator();
    disabledManagerDiv();


    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageAppointmentsTable.table().node();

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
            }


            if (settings.nTable !== tableNode) {
                return true;
            }

            return false;
        }
    );


    $('input[name="searchStatus"]:radio').on('change', function () {
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageAppointmentsTable.draw();  // working
        manageAppointmentsTable.searchPanes.rebuildPane();
    });



    $("#addNewSchedule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            customerName: "required",
            scheduleTime: "required",
            scheduleDate: "required",
            stockno: "required",
            coordinator: "required",
            overrideBy: {
                required: function (params) {
                    var has_appointment = $('#has_appointment').val();
                    if (has_appointment) {
                        return true;
                    } else {
                        $(params).removeClass('is-invalid');
                        return false;
                    }
                },
            },
            'delivery': {
                required: function (params) {
                    var opt = $('input:radio[name="additionalServices"]:checked').val();
                    if (!opt) {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            'additionalServices': {
                required: function (params) {
                    var opt = $('input:radio[name="delivery"]:checked').val();
                    if (!opt) {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            'scheduleNotes': {
                required: function (params) {
                    var opt = $('input:radio[name="additionalServices"]:checked').val();
                    if (opt == 'other') {
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
            ecustomerName: "required",
            escheduleTime: "required",
            escheduleDate: "required",
            estockno: "required",
            ecoordinator: "required",
            eoverrideBy: {
                required: function (params) {
                    var has_appointment = $('#ehas_appointment').val();
                    if (has_appointment) {
                        return true;
                    } else {
                        $(params).removeClass('is-invalid');
                        return false;
                    }
                },
            },
            'edelivery': {
                required: function (params) {
                    var opt = $('input:radio[name="eadditionalServices"]:checked').val();
                    if (!opt) {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            'eadditionalServices': {
                required: function (params) {
                    var opt = $('input:radio[name="edelivery"]:checked').val();
                    if (!opt) {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            'escheduleNotes': {
                required: function (params) {
                    var opt = $('input:radio[name="eadditionalServices"]:checked').val();
                    if (opt == 'other') {
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
                    url: '../php_action/removeScheduleAppointment.php',
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
    var delivery_coordinator_id = 62;
    if (currentUser != delivery_coordinator_id && currentUser != 'Admin') {
        $('.delivery_coordinator').addClass('disabled-div');
        // $(".delivery_coordinator").find("*").prop("disabled", true);
        $(".delivery_coordinator").find("*").prop("readonly", true);
    } else {
        $('.delivery_coordinator').removeClass('disabled-div');
        // $(".delivery_coordinator").find("*").prop("disabled", true);
        $(".delivery_coordinator").find("*").prop("readonly", false);
    }
    var sales_manager_id = 67;
    var general_manager_id = 69;
    if (currentUser != 'Admin' && currentUser != sales_manager_id && currentUser != general_manager_id) {
        $('.manager_override_div').addClass('disabled-div');
        // $(".manager_override_div").find("*").prop("disabled", true);
        $(".manager_override_div").find("*").prop("readonly", true);
    } else {
        $('.manager_override_div').removeClass('disabled-div');
        // $(".manager_override_div").find("*").prop("disabled", true);
        $(".manager_override_div").find("*").prop("readonly", false);
    }

}
function loadSoldLogs() {
    var selectBoxes = document.getElementsByClassName('stockno');
    selectBoxes.forEach(element => {
        element.innerHTML = ``;
    });
    $('.selectpicker').selectpicker('refresh');

    $.ajax({
        url: '../php_action/fetchSoldLogsForAppointmenst.php',
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
    var id = 62;
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

        console.log(date, time, selectBox);
    }
    selectBox.innerHTML = "";
    $('.selectpicker').selectpicker('refresh');
    if (date != '' && time != '') {
        let dayname = moment(date).format('dddd').toLowerCase();
        deliveryCoordinatorArray.forEach(element => {
            let startTime = element[3][dayname][0];
            let endTime = element[3][dayname][1];
            if (startTime && endTime) {

                time = moment(moment(time, ["h:mmA"]).format("HH:mm"), 'hh:mm');
                startTime = moment(moment(startTime, ["h:mmA"]).format("HH:mm"), 'hh:mm');
                endTime = moment(moment(endTime, ["h:mmA"]).format("HH:mm"), 'hh:mm');

                if (time.isBetween(startTime, endTime)) {
                    selectBox.innerHTML += `<option value="${element[0]}" title="${element[1]} - ${element[2]}">${element[1]} - ${element[2]} </option>`;
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }

})






function editShedule(id = null) {

    if (id) {
        $.ajax({
            url: '../php_action/fetchSelectedAppointment.php',
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
                echangeStockDetails({ value: response.sale_id} , false);
                $('#ehas_appointment').val("");

                $('#esubmittedBy').val(response.submitted_by);
                $('#esubmittedByRole').val(response.submitted_by_role);

                $('#eoverrideBy').prop('checked', response.manager_override != "" ? true : false);
                $('#eoverrideByName').val(response.manager_overrideName);
                $('#eoverrideById').val(response.eoverrideById);


                const number = moment(response.appointment_time, ["HH:mm"]).format("h:mma");
                $('#escheduleTime').val(number);

                var date = moment(response.appointment_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
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


                if ($('#ecoordinatorList').children().length > 0) {
                    $('#ecoordinator').val(response.coordinator);
                    $('.selectpicker').selectpicker('refresh');
                }

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
        var sales_manager_id = 67;
        var general_manager_id = 69;
        if (apptStatus != null && currentUser != sales_manager_id && currentUser != general_manager_id && currentUser != 'Admin') {
            toastr.error('Error! - Appointment Allready Exist');

            $('#sale_id').val('');
            $('.selectpicker').selectpicker('refresh');
            return false;
        }


        $('#customerName').val(obj[2] + ' ' + obj[3]);
        $('#vechicle').val(obj[6] + ' ' + obj[7] + ' ' + obj[9]);
        $('#has_appointment').val(obj[10])
        $('#stockno').val(obj[1])
    }

}
function echangeStockDetails(ele , checkAppt = true) {
    
    let obj = stockArray.find(data => data[0] === ele.value);
    $('#ecustomerName').val("");
    $('#evechicle').val("");
    $('#estockno').val("");
    $('#ehas_appointment').val("null");
    if (obj) {
        if(checkAppt && checkAppt == true){
            let currentUser = $('#loggedInUserRole').val();
            var apptStatus = obj[10];
            var sales_manager_id = 67;
            var general_manager_id = 69;
            if (apptStatus != null && currentUser != sales_manager_id && currentUser != general_manager_id && currentUser != 'Admin') {
                toastr.error('Error! - Appointment Allready Exist');
                $('#esale_id').val('');
                $('.selectpicker').selectpicker('refresh');
                return false;
            }
        }
        $('#ecustomerName').val(obj[2] + ' ' + obj[3]);
        $('#evechicle').val(obj[6] + ' ' + obj[7] + ' ' + obj[9]);
        $('#ehas_appointment').val(obj[10])
        $('#estockno').val(obj[1])
    }

}

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
