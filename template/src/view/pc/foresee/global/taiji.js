define(function(require){
    /* 旋转的太极图 */
    var domid = 'taiji_loading_div';
    var o={};

    o.show=function() {
        mwt.createDiv(domid);
        var code = '<div style="margin:140px auto;text-align:center;">'+
          '<div class="square">'+
            '<div class="rect rect_one">'+
                '<div class="inner_circle rect_one_one">'+
                    '<div class="dot rect_one_two"></div>'+
                '</div>'+
            '</div>'+
            '<div class="rect rect_two">'+
                '<div class="inner_circle rect_two_one">'+
                    '<div class="dot rect_two_two"></div>'+
                '</div>'+
            '</div>'+
          '</div>'+
          '<p style="font-size:20px;font-weight:bold;margin-top:20px;">掐指一算...<br>'+
            '<span style="font-size:12px;">（若长时间未出结果，请刷新页面试试）</span>'+
          '</p>'+
        '</div>';
        jQuery('#'+domid).html(code);
    };

    o.hide=function() {
        jQuery('#'+domid).remove();
    };
    return o;
})
