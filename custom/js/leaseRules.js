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

    $("#editExpireIn").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
    });
    $("#expireIni").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
    });

    loadTypeHead(1);


    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchLeaseRules.php',
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
                                    data: { data: 'lease_rule' },
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
                title: 'Lease Rules',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Lease Rules',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'print',
                title: 'Lease Rules',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
        ],

        columnDefs: [
            {
                targets: 19,
                visible: false,
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
            },
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
            if ($('#isAllowed').val() == 'true') {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editRule(" + data[19] + ")"
                });
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
            "expireIn[]": {
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
            // e.preventDefault();
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

            editYear: {
                required: !0,
            },
            editModelno: {
                required: !0,
            },
            editExpireIn: {
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

    });


    $("#ImportRule").validate({
        rules: {
            excelFile: {
                required: true,
            },
            "expireIni": {
                required: !0,
            },
            "12_24_33i": {
                required: !0,
                number: !0,
            },
            "12_36_48i": {
                required: !0,
                number: !0,
            },
            "10_24_33i": {
                required: !0,
                number: !0,
            },
            "10_36_48i": {
                required: !0,
                number: !0,
            },
        },
        messages: {
            excelFile: {
                required: "File must be required",
            }
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            var allowedFiles = [".xlsx", ".xls", ".csv"];
            var fileUpload = $("#excelFile");
            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
            if (!regex.test(fileUpload.val().toLowerCase())) {
                e1.fire("Files having extensions: " + allowedFiles.join(', ') + " only.");
                return false;
            }

            $('#import').block({
                message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
                timeout: 1e3
            });
            $("#importSubmitBtn").attr("disabled", true);
            $('#importSubmitBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            // $('#importSubmitBtn').button("loading");

            var form = $('#ImportRule');
            var fd = new FormData(document.getElementById("ImportRule"));
            fd.append("CustomField", "This is some extra data");

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function (response) {
                    response = JSON.parse(response);

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
                        $('#importSubmitBtn').html('Submit');
                        $("#importSubmitBtn").removeAttr("disabled");

                        if (response.error && response.error.length > 0) {
                            var i = 0;
                            $('#errorDiv').removeClass('d-none');
                            // console.log(response.erorStock);
                            while (response.error[i]) {
                                console.log(response.error[i]);
                                document.getElementById('errorList').innerHTML += `
                                    <span class="list-group-item list-group-item-danger">
                                    ${response.error[i]}
                                </span> `;
                                i++;
                            }
                        }

                    } else {
                        e1.fire({
                            position: "top-end",
                            icon: "error",
                            title: response.error,
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
    });

    $("#expireIn" + id).datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',

    });


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

                var expire_in = moment(response.expire_in).format('MM-DD-YYYY');
                $('#editExpireIn').datepicker('update', expire_in)





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
            <td class="form-group">
                <input type="text" class="form-control" id="expireIn${count}" name="expireIn[]" placeholder="Expire In.">
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