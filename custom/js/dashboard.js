"use strict";
var r;
var dataArray = [], manageChartTableTable;
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
    var graphData = [] , tableData = [];
    var usedArray = [], usedTArray = [];
    var newArray = [], newTArray = [];
    var userArray = [], userTArray = [];


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
                    name: 'NEW',
                    // data: [44, 55, 41, 67, 22, 43]
                    data: newArray
                },
                {
                    name: 'USED',
                    // data: [13, 23, 20, 8, 13, 27]
                    data: usedArray
                },
            ],
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
                        if (config.config.series[config.seriesIndex]?.name) {
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
                var graphArray = response.graph;
                const uid = $('#uid_graph').val();
                if(uid != "null"){
                    graphArray = graphArray.filter((e) => e.id == uid);
                }
                graphData = graphArray;
                tableData = response.graph;

                if ($('input[name="changeView"]').is(':checked')) {
                    drawTable()
                } else {
                    drawGraph()
                }

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
        // drawGraph();
        // drawTable();
        if ($('input[name="changeView"]').is(':checked')) {
            drawTable();
        } else {
            drawGraph();
        }


    });
    $('input[name="date_range"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY') + ' / ' + picker.endDate.format('MM-DD-YYYY'));
        $('#searchStatus :radio[name="searchStatus"]').prop('checked', false);
        $('#searchStatus .active').removeClass('active');
        var start = picker.startDate.format('YYYY-MM-DD');
        var end = picker.endDate.format('YYYY-MM-DD');

        // drawGraph(start, end);
        // drawTable(start, end);
        if ($('input[name="changeView"]').is(':checked')) {
            drawTable(start, end);
        } else {
            drawGraph(start, end);
        }
    });


    $('input[name="searchStatus"]:radio').on('change', function () {
        var dateType = $('input:radio[name="searchStatus"]:checked').map(function () {
            if (this.value !== "") {
                return this.value;
            }
        }).get();


        if (dateType == 'lastMonth') {

            const todayDate = moment(new Date(new Date().toLocaleString('en', {timeZone: 'America/New_York'}))).format("YYYY-MM-DD");
            const startDayOfPrevMonth = moment(todayDate).subtract(1, 'month').startOf('month').format('YYYY-MM-DD')
            const lastDayOfPrevMonth = moment(todayDate).subtract(1, 'month').endOf('month').format('YYYY-MM-DD')

            // drawGraph(startDayOfPrevMonth, lastDayOfPrevMonth);
            // drawTable(startDayOfPrevMonth, lastDayOfPrevMonth);
            if ($('input[name="changeView"]').is(':checked')) {
                drawTable(startDayOfPrevMonth, lastDayOfPrevMonth);
            } else {
                drawGraph(startDayOfPrevMonth, lastDayOfPrevMonth);
            }
        } else if (dateType == 'thisMonth') {
            const startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
            const endOfMonth = moment().endOf('month').format('YYYY-MM-DD');

            if ($('input[name="changeView"]').is(':checked')) {
                drawTable(startOfMonth, endOfMonth)
            } else {
                drawGraph(startOfMonth, endOfMonth)
            }
        }
    });


    $('input[name="changeView"]:checkbox').on('change', function () {
        const checked = $(this).is(':checked');
        if (checked) {
            $('#chart').addClass('d-none');
            $('#chartTableDiv').removeClass('d-none');
        } else {
            $('#chart').removeClass('d-none');
            $('#chartTableDiv').addClass('d-none');
        }
        $('input[name="date_range"]').val('');
        $('input:radio[name="searchStatus"]').attr('checked', false);
        $('#searchStatus .active').removeClass('active');
        $('#thisMonth').click();
        setTimeout(() => {
            manageChartTableTable.columns.adjust();
        }, 500);
    });

    function drawTable(start = null, end = null) {
        var maxNumber = 0;
        // usedTArray = []; newTArray = []; userTArray = [];
        userTArray = [];
        var rankIndex = 0;
        tableData.forEach((element) => {
            var nT = 0, nD = 0, nP = 0;
            var uT = 0, uD = 0, uP = 0;
            var totalq = 0;
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
                        nD += e.newD;
                        nP += e.newP;
                        uD += e.usedD;
                        uP += e.usedP;

                    }
                } else {
                    nT += e.new;
                    uT += e.used;
                    nD += e.newD;
                    nP += e.newP;
                    uD += e.usedD;
                    uP += e.usedP;
                }

            });
            totalq = (uT + nT);
            if (totalq > 0) {
                userTArray.push([rankIndex + 1, element.name, uT, nT, totalq, uD, uP, nD, nP]);
                rankIndex++;
            }
        });
        userTArray = userTArray.sort((a, b) => (b[5] + b[6] + b[7] + b[8]) - (a[5] + a[6] + a[7] + a[8]));

        if ($.fn.dataTable.isDataTable('#chartTable')) {
            manageChartTableTable.clear().draw();
            manageChartTableTable.rows.add(userTArray); // Add new data
            manageChartTableTable.columns.adjust().draw(); // Redraw the DataTable
        } else {
            manageChartTableTable = $('#chartTable').DataTable({
                "paging": false,
                "scrollX": true,
                dom: `<'row'>
                <'row'<'col-12'tr>>\n      
                <'row align-items-baseline mt-3' <'col-md-12 m-auto'<'#filterDatatableDivs'>>  >\n`,
                "orderClasses": false,
                // "order": [[4, "desc"]],
                "ordering": false,
                // working...
                // "aaSorting": [[4, "desc"]],
                // "aoColumns": [
                //     { "bSortable": false },
                //     { "bSortable": false },
                //     { "bSortable": false },
                //     { "bSortable": false },
                //     { "bSortable": false },
                //     { "bSortable": false },
                // ],
                "bInfo": false,
                "deferRender": false,
                autoWidth: false,
                data: userTArray,
                columnDefs: [
                    {
                        targets: [0, 2, 3, 4],
                        width: 60,
                        className: 'text-center'
                    },
                    {
                        targets: [1],
                        width: 80,
                        className: 'text-center',
                    },
                    {
                        targets: [0],
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                    },
                    {
                        targets: [5],
                        createdCell: function (td, cellData, rowData, row, col) {
                            let array = [rowData[5], rowData[6], rowData[7], rowData[8]];
                            let eachRowTotal = (rowData[5] + rowData[6] + rowData[7] + rowData[8]);
                            let images = '';
                            if (eachRowTotal > maxNumber) {
                                maxNumber = eachRowTotal
                            }
                            array.forEach((elements, elementIndex) => {
                                if (elements > 0) {
                                    for (let i = 0; i < elements; i++) {
                                        switch (elementIndex) {
                                            case 0:
                                                images += '<img src="./assets/used-delivered.png" class="p-2" style="max-width:60px" alt="nd">';
                                                break;
                                            case 1:
                                                images += '<img src="./assets/used-pending.png" class="p-2" style="max-width:60px" alt="nd">';
                                                break;
                                            case 2:
                                                images += '<img src="./assets/new-delivered.png" class="p-2" style="max-width:60px" alt="nd">';
                                                break;
                                            case 3:
                                                images += '<img src="./assets/new-pending.png" class="p-2" style="max-width:60px" alt="nd">';
                                                break;
                                            default:
                                                break;
                                        }
                                    }
                                }
                            });
                            $(td).html(`
                            <div class="row" style="display:inline-flex">
                                <div class="col">
                                   ${images}
                                </div>
                            </div>
                            `);
                        },
                    },
                ],
                language: {
                    "infoFiltered": "",
                },
                "drawCallback": function (settings) {
                    let titleHtml_wrap = ``;
                    let titleHtml = ``;
                    for (let i = 0; i < maxNumber; i++) {
                        // titleHtml += `<span style="margin:0 25.9px" >${i + 1}</span>`;
                        titleHtml += `<span style="min-width: 60px;max-width: 60px;width: 60px!important;display: flex;flex-wrap: nowrap;justify-content: space-around;align-items: center;" >${i + 1}</span>`;
                    }
                    titleHtml_wrap = `<div class="title_rank" style="overflow: hidden;text-overflow: ellipsis;width: inherit;display:inline-flex"> ${titleHtml}<div>`;
                    // $('#chartTable_wrapper .dataTables_scrollHead thead tr:eq(0) th:eq(5)').html(titleHtml_wrap);
                    $('#max_number_row').html(titleHtml_wrap);
                    maxNumber = 0;
                    writeFilterDatatableHTML();
                },
            });

        }
    }

    function writeFilterDatatableHTML() {
        $('#filterDatatableDivs').html(`
        <div class="row">
            <div class="col-md-12 d-md-flex justify-content-center">
                <div class="d-flex justify-items-center flex-column align-items-center m-3">
                    <img src="./assets/used-pending.png" class="m-2" style="max-width:50px;" alt="used-pending" />
                    <label class="form-label h6" for="used-delivered">
                        Used Pending
                    </label>
                </div>
                <div class="d-flex justify-items-center flex-column align-items-center m-3">
                    <img src="./assets/used-delivered.png" class="m-2" style="max-width:50px;" alt="used-delivered" />
                    <label class="form-label h6" for="used-delivered">
                        Used Delivered
                    </label>
                </div>
                <div class="d-flex justify-items-center flex-column align-items-center m-3">
                    <img src="./assets/new-pending.png" class="m-2" style="max-width:50px;" alt="new-pending" />
                    <label class="form-label h6" for="new-delivered">
                        New Pending
                    </label>
                </div>
                <div class="d-flex justify-items-center flex-column align-items-center m-3">
                    <img src="./assets/new-delivered.png" class="m-2" style="max-width:50px;" alt="new-delivered" />
                    <label class="form-label h6" for="new-delivered">
                        New Delivered
                    </label>
                </div>
            </div>
        </div>`);
    }



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

        r.updateSeries([
            {
                name: 'NEW',
                data: newArray
            },
            {
                name: 'USED',
                data: usedArray
            }
        ]);

        r.updateOptions({
            xaxis: {
                categories: userArray
            }
        }, true);

    }
});

