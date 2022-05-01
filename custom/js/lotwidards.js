
"use strict";
var manageInvTable, TableData, maxFileLimit = 10, rowGroupSrc = 4; // 17; // wholesale 
var manageCarDealersTable;
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
    $('#lotWizars').addClass('active');

    $("#repairSent").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });
    $("#dateSent").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });
    $("#dateReturn").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });
    $("#repairReturn").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,

    });

    $("#repais").select2({
        dropdownAutoWidth: !0,
        placeholder: "Select Repairs",
        allowClear: !0,
        tags: !0
    })
    var $select = $("#bodyshop").select2({
        dropdownAutoWidth: !0,
        placeholder: "Select Repairs",
        allowClear: !0,
        maximumSelectionLength: 1
    })
    autosize($(".autosize"));

    $('.slick-2').each(function () {
        var slider = $(this);
        slider.slick({
            infinite: false,
            autoplay: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            responsive: [{
                breakpoint: 600, // 500
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
            ]
        });
        var sLightbox = $(this);
        sLightbox.slickLightbox({
            src: 'src',
            imageMaxHeight: 9,
            itemSelector: '.carousel-item img'
        });
    })

    manageInvTable = $("#datatable-1").DataTable({

        responsive: !0,
        'ajax': '../php_action/fetchInspections.php',
        "paging": true,
        "scrollX": true,
        "orderClasses": false,
        "deferRender": true,
        "pageLength": 25,

        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [6, 5, 4]
        },

        columnDefs: [
            {
                targets: [1, 2, 3, 4],
                visible: false,
            },
            {
                targets: [5],
                createdCell: function (td, cellData, rowData, row, col) {
                    var data = $(td).html();
                    if (data > 4) {
                        $(td).addClass('h5 font-weight-bolder text-danger');
                    } else {
                        $(td).addClass('font-weight-bold p');
                    }
                }
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [6, 5, 4]
            },
        ],
        dom: `\n     
                 <'row'<'col-12'P>>\n
                \n     
                <'row'<'col-sm-6 text-left text-sm-left pl-3'f>
                    <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'>>\n
                <'row'<'col-12'tr>>\n      
                <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

        rowGroup: {
            // dataSrc: 4,
            dataSrc: rowGroupSrc,
            // enable: false,
            startRender: function (rows, group) {
                var collapsed = !!collapsedGroups[group];

                // console.log(rows);
                // rows.nodes().each(function (r) {
                //     r.style.display = collapsed ? 'none' : '';
                // });
                // if (rowGroupSrc == 17) {
                //     if (group == "No") {
                //         group = "Retail";
                //     } else if (group == "Yes") {
                //         group = "Wholesale";
                //     }
                // }
                // // Add category name to the <tr>. NOTE: Hardcoded colspan
                // return $('<tr/>')
                //     .append('<td colspan="17">' + group + ' (' + rows.count() + ')</td>')
                //     .attr('data-name', group)
                //     .toggleClass('collapsed', collapsed);

                // -------------  For Display All Number of Filtered Rows -----------------

                var filteredData = $('#datatable-1').DataTable()
                    .rows({ search: 'applied' })
                    .data()
                    .filter(function (data, index) {
                        return data[rowGroupSrc] == group ? true : false;
                    });
                if (rowGroupSrc == 17) {
                    if (group == "No") {
                        group = "Retail";
                    } else if (group == "Yes") {
                        group = "Wholesale";
                    }
                }

                return $('<tr/>')
                    .append('<td colspan="17">' + group + ' (' + filteredData.length + ')</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
        },
        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {
                var obj = json.totalNumber;
                for (const [key, value] of Object.entries(obj)) {
                    $(`input[name='mod'][value='${key}']`).next().next().html(value)
                }
            }
        },
        "order": [[4, "desc"]],
    });


    // --------------------- checkboxes query --------------------------------------

    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageInvTable.table().node();

            var activebtnvalue = $("#mods .btn.active input[name='mod']").val();
            // console.log(activebtnvalue);

            if (activebtnvalue == 'notTouched') {
                // console.log(rowData);
                var balance = rowData[13];
                var recon = rowData[1];
                var notes = rowData[3];

                var windshield = rowData[18];
                var arr = windshield ? (windshield.trim()).split('__') : [];
                var doneEle = arr.find((element) => {
                    return element != "Done";
                })

                var wheels = rowData[19];
                var arrWheels = wheels ? (wheels.trim()).split('__') : [];
                var doneEleWheel = arrWheels.find((element) => {
                    return element != "Done";
                })


                var repairs = rowData[20];
                var repairArr = repairs ? (repairs.trim()).split('__') : [];
                var repairSent = rowData[21];

                // not touched old 
                // if (recon == "" || recon == null || notes == null || notes == "" || doneEleWheel || doneEle || repairArr.length == 0) {
                //     return true;
                // }

                if ((recon == "" || recon == null) && repairArr.length == 0) {
                    return true;
                }
            }
            if (activebtnvalue == 'holdForRecon') {

                var balance = rowData[13];
                var recon = rowData[1];
                if (recon == 'hold' && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'sendToRecon') {

                var balance = rowData[13];
                var recon = rowData[1];
                if (recon == 'send' && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'LotNotes') {

                var notes = rowData[3];
                if (notes) {
                    return true;
                }
            }
            if (activebtnvalue == 'windshield') {
                var windshield = rowData[18];
                var arr = windshield ? (windshield.trim()).split('__') : [];
                // console.log(arr);
                var doneEle = arr.find((element) => {
                    return element == "Done";
                })
                if (doneEle) {
                    return true;
                }
            }
            if (activebtnvalue == 'wheels') {
                var wheels = rowData[19];
                var arr = wheels ? (wheels.trim()).split('__') : [];
                // console.log(arr);
                var doneEle = arr.find((element) => {
                    return element == "Done";
                })
                if (doneEle) {
                    return true;
                }
            }
            if (activebtnvalue == 'toGo') {
                var repairs = rowData[20];
                var repairSent = rowData[21];
                var arr = repairs ? (repairs.trim()).split('__') : [];
                if (arr.length > 0 && (repairSent == "" || repairSent == null)) {
                    return true;
                }
            }
            if (activebtnvalue == 'atBodyshop') {
                var repairs = rowData[20];
                var repairSent = rowData[21];
                var arr = repairs ? (repairs.trim()).split('__') : [];
                if (arr.length > 0 && repairSent) {
                    return true;
                }
            }
            if (activebtnvalue == 'backFromBodyshop') {
                var repairSent = rowData[21];
                var repairReturned = rowData[22];
                if (repairReturned && repairSent) {
                    return true;
                }
            }
            if (activebtnvalue == 'retailReady') {
                var recon = rowData[1];
                if (recon == 'sent') {
                    return true;
                }
            }
            if (activebtnvalue == 'Gone') {
                var balance = rowData[13];
                if (balance == '' || balance == null) {
                    return true;
                }
            }

            if (settings.nTable !== tableNode) {
                return true;
            }

            return false;
        }
    );


    $('#mods input:radio').on('change', function () {

        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        })
        $('#datatable-2').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        })


        var title = $(this).data('title');
        $('#portlet-title').html(title);

        var currentElement = $(this).val();
        if (currentElement != "CarsToDealers") {

            $('.inspectionTable').removeClass('d-none');
            $('.DealerTable').addClass('d-none');

            switch (currentElement) {
                case "notTouched":
                case "windshield":
                case "wheels":
                case "toGo":
                case "atBodyshop":
                case "backFromBodyshop":
                case "Gone":
                    manageInvTable.rowGroup().enable().draw();
                    setColumVisibility(1);
                    setColumVisibility(2);
                    setColumVisibility(3);
                    break;
                case "holdForRecon":
                case "sendToRecon":
                case "retailReady":
                    manageInvTable.rowGroup().disable().draw();
                    setColumVisibility(1, true);
                    setColumVisibility(2);
                    setColumVisibility(3);
                    break;
                case "LotNotes":
                    setColumVisibility(1);
                    setColumVisibility(2, true);
                    setColumVisibility(3, true);
                    break;
                case "LotNotes":
                    setColumVisibility(1);
                    setColumVisibility(2, true);
                    setColumVisibility(3, true);
                    break;
                default:
                    break;
            }
            setTimeout(() => {
                manageInvTable.draw();
                manageInvTable.searchPanes.rebuildPane();
                var table_length = manageInvTable.rows({ search: 'applied' }).count();
                // console.log(table_length);
            }, 500);

        } else if (currentElement == 'CarsToDealers') {
            fetchCarsToDealers();
            $('.inspectionTable').addClass('d-none');
            $('.DealerTable').removeClass('d-none');
        }

    });



    $select.on('change', function () {
        $(this).trigger('blur');
    });


    $("#updateInspectionForm").validate({
        // ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',

        rules: {

            "bodyshop": {
                required: true,
            },
            // "repairReturn": {
            //     required: !0,
            // },
            // "repairSent": {
            //     required: !0,
            // },
            "estimate": {
                number: !0,
            },
            "repairPaid": {
                number: !0,
            },
        },


        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
                var form = $('#updateInspectionForm');
                var fd = new FormData(document.getElementById("updateInspectionForm"));
                fd.append("CustomField", "This is some extra data");
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: fd,
                    contentType: false,
                    processData: false,
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
                            manageInvTable.ajax.reload(null, false);
                            form[0].reset();
                        } else {
                            e1.fire({
                                // position: "center",
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

    $('#repais').on('change', function () {
        if($("#repais option:selected").length > 0){
            $('#bodyshop').addClass('required');
        }else{
            $('#bodyshop').removeClass('required');
        }
    });

   

    $("#updateCarsToDealersForm").validate({
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var c = confirm('Do you really want to save this?');
            if (c) {
                var form = $('#updateCarsToDealersForm');
                var fd = new FormData(document.getElementById("updateCarsToDealersForm"));
                fd.append("CustomField", "This is some extra data");
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: fd,
                    contentType: false,
                    processData: false,
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
                            manageCarDealersTable.ajax.reload(null, false);
                            form[0].reset();
                        } else {
                            e1.fire({
                                // position: "center",
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

    $("#images").on("change", function () {
        if ($("#images")[0].files.length > maxFileLimit) {
            Swal.fire("Deleted!", "You can Select only " + maxFileLimit + " images More", "error")
            $("#images").val(null);
        }
    });

});

function initTableWithoutRowGroup() {

    // $('#datatable-1').DataTable().rowGroup().disabled()
    // $('#datatable-1').DataTable().rowGroup().disable().draw();
}

function initTableWithRowGroup() {
    // manageInvTable.rowGroup().enable().draw();
    // manageInvTable.rowGroup().disable().draw();
    // $('#datatable-1').DataTable().rowGroup().disable().draw();

    // console.log($('#datatable-1').DataTable().rowGroup().enabled());
    // console.log(manageInvTable.rowGroup().enable());
    // $('#datatable-1').DataTable().rowGroup().enabled(true);
    // $('#datatable-1').DataTable().rowGroup().dataSrc($("#datatable-1").DataTable().data(4));
    // $('#datatable-1').dataTable().fnClearTable();
    // $('#datatable-1').dataTable().fnDestroy();
}


















// --------------------------------------------------------------------------------------------------------------------------------------------
function fetchCarsToDealers() {
    if ($.fn.dataTable.isDataTable('#datatable-2')) {
        // manageCarDealersTable = $('#datatable-2').DataTable();
    }
    else {
        manageCarDealersTable = $("#datatable-2").DataTable({
            responsive: !0,
            'ajax': '../php_action/fetchCarsToDealers.php',
            "paging": true,
            "scrollX": true,
            "orderClasses": false,
            "deferRender": true,
            "pageLength": 25,
            "order": [[1, "desc"]],

            dom: `\n     
            <'row'<'col-12'P>>\n
           \n     
           <'row'<'col-sm-6 text-center text-sm-left pl-3'B>
                <'col-sm-6 text-right text-sm-right pl-3'f>>\n
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [1, 2, 3]
            },
            columnDefs: [

                {
                    searchPanes: {
                        show: true
                    },
                    targets: [1, 2, 3]
                },
                {
                    targets: [1],
                    searchPanes: true,
                    searchable: true,
                    createdCell: function (td, cellData, rowData, row, col) {
                        var data = $(td).html();
                        if (data > 4) {
                            $(td).addClass('h5 font-weight-bolder text-danger');
                        } else {
                            $(td).addClass('font-weight-bold p');
                        }
                    }
                },

            ],


            buttons: [
                {
                    text: 'Pending',
                    action: function (e, dt, node, config) {
                        loadUncomplete();
                    },

                },
                {
                    text: 'Complete',
                    action: function (e, dt, node, config) {
                        manageCarDealersTable.button(0).active(false);
                        manageCarDealersTable.button(1).active(true);
                        // manageCarDealersTable.columns(6).search('^$', true, false).draw();;
                        $.fn.dataTable.ext.search.pop();
                        manageCarDealersTable.search('').draw();
                        var tableNode = this.table(0).node();
                        // console.log(tableNode);
                        $.fn.dataTable.ext.search.push(
                            function (settings, data, dataIndex) {
                                if (settings.nTable !== tableNode) {
                                    return true;
                                }

                                if (data[6] != '' && data[7] != '') {
                                    return true;
                                }
                                return false
                            }
                        )

                        manageCarDealersTable.draw();  // working

                    },

                }
            ],

            language: {
                searchPanes: {
                    count: "{total} found",
                    countFiltered: "{shown} / {total}"
                }
            },
            "drawCallback": function (settings, start, end, max, total, pre) {
                var json = this.fnSettings().json;
                if (json) {
                    var obj = json.totalNumber;
                    for (const [key, value] of Object.entries(obj)) {
                        $(`input[name='mod'][value='${key}']`).next().next().html(value)
                    }
                }
            },


        })
    }
    loadUncomplete();
}
function loadUncomplete() {
    manageCarDealersTable.button(0).active(true);
    manageCarDealersTable.button(1).active(false);

    $.fn.dataTable.ext.search.pop();
    manageCarDealersTable.search('').draw();
    var tableNode = $('#datatable-2')[0];

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            if (settings.nTable !== tableNode) {
                return true;
            }
            if (data[6] == '' || data[7] == '') {
                return true;
            }

            return false;
        }
    )
    manageCarDealersTable.draw();  // working
}


function setColumVisibility(n, b = false) {
    const column1 = manageInvTable.column(n);
    column1.visible(b);
}

function removeImage(ele) {
    var parents = $(ele).parent()
    var i = $(parents).data("slick-index");
    $('.slick-2').slick('slickRemove', i);
    // $('.slick-2').slick('unslick');
    $('.slick-2').slick('refresh');
    maxFileLimit += 1;
    $('#maxLimit').html(maxFileLimit);

}

function toggleFilterClass() {
    $('.dtsp-panes1').toggle();
}
function toggleFilterClass2() {
    $('.dtsp-panes').toggle();
}
function clearErrorsList() {
    $('#errorList').html('');
}

function editInspection(id) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedInspection.php',
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
                // stockno  stock and vin
                // selectedDetails   stock details
                $('#vehicleId').val(id);
                $('#stockno').val(response.stockno + " || " + response.vin);
                $('#lotNotes').val(response.lot_notes ? response.lot_notes : "");

                $('.btn-group :radio[name="recon"]').prop('checked', false);
                $('.btn-group .active').removeClass('active');
                (response.recon) ? $('#' + response.recon).prop('checked', true).click() : null;


                var arr = response.repairs ? (response.repairs.trim()).split('__') : [];
                $("#repais option:selected").removeAttr("selected");
                $('#repais').trigger('change');
                arr.forEach(element => {
                    if ((element !== '') || $('#repais').find("option[value='" + element + "']").length) {
                        $("#repais option[value='" + element + "']").attr("selected", 1);
                        $('#repais').trigger('change');
                    }
                });
                var arr = response.shops ? (response.shops.trim()).split('__') : [];
                $("#bodyshop option:selected").removeAttr("selected");
                $('#bodyshop').trigger('change');
                arr.forEach(element => {
                    if ((element !== '') || $('#bodyshop').find("option[value='" + element + "']").length) {
                        $("#bodyshop option[value='" + element + "']").attr("selected", 1);
                        $('#bodyshop').trigger('change');
                    }
                });

                $('#bodyshopNotes').val(response.shops_notes ? response.shops_notes : "");
                $('#estimate').val(response.estimated ? response.estimated : "");
                $('#repairPaid').val(response.repair_paid ? response.repair_paid : "");
                $('#repairSent').val(response.repair_sent ? response.repair_sent : "");
                $('#repairReturn').val(response.repair_returned ? response.repair_returned : "");
                $('#resend').prop('checked', response.resend == "true" ? true : false);

                var arr = response.windshield ? (response.windshield.trim()).split('__') : [];
                $("#windshield").val(arr)

                var arr = response.wheels ? (response.wheels.trim()).split('__') : [];
                $("#wheels").val(arr)


                $('#submittedBy').val(response.submitted_by);
                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} \n Balance: ${response.balance}`;
                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');




                maxFileLimit = 10;
                $("#images").val(null);
                $('.slick-slider').slick('slickRemove', null, null, true);

                // console.log($('#slickSlider'));

                $("#slick-track").html("");
                document.getElementById('slickSlider').innerHTML = "";
                if (response.pictures) {
                    var images = (response.pictures.trim()).split('__');
                    console.log(images);
                    images.forEach((element, index) => {
                        if (element !== "") {
                            document.getElementById('slickSlider').innerHTML += `<div class="carousel-item">
                            <img src="../assets/inspections/${element}" class="card-img">
                            <input type="hidden" name="uploads[]" id="uploads${index + 1}" value="${element}">
                            <div class="card" onclick="removeImage(this)">X</div>
                        </div>`;
                        }

                    });

                    maxFileLimit = 10 - (images.length);
                }

                setTimeout(() => {
                    $('.slick-2').slick('refresh');
                }, 500);
                // $('.slick-2').slick('refresh');
                $('#maxLimit').html(maxFileLimit);





                setTimeout(() => {
                    autosize.update($(".autosize"));
                }, 500);
                $('.selectpicker').selectpicker('refresh');




            }, // /success
            error: function (err) {
                console.log(err);
            }

        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }
}
function editCashToDealers(id) {
    if (id) {
        $('.spinner-grow1').removeClass('d-none');
        // modal result
        $('.showResult1').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedCarsToDealers.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                $('.spinner-grow1').addClass('d-none');
                // modal result
                $('.showResult1').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                console.log(response);
                // stockno  stock and vin
                // selectedDetails   stock details
                $('#evehicleId').val(id);
                $('#estockno').val(response.stockno + " || " + response.vin);
                $('#workNeeded').val(response.work_needed ? response.work_needed : "");
                $('#notes').val(response.notes ? response.notes : "");
                $('#dateSent').val(response.date_sent ? response.date_sent : "");
                $('#dateReturn').val(response.date_returned ? response.date_returned : "");
                $('#esubmittedBy').val(response.submitted_by);
                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} \n Balance: ${response.balance}`;
                $('#eselectedDetails').html(detailsDiv);
                $('#eselectedDetails').addClass('text-center');

                setTimeout(() => {
                    autosize.update($(".autosize"));
                }, 500);


            }, // /success
            error: function (err) {
                console.log(err);
            }

        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }
}
