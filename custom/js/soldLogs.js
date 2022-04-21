"use strict";
var manageSoldLogsTable;
var stockArray = [];

var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})


$(function () {
    $("#saleDate").datetimepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy hh:ii',
        minView: 2,
        pickTime: false,
    });
    autosize($(".autosize"));

    var divRequest = $(".div-request").text();

    if (divRequest == "man") {
        manageSoldLogsTable = $("#datatable-1").DataTable({

            // scrollY:"50vh",

            // responsive: !0,
            // responsive: {
            //     details: {
            //         type: 'column',
            //         target: 1
            //     }
            // },
            // scrollY: 500,
            scrollX: !0,
            scrollCollapse: !0,
            fixedColumns: !0,

            'ajax': '../php_action/fetchSoldLogs.php',

            // working.... with both
            // dom: "Pfrtip",
            dom: `\n     
             <'row'<'col-12'P>>\n      
             <'row'<'col-sm-12 col-md-6'l>>\n  
            \n     
            <'row'<'col-sm-6 text-center text-sm-left p-3'B>
                <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
            <'row'<'col-12'tr>>\n      
            <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [0, 2, 4, 5, 9],
                stateSave: true,
            },
            "pageLength": 25,
            buttons: [
                {
                    text: 'Today',
                    action: function (e, dt, node, config) {
                        // $.fn.dataTable.ext.search = [];
                        // var date2 = new Date("03-15-2022");
                        var date2 = new Date();
                        var today = moment(date2).format("MMM-DD-YYYY");
                        // manageSoldLogsTable.columns(0).search(today).draw();

                        $.fn.dataTable.ext.search.pop();
                        manageSoldLogsTable.search('').draw();
                        var tableNode = this.table(0).node();
                        $.fn.dataTable.ext.search.push(
                            function (settings, data, dataIndex) {
                                if (settings.nTable !== tableNode) {
                                    return true;
                                }
                                var date = data[0];
                                if (
                                    (today === date)
                                ) {
                                    return true;
                                }
                                return false
                            }
                        )
                        manageSoldLogsTable.draw();  // working
                        // manageSoldLogsTable.searchPanes.rebuildPane();
                        manageSoldLogsTable.button(0).active(true);
                        manageSoldLogsTable.button(1).active(false);
                        manageSoldLogsTable.button(2).active(false);
                        manageSoldLogsTable.button(3).active(false);
                    },

                },
                {
                    text: 'Yesterday',
                    action: function (e, dt, node, config) {
                        // $.fn.dataTable.ext.search = [];
                        // var date2 = moment(new Date('03-13-2022'), "MMM-DD-YYYY").subtract(1, 'days');
                        var date2 = moment(new Date(), "MMM-DD-YYYY").subtract(1, 'days');
                        var yesterday = moment(date2).format("MMM-DD-YYYY")
                        // console.log(yesterday);
                        // manageSoldLogsTable.columns(0).search(yesterday).draw();


                        $.fn.dataTable.ext.search.pop();
                        manageSoldLogsTable.search('').draw();
                        var tableNode = this.table(0).node();
                        $.fn.dataTable.ext.search.push(
                            function (settings, data, dataIndex) {
                                if (settings.nTable !== tableNode) {
                                    return true;
                                }
                                var date = data[0];
                                if (
                                    (yesterday === date)
                                ) {
                                    return true;
                                }
                                return false
                            }
                        )
                        manageSoldLogsTable.draw();  // working
                        // manageSoldLogsTable.searchPanes.rebuildPane();

                        manageSoldLogsTable.button(0).active(false);
                        manageSoldLogsTable.button(1).active(true);
                        manageSoldLogsTable.button(2).active(false);
                        manageSoldLogsTable.button(3).active(false);


                    },

                },
                {
                    text: 'Current Month',
                    action: function (e, dt, node, config) {
                        // manageSoldLogsTable.search('').draw();
                        // $.fn.dataTable.ext.search = [];
                        // manageSoldLogsTable.columns(0).search('').draw();

                        // $.fn.dataTable.ext.search = [];
                        $.fn.dataTable.ext.search.pop();
                        manageSoldLogsTable.search('').draw();
                        var tableNode = this.table(0).node();

                        const startOfMonth = moment().startOf('month').format('MMM-DD-YYYY');
                        const endOfMonth = moment().endOf('month').format('MMM-DD-YYYY');
                        var date;
                        $.fn.dataTable.ext.search.push(
                            function (settings, data, dataIndex) {
                                if (settings.nTable !== tableNode) {
                                    return true;
                                }
                                var min = startOfMonth;
                                var max = endOfMonth;
                                date = data[0];
                                if (
                                    (min === null && max === null) ||
                                    (min === null && date <= max) ||
                                    (min <= date && max === null) ||
                                    (min <= date && date <= max)
                                ) {
                                    return true;
                                }

                                return false;
                            }
                        );

                        manageSoldLogsTable.draw();  // working
                        // manageSoldLogsTable.searchPanes.rebuildPane();
                        manageSoldLogsTable.button(0).active(false);
                        manageSoldLogsTable.button(1).active(false);
                        manageSoldLogsTable.button(2).active(true);
                        manageSoldLogsTable.button(3).active(false);


                    },

                },
                {
                    text: "Show All",
                    action: function (e, dt, node, config) {
                        // $.fn.dataTable.ext.search = [];

                        // manageSoldLogsTable.clear().draw();
                        $.fn.dataTable.ext.search.pop();
                        manageSoldLogsTable.search('').draw();
                        // manageSoldLogsTable.draw();  // working
                        // manageSoldLogsTable.fnReloadAjax();
                        // manageSoldLogsTable.searchPanes.rebuildPane();
                        // manageSoldLogsTable.ajax.reload();


                        // $('#datatable-1').DataTable().searchPanes.clearSelections();

                        // manageSoldLogsTable.search('').draw();


                        var tableNode = this.table(0).node();
                        $.fn.dataTable.ext.search.push(
                            function (settings, data, dataIndex) {

                                // Check that the search is running on the intended table
                                if (settings.nTable !== tableNode) {
                                    return true;
                                }
                                if (data[0]) {
                                    return true;
                                }
                                return false;
                            }
                        );
                        manageSoldLogsTable.draw();  // working

                        manageSoldLogsTable.ajax.reload(null, false);
                        // manageSoldLogsTable.searchPanes.rebuildPane();

                        manageSoldLogsTable.button(0).active(false);
                        manageSoldLogsTable.button(1).active(false);
                        manageSoldLogsTable.button(2).active(false);
                        manageSoldLogsTable.button(3).active(true);


                    }
                }
            ],
            columnDefs: [
                { "width": "200px", "targets": 0 },
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [0, 2, 4, 5, 9],

                },
                {
                    targets: 9,
                    createdCell: function (td, cellData, rowData, row, col) {
                        if (cellData == 'pending') {
                            $(td).html('<span class="badge badge-info badge-pill">Pending</span>');
                        } else if (cellData == 'delivered') {
                            $(td).html('<span class="badge badge-success badge-pill">Delivered</span>');
                        } else if (cellData == 'cancelled') {
                            $(td).html('<span class="badge badge-danger badge-pill">Cancelled</span>');
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
        });

        manageSoldLogsTable.button(0).active(false);
        manageSoldLogsTable.button(1).active(false);
        manageSoldLogsTable.button(2).active(false);
        manageSoldLogsTable.button(3).active(true);


    } else if (divRequest == 'edit') {
        $(function () {

            loadStock();
            loadSaleConsultant();
            loadSaleManager();

            var saleId = $('#saleId').val();
            console.log(saleId);
            $('.spinner-grow').removeClass('d-none');
            // modal result
            $('.showResult').addClass('d-none');

            $.ajax({
                url: '../php_action/fetchSelectedSale.php',
                type: 'post',
                data: { id: saleId },
                dataType: 'json',
                success: function (response) {
                    // console.log(response);

                    // modal loading
                    $('.spinner-grow').addClass('d-none');
                    // modal result
                    $('.showResult').removeClass('d-none');

                    // modal footer
                    $('#saleDate').datetimepicker('update', response.date)
                    $('#' + response.sale_status).attr("checked", "checked");
                    $('#' + response.sale_status).parent().addClass('active')

                    $('#stockId').val(response.stock_id);

                    if (response.certified == 'on') {
                        $('#yes').attr('checked', 'checked')
                    } else {
                        $('#no').attr('checked', 'checked')
                    }

                    // show/calclulate gross if stockTypes is used gross is shows otherwise hide 
                    if (response.stocktype == 'USED') {
                        $('#grossDiv').removeClass('v-none');
                    } else {
                        $('#grossDiv').addClass('v-none');
                    }
                    $('#salesPerson').val(response.sales_consultant);
                    $('#dealNote').val(response.deal_notes);
                    $('#fname').val(response.fname);
                    $('#mname').val(response.mname);
                    $('#lname').val(response.lname);
                    $('#state').val(response.state);
                    $('#address1').val(response.address1);
                    $('#address2').val(response.address2);
                    $('#city').val(response.city);
                    $('#country').val(response.country);
                    $('#zipCode').val(response.zipcode);
                    $('#mobile').val(response.mobile);
                    $('#altContact').val(response.altcontact);
                    $('#email').val(response.email);
                    $('#profit').val(response.gross);

                    var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Vin: ${response.vin} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} \n Balance: ${response.balance}`;

                    $('#selectedDetails').html(detailsDiv);
                    $('#selectedDetails').addClass('text-center');


                    $('#college').val(response.college);
                    $('#military').val(response.military);
                    $('#loyalty').val(response.loyalty);
                    $('#conquest').val(response.conquest);
                    $('#misc1').val(response.misc1);
                    $('#misc2').val(response.misc2);
                    $('#misc3').val(response.misc3);


                    $('#vincheck').val(response.vin_check);
                    $('#insurance').val(response.insurance);
                    $('#tradeTitle').val(response.trade_title);
                    $('#registration').val(response.registration);
                    $('#inspection').val(response.inspection);
                    $('#salePStatus').val(response.salesperson_status);
                    $('#paid').val(response.paid);




                    $('.selectpicker').selectpicker('refresh')
                    autosize.update($(".autosize"));


                    var checkValue = $('#stockId').val();
                    if (!checkValue) {
                        // if Inventory item was deleted then search from deleted inv data
                        fetchSelectedInvForSearch(response.stock_id);
                    } else {
                        changeRules()
                    }


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


            // ---------------------- Edit Sale---------------------------

            // validateState
            $("#editSaleForm").validate({
                ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
                rules: {
                    saleDate: {
                        required: !0,
                    },
                    stockId: {
                        required: function (params) {
                            if (params.value == 0) {
                                params.classList.add('is-invalid');
                                $('#stockId').selectpicker('refresh');
                                params.classList.add('is-invalid');
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    salesPerson: {
                        required: function (params) {
                            if (params.value == 0) {
                                params.classList.add('is-invalid');
                                $('#salesPerson').selectpicker('refresh');
                                params.classList.add('is-invalid');
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    fname: {
                        required: !0,
                    },
                    lname: {
                        required: !0,
                    },

                    state: {
                        required: function (params) {
                            if (params.value == 0) {
                                params.classList.add('is-invalid');
                                $('#state').selectpicker('refresh');
                                params.classList.add('is-invalid');
                                return true;
                            } else {
                                return false;
                            }
                        },
                    },

                },
                messages: {
                    fname: {
                        required: "",
                    },
                    lname: {
                        required: "",
                    },

                    state: {
                        required: "",
                    },
                },
                submitHandler: function (form, event) {
                    // return true;
                    event.preventDefault();

                    var c = confirm('Do you really want to save this?');
                    if (c == true) {
                        $('[disabled]').removeAttr('disabled');
                        var form = $('#editSaleForm');
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
            });



        });
    }


});


function fetchSelectedInvForSearch(id = null) {
    // console.log(id);
    $.ajax({
        url: '../php_action/fetchSelectedInvForSearch.php',
        type: 'post',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            stockArray.push(response.data);
            var item = response.data;
            var selectBox = document.getElementById('stockId');
            selectBox.innerHTML += `<option value="${item[0]}" selected title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} || Stock Deleted</option>`;
            $('.selectpicker').selectpicker('refresh');
            changeStockDetails({ value: item[0] })
        }

    }); // /ajax function to remove the brand
}


function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
function showDetails(id = null) {

    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedSale.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#saleDate').datetimepicker('update', response.date)

                if (response.sale_status == 'pending') {
                    $('#statusDiv').html(`
                    <label class="btn btn-flat-primary active d-flex align-items-center">
                        <input type="radio" name="status" value="pending" id="pending" checked="checked">
                        <i class="fa fa-clock pr-1"></i> Pending
                    </label>
                    `)
                } else if (response.sale_status == 'delivered') {
                    $('#statusDiv').html(`
                    <label class="btn btn-flat-success active d-flex align-items-center">
                        <input type="radio" name="status" value="delivered" id="delivered" checked="checked">
                        <i class="fa fa-check pr-1"></i> Delivered
                    </label>
                    `)
                } else if (response.sale_status == 'cancelled') {
                    $('#statusDiv').html(`
                        <label class="btn btn-flat-danger active d-flex align-items-center">
                            <input type="radio" name="status" value="cancelled" id="cancelled" checked="checked">
                            <i class="fa fa-times pr-1"></i> Cancelled
                        </label> 
                    `)
                }

                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Vin: ${response.vin} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} \n Balance: ${response.balance} \n ${response.stocktype == "USED" ? `Gross:` + Number(response.gross) : ''} `;


                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');



                if (response.stocktype == "USED") {
                    $('#grossDiv').removeClass('v-none')
                } else {
                    $('#grossDiv').addClass('v-none')
                }


                $('#stockId').val(response.stockno);
                $('#salesPerson').val(response.salesConsultant);
                $('#dealNote').val(response.deal_notes);
                $('#iscertified').val((response.certified == "on" ? "YES" : "NO"));
                $('#fname').val(response.fname);
                $('#mname').val(response.mname);
                $('#lname').val(response.lname);
                $('#state').val(response.state);
                $('#address1').val(response.address1);
                $('#address2').val(response.address2);
                $('#city').val(response.city);
                $('#country').val(response.country);
                $('#zipCode').val(response.zipcode);
                $('#mobile').val(response.mobile);
                $('#altContact').val(response.altcontact);
                $('#email').val(response.email);

                setTimeout(() => {
                    autosize.update($(".autosize"));
                }, 500);

                var url = $('#editBtn').attr("href");
                var domain = url.split('&i=')

                $('#editBtn').attr("href", domain[0] + '&i=' + response.sale_id);


            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }

}

function removeSale(saleId = null) {
    if (saleId) {
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
                    url: '../php_action/removeSale.php',
                    type: 'post',
                    data: { saleId: saleId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageSoldLogsTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });

    }
}

function loadStock() {
    var selectBox = document.getElementById('stockId');
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
            selectBox.innerHTML = `<option value="0" selected disabled>Stock No:</option>`;
            for (var i = 0; i < stockArray.length; i++) {
                // for (var i = 0; i < 3; i++) {
                var item = stockArray[i];
                // console.log(item);
                selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
            }
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
            var selectBox = document.getElementById('salesPerson');
            for (var i = 0; i < saleCnsltntArray.length; i++) {
                var item = saleCnsltntArray[i];
                selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function loadSaleManager() {
    var sales_manager_id = 1;
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: sales_manager_id },
        success: function (response) {
            var array = response.data;
            var selectBoxs = document.getElementsByClassName('salesManagerList');

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


function changeStockDetails(ele) {

    $('#detailsSection').removeClass('d-none');
    let obj = stockArray.find(data => data[0] === ele.value);

    console.log(obj);

    var retail = obj[12];
    retail = parseFloat(retail.replace(/\$|,/g, ''))
    var blnce = obj[11];
    blnce = parseFloat(blnce.replace(/\$|,/g, ''))
    var profit = retail - blnce;
    $('#profit').val(profit.toFixed(2));

    if (obj[13] == 'on') {
        $("#yes").prop("checked", true);
    } else {
        $("#no").prop("checked", true);
    }


    $('#selectedStockType').val(obj[14]); // setting up stockType for sales person Todo

    var detailsDiv = `${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]} \n Vin: ${obj[8]} \n Mileage: ${obj[26] == 1 ? obj[9] : ""} \n Age: ${obj[26] == 1 ? obj[10] : ""} \n Lot: ${obj[26] == 1 ? obj[7] : ""} \n Balance: ${obj[26] == 1 ? obj[11] : ""} ${obj[26] == 2 ? "\n  Stock is Deleted" : ""} `;
    $('#selectedDetails').html(detailsDiv);
    $('#selectedDetails').addClass('text-center');


    $('#grossDiv').removeClass('v-none'); // show gross field on both stock type new / used

    // for checking this stock is already in sale or not if it is in sale then and status is not cancelled then make it red
    if ((obj[16] != null) && obj[16] != 'cancelled') {
        $('#selectedDetails').addClass('text-center text-danger is-invalid');  // invalid selectarea section
        $('#saleDetailsDiv').addClass('is-invalid');  // invalid stock details div
        $('#grossDiv').addClass('v-none');  // hide gross div
        $('#stockId').parent().addClass('text-danger is-invalid'); // invalid stock input div
    } else {
        $('#selectedDetails').removeClass('text-danger is-invalid');  // valid selectarea section
        $('#saleDetailsDiv').removeClass('is-invalid');  // valid stock details div
        $('#stockId').parent().removeClass('text-danger is-invalid'); // valid stock input div
    }

    autosize.update($("#selectedDetails"));
    changeRules()

}

function changeRules() {
    // console.log("function call");
    var eleV = $('#stockId').val();
    if (eleV) {
        let obj = stockArray.find(data => data[0] === eleV);
        console.log(obj);

        var saleDate = $('#saleDate').val();
        saleDate = moment(saleDate).format('MM-DD-YYYY');

        // setting Incentives Rulus
        var startDate = moment(obj[17]).format('MM-DD-YYYY');
        var endDate = moment(obj[18]).format('MM-DD-YYYY');

        var bool1 = moment(saleDate).isBetween
            (startDate, endDate, null, '[]'); // true

        console.log(saleDate);
        console.log("incentive start date", startDate);
        console.log("incentive End date", endDate);
        console.log("incentive Boolean", bool1);

        if (bool1) {

            $('#college').prop("disabled", obj[19] != 'N/A' ? false : true);
            $('#military').prop("disabled", obj[20] != 'N/A' ? false : true);
            $('#loyalty').prop("disabled", obj[21] != 'N/A' ? false : true);
            $('#conquest').prop("disabled", obj[22] != 'N/A' ? false : true);
            $('#misc1').prop("disabled", obj[23] != 'N/A' ? false : true);
            $('#misc2').prop("disabled", obj[24] != 'N/A' ? false : true);
            $('#misc3').prop("disabled", obj[25] != 'N/A' ? false : true);


        } else {

            $('#college').prop("disabled", true);
            $('#military').prop("disabled", true);
            $('#loyalty').prop("disabled", true);
            $('#conquest').prop("disabled", true);
            $('#misc1').prop("disabled", true);
            $('#misc2').prop("disabled", true);
            $('#misc3').prop("disabled", true);
        }

        $('.selectpicker').selectpicker('refresh');

        changeSalesPersonTodo();
    }

}

function changeSalesPersonTodo() {
    var eleV = $('#stockId').val();
    if (eleV) {
        let obj = stockArray.find(data => data[0] === eleV);
        // console.log(obj);

        // sales Person's Todo Rules
        // console.log(obj['spTodoArray']);
        var todoArray = obj['spTodoArray'];
        let state = $('#state').val();
        console.log(todoArray);
        var saleDate = $('#saleDate').val();
        if (state && todoArray && todoArray.length > 0) {

            var spTodoRulesObj = todoArray.find(data => data[4] === state);

            if (spTodoRulesObj) {

                console.log("Data found \n", spTodoRulesObj);
                changeSalesPersonTodoStyle("vincheck", spTodoRulesObj[5]);
                changeSalesPersonTodoStyle("insurance", spTodoRulesObj[6]);
                changeSalesPersonTodoStyle("tradeTitle", spTodoRulesObj[7]);
                changeSalesPersonTodoStyle("registration", spTodoRulesObj[8]);
                changeSalesPersonTodoStyle("inspection", spTodoRulesObj[9]);
                changeSalesPersonTodoStyle("salePStatus", spTodoRulesObj[10]);
                changeSalesPersonTodoStyle("paid", spTodoRulesObj[11]);

            } else {
                changeSalesPersonTodoStyle("vincheck", "N/A");
                changeSalesPersonTodoStyle("insurance", "N/A");
                changeSalesPersonTodoStyle("tradeTitle", "N/A");
                changeSalesPersonTodoStyle("registration", "N/A");
                changeSalesPersonTodoStyle("inspection", "N/A");
                changeSalesPersonTodoStyle("salePStatus", "N/A");
                changeSalesPersonTodoStyle("paid", "N/A");
            }

        } else {
            changeSalesPersonTodoStyle("vincheck", "N/A");
            changeSalesPersonTodoStyle("insurance", "N/A");
            changeSalesPersonTodoStyle("tradeTitle", "N/A");
            changeSalesPersonTodoStyle("registration", "N/A");
            changeSalesPersonTodoStyle("inspection", "N/A");
            changeSalesPersonTodoStyle("salePStatus", "N/A");
            changeSalesPersonTodoStyle("paid", "N/A");

        }

        $(".selectpicker").selectpicker("refresh");

    }

    // var stockType = $('#selectedStockType').val(); // setting stock type
    // var state = $('#state').val();
    // var obj = { id: 'vincheck', value: "" };


    // if (stockType != '' && state != null) {

    //     if (state !== "RI") {
    //         // console.log("Doest need Not RI: ", state);
    //         $('select[name=vincheck]').val('notNeed');
    //         $('#vincheck').selectpicker('refresh');
    //         obj.value = "notNeed";
    //         chnageStyle(obj);
    //         $('select[name=inspection]').val('need');
    //         $('#inspection').selectpicker('refresh');
    //         $('select[name=inspection]').selectpicker('setStyle', 'btn-outline-danger');
    //     }
    //     if ((state === "RI") && stockType === "USED") {
    //         // console.log("CheckTitle:  ", state, stockType);
    //         $('select[name=vincheck]').val('checkTitle');
    //         $('#vincheck').selectpicker('refresh');
    //         obj.value = "checkTitle";
    //         chnageStyle(obj);
    //         $('select[name=inspection]').val('need');
    //         $('#inspection').selectpicker('refresh');
    //         $('select[name=inspection]').selectpicker('setStyle', 'btn-outline-danger');
    //     }
    //     if ((state === "RI") && stockType === "NEW") {
    //         // console.log("Doesn't Need:  ", state, stockType);
    //         $('select[name=vincheck]').val('notNeed');
    //         obj.value = "notNeed";
    //         $('#vincheck').selectpicker('refresh');
    //         chnageStyle(obj);
    //         $('select[name=inspection]').val('notNeed');
    //         $('#inspection').selectpicker('refresh');
    //         $('select[name=inspection]').selectpicker('setStyle', 'btn-outline-success');
    //     }


    // }
}

function changeSalesPersonTodoStyle(elementID, value) {
    if (value !== "N/A") {
        $('#' + elementID).val(value);
        $('#' + elementID).prop("disabled", false);
    } else {
        $(`#${elementID} option:eq(0)`).prop("selected", true);
        $('#' + elementID).prop("disabled", true);
    }

}


function toggleInfo(className) {
    $('.' + className).toggleClass('hidden')
}


function chnageStyle(field) {

    var ele = $(`button[data-id="${field.id}"]`);

    switch (field.id) {

        case 'vincheck':
            if (field.value == 'checkTitle' || field.value == 'need') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;

        case 'insurance':
        case 'tradeTitle':
        case 'inspection':
            if (field.value == 'need') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        case 'registration':
            if (field.value == 'pending') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        case 'salePStatus':
            if (field.value == 'dealWritten') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        case 'paid':
            if (field.value == 'no') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
            }
            break;
        default:
            break;
    }


}
