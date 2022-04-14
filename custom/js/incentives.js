"use strict";
var manageSoldLogsTable, maxFileLimit = 10;
var stockArray = [];

var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})


$(function () {

    loadSaleManager();

    var t = "rtl" === $("html").attr("dir");
    $(".slick-2").slick({
        // rtl: t, 
        variableWidth: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: false,
    })



    $("#saleDate").datetimepicker({
        language: 'pt-BR',
        format: 'M-dd-yyyy',
        minView: 3,
        pickTime: false,
    });
    $(".datePicker").datetimepicker({
        language: 'pt-BR',
        format: 'M-dd-yyyy',
        autoclose: true,
        minView: 2,
        pickTime: false,
    });


    function loadUncomplete() {
        manageSoldLogsTable.button(0).active(true);
        manageSoldLogsTable.button(1).active(false);

        $.fn.dataTable.ext.search.pop();
        manageSoldLogsTable.search('').draw();
        var tableNode = $('#datatable-1')[0];

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                if (settings.nTable !== tableNode) {
                    return true;
                }

                if (data[11] === "" || data[12] === "" || data[13] === "" || data[14] === "" || data[15] === "" || data[16] === "" || data[17] === "" || data[18] === "") {
                    return true;
                }

                return false;
            }
        )

        manageSoldLogsTable.draw();  // working

    }



    manageSoldLogsTable = $("#datatable-1").DataTable({

        scrollX: !0,
        scrollCollapse: !0,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 1
        },

        'ajax': '../php_action/fetchIncentives.php',

        // working.... with both
        // dom: "Pfrtip",
        dom: `\n     
             <'row'<'col-12'P>>\n      
             <'row'<'col-sm-12 col-md-6'l>>\n  
            \n     
            <'row'<'col-sm-6 text-center text-sm-left p-3'B>
                <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
            <'row'<'col-12'tr>>\n      
            <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

        "pageLength": 25,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [0, 1, 2, 3],
            stateSave: true,
        },
        buttons: [
            {
                text: 'Pending Incentives',
                action: function (e, dt, node, config) {
                    loadUncomplete();
                },

            },
            {
                text: 'Submitted Incentives',
                action: function (e, dt, node, config) {
                    manageSoldLogsTable.button(0).active(false);
                    manageSoldLogsTable.button(1).active(true);

                    $.fn.dataTable.ext.search.pop();
                    manageSoldLogsTable.search('').draw();
                    var tableNode = this.table(0).node();
                    // console.log(tableNode);
                    $.fn.dataTable.ext.search.push(
                        function (settings, data, dataIndex) {
                            if (settings.nTable !== tableNode) {
                                return true;
                            }
                            if (data[11] !== "" && data[12] !== "" && data[13] !== "" && data[14] !== "" && data[15] !== "" && data[16] !== "" && data[17] !== "" && data[18] !== "") {
                                return true;
                            }
                            return false
                        }
                    )

                    manageSoldLogsTable.draw();  // working
                   
                

                },

            }
        ],


        "createdRow": function (row, data, dataIndex) {
            changePillCSS(row, data, 11, 4);  // collegeDate Index  11 +  college index 4
            changePillCSS(row, data, 12, 5);
            changePillCSS(row, data, 13, 6);
            changePillCSS(row, data, 14, 7);
            changePillCSS(row, data, 15, 8);
            changePillCSS(row, data, 16, 9);
            changePillCSS(row, data, 17, 10);
        },

        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3],

            },
            { 'visible': false, 'targets': [11, 12, 13, 14, 15, 16, 17, 18] }, //hide columns 
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

    });


    loadUncomplete();



    // ---------------------- Edit Sale---------------------------

    // validateState
    $("#editSoldTodoForm").validate({

        submitHandler: function (form, event) {
            // return true;
            event.preventDefault();

            var c = confirm('Do you really want to save this?');
            if (c == true) {
                $('[disabled]').removeAttr('disabled');
                // var form = $('#editSoldTodoForm');
                var form = $('#editSoldTodoForm');
                var fd = new FormData(document.getElementById("editSoldTodoForm"));
                fd.append("CustomField", "This is some extra data");

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        response = JSON.parse(response)
                        console.log(response);

                        if (response.success == true) {
                            e1.fire({
                                position: "top-end",
                                icon: "success",
                                title: response.messages,
                                showConfirmButton: !1,
                                timer: 2500,
                            })

                            manageSoldLogsTable.ajax.reload();
                            manageSoldLogsTable.ajax.reload(null, false);
                            manageSoldLogsTable.searchPanes.rebuildPane();

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

    $("#images").on("change", function () {
        if ($("#images")[0].files.length > maxFileLimit) {
            Swal.fire("Deleted!", "You can Select only " + maxFileLimit + " images More", "error")
            $("#images").val(null);
        }
    });


});

function removeImage(ele) {
    var parents = $(ele).parent()
    var i = $(parents).data("slick-index");
    $('.slick-2').slick('slickRemove', i);
    // $('.slick-2').slick('unslick');
    $('.slick-2').slick('refresh');
    maxFileLimit += 1;
    $('#maxLimit').html(maxFileLimit);

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

function changePillCSS(row, data, compareIndex, valueIndex) {
    if (data[compareIndex] == "") {
        return ($(row).find('td:eq(' + valueIndex + ')').html(`<span class="badge badge-lg badge-danger">${data[valueIndex]}</span>`))
    } else {
        return ($(row).find('td:eq(' + valueIndex + ')').html(`<span class="badge badge-lg badge-success">${data[valueIndex]}</span>`))
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
            url: '../php_action/fetchSelectedIncentive.php',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function (response) {

                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                setTimeout(() => {
                    $('.slick-2').slick('refresh');
                }, 100);


                console.log(response);
                maxFileLimit = 10;
                // var images = JSON.parse(response.images);
                $("#images").val(null);
                $('.slick-slider').slick('slickRemove', null, null, true);
                // console.log($('#slickSlider'));
                if (response.images !== "") {
                    var images = response.images.split(",");
                    console.log(images);
                    images.forEach((element, index) => {
                        document.getElementById('slickSlider').innerHTML += `<div class="carousel-item">
                        <img src="../assets/IncentivesProof/${element}" class="card-img">
                        <input type="hidden" name="uploads[]" id="uploads${index + 1}" value="${element}">
                        <div class="card" onclick="removeImage(this)">X</div>
                    </div>`
                    });

                    maxFileLimit = 10 - (images.length);
                }


                $('#maxLimit').html(maxFileLimit);


                $('#incentiveId').val(id);

                $('#customerName').val(response.sale_consultant);
                $('#stockNo').val(response.stockno);
                $('#vehicle').val(`${response.stocktype} ${response.year} ${response.make} ${response.model}`);
                $('#state').val(response.state);
                $('#saleDate').datetimepicker('update', response.date);



                $('#college').val(response.college);
                $('#military').val(response.military);
                $('#loyalty').val(response.loyalty);
                $('#conquest').val(response.conquest);
                $('#misc1').val(response.misc1);
                $('#misc2').val(response.misc2);
                $('#misc3').val(response.misc3);

                $('#collegeDate').val(response.college_date ? moment(response.college_date).format('MMM-DD-YYYY') : "");
                $('#militaryDate').val(response.military_date ? moment(response.military_date).format('MMM-DD-YYYY') : "");
                $('#loyaltyDate').val(response.loyalty_date ? moment(response.loyalty_date).format('MMM-DD-YYYY') : "");
                $('#conquestDate').val(response.conquest_date ? moment(response.conquest_date).format('MMM-DD-YYYY') : "");
                $('#misc1Date').val(response.misc1_date ? moment(response.misc1_date).format('MMM-DD-YYYY') : "");
                $('#misc2Date').val(response.misc2_date ? moment(response.misc2_date).format('MMM-DD-YYYY') : "");
                $('#misc3Date').val(response.misc3_date ? moment(response.misc3_date).format('MMM-DD-YYYY') : "");


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

function removeTodo(id = null) {
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
            console.log(t);
            if (t.isConfirmed == true) {

                $.ajax({
                    url: '../php_action/removeSoldTodo.php',
                    type: 'post',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageSoldLogsTable.ajax.reload(null, false);
                            manageSoldLogsTable.searchPanes.rebuildPane();
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });

    }
}



function toggleInfo(className) {
    $('.' + className).toggleClass('hidden')
}
