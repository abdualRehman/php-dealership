"use strict";
function r(r, e) {
    var t, a = Object.keys(r);
    return Object.getOwnPropertySymbols && (t = Object.getOwnPropertySymbols(r), e && (t = t.filter(function(e) {
        return Object.getOwnPropertyDescriptor(r, e).enumerable
    })), a.push.apply(a, t)), a
}

function p(a) {
    for (var e = 1; e < arguments.length; e++) {
        var o = null != arguments[e] ? arguments[e] : {};
        e % 2 ? r(Object(o), !0).forEach(function(e) {
            var r, t;
            r = a, e = o[t = e], t in r ? Object.defineProperty(r, t, {
                value: e,
                enumerable: !0,
                configurable: !0,
                writable: !0
            }) : r[t] = e
        }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(a, Object.getOwnPropertyDescriptors(o)) : r(Object(o)).forEach(function(e) {
            Object.defineProperty(a, e, Object.getOwnPropertyDescriptor(o, e))
        })
    }
    return a
}
$(function() {
    $("#widget-carousel").slick({
        rtl: "rtl" === $("html").attr("dir"),
        asNavFor: "#widget-carousel-nav",
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: !1
    }), $("#widget-carousel-nav").slick({
        rtl: "rtl" === $("html").attr("dir"),
        asNavFor: "#widget-carousel",
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: !1,
        centerMode: !0
    });
    var o = "dark" == localStorage.getItem("theme-variant"),
        s = o ? "dark" : "light",
        n = new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
            minimumFractionDigits: 0
        }),
        i = "#fff",
        c = "#424242",
        l = {
            light: {
                theme: {
                    mode: "light",
                    palette: "palette1"
                }
            },
            dark: {
                theme: {
                    mode: "dark",
                    palette: "palette1"
                }
            }
        },
        t = new ApexCharts(document.querySelector("#widget-chart-1"), p(p({}, l[s]), {}, {
            series: [{
                name: "Revenue",
                data: [3100, 4e3, 2800, 5100, 4200, 10900, 5600, 8600, 7e3]
            }],
            chart: {
                type: "area",
                height: 300,
                background: "transparent",
                sparkline: {
                    enabled: !0
                }
            },
            fill: {
                type: "solid"
            },
            markers: {
                strokeColors: o ? c : i
            },
            stroke: {
                show: !1
            },
            tooltip: {
                marker: {
                    show: !1
                },
                y: {
                    formatter: function(e) {
                        return n.format(e)
                    }
                }
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                crosshairs: {
                    show: !1
                }
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    chart: {
                        height: 200
                    }
                }
            }]
        })),
        a = new ApexCharts(document.querySelector("#widget-chart-6"), p(p({}, l[s]), {}, {
            series: [{
                name: "Unique",
                data: [6400, 4e3, 7600, 6200, 9800, 6400, 8600, 7e3]
            }],
            chart: {
                type: "area",
                background: "transparent",
                height: 300,
                sparkline: {
                    enabled: !0
                }
            },
            markers: {
                strokeColors: o ? c : i
            },
            fill: {
                type: "gradient",
                gradient: {
                    shade: s,
                    type: "vertical",
                    opacityFrom: 1,
                    opacityTo: 0,
                    stops: [0, 100]
                }
            },
            tooltip: {
                marker: {
                    show: !1
                },
                y: {
                    formatter: function(e) {
                        return "".concat(e, " Visited")
                    }
                }
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
                crosshairs: {
                    show: !1
                }
            }
        })),
        h = $(".widget-chart-7").map(function() {
            var e = $(this).data("chart-color"),
                r = $(this).data("chart-label"),
                t = $(this).data("chart-series").split(",").map(function(e) {
                    return Number(e)
                }),
                a = $(this).data("chart-currency");
            return new ApexCharts(this, p(p({}, l[s]), {}, {
                series: [{
                    name: r,
                    data: t
                }],
                chart: {
                    type: "area",
                    height: 200,
                    background: "transparent",
                    sparkline: {
                        enabled: !0
                    }
                },
                fill: {
                    type: "solid",
                    colors: [e],
                    opacity: .1
                },
                stroke: {
                    show: !0,
                    colors: [e]
                },
                markers: {
                    colors: o ? c : i,
                    strokeWidth: 4,
                    strokeColors: e
                },
                tooltip: {
                    marker: {
                        show: !1
                    },
                    y: {
                        formatter: function(e) {
                            return Boolean(a) ? n.format(e) : e
                        }
                    }
                },
                xaxis: {
                    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                    crosshairs: {
                        show: !1
                    }
                }
            }))
        });
    t.render(), a.render(), h.each(function() {
        this.render()
    }), $("#theme-toggle").on("click", function() {
        var e = $("body").hasClass("theme-dark"),
            r = e ? "dark" : "light";
        t.updateOptions(p(p({}, l[r]), {}, {
            markers: {
                strokeColors: e ? c : i
            }
        })), a.updateOptions(p(p({}, l[r]), {}, {
            markers: {
                strokeColors: e ? c : i
            },
            fill: {
                gradient: {
                    shade: r
                }
            }
        })), h.each(function() {
            this.updateOptions(p(p({}, l[r]), {}, {
                markers: {
                    colors: e ? c : i
                }
            }))
        })
    })
});