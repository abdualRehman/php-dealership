var resizeThrottled, selectedCalendar;
var deliveryCoordinatorArray = [];
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
        // {
        //     timezoneName: 'Asia/Karachi',
        //     tooltip: 'Seoul',
        //     displayLabel: 'GMT+09:00 / PK'
        // },
        {
            timezoneName: 'America/New_York',
            tooltip: 'New York',
            displayLabel: 'GMT-05:00 / USA'
        },
    ]
});

cal.on({
    'clickSchedule': function (e) {
        if ($('#isEditAllowed').val() == 'true') {
            let id = e.schedule.raw.creator.appointmentId
            console.log(id);
            if (id) {
                editShedule(id);
                $("#editScheduleModel").modal();
            }
        }
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
    var deliveryCoordinatorID = Number(localStorage.getItem('deliveryCoordinatorID'));

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

    $('.scheduleTime').timepicker({
        dynamic: true,
        dropdown: true,
        'showDuration': false,
        disableTextInput: true,
        scrollbar: true,
        show24Hours: false,
        interval: 60,
    });


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
            //         if ($('#loggedInUserRole').val() != deliveryCoordinatorID) {
            //             return true;
            //         } else {
            //             $(params).removeClass('is-invalid');
            //             return false;
            //         }
            //     },
            // },
            'delivery': {
                required: function (params) {
                    // var opt = $('input:radio[name="additionalServices"]:checked').val();
                    let checkboxes = $('input:checkbox[name="additionalServices[]"]');
                    var opt = [...checkboxes].some(checkbox => checkbox.checked);
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
                    // var opt = $('input:radio[name="additionalServices"]:checked').val();
                    let checkboxes = $('input:checkbox[name="additionalServices[]"]');
                    var opt = [...checkboxes].some(checkbox => checkbox.checked && checkbox.value === 'other');
                    if (opt && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
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
                        loadSoldLogs();

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
                    // var opt = $('input:radio[name="eadditionalServices"]:checked').val();
                    let checkboxes = $('input:checkbox[name="eadditionalServices[]"]');
                    var opt = [...checkboxes].some(checkbox => checkbox.checked);
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
                    // var opt = $('input:radio[name="eadditionalServices"]:checked').val();
                    let checkboxes = $('input:checkbox[name="eadditionalServices[]"]');
                    var opt = [...checkboxes].some(checkbox => checkbox.checked && checkbox.value === 'other');
                    if (opt && $('#loggedInUserRole').val() != deliveryCoordinatorID) {
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


    $('#delivery, #edelivery').on('click', function () {
        const targetId = this.id;
        const prev = $(`#${targetId} label.active :radio`).val();
        var current = '';
        setTimeout(() => {
            current = $(this).find('label.active').children(' input:radio').val();
            if (prev == current) {
                $(`#${targetId} :radio`).prop('checked', false);
                $(`#${targetId} .active`).removeClass('active');
            }
        }, 100);
    });



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
            let dayname = moment(date, 'MM-DD-YYYY').format('dddd').toLowerCase();
            deliveryCoordinatorArray.forEach(element => {
                let startTime = element[3][dayname][0];
                let endTime = element[3][dayname][1];
                let scheduledAppointments = element[4];
                let available_today = element[5];
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
                                // check today availabilit
                                var checkTodayDate = moment(moment().format('MM-DD-YYYY')).diff(moment(date).format('MM-DD-YYYY'));
                                if (checkTodayDate == 0 && available_today == false) {
                                    allready_appointed = false;
                                } else {
                                    allready_appointed = true;
                                }
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



});

$.fn.selectpicker.Constructor.DEFAULTS.virtualScroll = true;


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

                writeHTMLOptions(element, stockArray);
                const scroller = new InfiniteScroller(element, stockArray);
                scroller.setScroller(element, stockArray);

                // for (var i = 0; i < 300; i++) {
                //     var item = stockArray[i];
                //     element.innerHTML += `<option value="${item[0]}" title="${item[4]}">${item[4]} - ${item[5]} </option>`;
                // }
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function writeHTMLOptions(element, stockArray) {
    for (var i = 0; i < 5; i++) {
        var item = stockArray[i];
        // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${i}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`;
        $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`);
    }
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

        // var Selectpicker = $('.selectpicker').data('selectpicker');
        var Selectpicker = $('#' + p).data('selectpicker');

        Selectpicker?.$menuInner?.on('scroll', function () {

            if (Selectpicker?.$searchbox?.val() == '') {

                var scrollTop = Selectpicker?.selectpicker?.view?.scrollTop;
                // if within 100px of the bottom of the menu, load more options
                if ($(this)[0].scrollHeight - Selectpicker.sizeInfo.menuInnerHeight - scrollTop < 10) {

                    var optionDiv = Selectpicker?.$element[0];
                    var targetChildElement = $(optionDiv).children('optgroup')[0].lastChild;
                    var lastArrayIndex = $(targetChildElement).data('scroll-index') + 1;
                    $('.selectpicker').selectpicker('refresh');
                    if (array.length > lastArrayIndex) {
                        for (let j = lastArrayIndex; j < lastArrayIndex + 10; j++) {
                            var item = array[j];
                            if (item) {
                                // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${j}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`;
                                $(element).append(`<option value="${item[0]}" data-scroll-index="${j}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`);
                            }
                        }
                        $('#' + p).addClass('selectpicker');
                        $('#' + p).selectpicker('render');
                        // $('#' + p).selectpicker('refresh');
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
                    var stock = (element[4]).toLowerCase();
                    var vin = (element[5]).toLowerCase();
                    return stock.indexOf(search.toLowerCase()) >= 0 || vin.indexOf(search.toLowerCase()) >= 0;
                });
                results.forEach((item, i) => {
                    // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${i}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`;
                    $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`);
                });
                $('.dropdown-menu.show').unblock();
                $('.selectpicker').selectpicker('refresh');
                // $('#' + p).addClass('selectpicker');
                // $('#' + p).selectpicker('render');
            } else if (search == '') {
                element.innerHTML = '';
                $('.dropdown-menu.show').block();
                for (var i = 0; i < 5; i++) {
                    var item = array[i];
                    // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${i}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`;
                    $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[4]}"> ${item[4]} - ${item[5]} </option>`);
                }
                $('.dropdown-menu.show').unblock();
                $('.selectpicker').selectpicker('refresh');
            }
            $('.dropdown-menu.show').unblock();
        });
    }
}




function loadDeliveryCoordinator() {
    var id = Number(localStorage.getItem('deliveryCoordinatorID'));

    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            console.log(response);
            deliveryCoordinatorArray = response.data;
            // var Array = response.data;
            // var selectBoxes = document.getElementsByClassName('coordinator');
            // selectBoxes.forEach(element => {
            //     for (var i = 0; i < Array.length; i++) {
            //         var item = Array[i];
            //         element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]}</option>`;
            //     }
            // });
            // $('.selectpicker').selectpicker('refresh');
        }
    });
}



function disabledManagerDiv() {
    let currentUser = $('#loggedInUserRole').val();
    var delivery_coordinator_id = Number(localStorage.getItem('deliveryCoordinatorID'));;
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
function fetchSchedules() {
    $.ajax({
        url: '../php_action/fetchSchedules.php',
        type: "GET",
        dataType: 'json',
        success: function (response) {
            var dataArray = response.data;
            ScheduleList = [];
            dataArray.forEach(element => {
                let userRole = element[2] != 'Admin' ? element[2] : 1;
                var calendar = CalendarList.find(calender => calender.id == userRole);
                let coordinator_color = element[24] != '' ? element[24] : calendar.bgColor;
                var start = moment(element[4] + ':00', 'YYYY-MM-DD HH:mm:ss').toDate();
                var end = moment(element[5] + ':00', 'YYYY-MM-DD HH:mm:ss').toDate();
                var schedule = {
                    id: element[0],
                    calendarId: calendar.id,
                    // title: element[3],
                    title: element[8] + ' - ' + element[11],
                    isAllDay: false,
                    location: "",
                    start: start,
                    end: end,
                    category: 'time',
                    // category: 'allday',
                    color: calendar.color,
                    bgColor: coordinator_color,
                    dragBgColor: coordinator_color,
                    borderColor: calendar.borderColor,
                    state: 'Busy',
                    raw: {
                        memo: '',
                        "hasToOrCc": false,
                        "hasRecurrenceRule": false,
                        "location": null,
                        creator: {
                            appointmentId: element[17] ? element[0] : null,
                        }
                    },
                    customStyle: `background-color: ${coordinator_color}; color: ${calendar.color};`
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

                $('#edelivery :radio[name="edelivery"]').prop('checked', false);
                $('#edelivery .active').removeClass('active');
                (response.delivery) ? $('#e' + response.delivery).prop('checked', true).click() : null;

                $('#eadditionalServices :checkbox[name="eadditionalServices"]').prop('checked', false);
                $('#eadditionalServices .active').removeClass('active');
                // (response.additional_services) ? $('#e' + response.additional_services).prop('checked', true).click() : null;

                let additional_servicesArray = (response.additional_services) ? response.additional_services.split(",") : [];
                additional_servicesArray.forEach(additional_serviceValue => {
                    $('#e' + additional_serviceValue).prop('checked', true).click();
                });

                $('#scheduleId').val(response.id);
                $('#ecallenderId').val(response.calender_id);

                $('#esale_id').val(response.sale_id);
                echangeStockDetails({ value: response.sale_id }, false);
                $('#ehas_appointment').val(response.already_have);

                $('#esubmittedBy').val(response.submitted_by + ' - ' + response.submitted_by_time);
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
                } else {
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
        let currentUser = $('#loggedInUserRole').val();
        var apptStatus = obj[10];
        var sales_manager_id = Number(localStorage.getItem('salesManagerID'));;
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
        $('#has_appointment').val(obj[10])
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

    $('#esale_id .stockno').prepend(`<option value="${obj[0]}" data-scroll-index="-1" title="${obj[4]}"> ${obj[4]} - ${obj[5]} </option>`);
    $('#esale_id').val(`${obj[0]}`);

    $('#ecustomerName').val("");
    $('#evechicle').val("");
    $('#estockno').val("");
    $('#ehas_appointment').val("null");
    if (obj) {
        if (checkAppt && checkAppt == true) {
            let currentUser = $('#loggedInUserRole').val();
            var apptStatus = obj[10];
            var sales_manager_id = Number(localStorage.getItem('salesManagerID'));;
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
