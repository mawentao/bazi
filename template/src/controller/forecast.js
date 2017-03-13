/* 命理预测 */
define(function(require){
	var control='forecast';
    var o={};

	// 控制器配置
	o.conf = {
		controller: control,
		// url路由
		path: [
			'/'+control+'/index',
		]
	};

	// 默认action
	o.indexAction=function() {
		alert("aaa");
	};

	return o;
});
