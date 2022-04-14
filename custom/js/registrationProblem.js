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
    var today = new Date();
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
        dom: "Pfrtip",
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [0, 1, 2, 3],
        },
        "pageLength": 25,
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3],
            },
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
        "order": [[0, "asc"]]
    })

    loadStock();
    loadSaleConsultant();
    loadFinanceManager();

    function requireSelectBox(params) {
        var id = params.id;
        if (params.value == 0) {
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
            "financeManager": {
                required: requireSelectBox,
            },
            "stockId": {
                required: requireSelectBox,
            },

        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
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
            }
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
            "efinanceManager": {
                required: requireSelectBox,
            },
            "estockId": {
                required: requireSelectBox,
            },
        },

        submitHandler: function (form, e) {
            // return true
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
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
            }
            return false;

        }

    })



})


function loadStock() {
    $.ajax({
        url: '../php_action/fetchInvForSearch.php',
        type: "POST",
        dataType: 'json',
        beforeSend: function () {
            // selectBox.setAttribute("disabled", true);
        },
        success: function (response) {
            stockArray = response.data;
            // console.log(stockArray);
            // console.log(selectBox);

            var selectBoxs = document.getElementsByClassName('stockIdList');
            selectBoxs.forEach(element => {
                for (var i = 0; i < stockArray.length; i++) {
                    var item = stockArray[i];
                    element.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
                }
            })
            // selectBox.removeAttribute("disabled");
            $('.selectpicker').selectpicker('refresh');
        }
    });
}


function loadSaleConsultant() {
    var sales_consultant_id = 38;
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
    var sales_manager_id = 42; //finance manager role id in database
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: sales_manager_id },
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


function changeStockDetails(ele , targetEle) {

    $('#detailsSection').removeClass('d-none');
    let obj = stockArray.find(data => data[0] === ele.value);

    $('#'+targetEle).val(obj[2] + ' ' + obj[3] + ' ' + obj[4]);
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



function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}

