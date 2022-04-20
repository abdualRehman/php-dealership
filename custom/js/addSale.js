"use strict";

var stockArray = [];
$(function () {

    loadStock();
    loadSaleConsultant();
    loadSaleManager();

    autosize($(".autosize"));
    var today = new Date();
    $("#saleDate").datetimepicker({
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        startDate: today,
        language: 'pt-BR',
        format: 'mm-dd-yyyy hh:ii',
        defaultDate: new Date(),

        // to disable time picker
        minView: 2,
        pickTime: false,

    });
    $('#saleDate').datetimepicker('update', new Date())


    var e1 = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-label-success btn-wide mx-1",
            cancelButton: "btn btn-label-danger btn-wide mx-1",
        },
        buttonsStyling: !1,
    });

    var isStockTypeNew = false;

    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        // I use element.value instead value here, value parameter was always null
        // console.log(element.value);

        if (element.value === 'NEW') {
            isStockTypeNew = true;
        } else {
            isStockTypeNew = false;
        }

        return arg != element.value;
    }, "Value must not equal arg.");


    $.validator.addMethod("requireAndopenDiv", function (value, ele, arg) {
        if (value == "") {
            $('#pbody').removeClass('hidden')
            return false;
        } else {
            return true;
        }
    }, "This field is required.");

    // ---------------------- ADD NEW Stock---------------------------
    $("#addInvForm").validate({
        rules: {
            stockno: {
                required: !0,
            },
            year: {
                required: !0,
                number: !0,
            },
            make: {
                required: !0,
            },
            model: {
                required: !0,
            },

            stockType: {
                valueNotEquals: "0",
            },

            modelno: {
                required: function (params) {
                    if (isStockTypeNew == true) {
                        return true;
                    } else {
                        return false;
                    }
                },
            }

        },
        messages: {
            stockType: {
                valueNotEquals: "Please select Stock Type",
            },
            year: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            mileage: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            age: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            balance: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            retail: {
                required: "This field is required.",
                number: "Please enter a valid number",
            }


        },
        submitHandler: function (form, event) {
            event.preventDefault();

            var c = confirm('Do you really want to save this?');
            if (c == true) {
                var form = $('#addInvForm');
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

                            // form[0].reset();
                            loadStock();

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
    // ---------------------- ADD Sale---------------------------

    // validateState
    $("#addSaleForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            saleDate: {
                required: !0,
            },
            stockId: {
                // required: !0,
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
                // required: !0,
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
                // validateState: "0",
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

                var form = $('#addSaleForm');
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

                            form[0].reset();

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

    var detailsDiv = `${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]} \n Vin: ${obj[8]} \n Mileage: ${obj[9]} \n Age: ${obj[10]} \n Lot: ${obj[7]} \n Balance: ${obj[11]}`;
    $('#selectedDetails').html(detailsDiv);
    $('#selectedDetails').addClass('text-center');



    $('#grossDiv').removeClass('v-none');  // show gross field on both stock type new / used

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
    var eleV = $('#stockId').val();
    if (eleV) {
        let obj = stockArray.find(data => data[0] === eleV);

        var saleDate = $('#saleDate').val();
        saleDate = moment(saleDate).format('MM-DD-YYYY');


        // setting Incentives Rulus
        var startDate = moment(obj[17]).format('MM-DD-YYYY');
        var endDate = moment(obj[18]).format('MM-DD-YYYY');

        var bool1 = moment(saleDate).isBetween
            (startDate, endDate, null, '[]'); // true

        // console.log(saleDate);
        // console.log("incentive start date", startDate);
        // console.log("incentive End date", endDate);
        // console.log("incentive Boolean", bool1);

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
        if (state && todoArray.length > 0) {

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

