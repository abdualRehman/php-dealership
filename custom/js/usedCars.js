
"use strict";
var manageInvTable, TableData, maxFileLimit = 10, rowGroupSrc = 23;
var searhStatusArray = [];
var manageCDKAgeTable;
var collapsedGroups = {};
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})

$(function () {
    $('.nav-link').removeClass('active');
    $('#usedCars').addClass('active');

    $('#statusBar').addClass('d-none');
    $('#searchBar').removeClass('d-none');



    $("#invDate").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,
    });

    $("#dateSent").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });
    $("#dateSold").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });

    autosize($(".autosize"));

    // var a = new Bloodhound({
    //     datumTokenizer: Bloodhound.tokenizers.obj.whitespace('stockDetails'),
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: searhStatusArray
    // });

    // $('#searchcars').typeahead({
    //     hint: true,
    //     highlight: true,
    //     minLength: 1,
    // },
    //     {
    //         name: 'searhStatusArray',
    //         display: 'stockDetails',
    //         source: a,
    // templates: {
    //     suggestion: function (data) {
    //         var stockAvailibilityArray = data.stockAvailibility;
    //         const template = `<div><p><strong>${data.stockDetails}</strong></p><div class="row pl-2 pr-2">${stockAvailibilityArray.map(e => (e) ? `<div class="col-sm-4 p-1"> <span class="badge badge-label-primary"> ${e} </span> </div>` : ``).join('')}</div>`;
    //         return template;
    //     }
    // }
    //     });


    manageInvTable = $("#datatable-1").DataTable({

        responsive: !0,
        'ajax': '../php_action/fetchUsedCars.php',
        "paging": true,
        "scrollX": true,
        "orderClasses": false,
        "deferRender": true,
        "pageLength": 25,
        dom: `<'row'<'col-12'P>>
        <'row' 
        <'col-sm-4 text-left text-sm-left pl-3'<'#statusFilterDiv'>>
            <'col-sm-4 text-sm-center pl-3'B>
            <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
        <'row'<'col-12'tr>>\n      
        <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Used Cars',
                exportOptions: {
                    columns: [':visible'],
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Used Cars',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'print',
                title: 'Used Cars',
                exportOptions: {
                    columns: [':visible']
                },
                customize: function (win) {
                    $(win.document.body)
                        .css('font-size', '10pt');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            },
        ],

        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [21, 26, 27, 12, 28]
        },

        columnDefs: [
            {
                targets: [0, 3, 4, 11, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
                visible: false,
            },
            { width: 400, targets: [15 , 23] },
            {
                targets: [1], // age
                createdCell: function (td, cellData, rowData, row, col) {
                    var data = $(td).html();
                    if (data > 4) {
                        $(td).addClass('h5 font-weight-bolder text-danger');
                    } else {
                        $(td).addClass('font-weight-bold p');
                    }
                }
            },
            {
                targets: [2], // stockno || vin
                createdCell: function (td, cellData, rowData, row, col) {
                    let retailStatus = rowData[23];
                    let wholesale = rowData[20];
                    if (retailStatus == 'retail' && wholesale == 'Yes') {
                        $(td).addClass('font-weight-bolder text-danger');
                    } else {
                        $(td).addClass('font-weight-bold p');
                    }
                }
            },
            {
                targets: [4], // keys,
                createdCell: function (td, cellData, rowData, row, col) {

                    if ($('#isRoleAllowed').val() == 'true') {
                        $(td).html(` <div class="custom-control custom-control-lg custom-checkbox">
                            <input type="checkbox" name="keyCheckbox" data-attribute="key" onchange="handletitleCheckbox(this)" data-id="${rowData[0]}" class="custom-control-input keyCheckbox" id="${rowData[0]}Key" ${((rowData[4] == 'false') ? '' : 'checked="checked"')} >
                            <label class="custom-control-label" for="${rowData[0]}Key"></label> 
                        </div>`);
                    } else {
                        $(td).html(rowData[4]);
                    }
                }
            },
            {
                targets: [11], // title,
                createdCell: function (td, cellData, rowData, row, col) {
                    if ($('#isRoleAllowed').val() == 'true' || $('#titleEditAllowed').val() == 'true') {
                        $(td).html(`<div class="custom-control custom-control-lg custom-checkbox">
                                <input type="checkbox" name="titleCheckbox" data-attribute="title" onchange="handletitleCheckbox(this)" data-id="${rowData[0]}" class="custom-control-input titleCheckbox" id="${rowData[0]}Title" ${((rowData[11] == 'false') ? '' : 'checked="checked"')} >
                                <label class="custom-control-label" for="${rowData[0]}Title"></label> 
                            </div>`);
                    } else {
                        $(td).html(rowData[11]);
                    }

                }
            },
            {
                targets: [12], // Title Priority,
                createdCell: function (td, cellData, rowData, row, col) {
                    // data-container=".containerDiv${rowData[0]}"
                    let colorClass = '';
                    switch (rowData[12]) {
                        case 'New':
                            colorClass = 'badge-info';
                            break;
                        case 'Low':
                            colorClass = 'badge-primary';
                            break;
                        case 'Medium':
                            colorClass = 'badge-warning';
                            break;
                        case 'High':
                        case 'Problem':
                            colorClass = 'badge-danger';
                            break;
                        case 'Done':
                            colorClass = 'badge-success';
                            break;
                        default:
                            colorClass = '';
                            break;
                    }
                    $(td).html(`<span class="badge badge-lg ${colorClass}">${rowData[12]}</span>`);
                }
            },
            {
                targets: [20], // wholesale
                createdCell: function (td, cellData, rowData, row, col) {
                    var activebtnvalue = $(`#mods .btn.active input[name='mod']`).val();
                    if (activebtnvalue == 'readyToShip') {
                        var data = $(td).html();
                        if (data == 'No') {
                            $(td).addClass('h5 font-weight-bolder text-danger');
                        } else {
                            $(td).addClass('font-weight-bold p');
                        }
                    } else if (activebtnvalue == 'retail') {
                        var data = $(td).html();
                        if (data == 'Yes') {
                            $(td).addClass('h5 font-weight-bolder text-danger');
                        } else {
                            $(td).addClass('font-weight-bold p');
                        }
                    } else {
                        return data;
                    }

                }
            },
            {
                targets: [21], // retail status
                render: function (data, type, row) {
                    return row[23];
                },
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(rowData[23]);
                }
            },
            {
                targets: [22], // date sent,
                createdCell: function (td, cellData, rowData, row, col) {
                    if ($('#isRoleAllowed').val() == 'true') {
                        $(td).html(`<div class="show d-flex" >
                            <input type="text" class="form-control" name="date_in_table" value="${rowData[21]}" data-attribute="date_sent" data-id="${rowData[0]}" autocomplete="off"  />
                        </div>`);
                    } else {
                        $(td).html(rowData[21]);
                    }
                }
            },
            {
                targets: [23], // wholesale notes
                createdCell: function (td, cellData, rowData, row, col) {
                    if ($('#isRoleAllowed').val() == 'true') {
                        $(td).html(`<div class="show d-flex" >
                            <input type="text" class="form-control wholesale_notes" name="input_field" value="${rowData[25] ? rowData[25] : ""}" id="${rowData[0]}wholesale_notes" data-attribute="wholesale_notes" data-id="${rowData[0]}" autocomplete="off"  />
                        </div>`);
                    } else {
                        $(td).html(rowData[25]);
                    }

                }
            },
            {
                targets: [24], // sold price
                createdCell: function (td, cellData, rowData, row, col) {
                    if ($('#isRoleAllowed').val() == 'true') {
                        $(td).html(`<div class="show d-flex" >
                            <input type="text" class="form-control sold_price" name="input_field" value="${rowData[26] ? rowData[26] : 0}" id="${rowData[0]}sold_price" data-attribute="sold_price" data-id="${rowData[0]}" autocomplete="off"  />
                        </div>`);
                    } else {
                        $(td).html(rowData[26]);
                    }

                }
            },
            {
                targets: [25], // profit
                render: function (data, type, row) {
                    return row[27];
                },
            },
            {
                targets: [26], // UCI
                render: function (data, type, row) {
                    return row[28];
                },
            },
            {
                targets: [27], // purchase from
                render: function (data, type, row) {
                    return row[29];
                },
            },
            {
                targets: [28], // sold date
                render: function (data, type, row) {
                    return row[22];
                },
            },
            {
                targets: [29], // action
                render: function (data, type, row) {
                    return row[31];
                },
            },
            {
                targets: [30], // Uci Ro
                render: function (data, type, row) {
                    return row[33];
                },
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [21, 26, 27, 12, 28]
            },
        ],

        language: {
            "infoFiltered": "",
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

        rowGroup: {
            dataSrc: rowGroupSrc,
            enable: false,
            startRender: function (rows, group) {
                var collapsed = !!collapsedGroups[group];

                // -------------  For Display All Number of Filtered Rows -----------------
                // retails status
                if (rowGroupSrc == 23) {

                    rows.nodes().each(function (r) {
                        r.style.display = collapsed ? 'none' : '';
                    });

                    var filteredData = $('#datatable-1').DataTable()
                        .rows({ search: 'applied' })
                        .data()
                        .filter(function (data, index) {
                            return data[rowGroupSrc] == group ? true : false;
                        });
                    if (group == null || group == "") {
                        group = "Blank";
                    } else {
                        group = group.toUpperCase();
                    }
                    return $('<tr/>')
                        .append('<td colspan="17">' + group + ' (' + filteredData.length + ')</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
                }
                else if (rowGroupSrc == 22) {
                    
                    rows.nodes().each(function (r) {
                        r.style.display = 'none';
                        if (collapsed) {
                            r.style.display = '';
                        }
                    });

                    var totalProfit = 0;
                    var filteredData = $('#datatable-1').DataTable()
                        .rows({ search: 'applied' })
                        .data()
                        .filter(function (data, index) {
                            if (data[rowGroupSrc] == group) {
                                var profit = parseFloat((data[27]).replace(/\$|,/g, ''))
                                totalProfit += profit;
                                return true;
                            } else {
                                return false;
                            }
                        });
                    // console.log(filteredData);
                    return $('<tr/>')
                        .append('<td colspan="17">' + group + ' (' + formatToCurrency(totalProfit) + ')</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
                }


            }
        },

        initComplete: function () {
            // Start with closed groups
            $('#datatable-1 tbody tr.dtrg-start').each(function () {
                var name = $(this).data('name');
                collapsedGroups[name] = !collapsedGroups[name];
            });
            manageInvTable.draw(false);
        },

        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {
                var obj = json.totalNumber;
                for (const [key, value] of Object.entries(obj)) {
                    $(`input[name='mod'][value='${key}']`).next().next().html(value)
                    switch (key) {
                        case 'addToSheet':
                        case 'missingDate':
                        case 'titleIssue':
                            if (value == '0') {
                                $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-success')
                                $(`input[name='mod'][value='${key}']`).parent().removeClass('btn-outline-danger')
                            } else {
                                $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-danger')
                                $(`input[name='mod'][value='${key}']`).parent().removeClass('btn-outline-success')
                            }
                            break;
                        case 'retail':
                        case 'sold':
                            $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-success')
                            break;
                        case 'fixAge':
                            $(`input[name='mod'][value='fixAge`).parent().addClass('btn-outline-info')
                            break;
                        default:
                            $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-primary')
                            break;
                    }
                }
                // $(`input[name='mod'][value='fixAge`).parent().addClass('btn-outline-info');

                var activebtnvalue = $("#mods .btn.active input[name='mod']").val();
                if (activebtnvalue == 'missingDate' || activebtnvalue == 'keysPulled' || activebtnvalue == 'atAuction') {
                    setfun();
                    setInputChange();
                } else if (activebtnvalue == 'soldAtAuction') {
                    setInputChange();
                }
                // a.clear();
                // a.local = searhStatusArray;
                // a.initialize(true);
                // setSearchTypehead(searhStatusArray);
                searhStatusArray = [];
            }
        },

        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                var activebtnvalue = $("#mods .btn.active input[name='mod']").val();
                if (activebtnvalue == 'missingDate') {
                    $(row).children().not(':nth-child(2)').not(':nth-child(9)').not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'titleIssue') {

                    $(row).children().not(':nth-child(9)').not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'readyToShip') {

                    $(row).children().not(':nth-child(3)').not(':nth-child(10)').not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'keysPulled') {

                    $(row).children().not(':nth-child(3)').not(':nth-child(13)').not(':nth-child(10)').not(':nth-child(14)').not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'atAuction') {

                    $(row).children().not(':nth-child(9)').not(':nth-child(10)').not(':nth-child(14)').not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'soldAtAuction') {

                    $(row).children().not(':nth-child(3)').not(':nth-child(10)').not(':nth-child(12)').not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'sold') {
                    $(row).children().not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'retail') {
                    $(row).children().not(':nth-child(9)').not(':last-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else {
                    $(row).children().attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                }
            }
        },
        "order": [[1, "desc"]],
    });

    // Collapse Groups
    $('#datatable-1 tbody').on('click', 'tr.dtrg-start', function () {
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        manageInvTable.draw(false);
    });


    // --------------------- checkboxes query --------------------------------------

    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageInvTable.table().node();


            if (rowData[2] != 'undefined' && rowData[2] && rowData[2] != 'undefined') {
                searhStatusArray.push({
                    stockDetails: rowData[2],
                    stockAvailibility: rowData[30],
                })
            }


            var activebtnvalue = $("#mods .btn.active input[name='mod']").val();

            var balance = rowData[16];
            var wholesale = rowData[20];
            var date_in = rowData[24];
            // var date_in = String(rowData[24]);
            var title = rowData[11];
            var titlePriority = rowData[12];
            var key = rowData[4];
            var date_sent = rowData[21];
            var date_sold = rowData[22];
            var retail_status = rowData[23];
            var carshopId = rowData[32];

            var invStatus = rowData[34];

            if (activebtnvalue == 'addToSheet' && invStatus == 1) {
                if (date_in != '' && date_in === null) {
                    return true;
                }
            }
            if (activebtnvalue == 'missingDate') {
                if ((EmptyField(date_in) == false && date_in !== null) && invStatus == 1) {
                    return true;
                }
            }
            if (activebtnvalue == 'titleIssue') {
                var filter = $('#statusPriority').val();
                // if ((title == 'false' || title == null) && (date_in !== '' && date_in !== null)) {
                //     if (filter != '' && filter == titlePriority) {
                //         return true;
                //     } else if (filter == '') {
                //         return true;
                //     }
                // }
                if ((title == 'false' || title == null) && (date_in !== null) && invStatus == 1) {
                    if (filter != '' && filter == titlePriority) {
                        return true;
                    } else if (filter == '') {
                        return true;
                    }
                }
            }
            if (activebtnvalue == 'readyToShip') {
                if (title == 'true' && retail_status == 'wholesale' && key == 'false' && invStatus == 1) {
                    return true;
                }
            }
            if (activebtnvalue == 'keysPulled') {
                if (title == 'true' && retail_status == 'wholesale' && key == 'true' && !date_sent && !date_sold && invStatus == 1) {
                    return true;
                }
            }
            if (activebtnvalue == 'atAuction') {

                if (title == 'true' && retail_status == 'wholesale' && key == 'true' && date_sent && !date_sold && invStatus == 1) {
                    return true;
                }
            }
            if (activebtnvalue == 'soldAtAuction') {
                if (title == 'true' && retail_status == 'wholesale' && key == 'true' && date_sent && date_sold) {
                    return true;
                }

            }
            if (activebtnvalue == 'retail') {
                // if (wholesale == 'No' && balance !== '' && date_in !== null) {
                //     return true;
                // }
                if (retail_status != 'wholesale' && balance !== '' && (carshopId !== '' && carshopId !== null) && invStatus == 1) {
                    return true;
                }
            }
            if (activebtnvalue == 'sold') {
                if ((balance === "" || balance === null) && date_in !== null) {
                    return true;
                }
            }

            if (settings.nTable !== tableNode) {
                return true;
            }

            return false;
        }
    );

    setSearchTypehead(searhStatusArray);

    $('#clear-selection').click(function () {
        $('#purchaseFrom :radio').prop('checked', false);
        $('#purchaseFrom .active').removeClass('active');
    })

    $('.clear-selection').on('click', function () {
        var id = $(this).data('id');
        $('#' + id).val('');
        $('#' + id).selectpicker('refresh');
    });

    $('#purchaseFrom label').on('click', function () {
        let prev = $('#purchaseFrom .active :radio:checked').val();
        let current = $(this).children(':radio[name=purchaseFrom]').val();
        if (prev == current) {
            setTimeout(() => {
                $('#purchaseFrom :radio').prop('checked', false);
                $('#purchaseFrom .active').removeClass('active');
            }, 100);
        }
    })

    $('#retailStatus input:radio').on('change', function () {
        if ($(this).val() != 'wholesale') {
            $('#dateSent').attr('disabled', true)
        } else {
            $('#dateSent').attr('disabled', false)
        }
    });


    $('#mods input:radio').on('change', function () {
        $('#inspectionTable').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            // timeout: 1e3
        });
        // searhStatusArray = [];
        var currentElement = $(this).val();
        if (currentElement != 'fixAge') {
            $('.inspectionTable').removeClass('d-none');
            $('.FixSDKtable').addClass('d-none');
            switch (currentElement) {
                case 'missingDate':
                    setColumVisibility([0, 1, 4, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28, 30]);
                    manageInvTable.rowGroup().disable().draw();
                    break;
                case 'titleIssue':
                    setColumVisibility([0, 3, 4, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 30]);
                    manageInvTable.rowGroup().disable().draw();
                    break;
                case 'readyToShip':
                    setColumVisibility([0, 3, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28, 30]);
                    rowGroupSrc = 23;
                    manageInvTable.rowGroup().enable().draw();
                    manageInvTable.dataSrc(rowGroupSrc);
                    break;
                case 'keysPulled':
                    setColumVisibility([0, 3, 12, 13, 14, 15, 17, 18, 19, 21, 24, 25, 26, 27, 28, 30]);
                    rowGroupSrc = 23;
                    manageInvTable.rowGroup().enable().draw();
                    manageInvTable.dataSrc(rowGroupSrc);
                    break;
                case 'atAuction':
                    setColumVisibility([0, 3 , 4, 10 , 11, 12, 13, 14, 15, 17, 18, 19 , 20, 21, 24, 25, 26, 27, 28, 30]);
                    rowGroupSrc = 23;
                    manageInvTable.rowGroup().enable().draw();
                    manageInvTable.dataSrc(rowGroupSrc);
                    break;
                case 'soldAtAuction':
                    setColumVisibility([0, 3, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 26, 27, 28, 30]);
                    rowGroupSrc = 22; // sold date 
                    manageInvTable.rowGroup().enable().draw();
                    manageInvTable.dataSrc(rowGroupSrc);
                    break;
                case 'addToSheet':
                    setColumVisibility([0, 3, 4, 11, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30]);
                    manageInvTable.rowGroup().disable().draw();
                    break;
                case 'retail':
                    setColumVisibility([0, 3, 4, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28, 30]);
                    manageInvTable.rowGroup().disable().draw();
                    break;
                default:
                    setColumVisibility([0, 3, 4, 11, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28, 30]);
                    manageInvTable.rowGroup().disable().draw();
                    break;
            }
            if (currentElement == 'addToSheet') {
                $('#statusFilterDiv').removeClass('d-none');
            } else {
                $('#statusFilterDiv').addClass('d-none');
            }

            setTimeout(() => {
                $("#datatable-1").dataTable().fnFilter("");
                manageInvTable.draw();
                manageInvTable.searchPanes.rebuildPane();
                manageInvTable.ajax.reload(null, false);
                if (currentElement == 'soldAtAuction') {
                    manageInvTable.order([22, 'desc'], [1, 'desc']).draw();
                }
                $('#inspectionTable').unblock();
                setPlaceholder();
            }, 500);
        } else if (currentElement == 'fixAge') {
            fetchFixCDKAge();
            $('.inspectionTable').addClass('d-none');
            $('.FixSDKtable').removeClass('d-none');
        }

    });



    $("#updateUsedCarsForm").validate({
        // ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        rules: {
            "retailStatus": {
                required: !0,
            },
            "soldPrice": {
                number: !0,
                required: function (params) {
                    if ($('#dateSold').val() != '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            "uciApproved": {
                number: !0,
            },
            "uciClosed": {
                number: !0,
            },
        },


        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var form = $('#updateUsedCarsForm');
            var fd = new FormData(document.getElementById("updateUsedCarsForm"));
            fd.append("CustomField", "This is some extra data");
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: fd,
                contentType: false,
                processData: false,
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
                        manageInvTable.ajax.reload(null, false);
                        // form[0].reset();
                        $('#modal8').modal('hide');

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


    loadSaleConsultant();
    writeStatusHTML();

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const filter = urlParams.get('filter');
    const allowedForOffice = $('#allowedForOffice').val();

    if (filter == 'titleIssue' || allowedForOffice == 'false') {
        $('#searchTitleIssue').click();
    }
    else {
        $('#searchAddToSheet').click();
    }

});

function setSearchTypehead(searhStatusArray) {
    $('#searchcars').typeahead('destroy');

    function substringMatcher(strs) {
        return function findMatches(q, cb) {
            var matches = [];
            var substrRegex = new RegExp(q, 'i');
            $.each(strs,
                function (i, str) {
                    if (substrRegex.test(str.stockDetails)) {
                        matches.push(str);
                    }
                });
            cb(matches);
        };
    };
    $('#searchcars').typeahead({
        hint: true,
        highlight: true,
        minLength: 0,
        autoselect: false
    },
        {
            displayKey: "stockDetails",
            name: "searhStatusArray",
            source: substringMatcher(searhStatusArray),
            templates: {
                suggestion: function (data) {
                    var stockAvailibilityArray = data.stockAvailibility;
                    const template = `<div><p><strong>${data.stockDetails}</strong></p><div class="row pl-2 pr-2">${stockAvailibilityArray.map(e => (e) ? `<div class="col-sm-4 p-1"> <button class="badge badge-label-primary cursor-pointer searchStockBtn" onclick="searchStockBtn(this)"  data-head="${e}" data-search="${data.stockDetails}" > ${e} </button> </div>` : ``).join('')}</div></div>`;
                    return template;
                }
            },
        })
        .on('typeahead:cursorchanged', function ($e, datum) {
            $("#searchcars").typeahead('val', datum.stockDetails)
        })
        .on('typeahead:selected', function ($e, datum) {
            $("#searchcars").typeahead('val', datum.stockDetails)
        })

    $('.form-control.tt-input').next().addClass('w-inherit');

}

function searchStockBtn(params) {
    let head = $(params).data('head');
    let search = $(params).data('search');
    switch (head) {
        case 'Add To Sheet':
            head = 'addToSheet';
            break;
        case 'Missing Date':
            head = 'missingDate';
            break;
        case 'Title Issue':
            head = 'titleIssue';
            break;
        case 'Ready To Ship':
            head = 'readyToShip';
            break;
        case 'Keys Pulled':
            head = 'keysPulled';
            break;
        case 'At Auction':
            head = 'atAuction';
            break;
        case 'Sold At Auction':
            head = 'soldAtAuction';
            break;
        case 'Retail':
            head = 'retail';
            break;
        case 'Sold':
            head = 'sold';
            break;
        default:
            head = '';
            break;
    }
    let tab = $('#mods :radio[name=mod][value=' + head + ']').parent().button('toggle');
    if (tab) {
        setTimeout(() => {
            $("#datatable-1").dataTable().fnFilter(search);
            manageInvTable.order([1, 'desc']).draw();
            manageInvTable.ajax.reload(null, false);
        }, 1000);
    }



}


function setPlaceholder() {
    var element = $('.dtsp-searchPane')[3];
    var input = $(element).find('.dtsp-paneInputButton.form-control');
    $(input).attr("placeholder", "Title Priority");
}

function EmptyField(field) {
    // field = String(field);
    if (field != '' && field != 'undefined') {
        return true;
    } else {
        return false;
    }
}

function setInputChange() {
    var inputs = document.querySelectorAll("input[name=input_field]");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keyup", function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                let id = $(this).data('id');
                let attribute = $(this).data('attribute');
                let value = $(this).val();
                updateFieldsUsedCars({ id, attribute, value });
            }
        });
    }
}
function setfun() {
    $('input[name="date_in_table"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: !0,
        autoUpdateInput: false,
        cleanable: true,
        "opens": "left",
        "showDropdowns": true,
        locale: {
            format: 'MM-DD-YYYY',
            applyLabel: 'Submit',
            cancelLabel: 'Reset',
        },
    });
    $('input[name="date_in_table"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment());
        $(this).data('daterangepicker').setEndDate(moment());
        let id = $(this).data('id');
        let attribute = $(this).data('attribute');
        let value = "";
        updateFieldsUsedCars({ id, attribute, value });
    });
    $('input[name="date_in_table"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY'));
        let id = $(this).data('id');
        let attribute = $(this).data('attribute');
        let value = picker.startDate.format('MM-DD-YYYY');
        updateFieldsUsedCars({ id, attribute, value });
    });
}
function handletitleCheckbox(e) {
    let value = "false";
    let id = $(e).data('id');
    let attribute = $(e).data('attribute');
    if ($(e).is(':checked')) {
        value = "true";
    }
    updateFieldsUsedCars({ id, attribute, value }, false);
}
function handleFixedStatusCheckbox(e) {
    let value = "false";
    let id = $(e).data('id');
    let attribute = $(e).data('attribute');
    if ($(e).is(':checked')) {
        value = "true";
    }
    console.log({ id, attribute, value });
    updateFieldsUsedCars({ id, attribute, value });
    manageCDKAgeTable.ajax.reload(null, false);
}


// setTimeout(() => {
//     $('#mods input:radio[value=titleIssue]').click();
// }, 1000);


function fetchFixCDKAge() {
    if ($.fn.dataTable.isDataTable('#datatable-2')) {
    }
    else {
        manageCDKAgeTable = $("#datatable-2").DataTable({
            responsive: !0,
            'ajax': '../php_action/fetchFixCDKAge.php',
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

            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [1, 2, 5]
            },
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: 'Fix CDK Age',
                    exportOptions: {
                        columns: [':visible:not(:nth-child(4))']
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Fix CDK Age',
                    exportOptions: {
                        columns: [':visible:not(:nth-child(4))']
                    }
                },
                {
                    extend: 'print',
                    title: 'Fix CDK Age',
                    exportOptions: {
                        columns: [':visible:not(:nth-child(4))']
                    }
                },
            ],
            columnDefs: [
                {
                    targets: [0],
                    visible: false,
                },
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [1, 2, 5]
                },
                {
                    targets: [4],
                    createdCell: function (td, cellData, rowData, row, col) {
                        if ($('#isEditAllowed').val() == 'true') {
                            $(td).html(`<div class="custom-control custom-control-lg custom-checkbox">
                                    <input type="checkbox" name="fixed_statusCheckbox" data-attribute="fixed_status" onchange="handleFixedStatusCheckbox(this)" data-id="${rowData[0]}" class="custom-control-input fixed_statusCheckbox" id="${rowData[0]}fixed_status" ${((rowData[4] == 'false') ? '' : 'checked="checked"')} >
                                    <label class="custom-control-label" for="${rowData[0]}fixed_status"></label> 
                                </div>`);
                        } else {
                            $(td).html(rowData[4]);
                        }
                    }
                },
                {
                    targets: [2, 3],
                    searchPanes: true,
                    searchable: true,
                    createdCell: function (td, cellData, rowData, row, col) {
                        var data = $(td).html();
                        if (data > 4) {
                            $(td).addClass('h5 font-weight-bolder text-danger');
                        } else {
                            $(td).addClass('font-weight-bold p');
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
                    var obj = json.totalNumber;
                    for (const [key, value] of Object.entries(obj)) {
                        $(`input[name='mod'][value='${key}']`).next().next().html(value)
                    }
                }
            },
            // createdRow: function (row, data, dataIndex) {
            //     if ($('#isEditAllowed').val() == "true") {
            //         $(row).children().not(':first-child').attr({
            //             "data-toggle": "modal",
            //             "data-target": "#modal8",
            //             "onclick": "editUsedCar(" + data[0] + ")"
            //         });
            //     }
            // },
        })
    }
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
            var selectBox = document.getElementById('salesConsultant');
            for (var i = 0; i < saleCnsltntArray.length; i++) {
                var item = saleCnsltntArray[i];
                selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}


function toggleFilterClass2() {
    $('.dtsp-panes').toggle();
}

function toggleInfo(id) {
    $('#' + id).toggleClass('d-none');
}

function editUsedCar(id) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedUsedCar.php',
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


                let age = response.age;
                // get day difference
                if (response.date_in != '' && response.date_in != null) {
                    var given = moment(response.date_in, "MM-DD-YYYY").subtract(1, 'days');
                    var current = moment().startOf('day');
                    age = moment.duration(current.diff(given)).asDays();
                }


                // stockno  stock and vin
                // selectedDetails   stock details
                $('#vehicleId').val(id);
                $('#stockno').val(response.stockno + " || " + response.vin);
                $('#submittedBy').val(response.submitted_by);
                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Mileage: ${response.mileage} \n Age: ${age} \n Lot: ${response.lot} \n Balance: ${response.balance}`;
                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');


                $("#invDate").datepicker("setDate", response.date_in ? response.date_in : "");


                $('#retailStatus :radio[name="retailStatus"]').prop('checked', false);
                $('#retailStatus .active').removeClass('active');
                (response.retail_status) ? $('#' + response.retail_status).prop('checked', true).click() : null;


                $('#purchaseFrom :radio[name="purchaseFrom"]').prop('checked', false);
                $('#purchaseFrom .active').removeClass('active');
                (response.purchase_from) ? $('#' + response.purchase_from).prop('checked', true).click() : null;


                $('#certified').prop('checked', response.certified == "true" ? true : false);
                $('#title').prop('checked', response.title == "true" ? true : false);


                $('#titlePriority').val(response.title_priority ? response.title_priority : "");
                $('#salesConsultant').val(response.sales_consultant ? response.sales_consultant : "");
                $('#customerName').val(response.customer ? response.customer : "");


                $("#dateSent").datepicker("setDate", response.date_sent ? response.date_sent : "");
                $("#dateSold").datepicker("setDate", response.date_sold ? response.date_sold : "");
                $('#soldPrice').val(response.sold_price ? response.sold_price : "");
                // keys
                $('#keys').prop('checked', response.key == "true" ? true : false);


                $('#titleNotes').val(response.title_notes ? response.title_notes : "");
                $('#wholesaleNotes').val(response.wholesale_notes ? response.wholesale_notes : "");
                $('#onlineDescription').val(response.online_description ? response.online_description : "");
                $('#roNotes').val(response.ro_online_notes ? response.ro_online_notes : "");

                // uci
                $('#uci').val(response.uci ? response.uci : "");
                $('#uciRo').val(response.uci_ro ? response.uci_ro : "");
                $('#uciApproved').val(response.uci_approved ? response.uci_approved : "");
                $('#uciClosed').val(response.uci_close ? response.uci_close : "");
                $('#uciClosed').val(response.uci_close ? response.uci_close : "");

                $('#oci').prop('checked', response.oci_ok == "true" ? true : false);

                setTimeout(() => {
                    autosize.update($(".autosize"));
                }, 500);
                $('.selectpicker').selectpicker('refresh');


            }, // /success
            error: function (err) {
                console.log(err);
            }

        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }
}

function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary p-2"  id="statusFilterBtns" onclick="addALL()">
                <i class="fa fa-plus ml-1 mr-2"></i> Add All
            </button>
        </div>
    </div>`;
    }
}
function addALL() {
    var arrayObj = [];
    $('#datatable-1').DataTable()
        .rows({ search: 'applied' })
        .data().each((e) => {
            // console.log(e);
            var obj = {};

            var id = e[0];
            var wholesale = e[20];
            var retailStatus, uci, titlePriority = "";

            if (wholesale == 'No') {
                retailStatus = 'retail';
                titlePriority = "New";
                uci = "need";
            } else if (wholesale == 'Yes') {
                retailStatus = 'wholesale';
                titlePriority = "New";
                uci = "0";
            }
            obj = { id, retailStatus, titlePriority, uci };
            arrayObj.push(obj)


        })

    if (arrayObj.length) {
        // e1.fire({
        //     title: "Are you sure?",
        //     text: "You won't be able to revert this!",
        //     icon: "warning",
        //     showCancelButton: !0,
        //     confirmButtonColor: "#3085d6",
        //     cancelButtonColor: "#d33",
        //     confirmButtonText: "Yes, Add All!"
        // }).then(function (t) {
        //     if (t.isConfirmed == true) {
        $.ajax({
            url: '../php_action/allAddUsedCars.php',
            type: 'post',
            data: { arrayObj: arrayObj },
            dataType: 'json',

            success: function (response) {
                if (response.success == true) {
                    Swal.fire("Added!", "Your file has been Added.", "success");
                    setTimeout(() => {
                        Swal.close()
                    }, 900);
                    manageInvTable.ajax.reload(null, false);
                } // /response messages
            }

        }); // /ajax function to remove the brand
        //     }
        // });
    }




}
function setColumVisibility(columnArray) {
    var allC = manageInvTable.columns()[0];
    allC.forEach(column => {
        var col = manageInvTable.column(column);
        if (columnArray.indexOf(column) != -1) {
            col.visible(false);
        } else {
            col.visible(true);
        }
    });
    manageInvTable.columns.adjust().draw();
}

function updateFieldsUsedCars(obj, notify = true) {
    console.log(obj);
    if (obj) {
        // e1.fire({
        //     title: "Are you sure?",
        //     icon: "warning",
        //     showCancelButton: !0,
        //     confirmButtonColor: "#3085d6",
        //     cancelButtonColor: "#d33",
        //     confirmButtonText: "Yes, Change this!"
        // }).then(function (t) {
        //     if (t.isConfirmed == true) {
        $.ajax({
            url: '../php_action/updateFieldsUsedCars.php',
            type: 'post',
            data: obj,
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    manageInvTable.ajax.reload(null, false);
                    if (notify == true) {
                        Swal.fire("Added!", "Successfully Changed", "success");
                        setTimeout(() => {
                            Swal.close()
                        }, 900);
                    }
                } // /response messages
            }

        }); // /ajax function to remove the brand
        //     } else {
        //         manageInvTable.ajax.reload(null, false);
        //     }
        // });
    }
}

function filterDatatable() {
    $('#datatable-1').block({
        message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
        timeout: 1e3
    });
    manageInvTable.draw();
    manageInvTable.searchPanes.rebuildPane();
}

const formatToCurrency = amount => {
    if (amount > 0) {
        return "$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
    } else {
        amount = Math.abs(amount);
        return "-$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
    }
};

function removeUsedCar(id = null) {
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
            if (t.isConfirmed == true) {
                $.ajax({
                    url: '../php_action/removeUsedCar.php',
                    type: 'post',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success");
                            setTimeout(() => {
                                Swal.close()
                            }, 900);
                            manageInvTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
    }
}