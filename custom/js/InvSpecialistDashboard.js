"use strict";
var manageTable;
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});
autosize($(".autosize"));
$(function () {
    manageTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': 'php_action/fetchUsedCars2.php',
        "paging": true,
        "scrollX": false,
        "orderClasses": false,
        "deferRender": true,
        "pageLength": 25,
        dom: `
        <'row' 
        <'col-sm-4 text-left text-sm-left pl-3'<'#statusFilterDiv'>>
            <'col-sm-4 text-left text-sm-left pl-3'>
            <'col-sm-4 text-right text-sm-right mt-2 mt-sm-0'f>>\n
        <'row'<'col-12'tr>>\n      
        <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,
        columnDefs: [
            {
                targets: [0, 9],
                visible: false,
            },
            {
                targets: [1], // sold price
                createdCell: function (td, cellData, rowData, row, col) {
                    if (rowData[10] == "wholesale" || rowData[10] == null) {
                        $(td).addClass('bg-danger text-white');
                    } else {
                        $(td).removeClass('bg-danger text-white');
                    }
                }
            },
            {
                targets: [7], // sold price
                createdCell: function (td, cellData, rowData, row, col) {
                    if (rowData[7] == "Wholesale") {
                        $(td).addClass('bg-danger text-white');
                    } else {
                        $(td).removeClass('bg-danger text-white');
                    }
                }
            },
            {
                targets: [8], // sold price
                createdCell: function (td, cellData, rowData, row, col) {
                    if (rowData[8] == "Done") {
                        $(td).addClass('bg-success text-white');
                    } else {
                        $(td).removeClass('bg-success text-white');
                    }


                }
            },
        ],

        "drawCallback": function (settings, start, end, max, total, pre) {
            var json = this.fnSettings().json;
            if (json) {
                let allCount = 0, notDone = 0, roclosed = 0, totalDoneCount = 0, percentage = 0;
                let totalRetail = 0;
                $('#datatable-1').DataTable()
                    .rows()
                    .data()
                    .filter(function (data, index) {
                        var retail_status = data[10];
                        allCount += 1;
                        if (retail_status != 'wholesale' && retail_status != null && data[8] != 'Done' && data[9] != 'closed') {
                            notDone += 1;
                        }

                        if (retail_status != 'wholesale' && retail_status != null && data[8] != 'Done' && data[9] == 'closed') {
                            roclosed += 1;
                        }
                        if (retail_status != 'wholesale' && retail_status != null && data[8] == 'Done' && data[9] != 'closed') {
                            totalDoneCount += 1;
                        }

                        if (retail_status != 'wholesale' && retail_status != null) {
                            totalRetail += 1;
                        }


                        return true;
                    });
                // percentage = (totalDoneCount / allCount) * 100;
                percentage = (totalDoneCount / totalRetail) * 100;
                // percentage = Math.round(percentage);
                percentage = percentage.toFixed(2)

                $(`#allCount`).html(allCount);
                $('#notDoneCount').html(notDone);
                $('#roclosedCount').html(roclosed);
                $('#done-percentage span').html(percentage);

                if (percentage >= 90) {
                    $('#done-percentage').addClass('text-success');
                    $('#done-percentage').removeClass('text-danger');
                    $('#done-percentage').removeClass('text-oriange');
                } else if (percentage >= 70 && percentage < 90) {
                    $('#done-percentage').addClass('text-oriange');
                    $('#done-percentage').removeClass('text-danger');
                    $('#done-percentage').removeClass('text-success');
                } else if (percentage < 70) {
                    $('#done-percentage').addClass('text-danger');
                    $('#done-percentage').removeClass('text-oriange');
                    $('#done-percentage').removeClass('text-success');
                }

            }
        },


        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editUsedCar(" + data[0] + ")"
                });
            }
        },
        "order": [[3, "desc"]],
    });
    writeStatusHTML();
    $('#all').click();

    $.fn.dataTable.ext.search.push(
        function (settings, data, index, rowData) {
            var tableNode = manageTable.table().node();
            var retail_status = rowData[10];
            var notes_2 = rowData[8];
            var uci = rowData[9];
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
            if (searchStatus[0] === 'notDone') {
                if (retail_status != 'wholesale' && retail_status != null && notes_2 != 'Done' && uci != 'closed') {
                    return true;
                } else {
                    return false;
                }
            }
            if (searchStatus[0] === 'roclosed') {
                if (retail_status != 'wholesale' && retail_status != null && notes_2 != 'Done' && uci == 'closed') {
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

        setTimeout(() => {
            // manageTable.draw();  // working
            manageTable.ajax.reload(null, false);
            manageTable.columns.adjust().draw();
        }, 1000);

    });


    $("#updateUsedCarsForm").validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var form = $('#updateUsedCarsForm');
            var fd = new FormData(document.getElementById("updateUsedCarsForm"));
            fd.append("CustomField", "This is some extra data");
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    if (response.success == true) {
                        e1.fire({
                            position: "center",
                            icon: "success",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 1500
                        })
                        manageTable.ajax.reload(null, false);
                        // form[0].reset();
                        $('#modal8').modal('hide');

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

});

$('.clear-selection').on('click', function () {
    var id = $(this).data('id');
    $('#' + id).val('');
    $('#' + id).selectpicker('refresh');
})

function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
                <div id="sort">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-flat-primary active">
                            <input type="radio" name="searchStatus" id="all" value="all"> All Used <span class="badge badge-lg p-1" id="allCount" ></span>
                        </label>
                        <label class="btn btn-flat-primary"> 
                            <input type="radio" name="searchStatus" id="notDone" value="notDone"> Not Done <span class="badge badge-lg p-1" id="notDoneCount" ></span>
                        </label>
                        <label class="btn btn-flat-primary">
                            <input type="radio" name="searchStatus" id="roclosed" value="roclosed"> Ro Closed <span class="badge badge-lg p-1" id="roclosedCount" ></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>`;
    }
}



function editUsedCar(id) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: 'php_action/fetchSelectedUsedCar.php',
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


                let age = response.age;
                // get day difference
                if (response.date_in != '' && response.date_in != null) {
                    var given = moment(response.date_in, "MM-DD-YYYY").subtract(1, 'days');
                    var current = moment().startOf('day');
                    age = moment.duration(current.diff(given)).asDays();
                }


                // // stockno  stock and vin
                // // selectedDetails   stock details
                $('#vehicleId').val(id);
                $('#stockno').val(response.stockno + " || " + response.vin);
                $('#submittedBy').val(response.submitted_by);
                var detailsDiv = `${response.stocktype} ${response.year} ${response.make} ${response.model} \n Mileage: ${response.mileage} \n Age: ${age} \n Lot: ${response.lot} \n Balance: ${response.balance}`;
                $('#selectedDetails').html(detailsDiv);
                $('#selectedDetails').addClass('text-center');



                // // uci

                $('#notes_1').val(response.notes_1 ? response.notes_1 : "");
                $('#notes_2').val(response.notes_2 ? response.notes_2 : "");
                // $('#uci').val(response.uci ? response.uci : "");


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