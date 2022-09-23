"use strict";
var manageDataTable;
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});
$(function () {

    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchWriteDownRules.php',
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
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editRule(" + data[6] + ")"
                });
            }
        },
        "order": [[0, "asc"]]
    })



    $("#addNewRule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "ageFrom": {
                required: !0,
                min: 0,
                number: true,
            },
            "ageTo": {
                required: !0,
                min: function (params) {
                    let minV = $('#ageFrom').val() ? $('#ageFrom').val() : 0;
                    return Number(minV);
                },
                number: true,
            },
            "percntBalance[]": {
                required: !0,
                number: true,
            },
            "balanceFrom[]": {
                required: !0,
                number: true,
            },
            "balanceTo[]": {
                required: !0,
                number: true,
            },
            "maxWritedown[]": {
                required: !0,
                number: true,
            },
        },
        submitHandler: function (form, e) {
            // return false;
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
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 1500
                        })
                        form[0].reset();
                        $('#addNew').modal('hide');
                        manageDataTable.ajax.reload(null, false);
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

    $("#editRuleForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "eageFrom": {
                required: !0,
                min: 0,
                number: true,
            },
            "eageTo": {
                required: !0,
                min: function (params) {
                    let minV = $('#eageFrom').val() ? $('#eageFrom').val() : 0;
                    return Number(minV);
                },
                number: true,
            },
            "epercntBalance": {
                required: !0,
                number: true,
            },
            "ebalanceFrom": {
                required: !0,
                number: true,
            },
            "ebalanceTo": {
                required: !0,
                number: true,
            },
            "emaxWritedown": {
                required: !0,
                number: true,
            },
        },

        submitHandler: function (form, e) {
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
                        form[0].reset();
                        $('#modal8').modal('hide');
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



function editRule(ruleId = null) {
    if (ruleId) {

        $.ajax({
            url: '../php_action/fetchSelectedWritedownRule.php',
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

                $('#eageFrom').val(response.age_from);
                $('#eageTo').val(response.age_to);
                $('#epercntBalance').val(response.pencent_balance);
                $('#ebalanceFrom').val(response.balance_from);
                $('#ebalanceTo').val(response.balance_to);
                $('#emaxWritedown').val(response.max_writedown);


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
                    url: '../php_action/removeWritedownRule.php',
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

    $("#addRowBtn").button("reset");

    var tr = '<tr id="row' + count + '" class="' + arrayNumber + '">';

    tr += `<td class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" name="percntBalance[]" id="percntBalance${count}"  />
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-percent"></i>
                        </span>
                    </div>
                </div>
            </td>`;
    tr += `
            <td class="form-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-dollar-sign"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="balanceFrom[]" id="balanceFrom${count}"  />
                </div>
            </td>
            <td class="form-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-dollar-sign"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="balanceTo[]" id="balanceTo${count}"  />
                </div>
            </td>
            <td class="form-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-dollar-sign"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="maxWritedown[]" id="maxWritedown${count}"  />
                </div>
            </td>
            <td class="form-group text-center">
                <button type="button" class="btn btn-danger removeProductRowBtn" data-loading-text="Loading..." onclick="removeProductRow(${count})"><i class="fa fa-trash"></i></button>
            </td>
            </tr>
            `;


    if (tableLength > 0) {
        $("#productTable tbody tr:last").after(tr);
        $('.selectpicker').selectpicker('refresh');
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