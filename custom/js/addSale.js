"use strict";

var stockArray = [];
$(function () {
    $('.nav-link').removeClass('active');
    $('#addSalePage').addClass('active');

    loadStock();
    loadSaleConsultant();
    loadSaleManager();
    loadFinanceManager();

    autosize($(".autosize"));
    var today = new Date(new Date().toLocaleString('en', { timeZone: 'America/New_York' }));
    $("#saleDate").datetimepicker({
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        startDate: today,
        language: 'pt-BR',
        format: 'mm-dd-yyyy hh:ii',
        defaultDate: new Date(new Date().toLocaleString('en', { timeZone: 'America/New_York' })),

        // to disable time picker
        minView: 2,
        pickTime: false,

    });
    $("#reconcileDate").datepicker({
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        startDate: today,
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
    });
    $('#saleDate').datetimepicker('update', new Date(new Date().toLocaleString('en', { timeZone: 'America/New_York' })));


    var target = document.querySelector('#stockId').nextElementSibling;
    var stockIdTempValue = '';
    // Create a new observer instance
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'aria-expanded') {
                if ($(target).attr('aria-expanded') == 'false') {
                    let id = $('#stockId').val();
                    if (id != stockIdTempValue) {
                        changeStockDetails({ value: `${id}` });
                        stockIdTempValue = id;
                    }
                }
            }
        });
    });

    var config = { attributes: true, subtree: true };
    observer.observe(target, config);












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
                        form[0].reset();
                        $("#showDetails").modal('hide');
                        // loadStock();
                        // let { id } = response;
                        // setTimeout(() => {
                        //     $('#stockId').val(id);
                        //     $('#stockId').selectpicker('refresh');
                        //     $('#stockId').selectpicker('toggle');
                        //     changeStockDetails({ value: `${id}` });
                        // }, 2000);

                        var { id } = response;
                        $.when(loadStock()).then(() => {
                            setTimeout(() => {
                                writeNewHTMLOptions('stockId', stockArray, id);
                                $('#stockId').val(id);
                                $('#stockId').selectpicker('refresh');
                                $('#stockId').selectpicker('toggle');
                                console.log(id);
                                changeStockDetails({ value: `${id}` });
                            }, 2000);
                        });

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

                        // form[0].reset();
                        setTimeout(() => {
                            var siteURL = localStorage.getItem('siteURL');
                            console.log(siteURL);
                            if (siteURL != null) {
                                window.location.href = siteURL + '/sales/soldLogs.php?r=man&filter=today';
                            }
                        }, 1000);

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

$.fn.selectpicker.Constructor.DEFAULTS.virtualScroll = true;

// function loadStock() {
//     var selectBox = document.getElementById('stockId');
//     $.ajax({
//         url: '../php_action/fetchInvForSearch.php',
//         type: "POST",
//         dataType: 'json',
//         beforeSend: function () {
//             // selectBox.setAttribute("disabled", true);
//         },
//         success: function (response) {
//             stockArray = response.data;
//             // console.log(stockArray);
//             // console.log(selectBox);
//             selectBox.innerHTML = `<option value="0" selected disabled>Stock No:</option>`;
//             for (var i = 0; i < stockArray.length; i++) {
//                 // for (var i = 0; i < 3; i++) {
//                 var item = stockArray[i];
//                 // console.log(item);
//                 selectBox.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
//             }
//             // selectBox.removeAttribute("disabled");
//             $('.selectpicker').selectpicker('refresh');

//             $('#stockId').selectpicker('toggle');
//             $('#stockId').selectpicker('refresh');
//             $("#_").focus().val('').val("_");
//         }
//     });
// }
function loadStock() {
    var selectBoxes = document.getElementsByClassName('stockno');
    selectBoxes.forEach(element => {
        element.innerHTML = ``;
    });
    $('.selectpicker').selectpicker('refresh');
    $.ajax({
        url: '../php_action/fetchInvForSearch.php',
        type: "POST",
        dataType: 'json',
        success: function (response) {

            stockArray = response.data;
            // console.log(stockArray);
            selectBoxes.forEach((element, index) => {
                // for (var i = 0; i < stockArray.length; i++) {
                writeHTMLOptions(element, stockArray);

                const scroller = new InfiniteScroller(element, stockArray);
                scroller.setScroller(element, stockArray);

            });

            // selectBox.removeAttribute("disabled");
            $('.selectpicker').selectpicker('refresh');

            $('#stockId').selectpicker('toggle');
            $('#stockId').selectpicker('refresh');
            $("#_").focus().val('').val("_");
        }
    });
}

function writeHTMLOptions(element, stockArray) {
    for (var i = 0; i < 5; i++) {
        var item = stockArray[i];
        // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
        $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
    }
}
function writeNewHTMLOptions(element, stockArray, searchID) {
    let item = stockArray.find((e) => e[0] == searchID);
    $(`#${element} .stockno`).prepend(`<option value="${item[0]}" data-scroll-index="-1" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
}

class InfiniteScroller {
    element;
    stockArray;
    constructor(element, stockArray) {
        this.element = element;
        this.stockArray = stockArray;
    }
    setScroller(element, array) {
        var p = element.parentElement.id;


        // var Selectpicker = $('.selectpicker').data('selectpicker');
        var Selectpicker = $('#' + p).data('selectpicker');

        Selectpicker?.$menuInner?.on('scroll', function () {

            if (Selectpicker?.$searchbox?.val() == '') {

                var scrollTop = Selectpicker?.selectpicker?.view?.scrollTop;
                // if within 100px of the bottom of the menu, load more options
                if ($(this)[0].scrollHeight - Selectpicker.sizeInfo.menuInnerHeight - scrollTop < 10) {

                    var optionDiv = Selectpicker?.$element[0];
                    var targetChildElement = $(optionDiv).children('optgroup')[0].lastChild;
                    var lastArrayIndex = $(targetChildElement).data('scroll-index') + 1;
                    // console.log(lastArrayIndex);
                    if (array.length > lastArrayIndex) {
                        for (let j = lastArrayIndex; j < lastArrayIndex + 10; j++) {
                            var item = array[j];
                            if (item) {
                                // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${j}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
                                $(element).append(`<option value="${item[0]}" data-scroll-index="${j}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
                            }
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                }
            }
        });

        Selectpicker?.$searchbox.on('input', function () {
            var search = this.value;

            if (search != '' && search.length > 3) {
                element.innerHTML = '';
                $('.dropdown-menu.show').block();
                const results = array.filter(element => {
                    var stock = (element[1]).toLowerCase();
                    var model = (element[4]).toLowerCase();
                    var vin = (element[8]).toLowerCase();
                    return stock.indexOf(search.toLowerCase()) >= 0 || vin.indexOf(search.toLowerCase()) >= 0 || model.indexOf(search.toLowerCase()) >= 0;
                });
                results.forEach((item, i) => {
                    // element.innerHTML += `<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`;
                    $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
                });
                $('.dropdown-menu.show').unblock();
                $('.selectpicker').selectpicker('refresh');
            } else if (search == '') {
                element.innerHTML = '';
                $('.dropdown-menu.show').block();
                for (var i = 0; i < 5; i++) {
                    var item = array[i];
                    $(element).append(`<option value="${item[0]}" data-scroll-index="${i}" title="${item[1]}">${item[1]} || ${item[4]} ||  ${item[8]} </option>`);
                }
                $('.dropdown-menu.show').unblock();
                $('.selectpicker').selectpicker('refresh');
            }
            $('.dropdown-menu.show').unblock();

        });
    }
}






// ---------------------------    // --------- // ------------------- //






$('#salesPerson').on('change', function () {
    $('#fname').focus();
})


function loadSaleConsultant() {
    // var sales_consultant_id = 66;
    var sales_consultant_id = Number(localStorage.getItem('salesConsultantID'));
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

function loadFinanceManager() {
    // var finance_manager_id = 64; //finance manager role id in database
    var finance_manager_id = Number(localStorage.getItem('financeManagerID'));
    $.ajax({
        url: '../php_action/fetchUsersWithRoleForSearch.php',
        type: "POST",
        dataType: 'json',
        data: { id: finance_manager_id },
        success: function (response) {
            var array = response.data;
            var selectBoxs = document.getElementById('financeManager');
            for (var i = 0; i < array.length; i++) {
                var item = array[i];
                selectBoxs.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]} || ${item[2]} </option>`;
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}


function loadSaleManager() {
    // var sales_manager_id = 67;
    var sales_manager_id = Number(localStorage.getItem('salesManagerID'));
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

function changeReconcile() {
    if (!$('#reconcileDate').attr('disabled')) {
        $('#reconcileDate').val('')
    }
    $('#reconcileDate').attr('disabled', function (_, attr) { return !attr });
}

function changeStockDetails(ele) {
    console.log(ele.value);
    if (ele?.value && ele?.value != 'null') {
        $('#detailsSection').removeClass('d-none');
        let obj = stockArray.find(data => data[0] === ele.value);
        // console.log(obj);
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

        var detailsDiv = `${obj[14]} ${obj[2]} ${obj[3]} ${obj[4]} \n Vin: ${obj[8]} \n Mileage: ${obj[9]} \n Age: ${obj[10]} \n Lot: ${obj[7]}  ${($('#vgb').val() == "true") ? `\n Balance: ${obj[11]}` : ``} `;
        $('#selectedDetails').html(detailsDiv);
        $('#selectedDetails').addClass('text-center');


        if (obj[32] == 'true') {
            $('#codp_warn').removeClass('d-none');
        } else {
            $('#codp_warn').addClass('d-none');
        }
        if (obj[33] == 'true') {
            $('#lwbn_warn').removeClass('d-none');
        } else {
            $('#lwbn_warn').addClass('d-none');
        }


        if ($('#vgb').val() == "true") {
            $('#grossDiv').removeClass('v-none'); // show gross field on both stock type new / used
        }


        // for checking this stock is already in sale or not if it is in sale then and status is not cancelled then make it red
        if ((obj[16].length > 0) && obj[16].every(element => element != 'cancelled')) {
            $('#selectedDetails').addClass('text-center text-danger is-invalid');  // invalid selectarea section
            $('#saleDetailsDiv').addClass('is-invalid');  // invalid stock details div
            // $('#grossDiv').addClass('v-none');  // hide gross div
            $('#stockId').parent().addClass('text-danger is-invalid'); // invalid stock input div
        } else {
            $('#selectedDetails').removeClass('text-danger is-invalid');  // valid selectarea section
            $('#saleDetailsDiv').removeClass('is-invalid');  // valid stock details div
            $('#stockId').parent().removeClass('text-danger is-invalid'); // valid stock input div
        }

        autosize.update($("#selectedDetails"));
        changeRules();

        setTimeout(() => {
            $('#salesPerson').selectpicker('toggle');
            $('#salesPerson').selectpicker('refresh');
            $("#_").focus().val('').val("_");
        }, 500);
    }

}

function changeRules() {
    var eleV = $('#stockId').val();
    if (eleV) {
        let obj = stockArray.find(data => data[0] === eleV);

        // console.log(obj);

        chnageIncentiveStatus(obj[17], obj[18], 'college');
        chnageIncentiveStatus(obj[19], obj[20], 'military');
        chnageIncentiveStatus(obj[21], obj[22], 'loyalty');
        chnageIncentiveStatus(obj[23], obj[24], 'conquest');
        chnageIncentiveStatus(obj[25], obj[26], 'misc1');
        chnageIncentiveStatus(obj[27], obj[28], 'misc2');
        chnageIncentiveStatus(obj[29], obj[30], 'leaseLoyalty');

        if (obj[17] != 'N/A' || obj[19] != 'N/A' || obj[21] != 'N/A' || obj[23] != 'N/A' || obj[25] != 'N/A' || obj[27] != 'N/A' || obj[29] != 'N/A') {
            $('#loadIncentivesDiv').removeClass('hidden');
        } else {
            $('#loadIncentivesDiv').addClass('hidden');
        }

        $('.selectpicker').selectpicker('refresh');
        changeSalesPersonTodo();

    }

}

function chnageIncentiveStatus(value, date, element) {
    if (value != 'N/A') {
        var saleDate = $('#saleDate').val();
        saleDate = moment(saleDate).format('MM-DD-YYYY');

        var edate = moment(date);
        var cduration = moment.duration(edate.diff(saleDate));
        var cdays = cduration.asDays();
        cdays = Math.ceil(cdays);

        if (element == 'misc1') {
            if (cdays >= 0) {
                $('#misc1').val("Yes");
            } else {
                $('#misc1').val("No");
            }
            $(".selectpicker").selectpicker("refresh");
        }

        if (cdays >= 0) {
            $('#' + element).prop("disabled", false);
            $('#' + element + '_v').html('$' + value);
        } else {
            $('#' + element).prop("disabled", true);
        }
    } else {
        $('#' + element).prop("disabled", true);
        $('#' + element + '_v').html('');
        $('#misc1').val("No");
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
        // console.log(todoArray);
        var saleDate = $('#saleDate').val();
        if (state && todoArray && todoArray.length > 0) {

            var spTodoRulesObj = todoArray.find(data => data[4] === state);

            if (spTodoRulesObj) {

                // console.log("Data found \n", spTodoRulesObj);
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
                ele.removeClass('btn-outline-primary');
            } else if (field.value == 'done') {
                ele.addClass('btn-outline-primary');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-primary');
            }
            break;
        case 'salePStatus':
            if (field.value == 'cancelled') {
                ele.addClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
                ele.removeClass('btn-outline-primary');
            } else if (field.value == 'dealWritten' || field.value == 'contracted' || field.value == 'gmdSubmit') {
                ele.addClass('btn-outline-primary');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-success');
            } else {
                ele.addClass('btn-outline-success');
                ele.removeClass('btn-outline-danger');
                ele.removeClass('btn-outline-primary');
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

