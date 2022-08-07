"use strict";
var r;
var usedArray = [];
var newArray = [];
var dataArray = [];
function t(t, e) {
    var a, n = Object.keys(t);
    return Object.getOwnPropertySymbols && (a = Object.getOwnPropertySymbols(t), e && (a = a.filter(function (e) {
        return Object.getOwnPropertyDescriptor(t, e).enumerable
    })), n.push.apply(n, a)), n
}

function x(n) {
    for (var e = 1; e < arguments.length; e++) {
        var r = null != arguments[e] ? arguments[e] : {};
        e % 2 ? t(Object(r), !0).forEach(function (e) {
            var t, a;
            t = n, e = r[a = e], a in t ? Object.defineProperty(t, a, {
                value: e,
                enumerable: !0,
                configurable: !0,
                writable: !0
            }) : t[a] = e
        }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(n, Object.getOwnPropertyDescriptors(r)) : t(Object(r)).forEach(function (e) {
            Object.defineProperty(n, e, Object.getOwnPropertyDescriptor(r, e))
        })
    }
    return n
}
$(function () {

    let chart = document.querySelector("#chart");
    if (chart) {
        fetchSalesGrapData();

        $("#daterangepicker-1").daterangepicker();

        $('input[name="date_range"]').daterangepicker({
            // "showDropdowns": false,
            // timePicker: !0,
            autoUpdateInput: false,
            cleanable: true,
            "opens": "left",
            locale: {
                format: 'MM-DD-YYYY',
                applyLabel: 'Submit',
                cancelLabel: 'Reset',
            },
        });

        var e = "dark" == localStorage.getItem("theme-variant") ? "dark" : "light",
            t = {
                light: {
                    theme: {
                        mode: "light",
                        palette: "palette1"
                    }
                },
                dark: {
                    theme: {
                        mode: "dark",
                        palette: "palette1"
                    }
                }
            };


        r = new ApexCharts(document.querySelector("#chart"), x(x({}, t[e]), {}, {
            series: [
                {
                    name: "NEW",
                    data: newArray
                },
                {
                    name: "USED",
                    data: usedArray
                },
            ],
            chart: {
                type: "bar",
                height: 350,
                width: "100%",
                background: "transparent",
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                events: {
                    beforeResetZoom: function (chartContext, opts) {
                        console.log("fun called");
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    endingShape: "rounded",
                    columnWidth: '170%',
                    rangeBarOverlap: true,
                    dataLabels: {
                        position: 'center',
                    },
                }
            },
            dataLabels: {
                enabled: !0,
                enabledOnSeries: undefined,
                formatter: function (val, opts) {
                    return val
                },
                textAnchor: 'end',
                distributed: false,
                offsetX: 3,
                offsetY: 10,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 'bold',
                    colors: undefined
                },
                background: {
                    enabled: true,
                    foreColor: '#fff',
                    padding: 4,
                    borderRadius: 2,
                    borderWidth: 0,
                    borderColor: '#fff',
                    opacity: 1,
                    dropShadow: {
                        enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 1
                    }
                },
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            toolbar: {
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: false,
                    customIcons: []
                }
            },
            stroke: {
                // show: !0,
                // width: 2,
                colors: ["transparent"]
            },
            xaxis: {
                type: 'datetime'
            },
            yaxis: {
                title: {
                    text: "Quantity"
                }
            },
            fill: {
                opacity: 1
            },

        }));

        r.render(),
            $("#theme-toggle").on("click", function () {
                console.log("dara");
                var e = $("body").hasClass("theme-dark") ? "dark" : "light";
                r.updateOptions(t[e]);
            });

    }


    function fetchSalesGrapData() {
        // console.log("wdwd");
        $.ajax({
            url: './php_action/fetchSoldLogGraph.php',
            type: "GET",
            dataType: 'json',
            success: function (response) {
                console.log(response);
                dataArray = response.data;
                var graphArray = response.graph;

                graphArray.map((item) => {
                    if (item['stocktype'] == 'USED') {
                        usedArray.push([item['time'], item['qty']]);
                    } else if (item['stocktype'] == 'NEW') {
                        newArray.push([item['time'], item['qty']]);
                    }
                });
                // console.log(newArray);
                // console.log(usedArray);

                var e = $("body").hasClass("theme-dark") ? "dark" : "light";
                r.updateOptions(t[e]);

                // console.log(Number(price).toLocaleString('en'));
                console.log(response.data);


                $('#avgN').html(dataArray[0] ? '$' + Number((dataArray[0]).toFixed(2)).toLocaleString('en') : "$0");
                $('#avgU').html(dataArray[1] ? '$' + Number((dataArray[1]).toFixed(2)).toLocaleString('en') : "$0");
                $('#avgT').html(dataArray[2] ? '$' + Number((dataArray[2]).toFixed(2)).toLocaleString('en') : "$0");

                $('#todayN').html(dataArray[3] ? '$' + Number((dataArray[3]).toFixed(2)).toLocaleString('en') : "$0");
                $('#todayU').html(dataArray[4] ? '$' + Number((dataArray[4]).toFixed(2)).toLocaleString('en') : "$0");
                $('#todayT').html(dataArray[5] ? '$' + Number((dataArray[5]).toFixed(2)).toLocaleString('en') : "$0");

                $('#penN').html(dataArray[6] ? Number(dataArray[6]) : "0");
                $('#penU').html(dataArray[7] ? Number(dataArray[7]) : "0");
                $('#penT').html(dataArray[8] ? Number(dataArray[8]) : "0");

                $('#regC').html(dataArray[9] ? Number(dataArray[9]) : "0");
                $('#todoC').html(dataArray[10] ? Number(dataArray[10]) : "0");
                $('#titleC').html(dataArray[11] ? Number(dataArray[11]) : "0");

            }
        });
    }

    $('#filterBtn').on('click', function (e) {
        // console.log(e);
        r.zoomX(new Date('2022-05-29').getTime(), new Date('2022-06-22').getTime());
        var e = $("body").hasClass("theme-dark") ? "dark" : "light";
        r.updateOptions(t[e], true);
    });

    $('input[name="date_range"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment());
        $(this).data('daterangepicker').setEndDate(moment());

        $('#searchStatus :radio[name="searchStatus"]').prop('checked', false);
        $('#searchStatus .active').removeClass('active');
        r.resetSeries();

    });
    $('input[name="date_range"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY') + ' / ' + picker.endDate.format('MM-DD-YYYY'));
        $('#searchStatus :radio[name="searchStatus"]').prop('checked', false);
        $('#searchStatus .active').removeClass('active');
        var start = picker.startDate.format('YYYY-MM-DD');
        var end = picker.endDate.format('YYYY-MM-DD');
        r.zoomX(new Date(start).getTime(), new Date(end).getTime());
        var e = $("body").hasClass("theme-dark") ? "dark" : "light";
        r.updateOptions(t[e], true);
    });


    $('input[name="searchStatus"]:radio').on('change', function () {
        var dateType = $('input:radio[name="searchStatus"]:checked').map(function () {
            if (this.value !== "") {
                return this.value;
            }
        }).get();

        console.log(dateType);

        if (dateType == 'lastMonth') {


            const todayDate = moment(new Date()).format("YYYY-MM-DD");
            const startDayOfPrevMonth = moment(todayDate).subtract(1, 'month').startOf('month').format('YYYY-MM-DD')
            const lastDayOfPrevMonth = moment(todayDate).subtract(1, 'month').endOf('month').format('YYYY-MM-DD')


            r.zoomX(new Date(startDayOfPrevMonth).getTime(), new Date(lastDayOfPrevMonth).getTime());
            var e = $("body").hasClass("theme-dark") ? "dark" : "light";
            r.updateOptions(t[e], true);

        } else if (dateType == 'thisMonth') {
            const startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
            const endOfMonth = moment().endOf('month').format('YYYY-MM-DD');

            r.zoomX(new Date(startOfMonth).getTime(), new Date(endOfMonth).getTime());
            var e = $("body").hasClass("theme-dark") ? "dark" : "light";
            r.updateOptions(t[e], true);


        }


    });

});

