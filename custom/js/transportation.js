"use strict";
var manageDataTable, stockArray, defaultOptionsData;
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

    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        ajax: {
            url: '../php_action/fetchTransportations.php',
            type: "POST",
            data: function (data) {
                var statusPriority = $('#statusPriority').val();
                statusPriority = statusPriority ? statusPriority : "";
                data.statusPriority = statusPriority;
            }
        },
        dom: `\n     
            <'row'<'col-12'P>>\n      
            <'row'<'col-sm-12 text-sm-left col-md-3 mb-2 '<'#statusFilterDiv'>> <'col-sm-12 col-md-6 text-center 'B> <'col-sm-12 col-md-3 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,
        "pageLength": 25,
        // 'rowsGroup': [0],
        'rowsGroup': [0, 1, 2, 3],
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [1, 2, 3, 4],
        },
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Transportation Damager',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Transportation Damager',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'print',
                title: 'Transportation Damager',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5]
                }
            },
        ],
        columnDefs: [
            {
                targets: 0,
                visible: false,
            },
            {
                targets: [1, 2, 3],
                createdCell: function (td) {
                    $(td).addClass('align-content-center');
                }
            },
            {
                targets: [4],
                width: "60%",
            },
            {
                targets: [5],
                render: function (data) {
                    return formatCamelCase(data)
                },
                createdCell: function (td, cellData) {
                    if (cellData == 'pendingInspection') {
                        $(td).html('<h3 data-order="1" class="badge badge-lg badge-warning badge-pill">' + formatCamelCase(cellData) + '</h3>');
                    } else if (['partsNeeded', 'partsRequested', 'partsArrivedPendingService', 'bodyshopNeeded'].includes(cellData)) {
                        $(td).html('<h3 data-order="2" class="badge badge-lg badge-danger badge-pill">' + formatCamelCase(cellData) + '</h3>');
                    } else if (['atBodyshop', 'bodyshopCompleted', 'completedAwaitingPayment'].includes(cellData)) {
                        $(td).html('<h3 data-order="3" class="badge badge-lg badge-primary badge-pill">' + formatCamelCase(cellData) + '</h3>');
                    } else if (['repairNotRequired', 'done'].includes(cellData)) {
                        $(td).html('<h3 data-order="4" class="badge badge-lg badge-success badge-pill">' + formatCamelCase(cellData) + '</h3>');
                    }
                }
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [1, 2, 3, 4],
            },
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isAllowed').val() == 'true') {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editFun(" + data[0] + ")"
                });
            }

        },
        // "order": [[0, "asc"]]
        "order": []
    })


    writeStatusHTML();
    $('#pending').click();


    loadStock();
    loadDefaultOptionsFromAPI();


    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index) {
            var tableNode = manageDataTable.table().node();

            var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                if (this.value !== "") {
                    return this.value;
                }
            }).get();

            if (searchStatus.length === 0) {
                return true;
            }

            if (settings.nTable !== tableNode) {
                return true;
            }

            if (searchStatus[0] === 'pending') {
                var status = searchData[5];
                if (!['Repair Not Required', 'Not Required', 'Done'].includes(status)) {
                    return true;
                }
            }
            if (searchStatus[0] === 'done') {
                var status = searchData[5];
                if (['Repair Not Required', 'Not Required', 'Done'].includes(status)) {
                    return true;
                }
            }

            return false;
        }
    );


    $('input:radio').on('change', function () {
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageDataTable.draw();  // working
        manageDataTable.searchPanes.rebuildPane();
    });


    $("#addNewFrom").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        submitHandler: function (form, e) {
            // return true;
            // e.preventDefault();
            var form = $('#addNewFrom');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {

                    if ((response.errorMessages) && response.errorMessages.length > 0) {
                        response.errorMessages.forEach(message => {
                            toastr.error(message, 'Error while Adding');
                        });
                    }
                    if (response.success == true) {
                        e1.fire({
                            position: "center",
                            icon: "success",
                            title: response.messages.length > 0 ? response.messages[0] : "Successfully Added",
                            showConfirmButton: !1,
                            timer: 1500
                        })
                        manageDataTable.ajax.reload(null, false);
                        form[0].reset();
                        $(".selectpicker").val('0').selectpicker("refresh");
                        $("#status").val('pending').selectpicker("refresh");
                    } else {
                        e1.fire({
                            // position: "center",
                            icon: "error",
                            title: response.messages.length > 0 ? response.messages[0] : "Error while Adding",
                            showConfirmButton: !1,
                            timer: 2500
                        })


                    }


                }
            });

            return false;

        }


    });

    $("#editForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is

        submitHandler: function (form, e) {
            // return true
            e.preventDefault();
            var form = $('#editForm');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {

                    if (response.success == true) {
                        e1.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 1500
                        })
                        // form[0].reset();
                        manageDataTable.ajax.reload(null, false);

                    } else {
                        e1.fire({
                            position: "top-end",
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

});

function filterDatatable() {
    $('#datatable-1').block({
        message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
        timeout: 1e3
    });
    manageDataTable.ajax.reload();
}
function formatCamelCase(input) {
    if (!input) return ""; // Handle empty input

    // Insert a space before each uppercase letter and capitalize the first letter
    const formatted = input
        .replace(/([A-Z])/g, " $1") // Add space before uppercase letters
        .replace(/^\w/, (c) => c.toUpperCase()); // Capitalize the first letter

    return formatted.trim(); // Trim any extra spaces
}



function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div id="sort">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="pending" value="pending"> Pending
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="done" value="done"> Done
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
}

async function loadStock() {
    await $.ajax({
        url: '../php_action/fetchInvForSearch.php',
        type: "POST",
        data: { type: 'NEW' },
        dataType: 'json',
        success: function (response) {
            stockArray = response.data;
            var selectBoxes = document.getElementsByClassName('stockId');
            selectBoxes.forEach(selectBox => {
                // selectBox.innerHTML = `<option value="0" selected disabled>Stock No:</option>`;
                for (var i = 0; i < stockArray.length; i++) {
                    // for (var i = 0; i < 3; i++) {
                    var item = stockArray[i];

                    selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]} - ${item[8]}">${item[1]} - ${item[8]} </option>`;
                }
            });
            // selectBox.removeAttribute("disabled");
            $('.selectpicker').selectpicker('refresh');
        }
    });
}
async function loadDefaultOptionsFromAPI(editModeValue = '') {
    await $.ajax({
        url: '../php_action/fetchDefaultOptions.php',
        type: "POST",
        dataType: 'json',
        success: function (response) {
            defaultOptionsData = response.data;
            loadDefaultOptionsData(1, editModeValue);
        }
    });
}
function loadDefaultOptionsData(rowCount, editModeValue = '') {

    if (defaultOptionsData) {
        var defaultOptions = document.getElementsByClassName(editModeValue + 'defaultOptions' + rowCount);

        defaultOptions.forEach(element => {
            if (element.textContent.trim() === '') {
                for (var i = 0; i < defaultOptionsData.length; i++) {

                    var item = defaultOptionsData[i];

                    if (item[1] != "") {
                        if ($(element).hasClass("damageType")) {
                            element.innerHTML += `<option>${item[1]} </option>`;
                        }
                    }

                    if (item[2] != "") {
                        if ($(element).hasClass("damageSeverity")) {
                            element.innerHTML += `<option>${item[2]} </option>`;
                        }
                    }
                    if (item[3] != "") {
                        if ($(element).hasClass("damageGrid")) {
                            element.innerHTML += `<option>${item[3]} </option>`;
                        }
                    }
                    if (item[0] != "") {
                        if ($(element).hasClass("locNum")) {
                            element.innerHTML += `<option>${item[0]} </option>`;
                        }
                    }
                }

            }

        });
        $('.selectpicker').selectpicker('refresh');
    }
}


function changeStockDetails(ele) {
    let obj = stockArray.find(data => data[0] === ele.value);
    $('#stockDetails').val(`${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]}`);
}


function echangeStockDetails(ele) {
    let obj = stockArray.find(data => data[0] === ele.value);
    $('#estockDetails').val(`${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]}`);
}


async function editFun(id = null) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.eshowResult').addClass('d-none');
        $('.modal-footer').addClass('d-none');

        $.ajax({
            url: '../php_action/fetchSelectedtransportation.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: async (response) => {

                console.log("response", response);


                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.eshowResult').removeClass('d-none');
                // // modal footer
                $('.modal-footer').removeClass('d-none');

                if (defaultOptionsData) {
                    loadDefaultOptionsData(1, 'e')
                } else {
                    await loadDefaultOptionsFromAPI('e')
                }

                var tableLength = $(`#eproductTable tbody tr`).length;
                if (tableLength > 1) {
                    $(`#eproductTable tbody tr:gt(0)`).remove();
                }

                if (response?.length > 0) {
                    const responseObj = response[0];

                    $('#editForm')[0].reset();
                    $('#transId').val(responseObj.tid);
                    $('#estockId').val(responseObj.stock_id);
                    $('#enotes').val(responseObj.notes);
                    echangeStockDetails({ value: responseObj.stock_id });
                    $('#estatus').val(responseObj.transport_status);

                    for (let i = 0; i < response.length; i++) {
                        const element = response[i];
                        $('#erowId' + (i + 1)).val(element.tdid);
                        $('#elocNum' + (i + 1)).val(element.loc_num);
                        $('#edamageType' + (i + 1)).val(element.damage_type);
                        $('#edamageSeverity' + (i + 1)).val(element.damage_severity);
                        $('#edamageGrid' + (i + 1)).val(element.damage_grid);

                        var tableLength = $(`#eproductTable tbody tr`).length;

                        if (response.length > (i + 1) && tableLength < response.length) {
                            addRow('e', response[i + 1]?.tdid)
                        }
                    }
                }

                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeDetails(tdid = null, id = null) {
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
                    url: '../php_action/removeTransportation.php',
                    type: 'post',
                    data: { id: id, tdid: tdid },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageDataTable.ajax.reload(null, false);
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


function addRow(editModeValue = '', editRowId = null) {

    $(`#${editModeValue}addRowBtn`).button("loading");

    var tableLength = $(`#${editModeValue}productTable tbody tr`).length;

    var tableRow;
    var arrayNumber;
    var count;

    if (tableLength > 0) {
        tableRow = $(`#${editModeValue}productTable tbody tr:last`).attr('id');
        arrayNumber = $(`#${editModeValue}productTable tbody tr:last`).attr('class');
        count = tableRow.substring(editModeValue ? 4 : 3);
        count = Number(count) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    }


    var tr = '<tr id="' + editModeValue + 'row' + count + '" class="' + arrayNumber + '">';

    tr += '<td class="form-group" >';
    if (editRowId) {
        tr += `<input type="hidden" name="erowId[]" id="erowId${count}" >`;
    }
    tr += `
    
        <select class="selectpicker required" name="${editModeValue}locNum[]" id="${editModeValue}locNum${count}" data-live-search="true" data-size="4" autocomplete="off">
            <option value="0" selected disabled>Select</option>
            <optgroup class="locNum ${editModeValue}defaultOptions${count}">
            </optgroup>
        </select>
    </td>
    <td class="form-group">
        <select class="selectpicker required" name="${editModeValue}damageType[]" id="${editModeValue}damageType${count}" data-live-search="true" data-size="4" autocomplete="off">
            <option value="0" selected disabled>Select</option>
            <optgroup class="damageType ${editModeValue}defaultOptions${count}">
            </optgroup>
        </select>
    </td>
    <td class="form-group">
        <select class="selectpicker required" name="${editModeValue}damageSeverity[]" id="${editModeValue}damageSeverity${count}" data-live-search="true" data-size="4" autocomplete="off">
            <option value="0" selected disabled>Select</option>
            <optgroup class="damageSeverity ${editModeValue}defaultOptions${count}">
            </optgroup>
        </select>
    </td>
    <td class="form-group">
        <select class="selectpicker required" name="${editModeValue}damageGrid[]" id="${editModeValue}damageGrid${count}" data-live-search="true" data-size="4" autocomplete="off">
            <option value="0" selected disabled>Select</option>
            <optgroup class="damageGrid ${editModeValue}defaultOptions${count}">
            </optgroup>
        </select>
    </td>

    <td class="form-group text-center">
        <button type="button" class="btn btn-danger removeProductRowBtn" data-loading-text="Loading..." onclick="removeProductRow(${count}, ${editModeValue ? true : false},${editRowId})"><i class="fa fa-trash"></i></button>
    </td>
    </tr>
    `;

    if (tableLength > 0) {
        $(`#${editModeValue}productTable tbody tr:last`).after(tr);
        $('.selectpicker').selectpicker('refresh');
        $(`#${editModeValue}addRowBtn`).button("reset");
        if (defaultOptionsData) {
            loadDefaultOptionsData(count, editModeValue)
        } else {
            loadDefaultOptionsFromAPI(editModeValue)
        }
    }
}

function removeProductRow(row = null, editModeValue = false, editRowId = null) {
    if (row) {

        if (editRowId) {
            const deletedRowValues = $('#deletedRows').val();
            $('#deletedRows').val(deletedRowValues + (deletedRowValues ? "," : "") + editRowId)
        }

        $(`#${editModeValue ? 'e' : ''}row` + row).remove();
    } else {
        alert('error! Refresh the page again');
    }
}