"use strict";
$(function() {
    $("#datatable-1").DataTable({
        scrollY: 300,
        scrollX: !0,
        scrollCollapse: !0,
        fixedColumns: !0
    }), $("#datatable-2").DataTable({
        scrollY: 300,
        scrollX: !0,
        scrollCollapse: !0,
        fixedColumns: {
            leftColumns: 2
        }
    }), $("#datatable-3").DataTable({
        scrollY: 300,
        scrollX: !0,
        scrollCollapse: !0,
        fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
        }
    })
});