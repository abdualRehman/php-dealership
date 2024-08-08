"use strict";
var manageDataTable;
var stockArray = [];
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


    autosize($(".autosize"));
    var today = new Date(new Date().toLocaleString('en', {timeZone: 'America/New_York'}));
    $(".setDate").datetimepicker({
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        language: 'pt-BR',
        format: 'mm-dd-yyyy hh:ii',
        // to disable time picker
        minView: 2,
        pickTime: false,
    });


    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchRegistrationPrblm.php',
        // dom: "PfBrtip",
        dom: `\n     
        <'row'<'col-12'P>>\n      
        <'row'<'col-sm-12 text-sm-left col-md-4 mb-2'<'#statusFilterDiv'> > <'col-sm-12 col-md-4 text-center'B> <'col-sm-12 col-md-4 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
       <'row'<'col-12'tr>>\n      
       <'row align-items-baseline'
       <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
       <'col-md-5'p>>\n`,

        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [0, 1, 2, 3],
        },
        "pageLength": 100,
        buttons: [
            {
                extend: 'copyHtml5',
                title: function () {
                    var printTitle = 'Registration Problems';
                    return printTitle
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                extend: 'excelHtml5',
                title: function () {
                    var printTitle = 'Registration Problems';
                    return printTitle
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                extend: 'print',
                title: function () {
                    var printTitle = 'Registration Problems';
                    return printTitle
                },
                exportOptions: {
                    columns: [3, 4, 5, 6, 7]
                }
            },
        ],
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3],
            },
            {
                targets: ($('#isConsultant').val() == "true") ? [4] : [],
                visible: false,
            },
            {
                targets: [9, 11],
                visible: false,
            },
            { width: 300, targets: [7, 8] },
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
                var fixedCount = 0, notFixedCount = 0;

                for (const [key, value] of Object.entries(obj)) {
                    var rowStatus = value[9];
                    if (rowStatus == '0') {
                        fixedCount += 1;
                    } else if (rowStatus == '1') {
                        notFixedCount += 1;
                    }
                }
                $(`#notFixedCount`).html(notFixedCount);
                $(`#fixedCount`).html(fixedCount);
            }
            $('.editCheckbox').on('change', function () {
                changeProblemStatus($(this));
            });
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editProblem(" + data[11] + ")"
                });
            }
        },

        "order": [[4, "asc"],[5, "asc"],[0, "asc"]]
    })

    writeStatusHTML();
    loadTypeHeadCustomerName();
    loadSaleConsultant();
    loadFinanceManager();

    let filterById = null;
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const filter = urlParams.get('filter');

    if (filter != null) {
        filterById = filter;
    } else {
        $('#searchStatusNotFixed').click();
    }


    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageDataTable.table().node();
            if (filterById == null) {
                var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                    if (this.value !== "") {
                        return this.value;
                    }
                }).get();

                if (searchStatus.length === 0) {
                    return true;
                }

                if (searchStatus.indexOf(searchData[9]) !== -1) {
                    return true;
                }
            } else {
                let rowId = searchData[11];
                if (rowId == filter) {
                    return true;
                }
                return false;
            }
            if (settings.nTable !== tableNode) {
                return true;
            }
            return false;
        }
    );


    $('input:radio').on('change', function () {
        filterById = null;
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageDataTable.draw();  // working
        manageDataTable.searchPanes.rebuildPane();
    });











    function requireSelectBox(params) {
        var id = params.id;
        let managerId = '';
        if (id == 'salesConsultant') {
            managerId = 'financeManager'
        } else if (id == 'esalesConsultant') {
            managerId = 'efinanceManager';
        }
        let finance = $('#' + managerId).val();
        if (params.value == 0 && (finance == null || finance == 0)) {
            params.classList.add('is-invalid');
            params.classList.remove('is-valid');
            $('#' + id).selectpicker('refresh');
            return true;
        } else {
            params.classList.remove('is-invalid');
            params.classList.add('is-valid');
            $('#' + id).selectpicker('refresh');
            return false;
        }
    }

    $("#addNewProblem").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "contractDate": {
                required: !0
            },
            "problemDate": {
                required: !0
            },
            "customerName": {
                required: !0
            },
            "salesConsultant": {
                required: requireSelectBox,
            },
            // "financeManager": {
            //     required: requireSelectBox,
            // },
            // "stockId": {
            //     required: !0,
            // },
            "vehicle": {
                required: !0,
            },

        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();

            var form = $('#addNewProblem');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log(response);

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
                        form[0].reset();
                        resetForm();
                        manageDataTable.ajax.reload(null, false);
                    } else {
                        e1.fire({
                            // position: "center",
                            icon: "error",
                            title: response.messages.length > 0 ? response.messages[0] : "Error while Adding",
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

    $("#editProblemForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "econtractDate": {
                required: !0
            },
            "eproblemDate": {
                required: !0
            },
            "ecustomerName": {
                required: !0
            },
            "esalesConsultant": {
                required: requireSelectBox,
            },
            // "efinanceManager": {
            //     required: requireSelectBox,
            // },
            // "estockId": {
            //     required: !0,
            // },
            "evehicle": {
                required: !0,
            },
        },

        submitHandler: function (form, e) {
            // return true
            e.preventDefault();

            var form = $('#editProblemForm');
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

function resetForm() {
    $('.typeahead').val('');
    $('.typeahead').trigger('change');
    $('.selectpicker').each(function () {
        $(this).find('option:first').prop('selected', 'selected');
        $(".selectpicker").selectpicker("refresh");
    });
    $(".tags").empty();
}

// $('input:checkbox').on('change', function () {
//     console.log("funcall");
//     console.log($(this));  
// });


function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        // checked="checked"
        element.innerHTML = `
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusNotFixed" value="1" >Not Fixed <span class="badge badge-lg p-1" id="notFixedCount" ></span>
            </label>
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="searchStatusFixed" value="0">Fixed <span class="badge badge-lg p-1"  ></span>
            </label> 
            
        </div>`;
        // element.innerHTML = `
        // <div class="row">
        //     <div class="col-md-12">
        //         <div id="year">
        //             <div class="btn-group-toggle" data-toggle="buttons">
        //                 <label class="btn btn-outline-primary">
        //                     <input type="radio" name="searchStatus" id="searchStatusAll" value=""> ALL
        //                 </label>
        //                 <label class="btn btn-outline-info">
        //                     <input type="radio" name="searchStatus" value="pending"> Pending <span class="badge badge-lg p-1" id="pendingCount" ></span>
        //                 </label>
        //                 <label class="btn btn-outline-success">
        //                     <input type="radio" name="searchStatus" value="delivered"> Delivered <span class="badge badge-lg p-1" id="deliveredCount" ></span>
        //                 </label>
        //                 <label class="btn btn-outline-danger">
        //                     <input type="radio" name="searchStatus" value="cancelled"> Cancelled <span class="badge badge-lg p-0" id="cancelledCount" ></span>
        //                 </label>
        //             </div>
        //         </div>
        //     </div>
        // </div>`;
    }
}

function loadTypeHeadCustomerName() {

    $.ajax({
        url: '../php_action/fetchCustomerDetails.php',
        type: "POST",
        dataType: 'json',
        success: function (response) {
            var o, e = response.data;
            $(".typeahead1").typeahead({
                hint: true,
                highlight: true,
                minLength: 2
            }, {
                name: "customerName",
                source: (o = e, function (e, a) {
                    var t = [],
                        n = new RegExp(e, "i");
                    o.forEach(function (e) {
                        n.test(e) && t.push(e)
                    }), a(t)
                })
            });
            // console.log(selectBox);
        }
    });

}

// function loadStock() {
// $.ajax({
//     url: '../php_action/fetchInvForSearch.php',
//     type: "POST",
//     dataType: 'json',
//     beforeSend: function () {
//         // selectBox.setAttribute("disabled", true);
//     },
//     success: function (response) {
//         stockArray = response.data;
//         // console.log(stockArray);
//         // console.log(selectBox);

//         var selectBoxs = document.getElementsByClassName('stockIdList');
//         selectBoxs.forEach(element => {
//             for (var i = 0; i < stockArray.length; i++) {
//                 var item = stockArray[i];
//                 element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
//             }
//         })
//         // selectBox.removeAttribute("disabled");
//         $('.selectpicker').selectpicker('refresh');
//     }
// });
// }


function loadSaleConsultant() {
    var sales_consultant_id = Number(localStorage.getItem('salesConsultantID'));
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: sales_consultant_id },
        success: function (response) {
            var saleCnsltntArray = response.data;
            var selectBoxs = document.getElementsByClassName('salesConsultantList');
            selectBoxs.forEach(element => {
                for (var i = 0; i < saleCnsltntArray.length; i++) {
                    var item = saleCnsltntArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
                }
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function loadFinanceManager() {
    var finance_manager_id = Number(localStorage.getItem('financeManagerID'));
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: finance_manager_id },
        success: function (response) {
            var array = response.data;
            var selectBoxs = document.getElementsByClassName('financeManagerList');
            selectBoxs.forEach(element => {
                for (var i = 0; i < array.length; i++) {
                    var item = array[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
                }
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}



function editProblem(probId = null) {
    if (probId) {

        $.ajax({
            url: '../php_action/fetchSelectedRegistrationPrblm.php',
            type: 'post',
            data: { id: probId },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editProblemForm')[0].reset();

                // console.log(response.consultantEdit);
                $('#problemId').val(response.id);

                var contract_date = moment(response.contract_date).format('MM-DD-YYYY HH:mm');
                $('#econtractDate').val(contract_date);
                var problem_date = moment(response.problem_date).format('MM-DD-YYYY HH:mm');
                $('#eproblemDate').val(problem_date);

                $('#ecustomerName').val(response.customer_name);
                $('#esalesConsultant').val(response.sales_consultant);
                $('#efinanceManager').val(response.finance_manager);
                $('#estockId').val(response.stock_id);
                $('#evehicle').val(response.vehicle);
                $('#eproblem').val(response.problem);
                $('#enotes').val(response.notes);
                $('#enotes').prop('readonly', response.consultantEdit);

                $('.selectpicker').selectpicker('refresh');


            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeProblem(probId = null) {
    if (probId) {
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
                    url: '../php_action/removeRegistrationProblem.php',
                    type: 'post',
                    data: { id: probId },
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
function changeProblemStatus(obj = null) {
    var probId = obj[0].id;
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
                    url: '../php_action/changeProblemStatus.php',
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

            } else {
                // $(obj[0]).change();
            }
        });
    }
}



function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}

