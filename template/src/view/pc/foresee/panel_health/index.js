define(function(require){
    /* 健康 */

    var o={};
    o.show=function(domid) {
        var code = '代码编写中，敬请期待...';
        jQuery('#'+domid).html(code);
    };

	return o;
});
