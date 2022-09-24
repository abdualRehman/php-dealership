"use strict";
var manageLeadTable;
var o, vehicleArray = ["All"];
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
    $('#bdcPage').addClass('active');

    $(".leadDate").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        language: 'pt-BR',
    });
    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        "opens": "left",
        locale: {
            cancelLabel: 'Clear'
        }
    });

    var a = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: vehicleArray
    });

    $(".typeahead").typeahead({
        hint: !0,
        highlight: true,
        minLength: 1
    }, {
        name: "vehicles",
        source: a
    });

    function assignKey(obj, key) {
        // console.log(key);
        typeof obj[key] === 'undefined' ? obj[key] = 1 : obj[key]++;
    }

    manageLeadTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchBdcLeads.php',
        dom: `<'row'<'col-12'P>>
        <'row' 
        <'col-sm-4 text-left text-sm-left pl-3'<'#statusFilterDiv'>>
            <'col-sm-4 text-center pl-3'B>
            <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
        <'row'<'col-12'tr>>\n      
        <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [2, 7, 8, 9, 10],
        },
        "pageLength": 25,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'BDC Leads',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'BDC Leads',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'print',
                title: 'BDC Leads',
                exportOptions: {
                    columns: [':visible']
                }
            },
        ],
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [2, 7, 8, 9, 10],
            },
            {
                targets: [0],
                visible: false,
            },
            {
                targets: [8],
                render:function (data, type, row) {
                    return data;
                },
                createdCell: function (td, cellData, rowData, row, col) {
                    console.log(cellData);
                    console.log(rowData);
                    if (cellData == 'Show') {
                        $(td).html(`<span class="badge badge-lg" style="background-color:#${rowData[14]};text-shadow: 2px 2px 3px black;color: #ebe9e9;">${cellData}</span>`);
                    } else {
                        $(td).html(`<span class="badge badge-lg badge-success">${cellData}</span>`);
                    }
                }
            },
            {
                targets: [12],
                createdCell: function (td, cellData, rowData, row, col) {
                    console.log(rowData);
                    if (cellData == 'Show Verified') {
                        if (rowData[14] != '') {
                            $(td).html(`<span class="badge badge-lg badge-pill" style="background-color:#${rowData[14]};text-shadow: 2px 2px 3px black;color: #ebe9e9;">${cellData}</span>`);
                        } else {
                            $(td).html(`<span class="badge badge-lg badge-primary badge-pill">${cellData}</span>`);
                        }
                    } else if (cellData == 'Does Not Count' || cellData == 'Last Month') {
                        $(td).html(`<span class="badge badge-lg badge-danger badge-pill">${cellData}</span>`);
                    } else {
                        $(td).html(`<span class="badge badge-lg badge-primary badge-pill">${cellData}</span>`);
                    }
                }
            }
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
                var obj = json.data;
                var counterObj = {};

                $('#datatable-1').DataTable()
                    .rows({ search: 'applied' })
                    .data()
                    .filter(function (data, index) {
                        var Status = data[8];
                        var Type = data[9];
                        var Source = data[10];
                        var Verified = data[12];

                        if (Status == 'Sold') {
                            assignKey(counterObj, 'totalSold');
                            if (Type == 'New') {
                                assignKey(counterObj, 'newSold');
                                if (Verified == 'Ok') {
                                    assignKey(counterObj, 'newSoldv');
                                    assignKey(counterObj, 'totalSoldv');
                                }

                            } else if (Type == 'Used') {
                                assignKey(counterObj, 'usedSold');
                                if (Verified == 'Ok') {
                                    assignKey(counterObj, 'usedSoldv');
                                    assignKey(counterObj, 'totalSoldv');
                                }
                            }
                        }
                        if (Source == 'Internet') {
                            assignKey(counterObj, 'totalInt');
                            if (Type == 'New') {
                                assignKey(counterObj, 'newInt');
                                if (Verified == 'Ok') {
                                    assignKey(counterObj, 'newIntv');
                                    assignKey(counterObj, 'totalIntv');
                                }
                            } else if (Type == 'Used') {
                                assignKey(counterObj, 'usedInt');
                                if (Verified == 'Ok') {
                                    assignKey(counterObj, 'usedIntv');
                                    assignKey(counterObj, 'totalIntv');
                                }
                            }
                        }
                        if (Source == 'Auto Alert') {
                            assignKey(counterObj, 'totalAa');
                            if (Type == 'New') {
                                assignKey(counterObj, 'newAa');
                                if (Verified == 'Ok') {
                                    assignKey(counterObj, 'newAav');
                                    assignKey(counterObj, 'totalAav');
                                }
                            } else if (Type == 'Used') {
                                assignKey(counterObj, 'usedAa');
                                if (Verified == 'Ok') {
                                    assignKey(counterObj, 'usedAav');
                                    assignKey(counterObj, 'totalAav');
                                }
                            }
                        }


                        if (Status == 'Show') {
                            assignKey(counterObj, 'unSoldLead');
                            assignKey(counterObj, 'totalLead');
                            if (Verified == 'Show Verified') {
                                assignKey(counterObj, 'unSoldLeadv');
                                assignKey(counterObj, 'totalLeadv');
                            }
                        } else if (Status == 'Sold') {
                            assignKey(counterObj, 'soldLead');
                            assignKey(counterObj, 'totalLead');
                            if (Verified == 'Ok') {
                                assignKey(counterObj, 'SoldLeadv');
                                assignKey(counterObj, 'totalLeadv');
                            }
                        }
                    });
                for (const [key, value] of Object.entries(obj)) {
                    vehicleArray.push(value[6]);
                }
                a.clear();
                a.local = vehicleArray;
                a.initialize(true);

                $('.counterDiv').each(function (index, element) {
                    $(element).find('span').each((i, span) => {
                        $(span).html('0')
                    })
                });
                for (const [key, value] of Object.entries(counterObj)) {
                    $(`#` + key).html(value);
                }


            }
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editLead(" + data[0] + ")"
                });
            }
        },
        "order": [[8, "desc"]]
    })

    writeStatusHTML();
    $('#thisMonth').click();
    loadSaleConsultant();
    disabledManagerDiv();
    applyDateRageFilter();


    $('input[name="searchStatus"]:radio').on('change', function () {
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        $('input[name="datefilter"]').val('');
        applyDateRageFilter();
        manageLeadTable.draw();  // working
        manageLeadTable.searchPanes.rebuildPane();
    });



    $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY') + ' / ' + picker.endDate.format('MM-DD-YYYY'));
        applyDateRageFilter(picker.startDate.format('MM-DD-YYYY'), picker.endDate.format('MM-DD-YYYY'));
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageLeadTable.draw();  // working
        manageLeadTable.searchPanes.rebuildPane();
    });

    $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        applyDateRageFilter();
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageLeadTable.draw();  // working
        manageLeadTable.searchPanes.rebuildPane();
    });


    $("#addNewLead").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            leadDate: {
                required: true
            },
            entityId: {
                required: true
            },
            fname: {
                required: true
            },
            lname: {
                required: true
            },
            vehicle: {
                required: true
            },
            leadType: {
                required: true
            },
            source: {
                required: true
            }
        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var form = $('#addNewLead');
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
                        form[0].reset();
                        $('#addNew').modal('hide');
                        manageLeadTable.ajax.reload(null, false);
                    } else {
                        e1.fire({
                            // position: "center",
                            icon: "error",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500
                        })

                        // form[0].reset();
                    }




                }
            });

            return false;

        }


    });


    $("#editLeadForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            eleadDate: {
                required: true
            },
            eentityId: {
                required: true
            },
            efname: {
                required: true
            },
            elname: {
                required: true
            },
            evehicle: {
                required: true
            },
            eleadType: {
                required: true
            },
            esource: {
                required: true
            }
        },
        submitHandler: function (form, e) {
            // return true
            e.preventDefault();

            var form = $('#editLeadForm');
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
                            timer: 1500
                        })
                        // form[0].reset();
                        $('#modal8').modal('hide');
                        manageLeadTable.ajax.reload(null, false);

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

    })

})


$('.clear-selection').click(function () {
    var id = $(this).data('id');
    $(`#${id} :radio`).prop('checked', false);
    $(`#${id} .active`).removeClass('active');
})


function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="searchStatus">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="searchStatusAll" value="all" > ALL <span class="badge badge-lg p-1" id="allCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" value="lastMonth"> Last Month <span class="badge badge-lg p-1" id="lastMonthCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" value="thisMonth" id="thisMonth" > This Month <span class="badge badge-lg p-1" id="thisMonthCount" ></span>
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
}


function applyDateRageFilter(startFiterDate = "", endFilterDate = "") {

    $.fn.dataTable.ext.search.pop();
    manageLeadTable.search('').draw();
    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageLeadTable.table().node();

            var dateType = $('input:radio[name="searchStatus"]:checked').map(function () {
                if (this.value !== "") {
                    return this.value;
                }
            }).get();

            if (dateType == 'all') {

                if (startFiterDate == "" && endFilterDate == "") {
                    return true;
                } else {
                    var date = searchData[1];
                    var dateBolean = moment(date).isBetween(startFiterDate, endFilterDate, null, '[]');
                    if (dateBolean) {
                        return true;
                    }
                }

            }
            else if (dateType == 'lastMonth') {
                const todayDate = moment(new Date()).format("MM-DD-YYYY");
                var date = searchData[1];
                const startDayOfPrevMonth = moment(todayDate).subtract(1, 'month').startOf('month').format('MM-DD-YYYY')
                const lastDayOfPrevMonth = moment(todayDate).subtract(1, 'month').endOf('month').format('MM-DD-YYYY')

                var bool1 = moment(date).isBetween(startDayOfPrevMonth, lastDayOfPrevMonth, null, '[]');
                if (bool1) {
                    // return true;
                    if (startFiterDate == "" && endFilterDate == "") {
                        return true;
                    } else {
                        var date = searchData[1];
                        var dateBolean = moment(date).isBetween(startFiterDate, endFilterDate, null, '[]');
                        if (dateBolean) {
                            return true;
                        }
                    }
                }

            } else if (dateType == 'thisMonth') {
                const startOfMonth = moment().startOf('month').format('MM-DD-YYYY');
                const endOfMonth = moment().endOf('month').format('MM-DD-YYYY');

                var date = searchData[1];
                var bool1 = moment(date).isBetween(startOfMonth, endOfMonth, null, '[]');
                if (bool1) {
                    // return true;
                    if (startFiterDate == "" && endFilterDate == "") {
                        return true;
                    } else {
                        var date = searchData[1];
                        var dateBolean = moment(date).isBetween(startFiterDate, endFilterDate, null, '[]');
                        if (dateBolean) {
                            return true;
                        }
                    }
                }
            }

            if (settings.nTable !== tableNode) {
                return true;
            }
            return false;
        }
    );

}



function editLead(leadId = null) {
    if (leadId) {

        $.ajax({
            url: '../php_action/fetchSelectedBdcLead.php',
            type: 'post',
            data: { id: leadId },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editLeadForm')[0].reset();

                $('#leadId').val(response.id);
                (response.verified_by != '') ? $('#eapprovedBy').val(response.verified_by) : null;

                $('#eleadDate').val(response.date);
                $('#eleadDate').datepicker('update', response.date);

                $('#eentityId').val(response.entity);
                $('#esubmittedBy').val(response.ccs);
                $('#efname').val(response.fname);
                $('#elname').val(response.lname);
                $('#esalesConsultant').val(response.sales_consultant);
                $('#evehicle').val(response.vehicle);


                $('#eleadType :radio[name="eleadType"]').prop('checked', false);
                $('#eleadType .active').removeClass('active');
                (response.lead_type) ? $('#e' + response.lead_type).prop('checked', true).click() : null;



                $('#eleadStatus :radio[name="eleadStatus"]').prop('checked', false);
                $('#eleadStatus .active').removeClass('active');
                (response.lead_status) ? $('#e' + response.lead_status).prop('checked', true).click() : null;


                $('#esource :radio[name="esource"]').prop('checked', false);
                $('#esource .active').removeClass('active');
                (response.source) ? $('#e' + response.source).prop('checked', true).click() : null;

                $('#eleadNotes').val(response.notes);

                $('#evarifiedStatus :radio[name="evarifiedStatus"]').prop('checked', false);
                $('#evarifiedStatus .active').removeClass('active');
                (response.verified) ? $('#e' + response.verified).prop('checked', true).click() : null;


                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeLead(leadId = null) {
    if (leadId) {
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
                    url: '../php_action/removeBdcLead.php',
                    type: 'post',
                    data: { id: leadId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageLeadTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
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
            // var selectBox = document.getElementById('salesConsultant');
            var selectBoxes = document.getElementsByClassName('salesConsultant');
            selectBoxes.forEach(element => {
                // element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
                for (var i = 0; i < saleCnsltntArray.length; i++) {
                    var item = saleCnsltntArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
                }

            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}

function disabledManagerDiv() {

    let currentUser = $('#loggedInUserRole').val();
    console.log(currentUser);
    var bdc_manager_id = Number(localStorage.getItem('bdcManagerID'));
    if (currentUser != bdc_manager_id) {
        $('.bdc_manager').addClass('disabled-div');
        // $(".bdc_manager").find("*").prop("disabled", true);
        $(".bdc_manager").find("*").prop("readonly", true);
    } else {
        $('.bdc_manager').removeClass('disabled-div');
        // $(".bdc_manager").find("*").prop("disabled", true);
        $(".bdc_manager").find("*").prop("readonly", false);
    }
}