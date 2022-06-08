
"use strict";
var e1 = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-label-success btn-wide mx-1",
        cancelButton: "btn btn-label-danger btn-wide mx-1"
    },
    buttonsStyling: !1
})

$(function () {


    //validate file extension custom  method.
    jQuery.validator.addMethod("extension", function (value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Please enter a value with a valid extension.");

    $('form').each(function () {

        $(this).validate({
            rules: {
                retailRate: {
                    required: true,
                    extension: "pdf"
                }
            },
            messages: {
                retailRate: {
                    required: "Please upload File",
                    extension: "Please upload valid file formats"
                }
            },
            submitHandler: function (form, event) {
                // return true;
                event.preventDefault();
                var fd = new FormData(form);
                fd.append("CustomField", "This is some extra data");

                console.log(form);
                $.ajax({
                    async: false,
                    url: form.action,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (response) {
                        // response = JSON.parse(response);
                        console.log(response);

                        if (response.success == true) {

                            e1.fire({
                                position: "top-end",
                                icon: "success",
                                title: response.messages,
                                showConfirmButton: !1,
                                timer: 2500,
                            })

                            setTimeout(() => {
                                location.reload(true);
                            }, 1000);
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

        });
    });


})

function removeFile(fileType , fileName) {
    console.log(fileName);
    if (fileType && fileName) {
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
                    url: '../php_action/removeMatrixFile.php',
                    type: 'post',
                    data: { fileType: fileType , fileName: fileName },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire("Deleted!", "Your file has been deleted.", "success");
                            setTimeout(() => {
                                location.reload(true);
                            }, 1000);
                        } // /response messages
                    }
                }); // /ajax function to remove the brand

            }
        });
    }
}