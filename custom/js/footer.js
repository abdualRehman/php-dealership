"use strict";
var manageAvailTable, stockArray, collapsedGroups = {};
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});
var siteURL = localStorage.getItem('siteURL');
autosize($(".autosize"));

$(function () {

    $('#todayDate').html(moment().format("MMM DD,YYYY"));

    $("#editAvailibilityForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();
            var form = $('#editAvailibilityForm');
            $.ajax({
                type: "POST",
                url: `${siteURL}/php_action/editAvailability.php`,
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {

                    if (response.success == true) {
                        e1.fire({
                            position: "center",
                            icon: "success",
                            title: response.messages,
                            showConfirmButton: !1,
                            timer: 1500
                        })
                        manageAvailTable.ajax.reload(null, false);
                    } else {
                        e1.fire({
                            position: "center",
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


function loadSchedules() {

    if ($.fn.dataTable.isDataTable('#datatable-4')) {
        return true;
    }
    else {
        manageAvailTable = $("#datatable-4").DataTable({
            responsive: !0,
            'ajax': `${siteURL}/php_action/fetchAvailability.php`,
            dom: `
        <'row'<'col-sm-12 text-center text-sm-right'f> >\n  
       <'row'<'col-12'tr>>\n      
       <'row align-items-baseline'
       <'col-md-12'p>>\n`,
            "pageLength": 25,
            columnDefs: [
                {
                    targets: [0, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    visible: false,
                },
                {
                    targets: 1,
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).html(rowData[15]);
                        $(td).addClass('link-css');
                        $(td).attr({
                            "data-toggle": "modal",
                            "data-target": "#editSchedule",
                            "onclick": "editSchedule(" + rowData[0] + ")"
                        });
                    }
                },
                {
                    targets: 2,
                    createdCell: function (td, cellData, rowData, row, col) {
                        var obj = rowData[20];
                        var today = moment().format("MM-DD-YYYY")
                        if (obj.previous_date != today) {
                            // change 12h to 24h to calculate time
                            var start, end;
                            // get hours differece
                            var ms = moment(obj.end, "h:mmA").diff(moment(obj.start, "h:mmA"));
                            var d = moment.duration(ms);
                            var hours = d.asHours();
                            if (hours < 0) {
                                var todayDate = moment().format("MM-DD-YYYY");
                                var tomorrow = moment().add(1, 'day').format('MM-DD-YYYY');
                                start = todayDate + obj.start;
                                end = tomorrow + obj.end;
                            } else {
                                var todayDate = moment().format("MM-DD-YYYY");
                                start = todayDate + obj.start;
                                end = todayDate + obj.end;
                            }

                            let current_time = moment().format("MM-DD-YYYY h:mmA")
                            var format = 'MM-DD-YYYY h:mmA';
                            var time = moment(current_time, format),
                                beforeTime = moment(start, format),
                                afterTime = moment(end, format);

                            if (time.isBetween(beforeTime, afterTime)) {
                                $(td).html('<i class="marker marker-dot marker-lg text-success"></i> Available');
                                $(td).addClass('text-success font-initial');
                            } else {
                                $(td).html("Not Available");
                            }

                        } else {
                            //status
                            // console.log(rowData);
                            if (rowData[16] == 'Available') {
                                $(td).html('<i class="marker marker-dot marker-lg text-success"></i> Available');
                                $(td).addClass('text-success font-initial');
                            } else {
                                $(td).html(rowData[16]);
                            }
                        }

                    }
                },
                {
                    targets: 3, //Sales Consultant
                    render: function (data, type, row) {
                        return row[19];
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).html(rowData[19]);
                    }
                },
                {
                    targets: 4, //NOTES
                    createdCell: function (td, cellData, rowData, row, col) {
                        var obj = rowData[20];
                        var today = moment().format("MM-DD-YYYY")
                        if (obj.previous_date == today) {
                            $(td).html(rowData[17]);
                        } else {
                            $(td).html("");
                        }
                    }
                },
                {
                    targets: 5, //MANAGER
                    createdCell: function (td, cellData, rowData, row, col) {
                        var obj = rowData[20];
                        var today = moment().format("MM-DD-YYYY")
                        if (obj.previous_date == today) {
                            $(td).html(rowData[18]);
                        } else {
                            $(td).html("");
                        }
                    }
                },
                {
                    targets: 6, //sun
                    createdCell: function (td, cellData, rowData, row, col) {
                        var start = rowData[13] ? moment(rowData[13], ["h:mmA"]).format("h:mm") : "";
                        var end = rowData[14] ? moment(rowData[14], ["h:mmA"]).format("h:mm") : "";
                        $(td).html((start && end) ? (start + '-' + end) : "OFF");
                    }
                },
                {
                    targets: 7, //mon
                    createdCell: function (td, cellData, rowData, row, col) {
                        var start = rowData[1] ? moment(rowData[1], ["h:mmA"]).format("h:mm") : "";
                        var end = rowData[2] ? moment(rowData[2], ["h:mmA"]).format("h:mm") : "";
                        $(td).html((start && end) ? (start + '-' + end) : "OFF");
                    }
                },
                {
                    targets: 8, //tue
                    createdCell: function (td, cellData, rowData, row, col) {
                        var start = rowData[3] ? moment(rowData[3], ["h:mmA"]).format("h:mm") : "";
                        var end = rowData[4] ? moment(rowData[4], ["h:mmA"]).format("h:mm") : "";
                        $(td).html((start && end) ? (start + '-' + end) : "OFF");
                    }
                },
                {
                    targets: 9, //wed
                    createdCell: function (td, cellData, rowData, row, col) {
                        var start = rowData[5] ? moment(rowData[5], ["h:mmA"]).format("h:mm") : "";
                        var end = rowData[6] ? moment(rowData[6], ["h:mmA"]).format("h:mm") : "";
                        $(td).html((start && end) ? (start + '-' + end) : "OFF");
                    }
                },
                {
                    targets: 10, //thu
                    createdCell: function (td, cellData, rowData, row, col) {
                        var start = rowData[7] ? moment(rowData[7], ["h:mmA"]).format("h:mm") : "";
                        var end = rowData[8] ? moment(rowData[8], ["h:mmA"]).format("h:mm") : "";
                        $(td).html((start && end) ? (start + '-' + end) : "OFF");
                    }
                },
                {
                    targets: 11, //fri
                    createdCell: function (td, cellData, rowData, row, col) {
                        var start = rowData[9] ? moment(rowData[9], ["h:mmA"]).format("h:mm") : "";
                        var end = rowData[10] ? moment(rowData[10], ["h:mmA"]).format("h:mm") : "";
                        $(td).html((start && end) ? (start + '-' + end) : "OFF");
                    }
                },
                {
                    targets: 12, //sat
                    createdCell: function (td, cellData, rowData, row, col) {
                        var start = rowData[11] ? moment(rowData[11], ["h:mmA"]).format("h:mm") : "";
                        var end = rowData[12] ? moment(rowData[12], ["h:mmA"]).format("h:mm") : "";
                        $(td).html((start && end) ? (start + '-' + end) : "OFF");
                    }
                },

            ],
            rowGroup: {
                dataSrc: 19,
                startRender: function (rows, group) {
                    var collapsed = !!collapsedGroups[group];

                    var filteredData = $('#datatable-4').DataTable()
                        .rows()
                        .data()
                        .filter(function (data, index) {
                            return data[19] == group ? true : false;
                        });
                    // setting total numbers
                    $('#' + group + 'Count').html(filteredData.length);

                    return $('<tr/>')
                        .append('<td colspan="11">' + group + ' (' + filteredData.length + ')</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
                }
            },

            "order": [[3, "desc"]]
        })
    }

}


function toggleSidebar(e) {
    if ($(e).children("i").hasClass("fa-compress")) {
        $(e).children("i").removeClass('fa-compress');
        $(e).children("i").addClass('fa-expand');
    } else {
        $(e).children("i").addClass('fa-compress');
        $(e).children("i").removeClass('fa-expand');
    }
    $('#sidemenu-todo.sidemenu-wider').toggleClass('expanded');

    if ($('#sidemenu-todo').hasClass('expanded')) {
        setTableVisibility([0, 3]);
    } else {
        setTableVisibility([0, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
    }
    manageAvailTable.draw();
    manageAvailTable.ajax.reload(null, false);
}


function setTableVisibility(columnArray) {
    var allC = manageAvailTable.columns()[0];
    allC.forEach(column => {
        var col = manageAvailTable.column(column);
        if (columnArray.indexOf(column) != -1) {
            col.visible(false);
        } else {
            col.visible(true);
        }
    });
    manageAvailTable.columns.adjust().draw();
}


function editSchedule(id = null) {
    if (id) {

        $.ajax({
            url: `${siteURL}/php_action/fetchSelectedSchedule.php`,
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editAvailibilityForm')[0].reset();


                $('#shceduleId').val(response.id);
                $('#availability').val(response.today_availability);
                $('#offNotes').val(response.off_notes);

                $('.selectpicker').selectpicker('refresh');
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



function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}