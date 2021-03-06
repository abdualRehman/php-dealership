"use strict";
$(function() {
    new Sortable(document.querySelector("#sortable-1")), 
    new Sortable(document.querySelector("#sortable-2"), {
        handle: ".sortable-handle"
    }), 
    
    
    new Sortable(document.querySelector("#sortable-3-left"), {
        group: "shared"
    }), 
    new Sortable(document.querySelector("#sortable-3-right"), {
        group: "shared"
    }), 
    
    
    new Sortable(document.querySelector("#sortable-4-left"), {
        group: {
            name: "shared",
            pull: "clone"
        }
    }), 
    new Sortable(document.querySelector("#sortable-4-right"), {
        group: {
            name: "shared",
            pull: "clone"
        }
    }), 
    
    
    new Sortable(document.querySelector("#sortable-5-left"), {
        group: {
            name: "shared",
            pull: "clone",
            put: !1
        },
        sort: !1
    }), 
    new Sortable(document.querySelector("#sortable-5-right"), {
        group: "shared"
    }), 
    
    new Sortable(document.querySelector("#sortable-6"), {
        group: "shared",
        invertSwap: !0
    }), 
    
    $("#sortable-6").find(".sortable").each(function() {
        new Sortable(this, {
            group: "shared",
            invertSwap: !0
        })
    })
});