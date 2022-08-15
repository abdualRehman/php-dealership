"use strict";
var manageDataTable;
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

    $("#expireIn").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        language: 'pt-BR',
    });
    $("#editexpireIn").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        language: 'pt-BR',
    });

    loadTypeHead(1);


    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchCashIncentiveRules.php',
        // dom: "Pfrtip",
        dom: `
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
        "pageLength": 25,
        buttons: [
            {
                text: 'Delete All',
                action: function (e, dt, node, config) {
                  
                    var selData = manageDataTable.rows().data();

                    if (selData.length > 0) {
  
                        e1.fire({
                            title: "Are you sure?",
                            text: `You won't be able to revert this!?`,
                            icon: "warning",
                            footer: `<b>Total Selected Rows: ${selData.length}</b>`,
                            showCancelButton: !0,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, delete it!"
                        }).then(function (t) {

                            if (t.isConfirmed == true) {
                                console.log(t);

                                $.ajax({
                                    url: '../php_action/removeALLRecords.php',
                                    type: 'post',
                                    data: { data: 'cash_incentive_rules' },
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
                        });;
                    } else {

                        e1.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "No Row Selected!",
                        });
                    }

                }
            },
            {
                extend: 'copyHtml5',
                title: 'Dealer Cash Incentives Rules',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Dealer Cash Incentives Rules',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'print',
                title: 'Dealer Cash Incentives Rules',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
        ],
        columnDefs: [
            {
                targets: [9],
                visible: false,
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3],
            },
            {
                targets: 4,
                createdCell: function (td, cellData, rowData, row, col) {
                    if (cellData == 'Expire') {
                        $(td).html('<span class="badge badge-danger badge-pill">Expire</span>');
                    } else {
                        $(td).html('<span class="badge badge-info badge-pill">' + cellData + '</span>');
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
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editRule(" + data[9] + ")"
                });
            }
        },
        "order": [[0, "asc"]]
    })



    $("#addNewRule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            expireIn: {
                required: !0,
            },
            "year[]": {
                required: !0,
            },
            "modelno[]": {
                required: !0,
            },
            "model[]": {
                required: function (params) {
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
                },
            },
            "dealer": {
                required: !0,
                number: !0,
            },
            "other": {
                required: !0,
                number: !0,
            },

            "lease": {
                required: !0,
                number: !0,
            },


        },
        messages: {
            expireIn: {
                required: "",
            },
        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();

            var form = $('#addNewRule');
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

            return false;

        }


    });

    $("#editRuleForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            editexpireIn: {
                required: !0,
            },
            editYear: {
                required: !0,
            },
            editModelno: {
                required: !0,
            },
            editModel: {
                // valueNotEquals: "0",
                required: function (params) {
                    if (params.value == 0) {
                        params.classList.add('is-invalid');
                        $('#model').selectpicker('refresh');
                        params.classList.add('is-invalid');
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            "edealer": {
                required: !0,
                number: !0,
            },
            "eother": {
                required: !0,
                number: !0,
            },
            "elease": {
                required: !0,
                number: !0,
            },


        },
        messages: {
            editexpireIn: {
                required: "",
            },
        },
        submitHandler: function (form, e) {
            // return true
            e.preventDefault();

            var form = $('#editRuleForm');
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

    })



})



function loadTypeHead(id) {
    var o, e = ["All"];
    $(".typeahead" + id).typeahead({
        hint: !0,
        highlight: true,
        minLength: 1
    }, {
        name: "states",
        source: (o = e, function (e, a) {
            var t = [],
                n = new RegExp(e, "i");
            o.forEach(function (e) {
                n.test(e) && t.push(e)
            }), a(t)
        })
    });

    $(".select2" + id).select2({
        dropdownAutoWidth: !0,
        placeholder: "Exclude Modal No.",
        tags: !0,
        allowClear: true
    })

}


function editRule(ruleId = null) {
    if (ruleId) {

        $.ajax({
            url: '../php_action/fetchSelectedCashIncentiveRule.php',
            type: 'post',
            data: { id: ruleId },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editRuleForm')[0].reset();
                
                $('editexpireIn').datepicker('setDate', null);

                $('#ruleId').val(response.id);

                var expire_in = moment(response.expire_in).format('MM-DD-YYYY');
                

                $('#editexpireIn').datepicker('update', expire_in);

                $('#editModel').val(response.model);
                $('#editYear').val(response.year);
                $('#editModelno').val(response.modelno);

                var arr = (response.ex_modelno.trim()).split(' ');

                $("#editExModelno").html('');
                arr.forEach(element => {
                    if ((element == '') || $('#editExModelno').find("option[value='" + element + "']").length) {
                        $('#editExModelno').val(element).trigger('change');
                    } else {
                        var newOption = new Option(element, element, true, true);
                        $('#editExModelno').append(newOption).trigger('change');
                    }
                });





                $('#edealer').val(response.dealer);
                $('#eother').val(response.other);
                $('#elease').val(response.lease);

                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeRule(ruleId = null) {
    if (ruleId) {
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
                    url: '../php_action/removeCashIncentiveRule.php',
                    type: 'post',
                    data: { id: ruleId },
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

function addRow() {

    $("#addRowBtn").button("loading");

    var tableLength = $("#productTable tbody tr").length;

    var tableRow;
    var arrayNumber;
    var count;

    if (tableLength > 0) {
        tableRow = $("#productTable tbody tr:last").attr('id');
        arrayNumber = $("#productTable tbody tr:last").attr('class');
        count = tableRow.substring(3);
        count = Number(count) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    }

    $.ajax({
        url: '../php_action/fetchManuFacurePriceModelData.php',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $("#addRowBtn").button("reset");

            var tr = '<tr id="row' + count + '" class="' + arrayNumber + '">';

            tr += `<td class="form-group">
                    <select class="form-control selectpicker w-auto" id="model${count}" name="model[]" data-live-search="true" data-size="4">
                        <option value="0" selected disabled>Select Modal</option>
                        <option value="All">All</option>`
            $.each(response, function (index, value) {
                tr += '<option value="' + value[0] + '"> ' + value[0] + ' </option>';
            });

            tr += `
                </select>
            </td>`;


            tr += `
                    <td class="form-group">
                        <input type="text" class="form-control typeahead typeahead${count}" id="year${count}" name="year[]" placeholder="Year">
                    </td>
                    <td class="form-group">
                        <input type="text" class="form-control typeahead typeahead${count}" id="modelno${count}" name="modelno[]" placeholder="Modal No.">
                    </td>
                    <td class="form-group">
                        <select class="form-control select2${count}" id="exModelno${count}" name="exModelno${count}[]" multiple="multiple" title="Exclude Model No.">
                            <optgroup label="Press Enter to add">
                        </select>
                    </td>
                    <td class="form-group text-center">
                        <button type="button" class="btn btn-danger removeProductRowBtn" data-loading-text="Loading..." onclick="removeProductRow(${count})"><i class="fa fa-trash"></i></button>
                    </td>
                    </tr>
                    `;


            if (tableLength > 0) {
                $("#productTable tbody tr:last").after(tr);
                $('.selectpicker').selectpicker('refresh');
                loadTypeHead(count);
                $("#addRowBtn").button("reset");
            }
        } // /success
    }); // get the product data
}

function removeProductRow(row = null) {
    if (row) {
        $("#row" + row).remove();
    } else {
        alert('error! Refresh the page again');
    }
}