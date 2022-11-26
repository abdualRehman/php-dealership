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

        $('.nav-link').removeClass('active');
        $('#matrixPage').addClass('active');

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
                columns: [0, 1, 3]
            },
            "pageLength": 25,

            columnDefs: [
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [0, 1, 3]
                },
                // for hide columns as defaul
                {
                    targets: [3],
                    visible: false,
                },
                {
                    targets: 8,
                    className: 'manBDC',
                },
                {
                    targets: [0, 1, 2],
                    className: 'font-weight-bolder'
                }
                // {
                //     targets: [0],
                //     render: function (data, type, row, meta) {
                //         return `<a href='#' data-toggle="modal" data-target="#showDetails" onclick="showDetails(${row[9]})" >${data}</a>`;
                //     },
                // }

            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr({
                    "data-toggle": "modal",
                    "data-target": "#showDetails",
                    "onclick": "showDetails(" + data[9] + ")"
                });
            },
            language: {
                searchPanes: {
                    count: "{total} found",
                    countFiltered: "{shown} / {total}"
                }
            },
            "order": [[1, "asc"]]
        })



        // --------------------- checkboxes query --------------------------------------

        $.fn.dataTable.ext.search.push(
            function (settings, searchData, index, rowData, counter) {
                var tableNode = manageInvTable.table().node();


                var models = [];
                $('input:radio[name="mod"]:checked').map(function () {
                    if ($(this).data('texts') !== "") {
                        var valueArray = $(this).data("texts").split(',');
                        return valueArray.forEach(element => {
                            element = String(element).toLowerCase();
                            models.push(element)
                        })
                    }
                });

                // console.log(models);
                if (models.length === 0) {
                    return true;
                }
                let search = String(searchData[1]).toLowerCase();
                if (models.indexOf(search) !== -1) {
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
            });
            manageInvTable.draw();
            manageInvTable.searchPanes.rebuildPane();
        });
    }

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
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                // console.log(response);


                $('#title').html(`${response.year} ${response.model} ${response.trim} <br /> ${response.model_code}`);


                $('#net').html("$" + Number(Number(response.net).toFixed(2)).toFixed(2).toLocaleString("en-US"));
                $('#hb').html("$" + Number(Number(response.hb).toFixed(2)).toFixed(2).toLocaleString("en-US"));
                $('#invoice').html("$" + Number(Number(response.invoice).toFixed(2)).toFixed(2).toLocaleString("en-US"));
                $('#msrp').html("$" + Number(Number(response['m.s.r.p']).toFixed(0)).toLocaleString("en-US"));
                $('#bdc').html("$" + Number(Number(response.bdc).toFixed(0)).toLocaleString("en-US"));


                var f_status = false;
                var l_status = false;
                var c_status = false;
                var r_status = false;

                var now = moment(new Date(new Date().toLocaleString('en', {timeZone: 'America/New_York'}))); //todays date
                var end = moment(response['f_expire']); // another date 
                var duration = moment.duration(end.diff(now));
                var days = duration.asDays();
                days = Math.ceil(days);

                if (days >= 0) {
                    f_status = true;
                } else {
                    f_status = false;
                }

                var lend = moment(response['lease_expire']); // another date
                var lduration = moment.duration(lend.diff(now));
                var ldays = lduration.asDays();
                ldays = Math.ceil(ldays);

                if (ldays >= 0) {
                    l_status = true;
                } else {
                    l_status = false;
                }

                var cend = moment(response['expire_in']); // another date
                var cduration = moment.duration(cend.diff(now));
                var cdays = cduration.asDays();
                cdays = Math.ceil(cdays);

                if (cdays >= 0) {
                    c_status = true;
                } else {
                    c_status = false;
                }
                var rend = moment(response['residual_expire']); // another date
                var rduration = moment.duration(rend.diff(now));
                var rdays = rduration.asDays();
                rdays = Math.ceil(rdays);

                if (rdays >= 0) {
                    r_status = true;
                } else {
                    r_status = false;
                }


                $('#dealer').html("$" + ((c_status && response.dealer) ? Number(response.dealer).toLocaleString("en-US") : ""));
                $('#other').html("$" + ((c_status && response.other) ? Number(response.other).toLocaleString("en-US") : ""));
                $('#lease').html("$" + ((c_status && response.lease) ? Number(response.lease).toLocaleString("en-US") : ""));



                $('#f_24_36').html((f_status && response['f_24-36']) ? response['f_24-36'] : "");
                $('#f_37_48').html((f_status && response['f_37-48']) ? response['f_37-48'] : "");
                $('#f_49_60').html((f_status && response['f_49-60']) ? response['f_49-60'] : "");
                $('#f_61_72').html((f_status && response['f_61-72']) ? response['f_61-72'] : "");


                $('#f_610_24_36').html((f_status && response['f_659_610_24-36']) ? response['f_659_610_24-36'] : "");
                $('#f_610_37_60').html((f_status && response['f_659_610_37-60']) ? response['f_659_610_37-60'] : "");
                $('#f_610_61_72').html((f_status && response['f_659_610_61-72']) ? response['f_659_610_61-72'] : "");
                $('#f_expire').html((response['f_expire']) ? moment(response['f_expire']).format('MM-DD-YYYY') : "");

                $('#l_onePay').html((l_status && response['lease_one_pay_660']) ? response['lease_one_pay_660'] : "");
                $('#l_24_36').html((l_status && response['lease_660']) ? response['lease_660'] : "");


                $('#l_610_onePay').html((l_status && response['lease_one_pay_659_610']) ? response['lease_one_pay_659_610'] : "");
                $('#l_610_24_36').html((l_status && response['lease_659_610']) ? response['lease_659_610'] : "");
                $('#l_expire').html((response['lease_expire']) ? moment(response['lease_expire']).format('MM-DD-YYYY') : "");


                var miles = 24;
                for (var i = 0; i < 13; i++) {
                    // first fill all 15,000 values
                    $('#15_' + miles).html((r_status && response[miles]) ? response[miles] + '%' : "");

                    if (miles >= 24 && miles <= 33) {
                        var addittion_in_10k = (r_status && response['10_24_33']) ? response['10_24_33'] : 0;
                        var addittion_in_12k = (r_status && response['12_24_33']) ? response['12_24_33'] : 0;

                        $('#10_' + miles).html((r_status && response[miles]) ? (parseInt(response[miles]) + parseInt(addittion_in_10k)) + '%' : "");
                        $('#12_' + miles).html((r_status && response[miles]) ? (parseInt(response[miles]) + parseInt(addittion_in_12k)) + '%' : "");

                    } else if (miles >= 36 && miles <= 48) {

                        var addittion_in_10k = (r_status && response['10_36_48']) ? response['10_36_48'] : 0;
                        var addittion_in_12k = (r_status && response['12_36_48']) ? response['12_36_48'] : 0;

                        $('#10_' + miles).html((r_status && response[miles]) ? ((parseInt(response[miles]) + parseInt(addittion_in_10k)) + '%') : "");
                        $('#12_' + miles).html((r_status && response[miles]) ? ((parseInt(response[miles]) + parseInt(addittion_in_12k)) + '%') : "");
                    } else {
                        $('#10_' + miles).html((r_status && response[miles]) ? response[miles] + '%' : "");
                        $('#12_' + miles).html((r_status && response[miles]) ? response[miles] + '%' : "");
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

function showError() {
    Swal.fire("Not Found!", "This File is not uploaded yet.", "error");
}

function loadPDF(URL) {
    var nURL = 'http://docs.google.com/gview?url=' + URL + '&embedded=true';
    window.open(nURL, 'pdf'); // _blank
}

