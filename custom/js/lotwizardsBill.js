"use strict";
var manageBillsTable;
var stockArray = [];
var collapsedGroups = {};

var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})


$(function () {

    $('.nav-link').removeClass('active');
    $('#more').addClass('active');

    $("#saleDate").datetimepicker({
        language: 'pt-BR',
        format: 'M-dd-yyyy',
        minView: 2,
        pickTime: false,
    });


    manageBillsTable = $("#datatable-1").DataTable({

        responsive: !0,
        'ajax': '../php_action/fetchLotWizardsBills.php',
        dom: `\n     
            <'row'<'col-12'P>>\n      
            <'row'<'col-sm-12 text-sm-left col-md-3 mb-2 '<'#statusFilterDiv'>> <'col-sm-12 col-md-6 text-center 'B> <'col-sm-12 col-md-3 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,

        "pageLength": 50,
        searchPanes: {
            columns: [1, 2, 3],
        },
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Lot Wizards Bills',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Lot Wizards Bills',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'print',
                title: 'Lot Wizards Bills',
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
                targets: [1, 2, 3],
            },
            {
                targets: [0 , 8],
                visible: false,
            },
            {
                targets: [4],
                data: 4, // repairs 
                render: function (data, type, row) {
                    var repairArr = data ? data.replace(/__/g, " , ") : '';
                    repairArr = repairArr.slice(3, -3);
                    return repairArr;
                },
            },
            {
                targets: 7,
                data: 7,
                createdCell: function (td, cellData, rowData, row, col) {
                    if ($('#isEditAllowed').val() == "true") {
                        $(td).html(`<div class="show d-flex" >
                            <input type="text" class="form-control" name="input_field" value="${rowData[7]}" data-attribute="repair_paid" data-id="${rowData[0]}" autocomplete="off"  />
                        </div>`);
                    } else {
                        $(td).html(rowData[7]);
                    }
                }
            },
            {
                targets: 8,
                data: 9,  // bodyshop
                // createdCell: function (td, cellData, rowData, row, col) {
                //     if ($('#isEditAllowed').val() == "true") {
                //         $(td).html(`<div class="show d-flex" >
                //             <input type="text" class="form-control" name="date_in_table" value="${rowData[8]}" data-attribute="repair_paid_date" data-id="${rowData[0]}" autocomplete="off"  />
                //         </div>`);
                //     } else {
                //         $(td).html(rowData[8]);
                //     }
                // }
            },

        ],

        language: {
            "infoFiltered": "",
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

        rowGroup: {
            enable: true,
            dataSrc: 9,
            startRender: function (rows, group) {
                var collapsed = !!collapsedGroups[group];

                var filteredData = $('#datatable-1').DataTable()
                    .rows({ search: 'applied' })
                    .data()
                    .filter(function (data, index) {
                        return data[9] == group ? true : false;
                    });

                return $('<tr/>')
                    .append('<td colspan="14">' + group + ' (' + filteredData.length + ')</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            },
        },

        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {
                var obj = json.totalNumber;
                setfun();
                setInputChange();
            }
        },

        // createdRow: function (row, data, dataIndex) {
        //     $(row).children().not(':last-child').attr({
        //         "data-toggle": "modal",
        //         "data-target": "#editDetails",
        //         "onclick": "editDetails(" + data[0] + ")"
        //     });
        // },
        "order": [[8, "asc"], [1, "desc"]],

    });

    writeStatusHTML();
    $('#notPaid').click();




    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index) {
            var tableNode = manageBillsTable.table().node();
            var repairPaid = searchData[7];
            var searchStatus = $('input:radio[name="searchStatus"]:checked').map(function () {
                if (this.value !== "") {
                    return this.value;
                }
            }).get();

            if (searchStatus.length === 0) {
                return true;
            }

            if (settings.nTable !== tableNode) {
                return true;
            }

            if (searchStatus[0] === 'notPaid') {
                if (repairPaid == '' || repairPaid == null) {
                    return true;
                }
            }
            if (searchStatus[0] === 'paid') {
                if (repairPaid != '' && repairPaid != null) {
                    return true;
                }

            }
            return false;
        }
    );


    $('input:radio').on('change', function () {

        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });

        manageBillsTable.draw();  // working
        manageBillsTable.searchPanes.rebuildPane();

    });


    // ---------------------- Edit Sale---------------------------

    // validateState
    $("#editSoldTodoForm").validate({
        submitHandler: function (form, event) {
            // return true;
            event.preventDefault();

            $('[disabled]').removeAttr('disabled');
            var form = $('#editSoldTodoForm');
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
                            timer: 2500,
                        })

                        manageBillsTable.ajax.reload();
                        manageBillsTable.ajax.reload(null, false);
                        manageBillsTable.searchPanes.rebuildPane();
                        $('#editDetails').modal('hide');

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


function setfun() {
    $('input[name="date_in_table"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: !0,
        autoUpdateInput: false,
        cleanable: true,
        "opens": "left",
        "showDropdowns": true,
        locale: {
            format: 'MM-DD-YYYY',
            applyLabel: 'Submit',
            cancelLabel: 'Reset',
        },
    });
    $('input[name="date_in_table"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment());
        $(this).data('daterangepicker').setEndDate(moment());
        let id = $(this).data('id');
        let attribute = $(this).data('attribute');
        let value = "";

        let repair_ele = $('input[data-id="' + id + '"][data-attribute="repair_paid"]');
        var repair_ele_v = repair_ele.val();
        var repair_ele_attr = "repair_paid";

        updateFieldsUsedCars({ id: [id, id], attribute: [attribute, repair_ele_attr], value: [value, repair_ele_v] });
    });
    $('input[name="date_in_table"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY'));
        let id = $(this).data('id');
        let attribute = $(this).data('attribute');
        let value = picker.startDate.format('MM-DD-YYYY');

        let repair_ele = $('input[data-id="' + id + '"][data-attribute="repair_paid"]');
        var repair_ele_v = repair_ele.val();
        var repair_ele_attr = "repair_paid";
        if (repair_ele_v == '') {
            repair_ele.addClass('is-invalid');
            return false;
        } else {
            repair_ele.removeClass('is-invalid');
            updateFieldsUsedCars({ id: [id, id], attribute: [attribute, repair_ele_attr], value: [value, repair_ele_v] });
        }

    });
}


function setInputChange() {
    var inputs = document.querySelectorAll("input[name=input_field]");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keyup", function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                let id = $(this).data('id');
                let attribute = $(this).data('attribute');
                let value = $(this).val();

                // let repair_date_ele = $('input[data-id="' + id + '"][data-attribute="repair_paid_date"]');
                // var repair_date_ele_v = repair_date_ele.val();
                // var repair_date_ele_attr = "repair_paid_date";
                // if (repair_date_ele_v != '' && value == '') {
                //     $(this).addClass('is-invalid');
                //     return false;
                // } else {
                //     $(this).removeClass('is-invalid');
                // }
                updateFieldsUsedCars({ id: [id], attribute: [attribute], value: [value] });
            }
        });
    }
}


function updateFieldsUsedCars(data) {
    console.log(data);
    if (data) {
        // e1.fire({
        //     title: "Are you sure?",
        //     // text: "You won't be able to revert this!",
        //     icon: "warning",
        //     showCancelButton: !0,
        //     confirmButtonColor: "#3085d6",
        //     cancelButtonColor: "#d33",
        //     confirmButtonText: "Yes, Change this!"
        // }).then(function (t) {
        //     if (t.isConfirmed == true) {
        $.ajax({
            url: '../php_action/updateFieldsLotwizards.php',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    e1.fire({
                        position: "center",
                        icon: "success",
                        title: "Successfully Changed",
                        showConfirmButton: !1,
                        timer: 1500,
                    })
                    manageBillsTable.ajax.reload(null, false);
                } // /response messages
            }

        }); // /ajax function to remove the brand
        //     } else {
        //         manageBillsTable.ajax.reload(null, false);
        //     }
        // });
    }
}


function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div id="sort">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="notPaid" value="notPaid"> Not Paid
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="paid" value="paid"> Paid
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
}


function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
function editDetails(id = null) {

    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedSoldTodo1.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');



                console.log(response);



                $('#soldTodoId').val(id);

                $('#customerName').val(response.fname + ' ' + response.lname);
                $('#stockNo').val(response.stockno);
                $('#vehicle').val(`${response.stocktype} ${response.year} ${response.make} ${response.model}`);
                $('#state').val(response.state);
                $('#saleDate').datetimepicker('update', response.date);



                $('#vincheck').val(response.vin_check);
                $('#insurance').val(response.insurance);
                $('#tradeTitle').val(response.trade_title);
                $('#registration').val(response.registration);
                $('#inspection').val(response.inspection);
                $('#salePStatus').val(response.salesperson_status);
                $('#paid').val(response.paid);

                $('.selectpicker').selectpicker('refresh');

                chnageStyle({ id: 'vincheck', value: response.vin_check });
                chnageStyle({ id: 'insurance', value: response.insurance });
                chnageStyle({ id: 'tradeTitle', value: response.trade_title });
                chnageStyle({ id: 'registration', value: response.registration });
                chnageStyle({ id: 'inspection', value: response.inspection });
                chnageStyle({ id: 'salePStatus', value: response.salesperson_status });
                chnageStyle({ id: 'paid', value: response.paid });

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }

}


function toggleInfo(className) {
    $('.' + className).toggleClass('hidden')
}


