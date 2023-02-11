"use strict";
var manageDataTable;
var stockArray = [];
var collapsedGroups = {};

var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})
autosize($(".autosize"));

$(function () {
    $('.nav-link').removeClass('active');
    $('#more').addClass('active');

    $(".datepicker").datepicker({
        todayHighlight: !0,
        autoclose: true,
        todayBtn: "linked",
        language: 'pt-BR',
        format: 'mm-dd-yyyy',
    });


    manageDataTable = $("#datatable-1").DataTable({



        responsive: !0,
        'ajax': '../php_action/fetchRdr.php',

        dom: `  
            <'row'<'col-12'P>>\n
            <'row'<'col-sm-12 text-sm-left col-md-4 mb-2 '<'#statusFilterDiv'>> <'col-sm-12 col-md-4 text-center 'B> <'col-sm-12 col-md-4 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
           <'row'<'col-12'tr>>\n      
           <'row align-items-baseline'
           <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
           <'col-md-5'p>>\n`,

        "pageLength": 50,
        searchPanes: {
            columns: [1, 2, 3, 4],
        },
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'RDR (RETAIL DELIVERY REGISTRATION)',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'excelHtml5',
                title: 'RDR (RETAIL DELIVERY REGISTRATION)',
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                extend: 'print',
                title: 'RDR (RETAIL DELIVERY REGISTRATION)',
                exportOptions: {
                    columns: [':visible']
                }
            },
        ],
        columnDefs: [
            {
                targets: [0],
                visible: false,
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [1, 2, 3, 4],
            },
            {
                targets: [7, 8, 9],
                render: function (data, type, row) {
                    return data != "" ? moment(data).format('MM-DD-YYYY') : "";
                },
            }
        ],

        language: {
            "infoFiltered": "",
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
        rowGroup: {
            dataSrc: 1,
            startRender: function (rows, group) {
                var collapsed = !!collapsedGroups[group];

                var filteredData = $('#datatable-1').DataTable()
                    .rows({ search: 'applied' })
                    .data()
                    .filter(function (data, index) {
                        return data[1] == group ? true : false;
                    });
                // setting total numbers
                $('#' + group + 'Count').html(filteredData.length);

                return $('<tr/>')
                    .append('<td colspan="16">' + group + ' (' + filteredData.length + ')</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
        },
        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {

                var allCount = 0, todoCount = 0, waitingCount = 0;

                $('#datatable-1').DataTable()
                    .rows()
                    .data()
                    .filter(function (data, index) {
                        var entered = data[8];
                        var approved = data[9];
                        var certified = data[6];
                        if (entered == null || entered == '') {
                            todoCount += 1;
                        }
                        if ((entered != null && entered != '') && (approved == null || approved == '')) {
                            waitingCount += 1;
                        }
                        allCount += 1;
                        return true;
                    });

                $(`#allCount`).html(allCount);
                $('#todoCount').html(todoCount);
                $('#waitingCount').html(waitingCount);
            }
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).attr({
                    "data-toggle": "modal",
                    "data-target": "#editDetails",
                    "onclick": "editDetails(" + data[0] + ")"
                });
            }
        },
        "order": [[1, "asc"]],

    });

    writeStatusHTML();
    $('#todo').click();



    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index) {
            var tableNode = manageDataTable.table().node();
            var certified = searchData[6];
            var entered = searchData[8];
            var approved = searchData[9];

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

            if (searchStatus[0] === 'all') {
                return true;
            }
            if (searchStatus[0] === 'todo') {
                if (entered == null || entered == '') {
                    return true;
                }
            }
            if (searchStatus[0] === 'waiting') {
                if ((entered != null && entered != '') && (approved == null || approved == '')) {
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

        manageDataTable.draw();  // working
        manageDataTable.searchPanes.rebuildPane();

    });


    // ---------------------- Edit Sale---------------------------

    // validateState
    $("#editForm").validate({

        submitHandler: function (form, event) {
            // return true;
            event.preventDefault();

            var form = $('#editForm');
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

                        manageDataTable.ajax.reload();
                        manageDataTable.ajax.reload(null, false);
                        manageDataTable.searchPanes.rebuildPane();
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




function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div id="sort" class="d-flex">
                <div class="btn-group1 btn-group-toggle mr-2" data-toggle="buttons">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="all" value="all"> All <span class="badge badge-lg p-1" id="allCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="todo" value="todo"> Todo <span class="badge badge-lg p-1" id="todoCount" ></span>
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="waiting" value="waiting"> Waiting Approval <span class="badge badge-lg p-1" id="waitingCount" ></span>
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
            url: '../php_action/fetchSelectedRdr.php',
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

                $('#submittedBy').val(response.submittedByName);

                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Vin: ${response.vin} \n Mileage: ${response.mileage} \n Age: ${response.age} \n Lot: ${response.lot} ${($('#isConsultant').val() == "true") ? `` : `\n Balance: ${response.balance} \n ${response.stocktype == "USED" ? `Gross:` + Number(response.gross) : ''}`} `;


                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');



                if (response.stocktype == "USED") {
                    $('#grossDiv').removeClass('v-none')
                } else {
                    $('#grossDiv').addClass('v-none')
                }

                $('#sale_id').val(response.sale_id);
                $('#stockId').val(response.stockno);
                $('#salesPerson').val(response.salesConsultant);
                $('#iscertified').val((response.certified == "on" ? "YES" : "NO"));

                $('#fname').val(response.fname);
                $('#mname').val(response.mname);
                $('#lname').val(response.lname);
                $('#state').val(response.state);

                $("#deliveryDate").datepicker("setDate", response.delivered != "" && response.delivered != null ? moment(response.delivered).format('MM-DD-YYYY') : "");
                $("#enteredDate").datepicker("setDate", response.entered != "" && response.entered != null ? moment(response.entered).format('MM-DD-YYYY') : "");
                $("#approvedDate").datepicker("setDate", response.approved != "" && response.approved != null ? moment(response.approved).format('MM-DD-YYYY') : "");
                $('#rdrNotes').val(response.rdr_notes);


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
$('#enteredDate').on('change', function () {
    let approved = $('#approvedDate').val();
    let iscertified = $('#iscertified').val();
    console.log(iscertified);
    if (approved == '' && iscertified == 'NO') {
        $('#approvedDate').val($(this).val());
    }

    // if()
})


function toggleInfo(className) {
    $('.' + className).toggleClass('hidden')
}
