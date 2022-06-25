"use strict";
var manageLocTable
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

        manageLocTable = $("#datatable-1").DataTable({

            responsive: !0,
            // responsive: {
            //     details: {
            //         type: 'column',
            //         target: 1
            //     }
            // },

            'ajax': '../php_action/fetchLocations.php',

            // working.... with both
            dom: `\n     
             <'row'<'col-12'P>>\n      
             <'row'<'col-sm-12 col-md-6'l>>\n  
            \n     
            <'row'<'col-sm-6 text-center text-sm-left p-3'>
                <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
            <'row'<'col-12'tr>>\n      
            <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n`,


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

            ],

            language: {
                searchPanes: {
                    count: "{total} found",
                    countFiltered: "{shown} / {total}"
                },
            },
            "order": [[0, "asc"]]
        })


        $("#editLocationForm").validate({
            ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
            rules: {
                dealerno: {
                    required: !0,
                },
                dealership: {
                    required: !0,
                },
                address: {
                    required: !0,
                },
                city: {
                    required: !0,
                },
                state: {
                    required: function (params) {
                        if (params.value == 0) {
                            params.classList.add('is-invalid');
                            $('#state').selectpicker('refresh');
                            params.classList.add('is-invalid');
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                zip: {
                    required: !0,
                },
                phone: {
                    required: !0,
                },
                // fax: {
                //     required: !0,
                // },

            },

            submitHandler: function (form, event) {
                // return true;
                event.preventDefault();

                var c = confirm('Do you really want to save this?');
                if (c == true) {
                    var form = $('#editLocationForm');
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

                                form[0].reset();
                                manageLocTable.ajax.reload(null, false);

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




    } else if (divRequest == "add") {
        $(function () {


            $('#travelTime').timepicker({ 'timeFormat': 'H:i', 'showDuration': true });
            $('#roundTrip').timepicker({ 'timeFormat': 'H:i', 'showDuration': true });

            $("#addLocationForm").validate({
                ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
                rules: {
                    dealerno: {
                        required: !0,
                    },
                    dealership: {
                        required: !0,
                    },
                    address: {
                        required: !0,
                    },
                    city: {
                        required: !0,
                    },
                    state: {
                        required: function (params) {
                            if (params.value == 0) {
                                params.classList.add('is-invalid');
                                $('#state').selectpicker('refresh');
                                params.classList.add('is-invalid');
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    zip: {
                        required: !0,
                    },
                    phone: {
                        required: !0,
                    },
                    // fax: {
                    //     required: !0,
                    // },

                },

                submitHandler: function (form, event) {
                    event.preventDefault();

                    var c = confirm('Do you really want to save this?');
                    if (c == true) {
                        var form = $('#addLocationForm');
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

                                    form[0].reset();

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

            $("#importLocForm").validate({
                rules: {
                    excelFile: {
                        required: true,
                    }
                },
                messages: {
                    excelFile: {
                        required: "File must be required",
                    }
                },
                submitHandler: function (form, event) {
                    // return true;
                    event.preventDefault();

                    var allowedFiles = [".xlsx", ".xls", ".csv"];
                    var fileUpload = $("#excelFile");
                    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
                    if (!regex.test(fileUpload.val().toLowerCase())) {
                        e1.fire("Files having extensions: " + allowedFiles.join(', ') + " only.");
                        return false;
                    }


                    var form = $('#importLocForm');
                    var fd = new FormData(document.getElementById("importLocForm"));
                    fd.append("CustomField", "This is some extra data");


                    $.ajax({
                        async: false,
                        url: form.attr('action'),
                        type: 'POST',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            response = JSON.parse(response);
                            console.log(response);

                            // console.log(response);
                            // console.log(response.success);
                            if (response.success == true) {

                                e1.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: response.messages,
                                    showConfirmButton: !1,
                                    timer: 2500,
                                })
                                form[0].reset();

                                if (response.erorStock && response.erorStock.length > 0) {
                                    var i = 0;
                                    $('#errorDiv').removeClass('d-none');
                                    // console.log(response.erorStock);
                                    while (response.erorStock[i]) {
                                        console.log(response.erorStock[i]);
                                        document.getElementById('errorList').innerHTML += `
                                            <span class="list-group-item list-group-item-danger">
                                            ${response.erorStock[i]}
                                        </span> `;
                                        i++;
                                    }

                                }

                            } else {

                                e1.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: response.messages,
                                    showConfirmButton: !1,
                                    timer: 2500
                                })
                            }


                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                    return false;
                    // return true;

                }
            });

        })



    }

});


function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
function clearErrorsList() {
    $('#errorList').html('');
}
function showDetails(id = null) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedLocation.php',
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

                $('#locHading u').html(`${response.dealership} ${response.dealer_no}`);

                $('#locAddress').html(`${response.address} ${response.city} ${response.state} ${response.zip}`);
                $('#locphone').html(`${response.phone}`);
                $('#locfax').html(`${response.fax}`);
                $('#locMcontact').html(`${response.main_contact}`);
                $('#locCellandPreffer').html(`${response.cell} ${response.preffer}`);
                $('#locDis').html(`${response.miles}`);

                var d = moment(response.travel_time, 'HH:mm');
                var d1 = moment(response.round_trip, 'HH:mm');

                $('#loctravelTime').html(d.hours() + ' hour ' + d.minutes() + ' minutes');
                $('#locRoundTrip').html(d1.hours() + ' hour ' + d1.minutes() + ' minutes');



                // var url = $('#editBtn').attr("href");
                // var domain = url.split('&i=')

                // $('#editBtn').attr("href", domain[0] + '&i=' + response.sale_id);


            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function

    } else {
        alert('error!! Refresh the page again');
    }
}

function editDetails(id = null) {
    if (id) {
        $('.spinner-grow').removeClass('d-none');
        // modal result
        $('.showResult').addClass('d-none')
        $('.modal-footer').addClass('d-none')
        $.ajax({
            url: '../php_action/fetchSelectedLocation.php',
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

                $('#locId').val(response.id);
                $('#dealerno').val(response.dealer_no);
                $('#dealership').val(response.dealership);
                $('#address').val(response.address);
                $('#city').val(response.city);
                $('#state').val(response.state);
                $('#zip').val(response.zip);
                $('#miles').val(response.miles);
                $('#travelTime').val(response.travel_time);
                $('#roundTrip').val(response.round_trip);
                $('#phone').val(response.phone);
                $('#fax').val(response.fax);
                $('#mcontact').val(response.main_contact);
                $('#cell').val(response.cell);
                $('#preffer').val(response.preffer);

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

function removeLocation(id = null) {
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
                    url: '../php_action/removeLocation.php',
                    type: 'post',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageLocTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });

    }
}
