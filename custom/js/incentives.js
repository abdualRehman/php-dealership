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

    // $(".slick-2").slick({
    //     variableWidth: false,
    //     slidesToShow: 2,
    //     slidesToScroll: 1,
    //     infinite: false,
    // });

    $('.slick-2').each(function () {
        var slider = $(this);
        slider.slick({

            infinite: false,
            autoplay: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            responsive: [{
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
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


    function comparision(name, date) {
        // if(name != 'Yes' && name != 'No'){
        if (name.startsWith("Yes/Approved by")) {
            // console.log(name);
            if (date == "") {
                return false;
            } else {
                return true;
            }
        }
        else if (name == 'No') {
            return true;
        }
        else if (name == 'Yes') {
            return false;
        }
    }

    manageSoldLogsTable = $("#datatable-1").DataTable({

        responsive: !0,

        'ajax': '../php_action/fetchIncentives.php',

        // working.... with both
        // dom: "Pfrtip",
        // dom: `\n     
        //      <'row'<'col-12'P>>\n      
        //     <'row'<'col-sm-6 text-center text-sm-left pt-0 pb-0 'B>
        //         <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
        //     <'row'<'col-12'tr>>\n      
        //     <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,

        dom: `\n     
        <'row'<'col-12'P>>\n
        <'row'<'col-sm-12 text-sm-left col-md-3 mb-2'<'#statusFilterDiv'>> <'col-sm-12 col-md-6 text-center text-sm-left 'B> <'col-sm-12 col-md-3 text-center text-sm-right mt-2 mt-sm-0'f> >\n  
       <'row'<'col-12'tr>>\n      
       <'row align-items-baseline'
       <'col-md-5'i><'col-md-2 mt-2 mt-md-0'l>
       <'col-md-5'p>>\n`,

        "pageLength": 25,
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [0, 1, 2, 3],
            stateSave: true,
        },
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Sale Incentives',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Sale Incentives',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                }
            },
            {
                extend: 'print',
                title: 'Sale Incentives',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                }
            },
        ],

        "createdRow": function (row, data, dataIndex) {
            changePillCSS(row, data, 11, 4);  // collegeDate Index  11 +  college index 4
            changePillCSS(row, data, 12, 5);
            changePillCSS(row, data, 13, 6);
            changePillCSS(row, data, 14, 7);
            changePillCSS(row, data, 15, 8);
            changePillCSS(row, data, 16, 9);
            changePillCSS(row, data, 17, 10);
            $(row).attr({
                "data-toggle": "modal",
                "data-target": "#editDetails",
                "onclick": "editDetails(" + data[19] + ")"
            });
        },

        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3],

            },
            { 'visible': false, 'targets': [11, 12, 13, 14, 15, 16, 17, 18, 19] }, //hide columns 
        ],

        language: {
            "infoFiltered": "",
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },

    });


    writeStatusHTML();
    $('#pending').click();
    loadSaleManager();


    $.fn.dataTable.ext.search.push(
        function (settings, data, index) {
            var tableNode = manageSoldLogsTable.table().node();

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

            if (searchStatus[0] === 'pending') {
                if (data[4] != 'No' || data[5] != 'No' || data[6] != 'No' || data[7] != 'No' || data[8] != 'No' || data[9] != 'No' || data[10] != 'No') {
                    if (
                        comparision(data[4], data[11]) !== true ||
                        comparision(data[5], data[12]) !== true ||
                        comparision(data[6], data[13]) !== true ||
                        comparision(data[7], data[14]) !== true ||
                        comparision(data[8], data[15]) !== true ||
                        comparision(data[9], data[16]) !== true ||
                        comparision(data[10], data[17]) !== true
                    ) {
                        return true;
                    }
                }
                return false;
            }
            if (searchStatus[0] === 'submitted') {

                if (data[4] != 'No' || data[5] != 'No' || data[6] != 'No' || data[7] != 'No' || data[8] != 'No' || data[9] != 'No' || data[10] != 'No') {
                    if (
                        comparision(data[4], data[11]) === true &&
                        comparision(data[5], data[12]) === true &&
                        comparision(data[6], data[13]) === true &&
                        comparision(data[7], data[14]) === true &&
                        comparision(data[8], data[15]) === true &&
                        comparision(data[9], data[16]) === true &&
                        comparision(data[10], data[17]) === true
                    ) {

                        return true;
                    }
                }
                return false

            }

            return false;
        }
    );


    $('input:radio').on('change', function () {
        $('#datatable-1').block({
            message: '\n        <div class="spinner-grow text-success"></div>\n        <h1 class="blockui blockui-title">Processing...</h1>\n      ',
            timeout: 1e3
        });
        manageSoldLogsTable.draw();  // working
        manageSoldLogsTable.searchPanes.rebuildPane();

    });
    // ---------------------- Edit Sale---------------------------

    // validateState
    $("#editSoldTodoForm").validate({

        submitHandler: function (form, event) {
            // return true;
            event.preventDefault();

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


function writeStatusHTML() {
    var element = document.getElementById('statusFilterDiv');
    if (element) {
        element.innerHTML = `<div class="row">
        <div class="col-md-12">
            <div id="sort">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="pending" value="pending"> Pending Incentives
                    </label>
                    <label class="btn btn-flat-primary">
                        <input type="radio" name="searchStatus" id="submitted" value="submitted"> Submitted Incentives
                    </label>
                </div>
            </div>
        </div>
    </div>`;
    }
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

$('.datePicker').on('change', function () {
    var p = $(this).parents('div.row')[0];
    $(p).children('div.col-md-6').find('> div.form-group > div.dropdown.bootstrap-select').removeClass('border-red');
})


function checkValue(ele) {
    var value = ele.value;
    if (!isNaN(value)) {
        $('#' + ele.name + 'Date').attr('disabled', false);
    } else {

        $('#' + ele.name + 'Date').val("");
        $('#' + ele.name + 'Date').attr('disabled', true);
    }

    if (!isNaN(value) || value == 'Yes') {
        var date = $('#' + ele.name + 'Date').val();
        if (date == '') {
            $('#' + ele.name).parent().addClass('border-red');
        } else {
            $('#' + ele.name).parent().removeClass('border-red');
        }
    } else {
        $('#' + ele.name).parent().removeClass('border-red');
    }

}


function loadSaleManager() {
    // var sales_manager_id = 1;
    var sales_manager_id = 67;
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
                $('#vin').val(response.vin);
                $('#saleDate').datetimepicker('update', response.date);



                $('#college').val(response.college);
                $('#military').val(response.military);
                $('#loyalty').val(response.loyalty);
                $('#conquest').val(response.conquest);
                $('#misc1').val(response.misc1);
                $('#misc2').val(response.misc2);
                $('#leaseLoyalty').val(response.lease_loyalty);

                $('#collegeDate').val(response.college_date ? moment(response.college_date).format('MMM-DD-YYYY') : "");
                $('#militaryDate').val(response.military_date ? moment(response.military_date).format('MMM-DD-YYYY') : "");
                $('#loyaltyDate').val(response.loyalty_date ? moment(response.loyalty_date).format('MMM-DD-YYYY') : "");
                $('#conquestDate').val(response.conquest_date ? moment(response.conquest_date).format('MMM-DD-YYYY') : "");
                $('#misc1Date').val(response.misc1_date ? moment(response.misc1_date).format('MMM-DD-YYYY') : "");
                $('#misc2Date').val(response.misc2_date ? moment(response.misc2_date).format('MMM-DD-YYYY') : "");
                $('#leaseLoyaltyDate').val(response.lease_loyalty_date ? moment(response.lease_loyalty_date).format('MMM-DD-YYYY') : "");

                checkValue({ name: "college", value: response.college });
                checkValue({ name: "military", value: response.military });
                checkValue({ name: "loyalty", value: response.loyalty });
                checkValue({ name: "conquest", value: response.conquest });
                checkValue({ name: "misc1", value: response.misc1 });
                checkValue({ name: "misc2", value: response.misc2 });
                checkValue({ name: "leaseLoyalty", value: response.lease_loyalty });

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
