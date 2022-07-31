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

$(function () {


    autosize($(".autosize"));

    $(".datepicker").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,
    });

    $(".warranty").select2({
        dropdownAutoWidth: !0,
        placeholder: "",
        allowClear: true,
        tags: !0
    })

    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchWarrentyCancellation.php',
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
            columns: [1, 2, 3, 4],
        },
        "pageLength": 25,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Warrenty Cancellation',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Warrenty Cancellation',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
            {
                extend: 'print',
                title: 'Warrenty Cancellation',
                exportOptions: {
                    columns: [':visible:not(:last-child)']
                }
            },
        ],
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [1, 2, 3, 4],
            },
            {
                targets: [0],
                visible: false,
            },
            {
                targets: [2],
                "render": function (data, type, row, meta) {
                    var arr = (data.trim()).split('__');
                    arr.shift();
                    arr.pop();
                    return arr.join(', ');
                },
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
                    "onclick": "editCancellation(" + data[0] + ")"
                });
            }
        },

        "order": [[0, "asc"]]
    })

    writeStatusHTML();
    loadFinanceManager();
    $('#notPaidSearch').click();


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

            if (searchStatus.indexOf(searchData[7]) !== -1) {
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
        manageDataTable.searchPanes.rebuildPane();
    });




    $("#addNewForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        errorClass: "myErrorClass",
        rules: {
            "customerName": {
                required: !0
            },
        },

        submitHandler: function (form, e) {
            e.preventDefault();
            if ($('#warranty').select2('data').length == 0) {
                // $('.select2.select2-container.select2-container--default[data-select2-id=9]')
                var child = $('.select2.select2-container.select2-container--default[data-select2-id="2"]').find('.select2-selection.select2-selection--multiple');
                console.log(child);
                child.addClass('not-valid');
                child.after('<div id="warrenty-error" class="myErrorClass invalid-feedback">This field is required.</div>')
                return false;
            } else {
                var child = $('.select2.select2-container.select2-container--default[data-select2-id="2"]').find('.select2-selection.select2-selection--multiple');
                child.removeClass('not-valid');
                child.closest('#warrenty-error').remove();

                var form = $('#addNewForm');
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
                            })
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


                    },
                    error: function (e) {
                        console.log(e);
                    }
                });

                return false;

            }
        }


    });


    $("#editWCForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        errorClass: "myErrorClass",
        rules: {
            "ecustomerName": {
                required: !0
            },
        },

        submitHandler: function (form, e) {
            e.preventDefault();
            if ($('#ewarranty').select2('data').length == 0) {
                // $('.select2.select2-container.select2-container--default[data-select2-id=9]')
                var child = $('.select2.select2-container.select2-container--default[data-select2-id="1"]').find('.select2-selection.select2-selection--multiple');
                console.log(child);
                child.addClass('not-valid');
                child.after('<div id="ewarrenty-error" class="myErrorClass invalid-feedback">This field is required.</div>')
                return false;
            } else {
                var child = $('.select2.select2-container.select2-container--default[data-select2-id="1"]').find('.select2-selection.select2-selection--multiple');
                child.removeClass('not-valid');
                child.closest('#ewarrenty-error').remove();


                var form = $('#editWCForm');
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
        }

    });




});



function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        // checked="checked"
        element.innerHTML = `
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="notPaidSearch" value="No" >Not Paid <span class="badge badge-lg p-1" id="notPaidCount" ></span>
            </label>
            <label class="btn btn-flat-primary">
                <input type="radio" name="searchStatus" id="paidSearch" value="Yes">Paid <span class="badge badge-lg p-1" id="paidCount" ></span>
            </label> 
        </div>`;
    }
}


function loadFinanceManager() {
    // var finance_manager_id = 42; //finance manager role id in database
    var finance_manager_id = 64; //finance manager role id in database
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



function editCancellation(id = null) {
    if (id) {

        $.ajax({
            url: '../php_action/fetchSelectedWarrentyCancellation.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editWCForm')[0].reset();


                $('#wcId').val(response.id);
                $('#ecustomerName').val(response.customer_name);
                $('#efinanceManager').val(response.finance_manager);
                $('#erefundDes').val(response.refund_des);
                $('#edateCancelled').val(response.date_cancelled);
                $('#edateSold').val(response.date_sold);
                $('#enotes').val(response.enotes);


                var arr = (response.warrenty.trim()).split('__');
                arr.shift();
                arr.pop();
                $("#ewarranty").html('');
                // $('#ewarranty').select2().val(null).trigger("change");
                arr.forEach(element => {
                    if ((element == '') || $('#ewarranty').find("option[value='" + element + "']").length) {
                        $('#ewarranty').val(element).trigger('change');
                    } else {
                        var newOption = new Option(element, element, true, true);
                        $('#ewarranty').append(newOption).trigger('change');
                    }
                });

                $('#epaid').prop('checked', response.paid == "Yes" ? true : false);

                $('.selectpicker').selectpicker('refresh');

                setTimeout(() => {
                    autosize.update($(".autosize"));
                }, 500);



            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeCancellation(id = null) {
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
                    url: '../php_action/removeWarrentyCancellation.php',
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



function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}

