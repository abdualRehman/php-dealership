"use strict";
var r;
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
    var graphData = [];
    var usedArray = [];
    var newArray = [];
    var userArray = [];


    let chart = document.querySelector("#chart");
    if (chart) {
        fetchSalesGrapData();

        // $("#daterangepicker-1").daterangepicker();

        $('input[name="date_range"]').daterangepicker({
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
                    name: 'USED',
                    // data: [13, 23, 20, 8, 13, 27]
                    data: usedArray
                }, {
                    name: 'NEW',
                    // data: [44, 55, 41, 67, 22, 43]
                    data: newArray
                }],
            chart: {
                type: "bar",
                height: 350,
                width: "100%",
                stacked: true,
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
                    },
                    click: (event, chartContext, config) => {
                        if(config.config.series[config.seriesIndex]?.name){
                            document.location = 'sales/soldLogs.php?r=man';
                        }
                        // console.log(config.config.series[config.seriesIndex])
                        // console.log(config.config.series[config.seriesIndex].name)
                        // console.log(config.config.series[config.seriesIndex].data[config.dataPointIndex])
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    endingShape: "rounded",
                }
            },
            stroke: {
                colors: ["transparent"]
            },
            xaxis: {
                // type: 'category',
                type: 'category',
                // categories: ['Name 1', 'Name 2', 'Name 3', 'Name 4', 'Name 5', 'Name 6'],
                categories: userArray,
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
                var e = $("body").hasClass("theme-dark") ? "dark" : "light";
                r.updateOptions(t[e]);
            });

    }


    function fetchSalesGrapData() {
        $.ajax({
            url: './php_action/fetchSoldLogGraph.php',
            type: "GET",
            dataType: 'json',
            success: function (response) {
                dataArray = response.data;
                graphData = response.graph;
                console.log(graphData);
                drawGraph();

                // graphArray.map((item) => {
                //     if (item['stocktype'] == 'USED') {
                //         usedArray.push([item['time'], item['qty']]);
                //     } else if (item['stocktype'] == 'NEW') {
                //         newArray.push([item['time'], item['qty']]);
                //     }
                // });

                $('#avgN').html(dataArray[0] ? '$' + Number((dataArray[0]).toFixed(2)).toFixed(2).toLocaleString('en') : "$0.00");
                $('#avgU').html(dataArray[1] ? '$' + Number((dataArray[1]).toFixed(2)).toFixed(2).toLocaleString('en') : "$0.00");
                $('#avgT').html(dataArray[2] ? '$' + Number((dataArray[2]).toFixed(2)).toFixed(2).toLocaleString('en') : "$0.00");
                $('#todayN').html(dataArray[3] ? '$' + Number((dataArray[3]).toFixed(2)).toFixed(2).toLocaleString('en') : "$0.00");
                $('#todayU').html(dataArray[4] ? '$' + Number((dataArray[4]).toFixed(2)).toFixed(2).toLocaleString('en') : "$0.00");
                $('#todayT').html(dataArray[5] ? '$' + Number((dataArray[5]).toFixed(2)).toFixed(2).toLocaleString('en') : "$0.00");
                $('#penN').html(dataArray[6] ? Number(dataArray[6]) : "0");
                $('#penU').html(dataArray[7] ? Number(dataArray[7]) : "0");
                $('#penT').html(dataArray[8] ? Number(dataArray[8]) : "0");
                $('#regC').html(dataArray[9] ? Number(dataArray[9]) : "0");
                $('#todoC').html(dataArray[10] ? Number(dataArray[10]) : "0");
                $('#titleC').html(dataArray[11] ? Number(dataArray[11]) : "0");
                $('#currentMonthN').html(dataArray[12] ? Number(dataArray[12]) : "0");
                $('#currentMonthU').html(dataArray[13] ? Number(dataArray[13]) : "0");
                $('#currentMonthT').html(dataArray[14] ? Number(dataArray[14]) : "0");
                $('#todayCN').html(dataArray[15] ? Number(dataArray[15]) : "0");
                $('#todayCU').html(dataArray[16] ? Number(dataArray[16]) : "0");
                $('#todayCT').html(dataArray[17] ? Number(dataArray[17]) : "0");
                
                $('#warrantyC').html(dataArray[18] ? Number(dataArray[18]) : "0");
                $('#thisMonth').click();

            }
        });
    }

    $('input[name="date_range"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment());
        $(this).data('daterangepicker').setEndDate(moment());

        $('#searchStatus :radio[name="searchStatus"]').prop('checked', false);
        $('#searchStatus .active').removeClass('active');
        drawGraph();


    });
    $('input[name="date_range"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY') + ' / ' + picker.endDate.format('MM-DD-YYYY'));
        $('#searchStatus :radio[name="searchStatus"]').prop('checked', false);
        $('#searchStatus .active').removeClass('active');
        var start = picker.startDate.format('YYYY-MM-DD');
        var end = picker.endDate.format('YYYY-MM-DD');

        drawGraph(start, end);
    });


    $('input[name="searchStatus"]:radio').on('change', function () {
        var dateType = $('input:radio[name="searchStatus"]:checked').map(function () {
            if (this.value !== "") {
                return this.value;
            }
        }).get();


        if (dateType == 'lastMonth') {

            const todayDate = moment(new Date()).format("YYYY-MM-DD");
            const startDayOfPrevMonth = moment(todayDate).subtract(1, 'month').startOf('month').format('YYYY-MM-DD')
            const lastDayOfPrevMonth = moment(todayDate).subtract(1, 'month').endOf('month').format('YYYY-MM-DD')

            drawGraph(startDayOfPrevMonth, lastDayOfPrevMonth);
        } else if (dateType == 'thisMonth') {
            const startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
            const endOfMonth = moment().endOf('month').format('YYYY-MM-DD');
            drawGraph(startOfMonth, endOfMonth)
        }
    });



    function drawGraph(start = null, end = null) {
        usedArray = []; newArray = []; userArray = [];
        graphData.forEach(element => {
            var nT = 0;
            var uT = 0;
            (element.data).map((e) => {
                if (start != null && end != null) {
                    let compareDate = moment((e.time), "YYYY-MM-DD").format('DD/MM/YYYY');
                    compareDate = moment(compareDate, "DD/MM/YYYY")
                    let startDate = moment(start, "YYYY-MM-DD").format('DD/MM/YYYY');
                    startDate = moment(startDate, "DD/MM/YYYY");
                    let endDate = moment(end, "YYYY-MM-DD").format('DD/MM/YYYY');
                    endDate = moment(endDate, "DD/MM/YYYY");

                    const isBetween = compareDate.isBetween(startDate, endDate, 'days', '[]');
                    if (isBetween) {
                        nT += e.new;
                        uT += e.used;
                    }
                } else {
                    nT += e.new;
                    uT += e.used;
                }

            });
            usedArray.push(uT);
            newArray.push(nT);
            userArray.push(element.name)
        });

        r.updateSeries([{
            name: 'USED',
            data: usedArray
        }, {
            name: 'NEW',
            data: newArray
        }]);

        r.updateOptions({
            xaxis: {
                categories: userArray
            }
        }, true);

    }
});

