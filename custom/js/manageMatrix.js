"use strict";
var manageInvTable
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})

$(function () {

    var divRequest = $(".div-request").text();

    if (divRequest == "man") {

        manageInvTable = $("#datatable-1").DataTable({

            responsive: !0,


            'ajax': '../php_action/fetchMatrixs.php',

            // working.... with both
            dom: `\n     
             <'row'<'col-12'P>>\n
            \n     
            <'row'<'col-sm-6 text-center text-sm-left p-3'>
                <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
            <'row'<'col-12'tr>>\n      
            <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n    `,


            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [0, 1, 2]
            },
            "pageLength": 25,

            columnDefs: [
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [0, 1, 2]
                },
                // for hide columns as defaul
                // { 
                //     visible: false, 
                //     targets: [0, 1] 
                // },

            ],
            // select: {
            //     'style': 'multi', // 'single', 'multi', 'os', 'multi+shift'
            //     selector: 'td:first-child',
            // },
            language: {
                searchPanes: {
                    count: "{total} found",
                    countFiltered: "{shown} / {total}"
                }
            },
            "order": [[1, "asc"]]
        })


        $('#MyTableCheckAllButton').click(function () {
            if (manageInvTable.rows({
                selected: true
            }).count() > 0) {
                manageInvTable.rows().deselect();
                return;
            }

            manageInvTable.rows().select();
        });

        manageInvTable.on('select deselect', function (e, dt, type, indexes) {
            if (type === 'row') {
                // We may use dt instead of manageInvTable to have the freshest data.
                if (dt.rows().count() === dt.rows({
                    selected: true
                }).count()) {
                    // Deselect all items button.
                    $('#MyTableCheckAllButton i').attr('class', 'far fa-check-square');
                    return;
                }

                if (dt.rows({
                    selected: true
                }).count() === 0) {
                    // Select all items button.
                    $('#MyTableCheckAllButton i').attr('class', 'far fa-square');
                    return;
                }

                // Deselect some items button.
                $('#MyTableCheckAllButton i').attr('class', 'far fa-minus-square');
            }
        });


        // --------------------- checkboxes query --------------------------------------

        $.fn.dataTable.ext.search.push(
            function (settings, searchData, index, rowData, counter) {
                var tableNode = manageInvTable.table().node();


                var models = [];
                $('input:radio[name="mod"]:checked').map(function () {
                    if ($(this).data('texts') !== "") {
                        var valueArray = $(this).data("texts").split(',');
                        return valueArray.forEach(element => {
                            // console.log("element", element);
                            models.push(element)
                        })
                    }
                });

                // console.log(models);
                if (models.length === 0) {
                    return true;
                }
                if (models.indexOf(searchData[1]) !== -1) {
                    return true;
                }
                if (settings.nTable !== tableNode) {
                    return true;
                }

                return false;
            }
        );



        $.fn.dataTable.ext.search.push(
            function (settings, searchData, index, rowData, counter) {
                var tableNode = manageInvTable.table().node();

                var year = $('input:radio[name="year"]:checked').map(function () {
                    if (this.value !== "") {
                        return this.value;
                    }
                }).get();
                // console.log(year);

                if (year.length === 0) {
                    return true;
                }

                if (year.indexOf(searchData[0]) !== -1) {
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
            })

            manageInvTable.draw();
            manageInvTable.searchPanes.rebuildPane();
        });



    }
    // else if (divRequest == "add") {
    //     $(function () {
    //         $("#importMatrixForm").validate({
    //             rules: {
    //                 excelFile: {
    //                     required: true,
    //                 }
    //             },
    //             messages: {
    //                 excelFile: {
    //                     required: "File must be required",
    //                 }
    //             },
    //             submitHandler: function (form, event) {
    //                 // return true;
    //                 event.preventDefault();

    //                 var allowedFiles = [".xlsx", ".xls", ".csv"];
    //                 var fileUpload = $("#excelFile");
    //                 var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    //                 if (!regex.test(fileUpload.val().toLowerCase())) {
    //                     e1.fire("Files having extensions: " + allowedFiles.join(', ') + " only.");
    //                     return false;
    //                 }


    //                 var form = $('#importMatrixForm');
    //                 var fd = new FormData(document.getElementById("importMatrixForm"));
    //                 fd.append("CustomField", "This is some extra data");


    //                 $.ajax({
    //                     async: false,
    //                     url: form.attr('action'),
    //                     type: 'POST',
    //                     data: fd,
    //                     contentType: false,
    //                     processData: false,
    //                     success: function (response) {
    //                         console.log(response);


    //                         // var ne = window.open('', 'title');
    //                         // ne.document.write(response);

    //                         // response = JSON.parse(response);


    //                         // console.log(response);
    //                         // console.log(response.success);
    //                         if (response.success == true) {

    //                             e1.fire({
    //                                 position: "top-end",
    //                                 icon: "success",
    //                                 title: response.messages,
    //                                 showConfirmButton: !1,
    //                                 timer: 2500,
    //                             })
    //                             form[0].reset();

    //                             if (response.erorStock.length > 0) {
    //                                 var i = 0;
    //                                 $('#errorDiv').removeClass('d-none');
    //                                 // console.log(response.erorStock);
    //                                 while (response.erorStock[i]) {
    //                                     console.log(response.erorStock[i]);
    //                                     document.getElementById('errorList').innerHTML += `
    //                                         <span class="list-group-item list-group-item-danger">
    //                                         ${response.erorStock[i]}
    //                                     </span> `;
    //                                     i++;
    //                                 }

    //                             }

    //                         } else {

    //                             e1.fire({
    //                                 position: "top-end",
    //                                 icon: "error",
    //                                 title: response.erorStock,
    //                                 showConfirmButton: !1,
    //                                 timer: 2500
    //                             })
    //                         }


    //                     },
    //                     error: function (e) {
    //                         console.log(e);
    //                     }
    //                 });
    //                 return false;
    //                 // return true;

    //             }
    //         })
    //     })
    // }

});

function showDetails(id = null) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedMatrixDetails.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                // console.log(response);


                $('#title').html(`${response.year} ${response.model} ${response.trim} <br /> ${response.model_code}`);
                $('#net').html("$" + response.net);
                $('#hb').html("$" + response.hb);
                $('#invoice').html("$" + response.invoice);
                $('#msrp').html("$" + response['m.s.r.p']);
                $('#bdc').html("$" + response.bdc);

                $('#dealer').html("$" + ((response.dealer) ? response.dealer : ""));
                $('#other').html("$" + ((response.other) ? response.other : ""));
                $('#lease').html("$" + ((response.lease) ? response.lease : ""));


                var f_status = false;
                var l_status = false;

                var now = moment(new Date()); //todays date
                var end = moment(response['f_expire']); // another date 
                var duration = moment.duration(end.diff(now));
                var days = duration.asDays();
                days = Math.ceil(days);

                if (days >= 0) {
                    f_status = true;
                }else{
                     f_status = false;                    
                }
                
                var lend = moment(response['lease_expire']); // another date
                var lduration = moment.duration(lend.diff(now));
                var ldays = lduration.asDays();
                ldays = Math.ceil(ldays);
                
                if (ldays >= 0) {
                    l_status = true;
                }else{
                    l_status = false;                    
                }



                $('#f_24_36').html(( f_status && response['f_24-36']) ? response['f_24-36'] : "");
                $('#f_37_48').html((f_status && response['f_37-48']) ? response['f_37-48'] : "");
                $('#f_49_60').html((f_status && response['f_49-60']) ? response['f_49-60'] : "");
                $('#f_61_72').html((f_status && response['f_61-72']) ? response['f_61-72'] : "");


                $('#f_610_24_36').html(( f_status && response['f_659_610_24-36']) ? response['f_659_610_24-36'] : "");
                $('#f_610_37_60').html(( f_status && response['f_659_610_37-60']) ? response['f_659_610_37-60'] : "");
                $('#f_610_61_72').html(( f_status && response['f_659_610_61-72']) ? response['f_659_610_61-72'] : "");
                $('#f_expire').html(( response['f_expire']) ? moment(response['f_expire']).format('MM-DD-YYYY') : "");

                $('#l_onePay').html(( l_status && response['lease_one_pay_660']) ? response['lease_one_pay_660'] : "");
                $('#l_24_36').html(( l_status && response['lease_660']) ? response['lease_660'] : "");


                $('#l_610_onePay').html(( l_status && response['lease_one_pay_659_610']) ? response['lease_one_pay_659_610'] : "");
                $('#l_610_24_36').html(( l_status && response['lease_659_610']) ? response['lease_659_610'] : "");
                $('#l_expire').html(( response['lease_expire']) ? moment(response['lease_expire']).format('MM-DD-YYYY') : "");


                var miles = 24;
                for (var i = 0; i < 13; i++) {
                    // first fill all 15,000 values
                    $('#15_' + miles).html((response[miles]) ? response[miles] + '%' : "");

                    if (miles >= 24 && miles <= 33) {
                        var addittion_in_10k = (response['10_24_33']) ? response['10_24_33'] : 0;
                        var addittion_in_12k = (response['12_24_33']) ? response['12_24_33'] : 0;

                        $('#10_' + miles).html((response[miles]) ? (parseInt(response[miles]) + parseInt(addittion_in_10k)) + '%' : "");
                        $('#12_' + miles).html((response[miles]) ? (parseInt(response[miles]) + parseInt(addittion_in_12k)) + '%' : "");

                    } else if (miles >= 36 && miles <= 48) {

                        var addittion_in_10k = (response['10_36_48']) ? response['10_36_48'] : 0;
                        var addittion_in_12k = (response['12_36_48']) ? response['12_36_48'] : 0;

                        $('#10_' + miles).html((response[miles]) ? ((parseInt(response[miles]) + parseInt(addittion_in_10k)) + '%') : "");
                        $('#12_' + miles).html((response[miles]) ? ((parseInt(response[miles]) + parseInt(addittion_in_12k)) + '%') : "");
                    } else {
                        $('#10_' + miles).html((response[miles]) ? response[miles] + '%' : "");
                        $('#12_' + miles).html((response[miles]) ? response[miles] + '%' : "");
                    }

                    miles += 3;
                }


            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }
}

function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
function clearErrorsList() {
    $('#errorList').html('');
}