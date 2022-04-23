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


    loadTypeHead(1);


    manageDataTable = $("#datatable-1").DataTable({
        // responsive: !0,
        scrollX: !0,
        scrollCollapse: !0,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 1
        },
        'ajax': '../php_action/fetchLeaseRules.php',
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



    $("#addNewRule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
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
            "12_24_33": {
                required: !0,
                number: !0,
            },
            "12_36_48": {
                required: !0,
                number: !0,
            },
            "10_24_33": {
                required: !0,
                number: !0,
            },
            "10_36_48": {
                required: !0,
                number: !0,
            },
            "24": {
                required: !0,
                number: !0,
            },
            "27": {
                required: !0,
                number: !0,
            },
            "30": {
                required: !0,
                number: !0,
            },
            "33": {
                required: !0,
                number: !0,
            },
            "36": {
                required: !0,
                number: !0,
            },
            "39": {
                required: !0,
                number: !0,
            },
            "42": {
                required: !0,
                number: !0,
            },
            "45": {
                required: !0,
                number: !0,
            },
            "48": {
                required: !0,
                number: !0,
            },
            "51": {
                required: !0,
                number: !0,
            },
            "54": {
                required: !0,
                number: !0,
            },
            "57": {
                required: !0,
                number: !0,
            },
            "60": {
                required: !0,
                number: !0,
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

    $("#editRuleForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {

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

            "e12_24_33": {
                required: !0,
                number: !0,
            },
            "e12_36_48": {
                required: !0,
                number: !0,
            },
            "e10_24_33": {
                required: !0,
                number: !0,
            },
            "e10_36_48": {
                required: !0,
                number: !0,
            },
            "e24": {
                required: !0,
                number: !0,
            },
            "e27": {
                required: !0,
                number: !0,
            },
            "e30": {
                required: !0,
                number: !0,
            },
            "e33": {
                required: !0,
                number: !0,
            },
            "e36": {
                required: !0,
                number: !0,
            },
            "e39": {
                required: !0,
                number: !0,
            },
            "e42": {
                required: !0,
                number: !0,
            },
            "e45": {
                required: !0,
                number: !0,
            },
            "e48": {
                required: !0,
                number: !0,
            },
            "e51": {
                required: !0,
                number: !0,
            },
            "e54": {
                required: !0,
                number: !0,
            },
            "e57": {
                required: !0,
                number: !0,
            },
            "e60": {
                required: !0,
                number: !0,
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
            url: '../php_action/fetchSelectedLeaseRule.php',
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





                $('#e12_24_33').val(response['12_24_33']);
                $('#e12_36_48').val(response['12_36_48']);
                $('#e10_24_33').val(response['10_24_33']);
                $('#e10_36_48').val(response['10_36_48']);


                $('#e24').val(response['24']);
                $('#e27').val(response['27']);
                $('#e30').val(response['30']);
                $('#e33').val(response['33']);
                $('#e36').val(response['36']);
                $('#e39').val(response['39']);
                $('#e42').val(response['42']);
                $('#e45').val(response['45']);
                $('#e48').val(response['48']);
                $('#e51').val(response['51']);
                $('#e54').val(response['54']);
                $('#e57').val(response['57']);
                $('#e60').val(response['60']);

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
                    url: '../php_action/removeLeaseRule.php',
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

            tr += `</select>
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