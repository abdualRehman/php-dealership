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
        // responsive: !0,
        scrollX: !0,
        scrollCollapse: !0,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 1
        },
        'ajax': '../php_action/fetchIncentiveRules.php',
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
                targets: 5,
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
        "order": [[0, "asc"]]
    })



    $("#addNewRule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            fromDate: {
                required: !0,
            },
            toDate: {
                required: !0,
            },
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
        },
        messages: {
            fromDate: {
                required: "",
            },
            toDate: {
                required: "",
            },
        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
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
                            manageRuleTable.ajax.reload(null, false);
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


    })
    $("#editRuleForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            editfromDate: {
                required: !0,
            },
            edittoDate: {
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
        },
        messages: {
            editfromDate: {
                required: "",
            },
            edittoDate: {
                required: "",
            },
        },
        submitHandler: function (form, e) {
            // return true
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
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
            }
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
            url: '../php_action/fetchSelectedIncentiveRule.php',
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

                // var date  = new Date(response.from_date)
                var from_date = moment(response.from_date).format('MM-DD-YYYY');
                var to_date = moment(response.to_date).format('MM-DD-YYYY');

                $('#ruleId').val(response.id);

                $('#editfromDate').datepicker('update', from_date);
                $('#edittoDate').datepicker('update', to_date);

                $('#editModel').val(response.model);
                $('#editYear').val(response.year);
                $('#editModelno').val(response.modelno);
                $('#editModelType').val(response.type);

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


                $('#editCollege').attr("checked", (response.college == 'on' ? true : false));
                $('#editMilitary').attr("checked", (response.military == 'on' ? true : false));
                $('#editLoyalty').attr("checked", (response.loyalty == 'on' ? true : false));
                $('#editConquest').attr("checked", (response.conquest == 'on' ? true : false));
                $('#editMisc1').attr("checked", (response.misc1 == 'on' ? true : false));
                $('#editMisc2').attr("checked", (response.misc2 == 'on' ? true : false));
                $('#editMisc3').attr("checked", (response.misc3 == 'on' ? true : false));


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
                    url: '../php_action/removeIncentiveRule.php',
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


$('#selectAll').change(function () {
    // $('#selectAll').prop("checked", this.checked)
    $('#checkBoxRow .check').prop("checked", this.checked);
});

$('#editSelectAll').change(function () {
    // $('#selectAll').prop("checked", this.checked)
    $('#checkBoxRow .check').prop("checked", this.checked);
});
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
        <option value="0" selected disabled>Select Modal</option>
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
        <input type="text" class="form-control typeahead typeahead${count}" id="modelno${count}" name="modelno[]" placeholder="Modal No.">
    </td>
    <td class="form-group">
        <select class="form-control selectpicker w-auto" id="modelType${count}" name="modelType[]">
            <option value="ALL" selected>ALL</option>
            <option value="NEW">NEW</option>
            <option value="USED">USED</option>
        </select>
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

}

function removeProductRow(row = null) {
    if (row) {
        $("#row" + row).remove();
    } else {
        alert('error! Refresh the page again');
    }
}