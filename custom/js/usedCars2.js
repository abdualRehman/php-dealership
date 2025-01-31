
"use strict";
var manageInvTable, TableData, maxFileLimit = 10, rowGroupSrc = 23;
var manageCarDealersTable;
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
            <'col-sm-4 text-left text-sm-left pl-3'B>
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
            // {
            //     extend: 'button',
            //     title: 'Add All',
            //     class:"add-all",
            //     action:function () {
            //         console.log("button is called");
            //     }
            // }
        ],

        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [21, 26, 27, 12, 28]
        },

        columnDefs: [
            {
                targets: [0, 3, 4, 11, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28],
                visible: false,
            },
            { width: 400, targets: [15] },
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
                    if ($('#isRoleAllowed').val() == 'true') {
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
                searchPanes: {
                    show: true
                },
                targets: [21, 26, 27, 12, 28]
            },
        ],

        language: {
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

                    var totalProfit = 0;
                    var filteredData = $('#datatable-1').DataTable()
                        .rows({ search: 'applied' })
                        .data()
                        .filter(function (data, index) {
                            if (data[rowGroupSrc] == group) {
                                totalProfit += data[27];
                                return true;
                            } else {
                                return false;
                            }
                        });
                    // console.log(filteredData);
                    return $('<tr/>')
                        .append('<td colspan="17">' + group + ' (' + totalProfit + ')</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
                }


            }
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
                        default:
                            $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-primary')
                            break;
                    }
                }
                var activebtnvalue = $("#mods .btn.active input[name='mod']").val();
                if (activebtnvalue == 'missingDate' || activebtnvalue == 'keysPulled' || activebtnvalue == 'atAuction') {
                    setfun();
                    setInputChange();
                } else if (activebtnvalue == 'soldAtAuction') {
                    setInputChange();
                }
            }
        },

        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                var activebtnvalue = $("#mods .btn.active input[name='mod']").val();
                if (activebtnvalue == 'missingDate') {
                    // $(row).children().not(':nth-child(2)').attr({
                    //     "data-toggle": "modal",
                    //     "data-target": "#modal8",
                    //     "onclick": "editUsedCar(" + data[0] + ")"
                    // });
                    $(row).children().not(':nth-child(2)').not(':nth-child(9)').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'titleIssue') {

                    $(row).children().not(':nth-child(9)').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'readyToShip') {

                    $(row).children().not(':nth-child(3)').not(':nth-child(10)').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'keysPulled' || activebtnvalue == 'atAuction') {

                    $(row).children().not(':nth-child(3)').not(':nth-child(13)').not(':nth-child(10)').not(':nth-child(14)').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                } else if (activebtnvalue == 'soldAtAuction') {

                    $(row).children().not(':nth-child(3)').not(':nth-child(10)').not(':nth-child(12)').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal8",
                        "onclick": "editUsedCar(" + data[0] + ")"
                    });
                }
                else {
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



    // --------------------- checkboxes query --------------------------------------

    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageInvTable.table().node();
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


            if (activebtnvalue == 'addToSheet') {
                if ((date_in != '' && date_in != null) && retail_status != 'wholesale') {
                    return true;
                }
            }
            if (activebtnvalue == 'missingDate') {
                if (EmptyField(date_in) == false && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'titleIssue') {
                // if (title == 'false' && balance) {
                //     return true;
                // }
                var filter = $('#statusPriority').val();

                if ((title == 'false' || title == null) && (date_in != '' && date_in != null)) {
                    if (filter != '' && filter == titlePriority) {
                        return true;
                    } else if (filter == '') {
                        return true;
                    }
                }
            }
            if (activebtnvalue == 'readyToShip') {
                // if (title == 'true' && retail_status == 'wholesale' && key == 'false') {
                //     return true;
                // }
                if (title == 'true' && retail_status == 'wholesale' && key == 'false' && (date_in != '' && date_in != null)) {
                    return true;
                }
            }
            if (activebtnvalue == 'keysPulled') {
                if (title == 'true' && retail_status == 'wholesale' && key == 'true' && (date_in != '' && date_in != null) && !date_sent && !date_sold) {
                    return true;
                }
            }
            if (activebtnvalue == 'atAuction') {
                // if (date_sent && !date_sold) {
                //     return true;
                // }
                if (title == 'true' && retail_status == 'wholesale' && key == 'true' && (date_in != '' && date_in != null) && date_sent && !date_sold) {
                    return true;
                }
            }
            if (activebtnvalue == 'soldAtAuction') {

                // if (date_sold != "" && date_sold != null) {
                //     return true;
                // }
                if (title == 'true' && retail_status == 'wholesale' && key == 'true' && (date_in != '' && date_in != null) && date_sent && date_sold) {
                    return true;
                }

            }
            if (activebtnvalue == 'retail') {
                if (wholesale == 'No' && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'sold') {
                if (balance == "" || balance == null) {
                    return true;
                }
            }

            if (settings.nTable !== tableNode) {
                return true;
            }

            return false;
        }
    );



    $('.clear-selection').on('click', function () {
        var id = $(this).data('id');
        $('#' + id).val('');
        $('#' + id).selectpicker('refresh');
    })


    $('#retailStatus input:radio').on('change', function () {
        if ($(this).val() != 'wholesale') {
            $('#dateSent').attr('disabled', true)
        } else {
            $('#dateSent').attr('disabled', false)
        }
    });


    $('#mods input:radio').on('change', function () {
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        var currentElement = $(this).val();
        switch (currentElement) {
            case 'missingDate':
                setColumVisibility([0, 1, 4, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28]);
                manageInvTable.rowGroup().disable().draw();
                break;
            case 'titleIssue':
                setColumVisibility([0, 3, 4, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28]);
                manageInvTable.rowGroup().disable().draw();
                break;
            case 'readyToShip':
                setColumVisibility([0, 3, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28]);
                rowGroupSrc = 23;
                manageInvTable.rowGroup().enable().draw();
                manageInvTable.dataSrc(rowGroupSrc);
                break;
            case 'keysPulled':
            case 'atAuction':
                setColumVisibility([0, 3, 12, 13, 14, 15, 17, 18, 19, 21, 24, 25, 26, 27, 28]);
                rowGroupSrc = 23;
                manageInvTable.rowGroup().enable().draw();
                manageInvTable.dataSrc(rowGroupSrc);
                break;
            case 'soldAtAuction':
                setColumVisibility([0, 3, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 26, 27, 28]);
                rowGroupSrc = 22; // sold date 
                manageInvTable.rowGroup().enable().draw();
                manageInvTable.dataSrc(rowGroupSrc);
                break;
            default:
                setColumVisibility([0, 3, 4, 11, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28]);
                manageInvTable.rowGroup().disable().draw();
                break;
        }
        if (currentElement == 'addToSheet') {
            $('#statusFilterDiv').removeClass('d-none');
        } else {
            $('#statusFilterDiv').addClass('d-none');
        }

        setTimeout(() => {
            manageInvTable.draw();
            manageInvTable.searchPanes.rebuildPane();
            manageInvTable.ajax.reload(null, false);
            setPlaceholder();
        }, 500);

    });



    $("#updateUsedCarsForm").validate({
        // ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        rules: {
            // "bodyshop": {
            //     required: !0,
            // },

            "soldPrice": {
                number: !0,
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

    if (filter == 'titleIssue') {
        $('#searchTitleIssue').click();
    }
    else {
        $('#searchAddToSheet').click();
    }

});

function setPlaceholder() {
    var element = $('.dtsp-searchPane')[3];
    var input = $(element).find('.dtsp-paneInputButton.form-control');
    $(input).attr("placeholder", "Title Priority");
}

function EmptyField(field) {
    // field = String(field);
    console.log(field, typeof (field));
    if (field != '' && field != "null" && field != null && field != 'undefined') {
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
                updateFieldsUsedCars({ id:[id], attribute:[attribute], value:[value] });
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
        updateFieldsUsedCars({ id:[id], attribute:[attribute], value:[value] });
    });
    $('input[name="date_in_table"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY'));
        let id = $(this).data('id');
        let attribute = $(this).data('attribute');
        let value = picker.startDate.format('MM-DD-YYYY');
        updateFieldsUsedCars({ id:[id], attribute:[attribute], value:[value] });
    });
}
function handletitleCheckbox(e) {
    let value = "false";
    let id = $(e).data('id');
    let attribute = $(e).data('attribute');
    if ($(e).is(':checked')) {
        value = "true";
    }
    updateFieldsUsedCars({ id:[id], attribute:[attribute], value:[value] });
}


// setTimeout(() => {
//     $('#mods input:radio[value=titleIssue]').click();
// }, 1000);

function loadSaleConsultant() {
    var sales_consultant_id = Number(localStorage.getItem('salesConsultantID'));;
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
                    var given = moment(response.date_in, "MM-DD-YYYY");
                    var current = moment().startOf('day');
                    age = moment.duration(current.diff(given)).asDays();
                    age = Math.round(age);
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
                    Swal.fire("Added!", "Your file has been Added.", "success")
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

function updateFieldsUsedCars(obj) {
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
                    Swal.fire("Added!", "Successfully Changed", "success")
                    manageInvTable.ajax.reload(null, false);
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