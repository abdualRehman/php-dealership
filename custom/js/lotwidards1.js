
"use strict";
var manageInvTable, TableData, maxFileLimit = 10, rowGroupSrc = 19; // 19; // wholesale 
var searhStatusArray = [], editInspectionObj = {};
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
    $('#statusBar').addClass('d-none');
    $('#searchBar').removeClass('d-none');

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
        endDate: new Date()

    });
    $("#repairReturn").datepicker({
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        autoclose: true,
        endDate: new Date()

    });

    $("#repais").select2({
        dropdownAutoWidth: !0,
        placeholder: "Select Repairs",
        closeOnSelect: false,
        allowClear: !0,
        tags: !0
    })
    autosize($(".autosize"));


    // var a = new Bloodhound({
    //     datumTokenizer: Bloodhound.tokenizers.obj.whitespace('stockDetails'),
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: searhStatusArray
    // });

    // $('#searchcars').typeahead({
    //     hint: true,
    //     highlight: true,
    //     minLength: 1,
    // },
    //     {
    //         name: 'searhStatusArray',
    //         display: 'stockDetails',
    //         source: a,
    //         templates: {
    //             suggestion: function (data) {
    //                 var stockAvailibilityArray = data.stockAvailibility;
    //                 const template = `<div><p><strong>${data.stockDetails}</strong></p><div class="row pl-2 pr-2">${stockAvailibilityArray.map(e => (e) ? `<div class="col-sm-4 p-1"> <span class="badge badge-label-primary"> ${e} </span> </div>` : ``).join('')}</div>`;
    //                 return template;
    //             }
    //         }
    //     }
    // );






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
        "pageLength": 50,
        // autoWidth: false,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [7, 6, 4]
        },
        dom: `<'row'<'col-12'P>>
        <'row' <'col-sm-4 text-left text-sm-left pl-3'B>
            <'col-sm-4 text-left text-center pl-3'<'#statusFilterDiv.d-none'>>
            <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
        <'row'<'col-12'tr>>\n      
        <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Lot widzards',
                exportOptions: {
                    columns: [':visible:not(:first-child)'],
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Lot widzards',
                exportOptions: {
                    columns: [':visible:not(:first-child)']
                }
            },
            {
                extend: 'print',
                title: 'Lot widzards',
                exportOptions: {
                    columns: [':visible:not(:first-child)']
                },
                customize: function (win) {
                    $(win.document.body)
                        .css('font-size', '10pt');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            },
        ],

        columnDefs: [
            { width: 400, targets: [3] },
            {
                targets: [0, 1, 2, 3, 4, 5, 8, 20, 21, 22, 23, 24],
                visible: false,
            },
            {
                targets: [6],
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
                targets: [21],
                data: 22, // repairs 
                render: function (data, type, row) {
                    var repairArr = data ? data.replace(/__/g, " , ") : '';
                    repairArr = repairArr.slice(3, -3);
                    return repairArr;
                },
            },
            {
                targets: [22],
                data: 3, // lot notes
            },
            {
                targets: [23],
                data: 20, // windshield
                render: function (data, type, row) {
                    var repairArr = data ? data.replace(/__/g, " , ") : '';
                    repairArr = repairArr.slice(3, -3);
                    return repairArr;
                },
            },
            {
                targets: [24],
                data: 21, // wheels
                render: function (data, type, row) {
                    var repairArr = data ? data.replace(/__/g, " , ") : '';
                    repairArr = repairArr.slice(3, -3);
                    return repairArr;
                },
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [7, 6, 4]
            },
        ],
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
                // if (rowGroupSrc == 19) {
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
                if (rowGroupSrc == 19) {
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
                // console.log(obj);
                var obj = json.totalNumber;
                for (const [key, value] of Object.entries(obj)) {
                    $(`input[name='mod'][value='${key}']`).next().next().html(value)
                    // changing style
                    switch (key) {
                        case 'notTouched':
                        case 'LotNotes':
                        case 'windshield':
                        case 'wheels':
                        case 'toGo':
                        case 'CarsToDealers':
                            if (value == '0') {
                                $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-success')
                                $(`input[name='mod'][value='${key}']`).parent().removeClass('btn-outline-danger')
                            } else {
                                $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-danger')
                                $(`input[name='mod'][value='${key}']`).parent().removeClass('btn-outline-success')
                            }
                            break;
                        default:
                            $(`input[name='mod'][value='${key}']`).parent().addClass('btn-outline-primary')
                            break;
                    }

                }

                let totalPending = 0, totalComplete = 0;
                let modFilter = $(`#mods .btn.active input[name='mod']:checked`).val();
                if (modFilter == 'windshield') {
                    $('#datatable-1').DataTable()
                        .rows()
                        .data()
                        .each(function (data, index) {
                            var windshield = data[20]; // windshield
                            var arr = windshield ? (windshield.trim()).split('__') : [];
                            if (arr.length > 0 && arr.indexOf('Done') == -1) {
                                totalPending += 1;
                            }
                            if (arr.length > 0 && arr.indexOf('Done') != -1) {
                                totalComplete += 1;
                            }
                        })
                } else if (modFilter == 'wheels') {
                    $('#datatable-1').DataTable()
                        .rows()
                        .data()
                        .each(function (data, index) {
                            var wheels = data[21]; // wheels
                            var arr = wheels ? (wheels.trim()).split('__') : [];
                            if (arr.length > 0 && arr.indexOf('Done') == -1) {
                                totalPending += 1;
                            }
                            if (arr.length > 0 && arr.indexOf('Done') != -1) {
                                totalComplete += 1;
                            }
                        });
                }

                $('#pendingCount').html(totalPending);
                $('#completeCount').html(totalComplete);


                // a.clear();
                // a.local = searhStatusArray;
                // a.initialize(true);
                setSearchTypehead(searhStatusArray);
                searhStatusArray = [];


            }
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':first-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editInspection(" + data[25] + ")"
                });
            }
        },
        "order": [[19, "asc"], [6, "desc"]], // wholesale , age
        // "order": [[4, "asc"], [5, "desc"]],
    });

    loadbodyshops();
    writeStatusHTML();

    // --------------------- checkboxes query --------------------------------------
    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var tableNode = manageInvTable.table().node();

            if (rowData[7] && rowData[7] != 'undefined') {
                searhStatusArray.push({
                    stockDetails: rowData[7],
                    stockAvailibility: rowData[26],
                })
            }


            var activebtnvalue = $("#mods .btn.active input[name='mod']").val();
            // console.log(activebtnvalue);

            if (activebtnvalue == 'notTouched') {
                // console.log(rowData);
                var balance = rowData[15];
                var recon = rowData[1];
                var notes = rowData[3];

                var windshield = rowData[20];
                var arr = windshield ? (windshield.trim()).split('__') : [];
                var doneEle = arr.find((element) => {
                    return element != "Done";
                })

                var wheels = rowData[21];
                var arrWheels = wheels ? (wheels.trim()).split('__') : [];
                var doneEleWheel = arrWheels.find((element) => {
                    return element != "Done";
                })


                var repairs = rowData[22];
                var repairArr = repairs ? (repairs.trim()).split('__') : [];
                var repairSent = rowData[23];

                // not touched old 
                // if (recon == "" || recon == null || notes == null || notes == "" || doneEleWheel || doneEle || repairArr.length == 0) {
                //     return true;
                // }

                if ((recon == "" || recon == null) && repairArr.length == 0) {
                    return true;
                }
            }
            if (activebtnvalue == 'holdForRecon') {

                var balance = rowData[15];
                var recon = rowData[1];
                if (recon == 'hold' && balance) {
                    return true;
                }
            }
            if (activebtnvalue == 'sendToRecon') {

                var balance = rowData[15];
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
                var windshield = rowData[20];
                var balance = rowData[15];

                var arr = windshield ? (windshield.trim()).split('__') : [];
                var filterValue = $('#statusFilterBtns input:radio:checked').val();
                if (filterValue == 'pending') {
                    // if (arr.length > 0 && arr.indexOf('Done') == -1 && (balance != '' && balance < 0)) {
                    //     return true;
                    // }
                    if (arr.length > 0 && arr.indexOf('Done') == -1 && (balance && balance != 0)) {
                        return true;
                    }
                } else {
                    if (arr.length > 0 && arr.indexOf('Done') != -1 && (balance && balance != 0)) {
                        return true;
                    }
                }
            }
            if (activebtnvalue == 'wheels') {
                var wheels = rowData[21];
                var balance = rowData[15];

                var arr = wheels ? (wheels.trim()).split('__') : [];
                var filterValue = $('#statusFilterBtns input:radio:checked').val();
                if (filterValue == 'pending') {
                    if (arr.length > 0 && arr.indexOf('Done') == -1 && (balance && balance != 0)) {
                        return true;
                    }
                } else {
                    if (arr.length > 0 && arr.indexOf('Done') != -1 && (balance && balance != 0)) {
                        return true;
                    }
                }
            }
            // To go = repairs selected….repair sent bank
            if (activebtnvalue == 'toGo') {
                var repairs = rowData[22];
                var repairSent = rowData[23];
                var arr = repairs ? (repairs.trim()).split('__') : [];
                if (arr.length > 0 && (repairSent == "" || repairSent == null)) {
                    return true;
                }
            }
            // At bodyshop- repairs, bodyshop & repair sent selected…….repair returned blank
            if (activebtnvalue == 'atBodyshop') {
                var repairs = rowData[22];
                var repairSent = rowData[23];
                var repairReturned = rowData[24];
                var arr = repairs ? (repairs.trim()).split('__') : [];
                if (arr.length > 0 && (repairSent != "" && repairSent != null) && (repairReturned == "" || repairReturned == null)) {
                    return true;
                }
            }
            // Back from bodyshop - repair returned selected and recon is blank
            if (activebtnvalue == 'backFromBodyshop') {
                var recon = rowData[1];
                var repairSent = rowData[23];
                var repairReturned = rowData[24];
                if (repairReturned && repairSent && (recon == "" || recon == null)) {
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
                var balance = rowData[15];
                if (balance == '' || balance == null || balance == 0) {
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

            searhStatusArray = [];

            switch (currentElement) {
                case "notTouched":
                    rowGroupSrc = 19;
                    manageInvTable.rowGroup().enable().draw();
                    manageInvTable.rowGroup().dataSrc(rowGroupSrc);
                    setColumVisibility([0, 1, 2, 3, 4, 5, 8, 20, 21, 22, 23, 24]);
                    manageInvTable.order([19, "asc"], [6, "desc"]).draw(); // wholesale , age
                    break;
                case "toGo":
                case "atBodyshop":
                case "backFromBodyshop":
                case "Gone":
                    rowGroupSrc = 4;
                    manageInvTable.rowGroup().enable().draw();
                    manageInvTable.dataSrc(rowGroupSrc);
                    if (currentElement == 'toGo') {
                        setColumVisibility([1, 2, 3, 4, 5, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 23, 24]);
                        manageInvTable.order([4, "asc"], [6, "desc"]).draw(); // bodyshop , age
                    } else if (currentElement == 'atBodyshop' || currentElement == 'backFromBodyshop') {
                        setColumVisibility([1, 2, 3, 4, 7, 9, 10, 12, 13, 14, 15, 16, 17, 18, 19, 20, 23, 24]);
                        manageInvTable.order([4, "asc"], [5, "asc"]).draw(); // bodyshop , daysout
                    } else {
                        setColumVisibility([1, 2, 3, 4, 5, 8, 20, 21, 22, 23, 24]);
                        manageInvTable.order([4, "asc"], [6, "desc"]).draw(); // bodyshop , age
                    }
                    break;
                case "windshield":
                case "wheels":
                case "holdForRecon":
                case "sendToRecon":
                case "retailReady":
                    manageInvTable.rowGroup().disable().draw();
                    if (currentElement == 'windshield') {
                        setColumVisibility([1, 2, 3, 4, 5, 8, 14, 15, 16, 17, 18, 20, 21, 22, 24]);
                    } else if (currentElement == 'wheels') {
                        setColumVisibility([1, 2, 3, 4, 5, 8, 14, 15, 16, 17, 18, 20, 21, 22, 23]);
                    } else {
                        setColumVisibility([1, 2, 3, 4, 5, 8, 20, 21, 22, 23, 24]);
                    }
                    manageInvTable.order([19, "asc"], [6, "desc"]).draw(); // wholesale , age
                    break;
                case "LotNotes":
                    manageInvTable.rowGroup().disable().draw();
                    setColumVisibility([1, 4, 5, 8, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]);
                    manageInvTable.order([19, "asc"], [6, "desc"]).draw(); // wholesale , age
                    break;
                default:
                    break;
            }

            if (currentElement == 'windshield' || currentElement == 'wheels') {
                $('#statusFilterDiv').removeClass('d-none');
            } else {
                $('#statusFilterDiv').addClass('d-none');
            }

            setTimeout(() => {
                // manageInvTable.order([6, "desc"]).draw();
                $("#datatable-1").dataTable().fnFilter("");
                manageInvTable.searchPanes.rebuildPane();

            }, 500);

        } else if (currentElement == 'CarsToDealers') {
            fetchCarsToDealers();
            $('.inspectionTable').addClass('d-none');
            $('.DealerTable').removeClass('d-none');
        }

    });



    // $select.on('change', function () {
    //     $(this).trigger('blur');
    // });


    $("#updateInspectionForm").validate({
        // ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',

        rules: {

            "bodyshop": {
                // required: true,
                required: function (params) {
                    // console.log(params);
                    if (params.value == 0) {
                        params.classList.add('is-invalid');
                        $('#bodyshop').selectpicker('refresh');
                        params.classList.add('is-invalid');
                        return true;
                    } else {
                        return false;
                    }
                }
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
            e.preventDefault();

            $('#updateInspectionForm').block();

            if ($('#repais option:selected').length > 0 && $('#repairSent').val() != '' && $('#bodyshop').val() == 0) {
                $('#bodyshop').addClass('is-invalid');
                $('#bodyshop').removeClass('is-valid');
                $('#bodyshop').selectpicker('refresh');
                return false;
            } else {
                $('#bodyshop').addClass('is-valid');
                $('#bodyshop').removeClass('is-invalid');
                $('#bodyshop').selectpicker('refresh');
            }

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
                        $('#updateInspectionForm').unblock();
                        $('#modal8').modal('hide');
                    } else {
                        e1.fire({
                            // position: "center",
                            icon: "error",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 2500
                        })
                        $('#updateInspectionForm').unblock();
                    }


                }
            });

            return false;

        }


    });






    $('.repairAndBodyshop').on('change', function () {
        if ($('#repais option:selected').length > 0 && $('#bodyshop').val() != 0) {
            $('#repairSent').removeAttr('disabled');
        } else {
            $('#repairSent').attr('disabled', true);
            $('#repairSent').val('');
        }
    });

    $('#repairSent').on('change', function () {
        if ($("#repairSent").val() != '') {
            $('#repairReturn').removeAttr('disabled');
        } else {
            $('#repairReturn').attr('disabled', true);
            $('#repairReturn').val('');
        }
    });

    $('#recon-btn-group label').on('click', function () {
        let prev = $('#recon-btn-group .active :radio:checked').val();
        let current = $(this).children(':radio[name=recon]').val();
        if (prev == current) {
            setTimeout(() => {
                $('#recon-btn-group :radio').prop('checked', false);
                $('#recon-btn-group .active').removeClass('active');
            }, 100);
        }
    })


    $("#updateCarsToDealersForm").validate({
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();

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
                        // form[0].reset();
                        $('#modal9').modal('hide');

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


function loadbodyshops() {
    $.ajax({
        url: '../php_action/fetchBodyshopsForSearch.php',
        type: "POST",
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            var array = response.data;
            // var selectBoxs = document.getElementsByClassName('bodyshop');
            var selectBoxs = document.getElementById('bodyshop');
            selectBoxs.innerHTML = `<option value="0" selected >Select Bodyshop</option>`;
            for (var i = 0; i < array.length; i++) {
                var item = array[i];
                selectBoxs.innerHTML += `<option value="${item[0]}" title="${item[1]}">${item[1]}</option>`;
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}




function setSearchTypehead(searhStatusArray) {
    $('#searchcars').typeahead('destroy');

    function substringMatcher(strs) {
        return function findMatches(q, cb) {
            var matches = [];
            var substrRegex = new RegExp(q, 'i');
            $.each(strs,
                function (i, str) {
                    if (substrRegex.test(str.stockDetails)) {
                        matches.push(str);
                    }
                });
            cb(matches);
        };
    };
    $('#searchcars').typeahead({
        hint: true,
        highlight: true,
        minLength: 0,
        autoselect: false
    },
        {
            displayKey: "stockDetails",
            name: "searhStatusArray",
            source: substringMatcher(searhStatusArray),
            templates: {
                suggestion: function (data) {
                    var stockAvailibilityArray = data.stockAvailibility;
                    const template = `<div><p><strong>${data.stockDetails}</strong></p><div class="row pl-2 pr-2">${stockAvailibilityArray.map(e => (e) ? `<div class="col-sm-4 p-1"> <button class="badge badge-label-primary cursor-pointer searchStockBtn" onclick="searchStockBtn(this)"  data-head="${e}" data-search="${data.stockDetails}" > ${e} </button> </div>` : ``).join('')}</div></div>`;
                    return template;
                }
            },
        })
        .on('typeahead:cursorchanged', function ($e, datum) {
            $("#searchcars").typeahead('val', datum.stockDetails)
        })
        .on('typeahead:selected', function ($e, datum) {
            $("#searchcars").typeahead('val', datum.stockDetails)
        })

    $('.form-control.tt-input').next().addClass('w-inherit');

}

function searchStockBtn(params) {
    let head = $(params).data('head');
    let search = $(params).data('search');
    console.log(head);
    console.log(search);
    switch (head) {
        case 'Not Touched':
            head = 'notTouched';
            break;
        case 'Hold Recon':
            head = 'holdForRecon';
            break;
        case 'Send Recon':
            head = 'sendToRecon';
            break;
        case 'Lot Notes':
            head = 'LotNotes';
            break;
        case 'Windshield':
            head = 'windshield';
            break;
        case 'Wheels':
            head = 'wheels';
            break;
        case 'To Go':
            head = 'toGo';
            break;
        case 'At Bodyshop':
            head = 'atBodyshop';
            break;
        case 'Back Bodyshop':
            head = 'backFromBodyshop';
            break;
        case 'Retail Ready':
            head = 'retailReady';
            break;
        case 'Gone':
            head = 'Gone';
            break;
        default:
            head = '';
            break;
    }
    let tab = $('#mods :radio[name=mod][value=' + head + ']').parent().button('toggle');
    if (tab) {
        setTimeout(() => {
            $("#datatable-1").dataTable().fnFilter(search);
            manageInvTable.order([6, 'desc']).draw();
            manageInvTable.ajax.reload(null, false);
        }, 1000);
    }
}














// setTimeout(() => {
//     $('#mods input:radio[value=windshield]').click();
// }, 1000);
// --------------------------------------------------------------------------------------------------------------------------------------------

function fetchCarsToDealers() {
    if ($.fn.dataTable.isDataTable('#datatable-2')) {
        // manageCarDealersTable = $('#datatable-2').DataTable();
        // manageCarDealersTable.draw();
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
            // autoWidth: false,
            "order": [[1, "desc"]],

            dom: `\n     
            <'row'<'col-12'P>>\n
           \n     
           <'row'<'col-sm-4 text-left text-sm-left pl-3'B>
                <'col-sm-4 text-left text-center pl-3'<'#carDealersFilterDiv'>>
                <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [1, 2, 3]
            },
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: 'Cars To Dealers',
                    exportOptions: {
                        columns: [':visible:not(:first-child)'],
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Cars To Dealers',
                    exportOptions: {
                        columns: [':visible:not(:first-child)']
                    }
                },
                {
                    extend: 'print',
                    title: 'Cars To Dealers',
                    exportOptions: {
                        columns: [':visible:not(:first-child)']
                    },
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '10pt');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                },
            ],
            columnDefs: [
                { width: 400, targets: [4, 5] },
                {
                    targets: [8],
                    visible: false,
                },
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
            createdRow: function (row, data, dataIndex) {
                if ($('#isEditAllowed').val() == "true") {
                    $(row).children().not(':first-child').attr({
                        "data-toggle": "modal",
                        "data-target": "#modal9",
                        "onclick": "editCashToDealers(" + data[8] + ")"
                    });
                }
            },
        })
        carDealersFilterDivHTML();
    }
    var tableNode = $('#datatable-2')[0];
    $.fn.dataTable.ext.search.push(
        function (settings, data, index, rowData, counter) {
            // var tableNode = manageCarDealersTable.table().node();
            var activebtnvalue = $("#carDealersFilterDiv .btn.active input[name='carstoDealerStatus']").val();

            if (activebtnvalue == 'Inventory') {
                if (data[4] == '' || data[4] == null) {
                    return true;
                }
            }

            if (activebtnvalue == 'Pending') {
                if (data[4] != '' && data[4] != null && data[7] == '') {
                    return true;
                }
            }

            if (activebtnvalue == 'Complete') {
                if (data[7] != '') {
                    return true;
                }
            }


            if (settings.nTable !== tableNode) {
                return true;
            }

            return false;
        }
    );
}


function carDealersFilterDivHTML() {
    var element = document.getElementById('carDealersFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
            <div class="col-md-12">
                <div id="carDealersFilterBtns">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-flat-primary">
                            <input type="radio" name="carstoDealerStatus" id="Inventory" value="Inventory" > Inventory 
                        </label>
                        <label class="btn btn-flat-primary active">
                            <input type="radio" name="carstoDealerStatus" id="PendingDealers" value="Pending" checked="checked" > Pending 
                        </label>
                        <label class="btn btn-flat-primary">
                            <input type="radio" name="carstoDealerStatus" id="CompleteDealers" value="Complete"> Complete
                        </label>
                    </div>
                </div>
            </div>
        </div>`;
    }

    $('#carDealersFilterBtns input:radio').on('change', function () {
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        $('#datatable-2').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });

        let currentElement = $(this).val();
        const column1 = manageCarDealersTable.column(0);
        switch (currentElement) {
            case "Pending":
            case "Complete":
                column1.visible(true);
                break;
            case "Inventory":
                column1.visible(false);
                break;
            default:
                break;
        }
        setTimeout(() => {
            manageCarDealersTable.draw();
            manageCarDealersTable.searchPanes.rebuildPane();
        }, 500);
    })
}

$('#workNeeded').on('input', function () {
    if (this.value == '') {
        $('#dateSent').val('');
        $('#dateSent').prop('disabled', true);
        $('#dateReturn').val('');
        $('#dateReturn').prop('disabled', true);
    } else {
        $('#dateSent').prop('disabled', false);
    }
})
$('#dateSent').on('change', function () {
    if (this.value == '') {
        $('#dateReturn').val('');
        $('#dateReturn').prop('disabled', true);
    } else {
        $('#dateReturn').prop('disabled', false);
    }
})




function setColumVisibility(columnArray) {
    var allC = manageInvTable.columns()[0];
    allC.forEach(column => {
        var col = manageInvTable.column(column);
        if (columnArray.indexOf(column) != -1) {
            col.visible(false);
        } else {
            col.visible(true);
        }
    });
    manageInvTable.columns.adjust().draw();
}


function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div id="statusFilterBtns">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-flat-primary active">
                        <input type="radio" name="searchStatus" id="pending" value="pending" checked="checked" > Pending  <span class="badge badge-lg p-1" id="pendingCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="complete" value="complete"> Complete <span class="badge badge-lg p-1" id="completeCount" ></span>
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
    $('#statusFilterBtns input:radio').on('change', function () {

        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        $('#datatable-2').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });

        setTimeout(() => {
            manageInvTable.draw();
            manageInvTable.searchPanes.rebuildPane();
        }, 500);
    })
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

$('#repairReturn').on('change', function () {
    $('#resend').prop('disabled', $(this).val() != '' ? false : true);
    $('#resend').prop('checked', $(this).val() == '' ? false : null)
})


$('#resend').on('change', function () {
    console.log(this.checked);
    var obj = editInspectionObj;
    if (this.checked == true) {
        $('#resendDetailsDiv').removeClass('d-none');
        // console.log(obj);
        var arr = obj.repairs ? (obj.repairs.trim()).split('__') : [];
        arr.pop(); arr.shift();
        var detailsDiv = `History  Sent ${obj.repair_sent}     Returned ${obj.repair_returned} \nRepairs: ${arr.map(element => element).join(' , ')} \nBodyshop: ${obj.bodyshop_log} \nPaid: ${obj.repair_paid}`;
        $('#resendDetails').html(detailsDiv);


        // setting values to null

        $('#lotNotes').val("");
        $('.btn-group :radio[name="recon"]').prop('checked', false);
        $('#reconBtnGroup .active').removeClass('active');
        $("#repais option:selected").removeAttr("selected");
        $("#repais option:selected").prop("selected", 0);
        $('#repais').val(null).trigger('change');
        $('#bodyshop').val(0);
        $('#bodyshopNotes').val("");
        $('#estimate').val("");
        $('#repairPaid').val("");
        $("#repairSent").datepicker("setDate", "");
        $("#repairReturn").datepicker("setDate", "");
        $('#repairSent').prop('disabled', true);
        $('#repairReturn').prop('disabled', true);
        $("#windshield").val('')
        $("#windshield_done").prop('checked', false);
        $("#wheels").val("")
        $("#wheels_done").prop('checked', false);
        $("#images").val(null);
        $('.slick-slider').slick('slickRemove', null, null, true);
        $("#slick-track").html("");
        document.getElementById('slickSlider').innerHTML = "";
        $('#maxLimit').html(10);
        setTimeout(() => {
            autosize.update($(".autosize"));
            $('.slick-2').slick('refresh');
        }, 500);
        $('.selectpicker').selectpicker('refresh');
    } else {
        $('#resendDetailsDiv').addClass('d-none');

        $('#stockno').val(obj.stockno + " || " + obj.vin);
        $('#lotNotes').val(obj.lot_notes ? obj.lot_notes : "");

        $('.btn-group :radio[name="recon"]').prop('checked', false);
        $('#reconBtnGroup .active').removeClass('active');
        (obj.recon) ? $('#' + obj.recon).prop('checked', true).click() : null;

        var arr = obj.repairs ? (obj.repairs.trim()).split('__') : [];

        $("#repais option:selected").removeAttr("selected");
        $("#repais option:selected").prop("selected", 0);
        $('#repais').val(null).trigger('change');
        arr.forEach(element => {
            if ((element !== '') || $('#repais').find("option[value='" + element + "']").length) {
                $("#repais option[value='" + element + "']").prop("selected", 1);
                $('#repais').trigger('change');
            }
        });

        $('#bodyshop').val(obj.shops ? obj.shops : 0);
        $('#bodyshopNotes').val(obj.shops_notes ? obj.shops_notes : "");
        $('#estimate').val(obj.estimated ? obj.estimated : "");
        $('#repairPaid').val(obj.repair_paid ? obj.repair_paid : "");
        $("#repairSent").datepicker("setDate", obj.repair_sent ? obj.repair_sent : "");
        $("#repairReturn").datepicker("setDate", obj.repair_returned ? obj.repair_returned : "");
        $('#repairSent').prop('disabled', ((obj.shops != 0 || obj.shops != '' || obj.shops != null) && arr.length != 0) ? false : true);
        $('#repairReturn').prop('disabled', ((obj.shops != 0 || obj.shops != '' || obj.shops != null) && (arr.length != 0) && (obj.repair_sent != '' || obj.repair_sent != null)) ? false : true);

        // $('#resend').prop('disabled', obj.repair_returned != "" ? false : true);  ////
        // $('#resend').prop('checked', obj.resend == "true" ? true : false);

        var arr = obj.windshield ? (obj.windshield.trim()).split('__') : [];
        $("#windshield").val(arr)
        $("#windshield_done").prop('checked', (arr.indexOf('Done') != -1) ? true : false);
        var arr = obj.wheels ? (obj.wheels.trim()).split('__') : [];
        $("#wheels").val(arr)
        $("#wheels_done").prop('checked', (arr.indexOf('Done') != -1) ? true : false);
        $('#submittedBy').val(obj.submitted_by);
        var detailsDiv = `${obj.stocktype} ${obj.year} ${obj.make} ${obj.model} \n Mileage: ${obj.mileage} \n Age: ${obj.age} \n Lot: ${obj.lot} \n Balance: ${obj.balance}`;
        $('#selectedDetails').html(detailsDiv);
        $('#selectedDetails').addClass('text-center');
        maxFileLimit = 10;
        $("#images").val(null);
        $('.slick-slider').slick('slickRemove', null, null, true);
        $("#slick-track").html("");
        document.getElementById('slickSlider').innerHTML = "";
        if (obj.pictures) {
            var images = (obj.pictures.trim()).split('__');
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
            if (images.length > 1) {
                setTimeout(() => {
                    $('.slick-2').slick('refresh');
                }, 500);
            }
        }
        $('#maxLimit').html(maxFileLimit);
        setTimeout(() => {
            autosize.update($(".autosize"));
        }, 500);
        $('.selectpicker').selectpicker('refresh');

    }
})

function editInspection(id) {
    console.log(id);
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        $('.showResult').addClass('d-none');
        $('.modal-footer').addClass('d-none');
        $.ajax({
            url: '../php_action/fetchSelectedInspection.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                editInspectionObj = response;

                $('.spinner-grow').addClass('d-none');
                $('.showResult').removeClass('d-none');
                $('.modal-footer').removeClass('d-none');

                console.log(response);
                // stockno  stock and vin
                // selectedDetails   stock details
                $('#vehicleId').val(id);
                $('#stockno').val(response.stockno + " || " + response.vin);
                $('#lotNotes').val(response.lot_notes ? response.lot_notes : "");

                $('.btn-group :radio[name="recon"]').prop('checked', false);
                $('#reconBtnGroup .active').removeClass('active');
                (response.recon) ? $('#' + response.recon).prop('checked', true).click() : null;


                var arr = response.repairs ? (response.repairs.trim()).split('__') : [];

                $("#repais option:selected").removeAttr("selected");
                $("#repais option:selected").prop("selected", false);
                $('#repais').val(null).trigger('change');
                arr.forEach(element => {
                    if ((element !== '') || $('#repais').find("option[value='" + element + "']").length) {
                        $("#repais option[value='" + element + "']").prop("selected", 1);
                        $('#repais').trigger('change');
                    }
                });

                // console.log('bodyshops', response.shops);
                $('#bodyshop').val(response.shops ? response.shops : 0);

                // var arr = response.shops ? (response.shops.trim()).split('__') : [];
                // arr.forEach(element => {
                //     if ((element !== '')) {
                // $('#bodyshop').val(element);
                //     } else {
                //         $('#bodyshop').val(0);
                //     }
                // });

                $('#bodyshopNotes').val(response.shops_notes ? response.shops_notes : "");
                $('#estimate').val(response.estimated ? response.estimated : "");
                $('#repairPaid').val(response.repair_paid ? response.repair_paid : "");



                // setting date 
                $("#repairSent").datepicker("setDate", response.repair_sent ? response.repair_sent : "");
                $("#repairReturn").datepicker("setDate", response.repair_returned ? response.repair_returned : "");


                // console.log(response.shops);
                // console.log(arr);
                // console.log(response.repair_sent);
                $('#repairSent').prop('disabled', ((response.shops != 0 || response.shops != '' || response.shops != null) && arr.length != 0) ? false : true);
                $('#repairReturn').prop('disabled', ((response.shops != 0 || response.shops != '' || response.shops != null) && (arr.length != 0) && (response.repair_sent != '' || response.repair_sent != null)) ? false : true);

                $('#resend').prop('disabled', response.repair_returned != "" ? false : true);  ////

                $('#resend').prop('checked', response.resend == "true" ? true : false);

                if (response.resend == "true") {
                    $('#resendDetailsDiv').removeClass('d-none');
                    var arr = response.repairs ? (response.repairs.trim()).split('__') : [];
                    arr.pop(); arr.shift();
                    var detailsDiv = `History  Sent ${response.repair_sent}     Returned ${response.repair_returned} \nRepairs: ${arr.map(element => element).join(' , ')} \nBodyshop: ${response.bodyshop_log} \nPaid: ${response.repair_paid}`;
                    $('#resendDetails').html(detailsDiv);
                } else {
                    $('#resendDetailsDiv').addClass('d-none');
                    $('#resendDetails').html("");
                }






                var arr = response.windshield ? (response.windshield.trim()).split('__') : [];
                // console.log(arr.indexOf('Done'));
                $("#windshield").val(arr)
                $("#windshield_done").prop('checked', (arr.indexOf('Done') != -1) ? true : false);

                var arr = response.wheels ? (response.wheels.trim()).split('__') : [];

                $("#wheels").val(arr)
                $("#wheels_done").prop('checked', (arr.indexOf('Done') != -1) ? true : false);


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
                    if (images.length > 1) {
                        setTimeout(() => {
                            $('.slick-2').slick('refresh');
                        }, 500);
                    }
                }
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

                // response.work_needed

                // setting date 
                // $('#dateSent').val(response.date_sent ? response.date_sent : "");
                $("#dateSent").datepicker("setDate", response.date_sent ? response.date_sent : "");
                $('#dateSent').prop('disabled', (response.work_needed == '') ? true : false);

                // $('#dateReturn').val(response.date_returned ? response.date_returned : "");
                $("#dateReturn").datepicker("setDate", response.date_returned ? response.date_returned : "");
                $('#dateReturn').prop('disabled', (response.work_needed == '' && response.date_sent == '') ? true : false);


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


function removeInspections(id = null) {
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
            if (t.isConfirmed == true) {
                $.ajax({
                    url: '../php_action/removeInspections.php',
                    type: 'post',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageInvTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand
            }
        });
    }
}

function removeCarsToDealers(id = null) {
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
            if (t.isConfirmed == true) {
                $.ajax({
                    url: '../php_action/removeCarsToDealers.php',
                    type: 'post',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageInvTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand
            }
        });
    }
}