"use strict";
var manageWritedownTable;
var collapsedGroups = {};
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});

$(function () {

    $('.nav-link').removeClass('active');
    $('#writedownPage').addClass('active');
    autosize($(".autosize"));



    manageWritedownTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchWritedowns.php',
        dom: `<'row'<'col-12'P>>
        <'row' 
        <'col-sm-4 text-left text-sm-left pl-3'B>
            <'col-sm-4 text-center pl-3'<'#statusFilterDiv'>>
            <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
        <'row'<'col-12'tr>>\n      
        <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [1, 2, 7, 9, 10],
        },
        pageLength: 100,
                    lengthMenu: [[50, 100, 250, -1], [50, 100, 250, "All"]],
        fixedHeader: true,
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Writedown',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Writedown',
                exportOptions: {
                    columns: [':visible']
                },
                customize: function (xlsx) {
                    $(xlsx.xl["styles.xml"]).find('numFmt[numFmtId="164"]').attr('formatCode', '[$$-en-AU]#,##0.00;[Red]-[$$-en-AU]#,##0.00');
                }
            },
            {
                extend: 'print',
                title: 'Writedown',
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
                targets: [1, 2, 7, 9, 10],
            },
            {
                targets: [0, 15],
                visible: false,
            },
            {
                targets: [12], // MMR
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).html(`<div class="show d-flex" >
                            <input type="text" class="form-control" name="input_field" value="${rowData[12] ? rowData[12] : 0}" id="${rowData[12]}mmr" data-attribute="mmr" data-id="${rowData[15]}" autocomplete="off"  />
                        </div>`);
                }
            },
        ],
        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

        rowGroup: {
            dataSrc: 16,
            startRender: function (rows, group) {
                var collapsed = !collapsedGroups[group];

                rows.nodes().each(function (r) {
                    r.style.display = 'none';
                    if (collapsed) {
                        r.style.display = '';
                    }
                });
                var filteredData = $('#datatable-1').DataTable()
                    .rows()
                    .data()
                    .filter(function (data, index) {
                        return data[16] == group ? true : false;
                    });
                $('#' + group + 'Count').html(filteredData.length);
                return $('<tr/>')
                    .append('<td colspan="16" class="text-capitalize" >' + group + ' (' + filteredData.length + ')</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed); // collapsed
            }
        },
        initComplete: function () {
            // Start with closed groups
            $('#datatable-1 tbody tr.dtrg-start').each(function () {
                var name = $(this).data('name');
                collapsedGroups[name] = !!collapsedGroups[name];
            });
            manageWritedownTable.draw(false);
        },

        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {
                // var obj = json.data;
                var counterObj = json.totalNumber;
                // console.log(counterObj);


                $('.counterDiv').each(function (index, element) {
                    $(element).html('0')
                });
                for (const [key, value] of Object.entries(counterObj)) {
                    if (key == 'retailP' || key == 'retailA' || key == 'mmr_balanceV' || key == 'mmr_retailV') {
                        $(`#` + key).removeClass('text-danger text-primary text-success');
                        let checkVal = parseFloat((value).replace(/\$|,/g, ''))
                        if (checkVal <= 0) {
                            $(`#` + key).addClass('text-danger');
                        } else if (checkVal > 0 && (key == 'mmr_balanceV' || key == 'mmr_retailV')) {
                            $(`#` + key).addClass('text-success');
                        } else {
                            $(`#` + key).addClass('text-primary');
                        }
                    }
                    $(`#` + key).html(value);
                }
            }
            setInputChange();
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':nth-child(12)').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editWritedown(" + data[0] + ")"
                });
            }
        },
        "order": [[16, "asc"], [7, "desc"]]
    });


    window.onscroll = function () { myFunction() };
    function myFunction() {
        var desktopH = document.getElementById("sticky-header-desktop");
        var mobileH = document.getElementById("sticky-header-mobile");
        var datatableHeader = $('.table.fixedHeader-floating');
        manageWritedownTable?.fixedHeader.headerOffset(desktopH.offsetHeight + mobileH.offsetHeight - 3);
        if ($(window).width() < 580) {
            manageWritedownTable?.fixedHeader.headerOffset(mobileH.offsetHeight - 3);
        }
        if (datatableHeader.length > 0) {
            let topV = desktopH.offsetHeight + mobileH.offsetHeight - 3;
            datatableHeader[0].style.top = `${topV}px`;
        }
    }



    // Collapse Groups
    $('#datatable-1 tbody').on('click', 'tr.dtrg-start', function () {
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        manageWritedownTable.draw(false);
    });


    // writeStatusHTML();
    // $('#thisMonth').click();


    // $('input[name="searchStatus"]:radio').on('change', function () {
    //     $('#datatable-1').block({
    //         message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
    //         timeout: 1e3
    //     });
    //     $('input[name="datefilter"]').val('');

    //     manageWritedownTable.draw();  // working
    //     manageWritedownTable.searchPanes.rebuildPane();
    // });





    $("#editWritedownForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            mmr: {
                required: !0,
                number: !0,
            }
        },
        submitHandler: function (form, e) {
            // return true
            e.preventDefault();

            var id = $('#writedownId').val();
            var value = $('#mmr').val();
            var attribute = "mmr";

            var retail_status_v = $('#retail_status input:checked').val();
            var repair_ele_attr = "retail_status";

            updateFieldsUsedCars({ id: [id, id], attribute: [attribute, repair_ele_attr], value: [value, retail_status_v] });

            $('#modal8').modal('hide');

            return false;

        }

    })

})

function setInputChange() {
    var inputs = document.querySelectorAll("input[name=input_field]");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keyup", function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                let id = $(this).data('id');
                let attribute = $(this).data('attribute');
                let value = $(this).val();
                updateFieldsUsedCars({ id: [id], attribute: [attribute], value: [value] });
            }
        });
    }
}

$('.clear-selection').click(function () {
    var id = $(this).data('id');
    $(`#${id} :radio`).prop('checked', false);
    $(`#${id} .active`).removeClass('active');
})


function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="searchStatus">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="searchStatusAll" value="all" > ALL <span class="badge badge-lg p-1" id="allCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" value="lastMonth"> Last Month <span class="badge badge-lg p-1" id="lastMonthCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" value="thisMonth" id="thisMonth" > This Month <span class="badge badge-lg p-1" id="thisMonthCount" ></span>
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
}



function editWritedown(writedownId = null) {
    if (writedownId) {

        $.ajax({
            url: '../php_action/fetchSelectedWritedown.php',
            type: 'post',
            data: { id: writedownId },
            dataType: 'json',
            success: function (response) {
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editWritedownForm')[0].reset();

                $('#retail_status :radio[name="retail_status"]').prop('checked', false);
                $('#retail_status .active').removeClass('active');
                (response.retail_status) ? $('#' + response.retail_status).prop('checked', true).click() : null;

                $('#writedownId').val(response.Inv_id);
                $('#stockno').val(response.stockno + " || " + response.vin);
                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Mileage: ${response.mileage} \n Age: ${response.endOfAge} \n Lot: ${response.lot} \n Balance: ${response.balance}`;
                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');


                $('#writedown').val(Number(response.writedown).toFixed(2));
                $('#mmr_balance').val(Number(response.mmr_balance).toFixed(2));
                $('#mmr_retail').val(Number(response.mmr_retail).toFixed(2));
                $('#mmr').val(Number(response.mmr).toFixed(2));

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

function updateFieldsUsedCars(obj) {
    if (obj) {
        $.ajax({
            url: '../php_action/updateFieldsUsedCars.php',
            type: 'post',
            data: obj,
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    Swal.fire("Added!", "Successfully Changed", "success");
                    setTimeout(() => {
                        Swal.close();
                    }, 900);
                    manageWritedownTable.ajax.reload(null, false);
                } // /response messages
            }

        }); // /ajax function to remove the brand
    }
}



function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
