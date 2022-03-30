"use strict";
var manageInvTable
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

        manageInvTable = $("#datatable-1").DataTable({

            // responsive: !0,
            responsive: {
                details: {
                    type: 'column',
                    target: 1
                }
            },

            // 'ajax': '../php_action/fetchInv.php',

            // working.... with both
            dom: `\n     
             <'row'<'col-12'P>>\n      
             <'row'<'col-sm-12 col-md-6'l>>\n  
            \n     
            <'row'<'col-sm-6 text-center text-sm-left p-3'B>
                <'col-sm-6 text-center text-sm-right mt-2 mt-sm-0'f>>\n
            <'row'<'col-12'tr>>\n      
            <'row align-items-baseline'<'col-md-5'i><'col-md-2 mt-2 mt-md-0'l><'col-md-5'p>>\n    `,


            searchPanes: {
                cascadePanes: !0,
                viewTotal: !0,
                columns: [2, 3, 4, 5, 6]
            },

            buttons: [
                {
                    extend: "colvis",
                    text: "Visibility control",
                    collectionLayout: "two-column",
                },
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                // {
                //     extend: 'pdfHtml5',
                //     exportOptions: {
                //         columns: [ 1, 2,3,4 , 5 , 6 ]
                //     }
                // },

                {
                    text: 'Delete',
                    action: function (e, dt, node, config) {
                        // --------------- if user want to delete only visible rows  ----------------------
                        // $("#datatable-1 tbody tr.selected").each(function () {
                        //     var self = $(this);
                        //     // var stockno1 = self.find("td:eq(1)").text().trim();
                        //     var stockno1 = self.find("td:eq(1)").text();
                        //     console.log(stockno1);
                        // });
                        var selData = manageInvTable.rows(".selected", { "filter": "applied" }).data();

                        var i = 0, stockNoArray = [];

                        if (selData.length > 0) {
                            while (i < selData.length) {
                                stockNoArray.push(selData[i][1])
                                i++;
                            }
                            console.log(stockNoArray);
                            e1.fire({
                                title: "Are you sure?",
                                text: `You won't be able to revert this!?`,
                                icon: "warning",
                                footer: `<b>Total Selected Rows: ${stockNoArray.length}</b>`,
                                showCancelButton: !0,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Yes, delete it!"
                            }).then(function (t) {

                                if (t.isConfirmed == true) {
                                    console.log(t);

                                    $.ajax({
                                        url: '../php_action/removeInvByStock.php',
                                        type: 'post',
                                        data: { stockAr: stockNoArray },
                                        dataType: 'json',
                                        success: function (response) {
                                            console.log(response);

                                            if (response.success == true) {
                                                Swal.fire("Deleted!", "Your file has been deleted.", "success")
                                                manageInvTable.ajax.reload(null, false);
                                            } // /response messages
                                        },
                                        error: function (err) {
                                            console.log(err);
                                        }
                                    }); // /ajax function to remove the brand

                                }
                            });;
                        } else {

                            e1.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "No Row Selected!",
                            });
                        }

                    }
                }

            ],


            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    className: 'select-checkbox',
                    checkboxes: {
                        'selectRow': true
                    }
                },
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [3, 4, 5]
                },
                // for hide columns as defaul
                // { 
                //     visible: false, 
                //     targets: 2 
                // }
            ],
            select: {
                'style': 'multi', // 'single', 'multi', 'os', 'multi+shift'
                selector: 'td:first-child',
            },
            language: {
                searchPanes: {
                    count: "{total} found",
                    countFiltered: "{shown} / {total}"
                },

                copy: 'Copy',
                copySuccess: {
                    1: "Copied one row to clipboard",
                    _: "Copied %d rows to clipboard"
                },
                copyTitle: 'Copy to clipboard',
                copyKeys: 'Press <i>ctrl</i> or <i>\u2318</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.'
            },
            "order": [[1, "asc"]]
        })


        $('#MyTableCheckAllButton').click(function () {
            if (manageInvTable.rows({
                selected: true
            }).count() > 0) {
                manageInvTable.rows().deselect();
                return;
            }

            manageInvTable.rows().select();
        });

        manageInvTable.on('select deselect', function (e, dt, type, indexes) {
            if (type === 'row') {
                // We may use dt instead of manageInvTable to have the freshest data.
                if (dt.rows().count() === dt.rows({
                    selected: true
                }).count()) {
                    // Deselect all items button.
                    $('#MyTableCheckAllButton i').attr('class', 'far fa-check-square');
                    return;
                }

                if (dt.rows({
                    selected: true
                }).count() === 0) {
                    // Select all items button.
                    $('#MyTableCheckAllButton i').attr('class', 'far fa-square');
                    return;
                }

                // Deselect some items button.
                $('#MyTableCheckAllButton i').attr('class', 'far fa-minus-square');
            }
        });


    } else if (divRequest == "add") {
        $(function () {

            $("#importMatrixForm").validate({
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


                    var form = $('#importMatrixForm');
                    var fd = new FormData(document.getElementById("importMatrixForm"));
                    fd.append("CustomField", "This is some extra data");

                    
                    $.ajax({
                        async: false,
                        url: form.attr('action'),
                        type: 'POST',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            console.log(response);

                            
                            var ne = window.open('', 'title');
                            ne.document.write(response);
                            
                            // response = JSON.parse(response);


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

                                if (response.erorStock.length > 0) {
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
                                    title: response.erorStock,
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
            })

        })



    }

});


function toggleFilterClass() {
    $('.dtsp-panes').toggle();
}
function clearErrorsList() {
    $('#errorList').html('');
}