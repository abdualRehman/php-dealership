'use strict';

/*eslint-disable*/

var DefaultScheduleList = [];
var ScheduleList = [];
var start = moment("28/07/2022").format("DD/MM/YYYY")
var end = moment(new Date()).format("DD/MM/YYYY")
var ScheduleList1 = [
    {
        "id": "f908cdad-af63-5d52-9191-e79044c6a771",
        "calendarId": "1",
        "title": "Ubani guzfahal wdns.",
        "body": "Tenodop tomi gifivji wicos anhof mip zurosbo uzlovoc je noztupfen.",
        "location": "925 Nulzon Junction",
        "isAllday": false,
        "start": "2022-07-29T16:30:00.000Z",
        "end": "2022-07-29T19:30:00.000Z",
        "category": "time",
        "dueDateClass": "",
        "color": "#ffffff",
        "bgColor": "#9e5fff",
        "dragBgColor": "#9e5fff",
        "borderColor": "#9e5fff",
        "customStyle": "",
        "isFocused": false,
        "isPending": false,
        "isVisible": true,
        "isReadOnly": false,
        "isPrivate": false,
        "goingDuration": 0,
        "comingDuration": 0,
        "recurrenceRule": "",
        "state": "Busy",
        "raw": {
            "memo": "Nuza ofale migirlib izpedi bobe tevzuiv tone bi oc famer zekbev tu bergupe nevoten ungol sil ecivrum.",
            "hasToOrCc": false,
            "hasRecurrenceRule": false,
            "location": null,
            "creator": {
                "name": "Ophelia Baldwin",
                "avatar": "//www.gravatar.com/avatar/72a6af67b7a7646c95bbbf6dd179dc01",
                "company": "Apache Corporation",
                "email": "ute@vep.ck",
                "phone": "(850) 859-3512",
                "customer": "Customer",
                "stock": "Stock",
                "Delivery": "fhcwoin",
                "id": "1343"
            }
        },
        "attendees": [
            "Vincent Reed",
            "Catherine Scott",
            "Christian Patrick",
            "Erik Brewer",
            "Ollie Gonzalez",
            "Ricardo Sparks",
            "Frank Wood",
            "Nelle Norris"
        ]
    },
    {
        "id": "f908cdad-af63-5d52-9191-e79044c6a77123",
        "calendarId": "1",
        "title": "Ubani guzfahal fe.",
        "body": "Tenodop tomi gifivji wicos anhof mip zurosbo uzlovoc je noztupfen.",
        "location": "925 Nulzon Junction",
        "isAllday": false,
        // "start": "2022-07-25T00:00:00.000Z",
        // "end": "2022-07-25T04:30:00.000Z",
        "start": "2022-07-29T00:00:00.000Z",
        "end": "2022-07-29T04:30:00.000Z",
        "category": "time",
        "dueDateClass": "",
        "color": "#ffffff",
        "bgColor": "#9e5fff",
        "dragBgColor": "#9e5fff",
        "borderColor": "#9e5fff",
        "customStyle": "",
        "isFocused": false,
        "isPending": false,
        "isVisible": true,
        "isReadOnly": false,
        "isPrivate": false,
        "goingDuration": 0,
        "comingDuration": 0,
        "recurrenceRule": "",
        "state": "Busy",
        "raw": {
            "memo": "Nuza ofale migirlib izpedi bobe tevzuiv tone bi oc famer zekbev tu bergupe nevoten ungol sil ecivrum.",
            "hasToOrCc": false,
            "hasRecurrenceRule": false,
            "location": null,
            "creator": {
                "name": "",
                "avatar": "",
                "company": "",
                "email": "",
                "phone": "",
                "customer": "",
                "stock": "",
                "Delivery": "fhcwoin",
                "id": "1343"
            }
        },
        "attendees": [
            "Vincent Reed",
            "Catherine Scott",
            "Christian Patrick",
            "Erik Brewer",
            "Ollie Gonzalez",
            "Ricardo Sparks",
            "Frank Wood",
            "Nelle Norris"
        ]
    },
    {
        "id": "c501fa55-0928-55a9-b087-c2af342b907a",
        "calendarId": "2",
        "title": "Jamudi juv ropwusej.",
        "body": "",
        "location": "1611 Vugsat Glen",
        "isAllday": false,
        "start": "2022-07-21Z",
        "end": "2022-07-21Z",
        "category": "task",
        "dueDateClass": "morning",
        "color": "#ffffff",
        "bgColor": "#00a9ff",
        "dragBgColor": "#00a9ff",
        "borderColor": "#00a9ff",
        "customStyle": "",
        "isFocused": false,
        "isPending": false,
        "isVisible": true,
        "isReadOnly": false,
        "isPrivate": false,
        "goingDuration": 0,
        "comingDuration": 0,
        "recurrenceRule": "",
        "state": "Busy",
        "raw": {
            "memo": "Wavo sug jeg cineso ilro aveco zevif tebgimsis ruketehil mugpaf beewzik iwki nundi dugajfa pahnim malhufwad.",
            "hasToOrCc": false,
            "hasRecurrenceRule": false,
            "location": null,
            "creator": {
                "name": "Herman Newman",
                "avatar": "//www.gravatar.com/avatar/93b2fa34519d936e6759f4b821c13311",
                "company": "CMS Energy Corp.",
                "email": "",
                "phone": "",
                "customer": "Customer",
                "stock": "",
                "Delivery": "",
                "id": ""
            }
        },
        "attendees": [
            "Devin Shelton",
            "Ollie Neal",
            "Edith Baldwin",
            "Garrett Osborne",
            "Helen Dixon"
        ]
    }
];

var SCHEDULE_CATEGORY = [
    'milestone',
    'task'
];

function ScheduleInfo() {
    this.id = null;
    this.calendarId = null;

    this.title = null;
    this.body = null;
    this.location = null;
    this.isAllday = false;
    this.start = null;
    this.end = null;
    this.category = '';
    this.dueDateClass = '';

    this.color = null;
    this.bgColor = null;
    this.dragBgColor = null;
    this.borderColor = null;
    this.customStyle = '';

    this.isFocused = false;
    this.isPending = false;
    this.isVisible = true;
    this.isReadOnly = false;
    this.isPrivate = false;
    this.goingDuration = 0;
    this.comingDuration = 0;
    this.recurrenceRule = '';
    this.state = '';

    this.raw = {
        memo: '',
        hasToOrCc: false,
        hasRecurrenceRule: false,
        location: null,
        creator: {
            name: '',
            avatar: '',
            company: '',
            email: '',
            phone: ''
        }
    };
}

function generateTime(schedule, renderStart, renderEnd) {
    var startDate = moment(renderStart.getTime())
    var endDate = moment(renderEnd.getTime());
    var diffDate = endDate.diff(startDate, 'days');

    schedule.isAllday = chance.bool({ likelihood: 30 });
    if (schedule.isAllday) {
        schedule.category = 'allday';
    }
    else if (chance.bool({ likelihood: 30 })) {
        schedule.category = SCHEDULE_CATEGORY[chance.integer({ min: 0, max: 1 })];
        if (schedule.category === SCHEDULE_CATEGORY[1]) {
            schedule.dueDateClass = 'morning';
        }
    } else {
        schedule.category = 'time';
    }

    startDate.add(chance.integer({ min: 0, max: diffDate }), 'days');
    startDate.hours(chance.integer({ min: 0, max: 23 }))
    startDate.minutes(chance.bool() ? 0 : 30);
    schedule.start = startDate.toDate();

    endDate = moment(startDate);
    if (schedule.isAllday) {
        endDate.add(chance.integer({ min: 0, max: 3 }), 'days');
    }

    schedule.end = endDate
        .add(chance.integer({ min: 1, max: 4 }), 'hour')
        .toDate();

    if (!schedule.isAllday && chance.bool({ likelihood: 20 })) {
        schedule.goingDuration = chance.integer({ min: 30, max: 120 });
        schedule.comingDuration = chance.integer({ min: 30, max: 120 });;

        if (chance.bool({ likelihood: 50 })) {
            schedule.end = schedule.start;
        }
    }
}

function generateNames() {
    var names = [];
    var i = 0;
    var length = chance.integer({ min: 1, max: 10 });

    for (; i < length; i += 1) {
        names.push(chance.name());
    }

    return names;
}

function generateRandomSchedule(calendar, renderStart, renderEnd) {
    var schedule = new ScheduleInfo();

    schedule.id = chance.guid();
    schedule.calendarId = calendar.id;

    schedule.title = chance.sentence({ words: 3 });
    schedule.body = chance.bool({ likelihood: 20 }) ? chance.sentence({ words: 10 }) : '';
    schedule.isReadOnly = chance.bool({ likelihood: 20 });
    generateTime(schedule, renderStart, renderEnd);

    schedule.isPrivate = chance.bool({ likelihood: 10 });
    schedule.location = chance.address();
    schedule.attendees = chance.bool({ likelihood: 70 }) ? generateNames() : [];
    schedule.recurrenceRule = chance.bool({ likelihood: 20 }) ? 'repeated events' : '';
    schedule.state = chance.bool({ likelihood: 20 }) ? 'Free' : 'Busy';
    schedule.color = calendar.color;
    schedule.bgColor = calendar.bgColor;
    schedule.dragBgColor = calendar.dragBgColor;
    schedule.borderColor = calendar.borderColor;

    if (schedule.category === 'milestone') {
        schedule.color = schedule.bgColor;
        schedule.bgColor = 'transparent';
        schedule.dragBgColor = 'transparent';
        schedule.borderColor = 'transparent';
    }

    schedule.raw.memo = chance.sentence();
    schedule.raw.creator.name = chance.name();
    schedule.raw.creator.avatar = chance.avatar();
    schedule.raw.creator.company = chance.company();
    schedule.raw.creator.email = chance.email();
    schedule.raw.creator.phone = chance.phone();
    schedule.raw.creator.customer = "Customer";
    schedule.raw.creator.stock = "Stock";
    schedule.raw.creator.Delivery = "fhcwoin";
    schedule.raw.creator.id = "1343";

    if (chance.bool({ likelihood: 20 })) {
        var travelTime = chance.minute();
        schedule.goingDuration = travelTime;
        schedule.comingDuration = travelTime;
    }

    ScheduleList.push(schedule);
}

function generateSchedule(viewName, renderStart, renderEnd) {
    ScheduleList = [];
    CalendarList.forEach(function (calendar) {
        var i = 0, length = 10;
        if (viewName === 'month') {
            length = 3;
        } else if (viewName === 'day') {
            length = 4;
        }
        // for (; i < length; i += 1) {
        //     generateRandomSchedule(calendar, renderStart, renderEnd);
        // }
        for (; i < 1; i += 1) {
            generateRandomSchedule(calendar, renderStart, renderEnd);
        }
    });
}


