"use strict";$(function(){var t="rtl"===$("html").attr("dir");$("#touchspin-1").TouchSpin(),$("#touchspin-2").TouchSpin({step:5}),$("#touchspin-3").TouchSpin({decimals:1,step:.1}),$("#touchspin-4").TouchSpin({min:0,max:100,boostat:5,maxboostedstep:10,postfix:"%"}),$("#touchspin-5").TouchSpin({min:0,max:1e6,boostat:5,maxboostedstep:10,prefix:"$"}),$("#touchspin-6").TouchSpin({verticalbuttons:!0}),$("#touchspin-7").TouchSpin({buttondown_class:"btn btn-outline-primary",buttonup_class:"btn btn-outline-primary"}),$("#touchspin-8").TouchSpin({buttondown_class:"btn btn-label-primary",buttonup_class:"btn btn-label-primary"}),$("#touchspin-9").TouchSpin({verticalbuttons:!0,verticalup:'<i class="fa fa-angle-up"></i>',verticaldown:'<i class="fa fa-angle-down"></i>'}),$("#touchspin-10").TouchSpin({buttonup_txt:t?'<i class="fa fa-angle-left"></i>':'<i class="fa fa-angle-right"></i>',buttondown_txt:t?'<i class="fa fa-angle-right"></i>':'<i class="fa fa-angle-left"></i>'}),$(".touchspin-11").TouchSpin()});