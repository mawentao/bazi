/* 位置面包屑导航 */
define(function(require){
	var o={};
	// string or [{name:'aaa',href:'#/'}]
	o.get=function(str) {
		var code = '<ul class="posul">';
        code += '<li><a href="#/"><i class="fa fa-home" style="font-size:16px;"></i></a></li>';
        var items = []; 
        if (str instanceof Array) items = str;
        else items.push({name:str});
        for (var i=0;i<items.length;++i) {
            var item = items[i];
            code += '<li><i class="fa fa-angle-right" style="font-size:16px;margin:0 5px;"></i></li>';
			if (item.href) {
            	code += '<li><a href="'+item.href+'">'+item.name+'</a></li>';
			} else {
            	code += '<li>'+item.name+'</li>';
			}
        }   
        code += "</ul>";
        return code;
	};
	return o;
});
