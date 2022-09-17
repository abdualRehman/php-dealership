"use strict";
var manageDataTable
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})

$(function () {

    var divRequest = $(".div-request").text();

    if (divRequest == "man") {
        var x = 1;
        manageDataTable = $("#datatable-1").DataTable({
            responsive: !0,

            'ajax': '../php_action/fetchManufaturePrice.php',

            // working.... with both
            dom: `
            <'row'<'col-12'P>>\n      
            <'row'<'col-sm-12 text-sm-left col-md-4 mb-2'<'#statusFilterDiv'> > <'col-sm-12 col-md-4 text-center'B> <'col-sm-12 col-md-4 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,


            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [1, 2, 3]
            },
            "pageLength": 25,
            buttons: [
                {
                    extend: "colvis",
                    text: "Visibility control",
                    collectionLayout: "two-column",
                    exportOptions: {
                        columns: [1, 2, 3, 6, 7]
                    }
                },
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                },


                {
                    text: 'Delete',
                    action: function (e, dt, node, config) {
                        // --------------- if user want to delete only visible rows  ----------------------
                        // $("#datatable-1 tbody tr.selected").each(function () {
                        //     var self = $(this);
                        //     // var stockno1 = self.find("td:eq(1)").text().trim();
                        //     var stockno1 = self.find("td:eq(1)").text();
                        //     console.log(stockno1);
                        // });
                        var selData = manageDataTable.rows(".selected", { "filter": "applied" }).data();

                        var i = 0, idArray = [];

                        if (selData.length > 0) {
                            while (i < selData.length) {
                                idArray.push(selData[i][0])
                                i++;
                            }
                            console.log(idArray);
                            e1.fire({
                                title: "Are you sure?",
                                text: `You won't be able to revert this!?`,
                                icon: "warning",
                                footer: `<b>Total Selected Rows: ${idArray.length}</b>`,
                                showCancelButton: !0,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Yes, delete it!"
                            }).then(function (t) {

                                if (t.isConfirmed == true) {
                                    console.log(t);

                                    $.ajax({
                                        url: '../php_action/removeSelectedManPrice.php',
                                        type: 'post',
                                        data: { idArray: idArray },
                                        dataType: 'json',
                                        success: function (response) {
                                            console.log(response);

                                            if (response.success == true) {
                                                Swal.fire("Deleted!", "Your file has been deleted.", "success")
                                                manageDataTable.ajax.reload(null, false);
                                            } // /response messages
                                        },
                                        error: function (err) {
                                            console.log(err);
                                        }
                                    }); // /ajax function to remove the brand

                                }
                            });
                        } else {

                            e1.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "No Row Selected!",
                            });
                        }

                    }
                }

            ],

            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    className: 'select-checkbox',
                    checkboxes: {
                        'selectRow': true
                    }
                },
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [1, 2, 3]
                },
                // for hide columns as defaul
                {
                    visible: false,
                    targets: [9, 10]
                },
                {
                    targets: 0,
                    render: function (row, data, index) {
                        return "";
                    },
                }


            ],
            select: {
                'style': 'multi', // 'single', 'multi', 'os', 'multi+shift'
                selector: 'td:first-child',
            },
            language: {
                searchPanes: {
                    count: "{total} found",
                    countFiltered: "{shown} / {total}"
                },
                copy: 'Copy',
                copySuccess: {
                    1: "Copied one row to clipboard",
                    _: "Copied %d rows to clipboard"
                },
                copyTitle: 'Copy to clipboard',
                copyKeys: 'Press <i>ctrl</i> or <i>\u2318</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.'
            },
            "drawCallback": function (settings, start, end, max, total, pre) {
                var json = this.fnSettings().json;
                if (json) {
                    var obj = json.data;
                    var activeCount = 0, inactiveCount = 0;

                    for (const [key, value] of Object.entries(obj)) {
                        var rowStatus = value[10];
                        if (rowStatus == '0') {
                            inactiveCount += 1;
                        } else if (rowStatus == '1') {
                            activeCount += 1;
                        }
                    }
                    $(`#activeCount`).html(activeCount);
                    $(`#inactiveCount`).html(inactiveCount);
                }
                $('.editCheckbox').on('change', function () {
                    changeStatus($(this));
                });
            },
            createdRow: function (row, data, dataIndex) {
                if ($('#isAllowed').val() == 'true') {
                    $(row).children().not(':last-child').not(':first-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#editDetails",
                        "onclick": "editDetails(" + data[9] + ")"
                    });
                }
            },
            "order": [[1, "asc"]]
        })
        $('#MyTableCheckAllButton').click(function () {
            if (manageDataTable.rows({
                selected: true
            }).count() > 0) {
                manageDataTable.rows().deselect();
                return;
            }
            manageDataTable.rows({ "filter": "applied" }).select();
        });

        manageDataTable.on('select deselect', function (e, dt, type, indexes) {
            if (type === 'row') {
                // We may use dt instead of manageDataTable to have the freshest data.
                if (dt.rows().count() === dt.rows({
                    selected: true
                }).count()) {
                    // Deselect all items button.
                    $('#MyTableCheckAllButton i').attr('class', 'far fa-check-square');
                    return;
                }

                if (dt.rows({
                    selected: true
                }).count() === 0) {
                    // Select all items button.
                    $('#MyTableCheckAllButton i').attr('class', 'far fa-square');
                    return;
                }

                // Deselect some items button.
                $('#MyTableCheckAllButton i').attr('class', 'far fa-minus-square');
            }
        });



        $("#editForm").validate({
            ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
            rules: {
                model: {
                    required: !0,
                },
                modelCode: {
                    required: !0,
                },
                year: {
                    required: !0,
                },
            },

            submitHandler: function (form, event) {
                // return true;
                event.preventDefault();

                var c = confirm('Do you really want to save this?');
                if (c == true) {
                    var form = $('#editForm');
                    $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        data: form.serialize(),
                        dataType: 'json',
                        success: function (response) {
                            console.log(response);

                            if (response.success == true) {

                                e1.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: response.messages,
                                    showConfirmButton: !1,
                                    timer: 2500,
                                })

                                form[0].reset();
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
                }
                return false;

            }
        });


        writeStatusHTML();
        $('#searchStatusActive').click();

        $.fn.dataTable.ext.search.push(
            function (settings, searchData, index, rowData, counter) {
                var tableNode = manageDataTable.table().node();

                var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                    if (this.value !== "") {
                        return this.value;
                    }
                }).get();

                if (searchStatus.length === 0) {
                    return true;
                }

                if (searchStatus.indexOf(searchData[10]) !== -1) {
                    return true;
                }
                if (settings.nTable !== tableNode) {
                    return true;
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
        });


    } else if (divRequest == "add") {
        $(function () {


            $('#travelTime').timepicker({ 'timeFormat': 'H:i', 'showDuration': true });
            $('#roundTrip').timepicker({ 'timeFormat': 'H:i', 'showDuration': true });

            $("#addForm").validate({
                ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
                rules: {
                    model: {
                        required: !0,
                    },
                    modelCode: {
                        required: !0,
                    },
                    year: {
                        required: !0,
                    },
                },

                submitHandler: function (form, event) {
                    event.preventDefault();

                    var c = confirm('Do you really want to save this?');
                    if (c == true) {
                        var form = $('#addForm');
                        $.ajax({
                            type: "POST",
                            url: form.attr('action'),
                            data: form.serialize(),
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);

                                if (response.success == true) {

                                    e1.fire({
                                        position: "top-end",
                                        icon: "success",
                                        title: response.messages,
                                        showConfirmButton: !1,
                                        timer: 2500,
                                    })

                                    form[0].reset();

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
                    }
                    return false;

                }
            });

            $("#importManForm").validate({
                rules: {
                    excelFile: {
                        required: true,
                    }
                },
                messages: {
                    excelFile: {
                        required: "File must be required",
                    }
                },
                submitHandler: function (form, event) {
                    // return true;
                    event.preventDefault();

                    var allowedFiles = [".xlsx", ".xls", ".csv"];
                    var fileUpload = $("#excelFile");
                    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
                    if (!regex.test(fileUpload.val().toLowerCase())) {
                        e1.fire("Files having extensions: " + allowedFiles.join(', ') + " only.");
                        return false;
                    }


                    var form = $('#importManForm');
                    var fd = new FormData(document.getElementById("importManForm"));
                    fd.append("CustomField", "This is some extra data");


                    $.ajax({
                        async: false,
                        url: form.attr('action'),
                        type: 'POST',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            console.log(response);
                            response = JSON.parse(response);

                            // var ne = window.open('', 'title');
                            // ne.document.write(response);

                            // console.log(response);
                            // console.log(response.success);
                            if (response.success == true) {

                                e1.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: response.messages,
                                    showConfirmButton: !1,
                                    timer: 2500,
                                })
                                form[0].reset();

                                if (response.erorStock && response.erorStock.length > 0) {
                                    var i = 0;
                                    $('#errorDiv').removeClass('d-none');
                                    // console.log(response.erorStock);
                                    while (response.erorStock[i]) {
                                        console.log(response.erorStock[i]);
                                        document.getElementById('errorList').innerHTML += `
                                            <span class="list-group-item list-group-item-danger">
                                            ${response.erorStock[i]}
                                        </span> `;
                                        i++;
                                    }

                                }

                            } else {

                                e1.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: response.messages,
                                    showConfirmButton: !1,
                                    timer: 2500
                                })
                            }


                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                    return false;
                    // return true;

                }
            });

        })



    }

});


function changeStatus(obj = null) {
    console.log(obj);
    var probId = obj[0].id;
    console.log(probId);
    if (probId) {
        e1.fire({
            title: "Are you sure?",
            text: "Do you really want to change status?",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Change it!"
        }).then(function (t) {
            if (t.isConfirmed == true) {
                $.ajax({
                    url: '../php_action/changeManufacturePriceStatus.php',
                    type: 'post',
                    data: { id: probId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Changed!", "Status has been changes.", "success")
                            manageDataTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
    }
}

function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusInactive" value="0" >Inactive<span class="badge badge-lg p-1" id="inactiveCount" ></span>
            </label>
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusActive" value="1">Active <span class="badge badge-lg p-1" id="activeCount" ></span>
            </label> 
            
        </div>`;
    }

}


function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
function clearErrorsList() {
    $('#errorList').html('');
}


function editDetails(id = null) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedManufacturePrice.php',
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

                $('#manId').val(response.id);
                $('#year').val(response.year);
                $('#model').val(response.model);
                $('#modelCode').val(response.model_code);
                $('#msrp').val(response.msrp);
                $('#dlrInv').val(response.dlr_inv);
                $('#modelDescription').val(response.model_des);
                $('#trim').val(response.trim);

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

function removeManufacturePrice(id = null) {
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
            console.log(t);
            if (t.isConfirmed == true) {

                $.ajax({
                    url: '../php_action/removeManufacturePrice.php',
                    type: 'post',
                    data: { id: id },
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
