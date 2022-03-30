"use strict";
$(function () {
    var e1 = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-label-success btn-wide mx-1",
            cancelButton: "btn btn-label-danger btn-wide mx-1",
        },
        buttonsStyling: !1,
    });
    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        // I use element.value instead value here, value parameter was always null
        return arg != element.value;
    }, "Value must not equal arg.");
    //validate file extension custom  method.


    $("#addInvForm").validate({
        rules: {
            stockno: {
                required: !0,
            },
            year: {
                required: !0,
                number: !0,
            },
            make: {
                required: !0,
            },
            model: {
                required: !0,
            },
            modelno: {
                required: !0,
            },
            color: {
                required: !0,
            },
            lot: {
                required: !0,
            },
            vin: {
                required: !0,
            },
            mileage: {
                required: !0,
                number: !0,
            },
            age: {
                required: !0,
                number: !0,
            },
            balance: {
                required: !0,
                number: !0,
            },
            retail: {
                required: !0,
                number: !0,
            },
            stockType: {
                valueNotEquals: "0",
            }
        },
        messages: {
            stockType: {
                valueNotEquals: "Please select Stock Type",
            },
            year: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            mileage: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            age: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            balance: {
                required: "This field is required.",
                number: "Please enter a valid number",
            },
            retail: {
                required: "This field is required.",
                number: "Please enter a valid number",
            }


        },
        submitHandler: function (form, event) {
            event.preventDefault();
            
            var form = $('#addInvForm');
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
            return false;

        }
    });
    $("#importInvForm").validate({
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
            event.preventDefault();

            var allowedFiles = [".xlsx", ".xls", ".csv"];
            var fileUpload = $("#excelFile");
            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
            if (!regex.test(fileUpload.val().toLowerCase())) {
                e1.fire("Files having extensions: " + allowedFiles.join(', ') + " only.");
                return false;
            }

            
            var form = $('#importInvForm');
            var fd = new FormData(document.getElementById("importInvForm"));
            fd.append("CustomField", "This is some extra data");

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function (response) {
                    response = JSON.parse(response);

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

                        if(response.erorStock.length > 0){
                            var i = 0;
                            $('#errorDiv').removeClass('d-none');
                            // console.log(response.erorStock);
                            while (response.erorStock[i] ) {
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
                            title: response.erorStock,
                            showConfirmButton: !1,
                            timer: 2500
                        })
                    }

                    
                }
            });
            return false;

        }
    })
});
function clearErrorsList(){
    $('#errorList').html('');
}