"use strict";
var manageRuleTable;
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
    $(".input-daterange").datepicker({
        // orientation: t,
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        // to disable time picker
        minView: 2,
        pickTime: false,
    });

    loadTypeHead(1);


    manageRuleTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchSalesPersonRules.php',
        dom: "Pfrtip",
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [0, 1, 2, 3, 4],
        },
        "pageLength": 25,
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3, 4],
            },
            {
                "targets": [0, 1, 2, 5],
                "visible": false,
                "searchable": false
            },
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
                    "onclick": "editRule(" + data[14] + ")"
                });
            }
        },
        "order": [[0, "asc"]]
    })



    $("#addNewRule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            // fromDate: {
            //     required: !0,
            // },
            // toDate: {
            //     required: !0,
            // },
            "year[]": {
                required: !0,
            },
            "modelno[]": {
                required: !0,
            },
            "model[]": {
                // valueNotEquals: "0",
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
            "state[]": {
                // valueNotEquals: "0",
                required: function (params) {
                    var id = params.id;
                    if (params.value == 0 || params.value == '') {
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
                        $('#addNew').modal('hide');
                        form[0].reset();
                        resetForm();
                        manageRuleTable.ajax.reload(null, false);
                    } else {
                        e1.fire({
                            position: "center",
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


    })
    $("#editRuleForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            // editfromDate: {
            //     required: !0,
            // },
            // edittoDate: {
            //     required: !0,
            // },
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
        },
        // messages: {
        //     editfromDate: {
        //         required: "",
        //     },
        //     edittoDate: {
        //         required: "",
        //     },
        // },
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
                        manageRuleTable.ajax.reload(null, false);

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


function resetForm() {
    $('.typeahead').val('');
    $('.typeahead').trigger('change');
    $('.selectpicker').each(function () {
        $(this).find('option:first').prop('selected', 'selected');
        $(".selectpicker").selectpicker("refresh");
    });
    $(".tags").empty();
}

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
        placeholder: "Exclude Model No.",
        tags: !0,
        allowClear: true
    })
}


function editRule(ruleId = null) {
    if (ruleId) {

        $.ajax({
            url: '../php_action/fetchSelectedSalesPersonRule.php',
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


                $('#ruleId').val(response.id);

                $('#editModel').val(response.model);
                $('#editYear').val(response.year);
                $('#editModelno').val(response.modelno);
                $('#editModelType').val(response.type);
                $('#editState').val(response.state);

                var arr = (response.ex_modelno.trim()).split('_');
                arr.shift(); //remove first space
                arr.pop(); //remove last space
                $("#editExModelno").html('');
                arr.forEach(element => {
                    if ((element == '') || $('#editExModelno').find("option[value='" + element + "']").length) {
                        // $('#editExModelno').val(element).trigger('change');
                    }
                    else {
                        var newOption = new Option(element, element, true, true);
                        $('#editExModelno').append(newOption).trigger('change');
                    }
                });

                $('#editVinCheck').val(response.vin_check);
                $('#editInsurance').val(response.insurance);
                $('#editTradeTitle').val(response.trade_title);
                $('#editRegistration').val(response.registration);
                $('#editInspection').val(response.inspection);
                $('#editSalespersonStatus').val(response.salesperson_status);
                $('#editPaid').val(response.paid);


                $('.selectpicker').selectpicker('refresh');

                chnageStyle({ id: 'editVinCheck', value: response.vin_check });
                chnageStyle({ id: 'editInsurance', value: response.insurance });
                chnageStyle({ id: 'editTradeTitle', value: response.trade_title });
                chnageStyle({ id: 'editRegistration', value: response.registration });
                chnageStyle({ id: 'editInspection', value: response.inspection });
                chnageStyle({ id: 'editSalespersonStatus', value: response.salesperson_status });
                chnageStyle({ id: 'editPaid', value: response.paid });



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
                    url: '../php_action/removeSalesPersonRule.php',
                    type: 'post',
                    data: { id: ruleId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageRuleTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
    }
}


// $('#selectAll').change(function () {
//     // $('#selectAll').prop("checked", this.checked)
//     $('#checkBoxRow .check').prop("checked", this.checked);
// });

// $('#editSelectAll').change(function () {
//     // $('#selectAll').prop("checked", this.checked)
//     $('#checkBoxRow .check').prop("checked", this.checked);
// });
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


    var tr = '<tr id="row' + count + '" class="' + arrayNumber + '">';

    tr += `<td class="form-group">
    <select class="form-control selectpicker w-auto" id="model${count}" name="model[]" data-live-search="true" data-size="4">
        <option value="0" selected disabled>Select Model</option>
        <option value="All">All</option>
        <option value="ACCORD">ACCORD</option>
        <option value="ACCORD HYBRID">ACCORD HYBRID</option>
        <option value="CIVIC">CIVIC</option>
        <option value="CR-V">CR-V</option>
        <option value="CR-V HYBRID">CR-V HYBRID</option>
        <option value="HR-V">HR-V</option>
        <option value="INSIGHT">INSIGHT</option>
        <option value="ODYSSEY">ODYSSEY</option>
        <option value="PASSPORT">PASSPORT</option>
        <option value="PILOT">PILOT</option>
        <option value="RIDGELINE">RIDGELINE</option>
    </select>
</td>`;


    tr += `
    <td class="form-group">
        <input type="text" class="form-control typeahead typeahead${count}" id="year${count}" name="year[]" placeholder="Year">
    </td>
    <td class="form-group">
        <input type="text" class="form-control typeahead typeahead${count}" id="modelno${count}" name="modelno[]" placeholder="Model No.">
    </td>
    <td class="form-group">
        <select class="form-control selectpicker w-auto" id="modelType${count}" name="modelType[]">
            <option value="ALL" selected>All</option>
            <option value="NEW">NEW</option>
            <option value="USED">USED</option>
        </select>
    </td>
    <td class="form-group">
        <select class="form-control selectpicker w-auto" multiple data-selected-text-format="count > 4" name="state${count}[]" id="state${count}" title="State" data-live-search="true" data-size="8">
            <option value="0" disabled>State</option>
            <option value="MA">MA</option>
            <option value="RI">RI</option>
            <option value="CT">CT</option>
            <option value="NH">NH</option>
            <option value="AL">AL</option>
            <option value="AK">AK</option>
            <option value="AZ">AZ</option>
            <option value="AR">AR</option>
            <option value="CA">CA</option>
            <option value="CO">CO</option>
            <option value="DC">DC</option>
            <option value="DE">DE</option>
            <option value="FL">FL</option>
            <option value="GA">GA</option>
            <option value="HI">HI</option>
            <option value="ID">ID</option>
            <option value="IL">IL</option>
            <option value="IN">IN</option>
            <option value="IA">IA</option>
            <option value="KS">KS</option>
            <option value="KY">KY</option>
            <option value="LA">LA</option>
            <option value="ME">ME</option>
            <option value="MD">MD</option>
            <option value="MI">MI</option>
            <option value="MN">MN</option>
            <option value="MS">MS</option>
            <option value="MO">MO</option>
            <option value="MT">MT</option>
            <option value="NE">NE</option>
            <option value="NV">NV</option>
            <option value="NJ">NJ</option>
            <option value="NM">NM</option>
            <option value="NY">NY</option>
            <option value="NC">NC</option>
            <option value="ND">ND</option>
            <option value="OH">OH</option>
            <option value="OK">OK</option>
            <option value="OR">OR</option>
            <option value="PA">PA</option>
            <option value="SC">SC</option>
            <option value="SD">SD</option>
            <option value="TN">TN</option>
            <option value="TX">TX</option>
            <option value="UT">UT</option>
            <option value="VT">VT</option>
            <option value="VA">VA</option>
            <option value="WA">WA</option>
            <option value="WV">WV</option>
            <option value="WI">WI</option>
            <option value="WY">WY</option>
            <option value="N/A">N/A</option>
        </select>
    </td>
    <td class="form-group">
        <select class="form-control tags select2${count}" id="exModelno${count}" name="exModelno${count}[]" multiple="multiple" title="Exclude Model No.">
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

}

function removeProductRow(row = null) {
    if (row) {
        $("#row" + row).remove();
    } else {
        alert('error! Refresh the page again');
    }
}

function chnageStyle(field) {

    var ele = $(`button[data-id="${field.id}"]`);

    switch (field.id) {
        case 'vinCheck':
        case 'editVinCheck':
            if (field.value == 'checkTitle' || field.value == 'need') {
                $('#' + field.id).selectpicker('refresh');
                $('#' + field.id).selectpicker('setStyle', 'btn-outline-danger');
            } else {
                ele.removeClass('btn-outline-danger');
                $('#' + field.id).selectpicker('setStyle', ' btn-outline-success');
            }
            break;

        case 'insurance':
        case 'tradeTitle':
        case 'inspection':
        case 'editInsurance':
        case 'editTradeTitle':
        case 'editInspection':
            if (field.value == 'need') {
                $('#' + field.id).selectpicker('refresh');
                $('#' + field.id).selectpicker('setStyle', 'btn-outline-danger');
            } else {
                ele.removeClass('btn-outline-danger');
                $('#' + field.id).selectpicker('setStyle', ' btn-outline-success');;
            }
            break;
        case 'registration':
        case 'editRegistration':
            if (field.value == 'pending') {
                $('#' + field.id).selectpicker('refresh');
                $('#' + field.id).selectpicker('setStyle', 'btn-outline-danger');
            } else {
                ele.removeClass('btn-outline-danger');
                $('#' + field.id).selectpicker('setStyle', ' btn-outline-success');
            }
            break;
        case 'salePStatus':
        case 'editSalespersonStatus':
            if (field.value == 'dealWritten') {
                $('#' + field.id).selectpicker('refresh');
                $('#' + field.id).selectpicker('setStyle', 'btn-outline-danger');
            } else {
                ele.removeClass('btn-outline-danger');
                $('#' + field.id).selectpicker('setStyle', ' btn-outline-success');
            }
            break;
        case 'paid':
        case 'editPaid':
            if (field.value == 'no') {
                $('#' + field.id).selectpicker('refresh');
                $('#' + field.id).selectpicker('setStyle', 'btn-outline-danger');
            } else {
                ele.removeClass('btn-outline-danger');
                $('#' + field.id).selectpicker('setStyle', ' btn-outline-success');
            }
            break;
        default:

            break;

    }
    if (field.value == 'N/A') {
        ele.removeClass('btn-outline-danger');
        ele.removeClass('btn-outline-success');
        $('#' + field.id).selectpicker('refresh');
    }


}
