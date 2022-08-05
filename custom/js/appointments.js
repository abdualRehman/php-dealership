var resizeThrottled, selectedCalendar;
var stockArray = [];

setTimeout(() => {

    var calendarList = document.getElementById('calendarList');
    var html = [];
    html.push(' <div class="lnb-calendars-item dropdown-item" onclick="event.stopPropagation();" >' +
        '<label>' +
        '<input class="tui-full-calendar-checkbox-square" type="checkbox" value="all" checked>' +
        '<span></span>' +
        '<strong>View all</strong>' +
        '</label>' +
        '</div>');
    CalendarList.forEach(function (calendar, i) {
        html.push('<div class="dropdown-item" onclick="event.stopPropagation();"> ' +
            '<label class="lnb-calendars-item w-100" for="lebelId' + i + '">' +
            '<input type="checkbox" class="tui-full-calendar-checkbox-round" id="lebelId' + i + '" value="' + calendar.id + '" checked>' +
            '<span style="border-color: ' + calendar.borderColor + '; background-color: ' + calendar.borderColor + ';"></span>' +
            '<span>' + calendar.name + '</span>' +
            '</label> </div>'
        );
    });

    calendarList.innerHTML = html.join('\n');

}, 2000);



// register templates
var templates = {
    popupIsAllDay: function () {
        return 'All Day';
    },
    popupStateFree: function () {
        return 'Free';
    },
    popupStateBusy: function () {
        return 'Busy';
    },
    titlePlaceholder: function () {
        return 'Subject';
    },
    locationPlaceholder: function () {
        return 'Location';
    },
    startDatePlaceholder: function () {
        return 'Start date';
    },
    endDatePlaceholder: function () {
        return 'End date';
    },
    popupSave: function () {
        return 'Save';
    },
    popupUpdate: function () {
        return 'Update';
    },
    popupDetailDate: function (isAllDay, start, end) {
        var isSameDate = moment(start).isSame(end);
        var endFormat = (isSameDate ? '' : 'YYYY.MM.DD ') + 'hh:mm a';

        if (isAllDay) {
            return moment(start).format('YYYY.MM.DD') + (isSameDate ? '' : ' - ' + moment(end).format('YYYY.MM.DD'));
        }

        return (moment(start).format('YYYY.MM.DD hh:mm a') + ' - ' + moment(end).format(endFormat));
    },
    popupDetailLocation: function (schedule) {
        return 'Location : ' + schedule.location;
    },
    popupDetailUser: function (schedule) {
        return 'User : ' + (schedule.attendees || []).join(', ');
    },
    popupDetailState: function (schedule) {
        return 'State : ' + schedule.state || 'Busy';
    },
    popupDetailRepeat: function (schedule) {
        return 'Repeat : ' + schedule.recurrenceRule;
    },
    popupDetailBody: function (schedule) {
        return 'Body : ' + schedule.body;
    },
    popupEdit: function () {
        return 'Edit';
    },
    popupDelete: function () {
        return 'Delete';
    }
};

var cal = new tui.Calendar('#calendar', {
    defaultView: 'week',
    // defaultView: 'month',
    template: templates,
    calendars: CalendarList,
    // useCreationPopup: true,
    // useDetailPopup: true,
    useCreationPopup: false,
    useDetailPopup: false,
    taskView: false,
    // scheduleView: ['time', 'allday'],
    scheduleView: ['time'],
    timezones: [
        {
            timezoneName: 'Asia/Karachi',
            tooltip: 'Seoul',
            displayLabel: 'GMT+09:00 / PK'
        },
        // {
        //   timezoneName: 'America/New_York',
        //   tooltip: 'New York',
        //   displayLabel: 'GMT-05:00'
        // },
    ]
});

cal.on({
    'clickSchedule': function (e) {
        let id = e.schedule.raw.creator.appointmentId
        editShedule(id);
        $("#editScheduleModel").modal();
    },
    'beforeCreateSchedule': function (event) {
        var triggerEventName = event.triggerEventName;
        if (triggerEventName === 'click') {
            return false;
        } else if (triggerEventName === 'dblclick') {
            return false;
        } else if (triggerEventName == 'mouseup') {
            return false;
        }

    },
    'beforeUpdateSchedule': function (e) {
        // console.log('beforeUpdateSchedule', e);
        e.schedule.start = e.start;
        e.schedule.end = e.end;
        cal.updateSchedule(e.schedule.id, e.schedule.calendarId, e.schedule);
    },
    'beforeDeleteSchedule': function (e) {
        // console.log('beforeDeleteSchedule', e);
        cal.deleteSchedule(e.schedule.id, e.schedule.calendarId);
    }
});




function onChangeCalendars(e) {
    // console.log(e);
    var calendarId = e.target.value;
    var checked = e.target.checked;
    var viewAll = document.querySelector('.lnb-calendars-item input');
    var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));
    var allCheckedCalendars = true;

    if (calendarId === 'all') {
        allCheckedCalendars = checked;

        calendarElements.forEach(function (input) {
            var span = input.parentNode;
            input.checked = checked;
            span.style.backgroundColor = checked ? span.style.borderColor : 'transparent';
        });

        CalendarList.forEach(function (calendar) {
            calendar.checked = checked;
        });
    } else {
        findCalendar(calendarId).checked = checked;

        allCheckedCalendars = calendarElements.every(function (input) {
            return input.checked;
        });

        if (allCheckedCalendars) {
            viewAll.checked = true;
        } else {
            viewAll.checked = false;
        }
    }

    refreshScheduleVisibility();
}

function refreshScheduleVisibility() {
    var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));

    CalendarList.forEach(function (calendar) {
        cal.toggleSchedules(calendar.id, !calendar.checked, false);
    });

    cal.render(true);

    calendarElements.forEach(function (input) {
        var span = input.nextElementSibling;
        span.style.backgroundColor = input.checked ? span.style.borderColor : 'transparent';
    });
}



function onChangeNewScheduleCalendar(e) {
    var target = $(e.target).closest('a[role="menuitem"]')[0];
    var calendarId = getDataAction(target);
    changeNewScheduleCalendar(calendarId);
}

function changeNewScheduleCalendar(calendarId) {
    var calendarNameElement = document.getElementById('calendarName');
    var calendar = findCalendar(calendarId);
    console.log("changeNewScheduleCalendar", calendar);
    var html = [];

    html.push('<span class="calendar-bar" style="background-color: ' + calendar.bgColor + '; border-color:' + calendar.borderColor + ';"></span>');
    html.push('<span class="calendar-name">' + calendar.name + '</span>');

    calendarNameElement.innerHTML = html.join('');

    selectedCalendar = calendar;
}


function setEventListener1() {

    // $('.dropdown-menu a[role="menuitem"]').on('click', onClickMenu);
    $('#lnb-calendars').on('change', onChangeCalendars);
    $('#dropdownMenu-calendars-list').on('click', onChangeNewScheduleCalendar);

}

$(function () {

    $('.nav-link').removeClass('active');
    $('#more').addClass('active');

    setEventListener1();


    fetchSchedules()

    loadSoldLogs();
    loadDeliveryCoordinator();
    disabledManagerDiv();


    $(".scheduleDate").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        autoclose: true,
    });

    $(".scheduleTime").datetimepicker({
        todayHighlight: 0,
        minView: 0,
        startView: 1,
        maxView: 1,
        showMeridian: !0,
        format: "hh:ii",
        autoclose: true,
    })


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
                        })
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
                        fetchSchedules();
                        $('#editScheduleModel').modal('hide');
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
function loadSoldLogs() {
    $.ajax({
        url: '../php_action/fetchSoldLogsForAppointmenst.php',
        type: "GET",
        dataType: 'json',
        success: function (response) {
            stockArray = response.data;
            var selectBoxes = document.getElementsByClassName('stockno');
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
            var Array = response.data;
            var selectBoxes = document.getElementsByClassName('coordinator');
            selectBoxes.forEach(element => {
                for (var i = 0; i < Array.length; i++) {
                    var item = Array[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]} - ${item[2]}">${item[1]} - ${item[2]} </option>`;
                }
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}


function disabledManagerDiv() {
    let currentUser = $('#loggedInUserRole').val();
    var delivery_coordinator_id = 62;
    if (currentUser != delivery_coordinator_id) {
        $('.delivery_coordinator').addClass('disabled-div');
        // $(".delivery_coordinator").find("*").prop("disabled", true);
        $(".delivery_coordinator").find("*").prop("readonly", true);
    } else {
        $('.delivery_coordinator').removeClass('disabled-div');
        // $(".delivery_coordinator").find("*").prop("disabled", true);
        $(".delivery_coordinator").find("*").prop("readonly", false);
    }
}
function fetchSchedules() {
    $.ajax({
        url: '../php_action/fetchSchedules.php',
        type: "GET",
        dataType: 'json',
        success: function (response) {
            var dataArray = response.data;
            ScheduleList = [];
            dataArray.forEach(element => {
                var calendar = CalendarList.find(calender => calender.id == element[2]);
                var start = moment(element[4] + ':00', 'YYYY-MM-DD HH:mm:ss').toDate();
                var end = moment(element[5] + ':00', 'YYYY-MM-DD HH:mm:ss').toDate();

                var schedule = {
                    id: element[0],
                    calendarId: calendar.id,
                    title: element[3],
                    isAllDay: false,
                    location: "",
                    start: start,
                    end: end,
                    category: 'time',
                    // category: 'allday',
                    color: calendar.color,
                    bgColor: calendar.bgColor,
                    dragBgColor: calendar.bgColor,
                    borderColor: calendar.borderColor,
                    state: 'Busy',
                    raw: {
                        memo: '',
                        "hasToOrCc": false,
                        "hasRecurrenceRule": false,
                        "location": null,
                        creator: {
                            appointmentId: element[0],
                        }
                    },
                    customStyle: `background-color: ${calendar.bgColor}; color: ${calendar.color};`
                };
                ScheduleList.push(schedule);
            });

            setSchedules()
            refreshScheduleVisibility();
        }
    });
}

function editShedule(id = null) {
    // console.log(id);
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
                echangeStockDetails({ value: response.sale_id });
                $('#ehas_appointment').val("");

                $('#esubmittedBy').val(response.submitted_by);
                $('#esubmittedByRole').val(response.submitted_by_role);

                $('#eoverrideBy').prop('checked', response.manager_override != "" ? true : false);
                $('#eoverrideByName').val(response.manager_overrideName);
                $('#eoverrideById').val(response.eoverrideById);


                var date = moment(response.appointment_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
                $('#escheduleDate').val(date);
                $('#escheduleDate').datepicker('update', date);

                $('#escheduleTime').val(response.appointment_time);
                $('#escheduleTime').datetimepicker({ format: "hh:ii" }).val(response.appointment_time);

                $('#ecoordinator').val(response.coordinator);




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


                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }

}

function changeStockDetails(ele) {
    let obj = stockArray.find(data => data[0] === ele.value);
    console.log(obj);
    $('#customerName').val("");
    $('#vechicle').val("");
    $('#stockno').val("");
    $('#has_appointment').val("null");
    if (obj) {
        $('#customerName').val(obj[2] + ' ' + obj[3]);
        $('#vechicle').val(obj[6] + ' ' + obj[7] + ' ' + obj[9]);
        $('#has_appointment').val(obj[10])
        $('#stockno').val(obj[1])
    }

}
function echangeStockDetails(ele) {
    let obj = stockArray.find(data => data[0] === ele.value);
    $('#ecustomerName').val("");
    $('#evechicle').val("");
    $('#estockno').val("");
    $('#ehas_appointment').val("null");
    if (obj) {
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
