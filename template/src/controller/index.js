/* 默认controller, (c) 2016 mawentao */
define(function(require){
	var frame=require('frame');
    var o={};
	var control='index';

	// 控制器配置
	o.conf = {
		controller: control,
		path: [
			'/'+control+'/cases',
			'/'+control+'/index'
		],
		// 左部菜单
		menu: [
			{name:'批命',icon:'fa fa-hand-paper-o',action:'index'},
			{name:'命例库',icon:'fa fa-database',action:'cases'}
		]
	};

	function action_before() {
		require('./login').check();
		require('common/posnav').set_default();
	}


	// 默认action
	o.indexAction=function(erurl) {
		action_before();
		var code = '<div id="case-form-div" class="fill"></div>';
		frame.showpage(code);
		require('view/caseform/page').execute('case-form-div');
	};

	// 命例库
	o.casesAction=function(erurl){
		action_before();
		var code='<div id="cases-div" class="fill"></div>';
		frame.showpage(code);
		require('view/cases/page').execute('cases-div');
	};

	return o;
});

