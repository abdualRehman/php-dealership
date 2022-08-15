"use strict";
var manageDataTable;
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
});
toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": 0,
    "extendedTimeOut": 0,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "tapToDismiss": true
};

$(function () {


    loadTypeHead(1);


    manageDataTable = $("#datatable-1").DataTable({
        responsive: !0,
        'ajax': '../php_action/fetchRdrRules.php',
        dom: "Pfrtip",
        searchPanes: {
            cascadePanes: !0,
            viewTotal: !0,
            columns: [0, 1, 2, 3],
        },
        "pageLength": 25,
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: [0, 1, 2, 3],
            },
        ],

        language: {
            searchPanes: {
                count: "{total} found",
                countFiltered: "{shown} / {total}"
            }
        },
        createdRow: function (row, data, dataIndex) {
            if ($('#isEditAllowed').val() == "true") {
                $(row).children().not(':last-child').attr({
                    "data-toggle": "modal",
                    "data-target": "#modal8",
                    "onclick": "editRule(" + data[7] + ")"
                });
            }
        },

        "order": [[0, "asc"]]
    })



    $("#addNewRule").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "year": {
                required: !0,
            },
            "make": {
                required: !0,
            },
            "model": {
                required: !0,
            },
        },
        submitHandler: function (form, e) {
            // return true;
            e.preventDefault();

            var form = $('#addNewRule');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log(response);

                    if ((response.errorMessages) && response.errorMessages.length > 0) {
                        response.errorMessages.forEach(message => {
                            toastr.error(message, 'Error while Adding');
                        });
                    }
                    if (response.success == true) {
                        e1.fire({
                            position: "center",
                            icon: "success",
                            title: response.messages.length > 0 ? response.messages[0] : "Successfully Added",
                            showConfirmButton: !1,
                            timer: 1500
                        })
                        manageDataTable.ajax.reload(null, false);
                    } else {
                        e1.fire({
                            // position: "center",
                            icon: "error",
                            title: response.messages.length > 0 ? response.messages[0] : "Error while Adding",
                            showConfirmButton: !1,
                            timer: 2500
                        })

                        // form[0].reset();
                    }


                }
            });

            return false;

        }


    });

    $("#editRuleForm").validate({
        ignore: ":hidden:not(.selectpicker)", // or whatever your dropdown classname is
        rules: {
            "eyear": {
                required: !0,
            },
            "emake": {
                required: !0,
            },
            "emodel": {
                required: !0,
            },
        },

        submitHandler: function (form, e) {
            // return true
            e.preventDefault();

            var form = $('#editRuleForm');
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
                            timer: 1500
                        })
                        // form[0].reset();
                        manageDataTable.ajax.reload(null, false);

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

    })



})



function loadTypeHead(id) {
    var o, e = ["All"];
    $(".typeahead" + id).typeahead({
        hint: !0,
        highlight: true,
        minLength: 1
    }, {
        name: "states",
        source: (o = e, function (e, a) {
            var t = [],
                n = new RegExp(e, "i");
            o.forEach(function (e) {
                n.test(e) && t.push(e)
            }), a(t)
        })
    });
}


function editRule(ruleId = null) {
    if (ruleId) {

        $.ajax({
            url: '../php_action/fetchSelectedRdrRule.php',
            type: 'post',
            data: { id: ruleId },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.spinner-grow').addClass('d-none');
                // modal result
                $('.showResult').removeClass('d-none');
                // modal footer
                $('.modal-footer').removeClass('d-none');

                $('#editRuleForm')[0].reset();


                $('#ruleId').val(response.id);

                $('#eyear').val(response.year);
                $('#emake').val(response.make);
                $('#emodel').val(response.model);
                $('#emodelType').val(response.model_type);
                $('#ecertified').val(response.certified);
                $('#erdrType').val(response.rdr_type);


                $('.selectpicker').selectpicker('refresh');

            }, // /success
            error: function (err) {
                console.log(err);
            }
        }); // ajax function
    }
}

function removeRule(ruleId = null) {
    if (ruleId) {
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
                    url: '../php_action/removeRdrRule.php',
                    type: 'post',
                    data: { id: ruleId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success")
                            manageDataTable.ajax.reload(null, false);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
    }
}



function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
