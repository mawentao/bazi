define(function(require){
	var ajax=require('ajax');
    var o={};
    o.execute = function(){
        jQuery("input[name=disable_discuz][value="+v.disable_discuz+"]").attr("checked",true);
		set_select_value('page_style',v.page_style);
		set_value('page_copyright',v.page_copyright);
    };
    return o;
});
