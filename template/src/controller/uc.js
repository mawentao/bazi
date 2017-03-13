/* 个人中心[系统模块], (c) 2016 mawentao */
define(function(require){
	var posnav=require('common/posnav');
    var copyright=require('common/copyright');
	var control='uc';
    var o={};

	// 控制器配置
	o.conf = {
		controller: control,
		path: [
			'/'+control+'/profile',
			'/'+control+'/changepass'
		],
		// 左部菜单
		menu: [
			{name:'个人中心', icon:'sicon-grid', submenu:[
				{name:'我的资料',icon:'icon icon-log',action:'profile'},
				{name:'修改密码',icon:'icon icon-lock',action:'changepass'}
			]}
		]
	};

	// 默认action
	o.indexAction=function() {
		window.location='#/uc/profile';
	};

	// 基本资料
	o.profileAction=function(){
		require('./login').check();
		var posarr = [{name:'个人中心',href:'#/uc'},{name:'我的资料',href:'#/uc/profile'}];
        var code = posnav.get(posarr)+
            '<div id="form-div"></div>'+
            copyright.get();
        require('frame').showpage(code);
        require('view/uc/profile/page').execute();
	};

	// 修改密码 
	o.changepassAction=function(){
		require('./login').check();
		var posarr = [{name:'个人中心',href:'#/uc'},{name:'修改密码',href:'#/uc/changepass'}];
        var code = posnav.get(posarr)+
            '<div id="form-div"></div>'+
            copyright.get();
        require('frame').showpage(code);
        require('view/uc/changepass/page').execute();
	};

	return o;
});
