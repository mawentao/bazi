/* 默认controller, (c) 2016 mawentao */
define(function(require){
    var o={};
	var control='index';

	o.conf = {
		controller: control,
		path: [
			'/'+control+'/index'
		]
	};

	// 默认action
	o.indexAction=function(erurl) {
		//require('./login').check();
		if (dz.is_mobile) {
			alert("TODO");
		} else {
			require('view/pc/index/page').execute();
		}
	};

	return o;
});
