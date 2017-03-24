/* 命例From */
define(function(require){
	var ajax = require("ajax");
	var dict = require("common/dict");
	var shengchensel = require('common/shengchensel');
	var data,dialog,form;
	
	function init_form() {
		form = new MWT.Form();
		form.addField('name', new MWT.TextField({
            render  : 'name-div',
            value   : '',
            empty   : false,
            errmsg  : "请输入姓名",
            checkfun: function(v){return v.length<=50;}
        }));
		form.addField('gender', new MWT.RadioField({
            render  : 'gender-div',
            options : [{text:"男",value:'y'},{text:"女",value:'x'}],
            value   : '',
            errmsg  : '请选择性别',
            empty   : false
        }));
		form.addField('desc', new MWT.TextField({
            render  : 'desc-div',
            value   : '',
            empty   : true,
            errmsg  : "请输入备注"
        }));
	}

	function init() {
		init_form();
		dialog = new MWT.Dialog({
            title  : '添加命例',
            width  : 500,
            height : "auto",
            top    : 50,
            form   : form,
            bodyStyle: 'padding:10px',
            body   : '<table class="mwt-formtab">'+
				'<tr><td width="40">姓名:</td><td id="name-div"></td><td width="40" class="tips">*</td></tr>'+
				'<tr><td>性别:</td><td id="gender-div"></td><td class="tips">*</td></tr>'+
				'<tr><td>生辰:</td><td id="birthday-div"></td><td class="tips">*</td></tr>'+
				'<tr><td>备注:</td><td id="desc-div"></td><td width="40" class="tips"></td></tr>'+
			  '</table>',
            buttons: [
                {"label":"提交",cls:'mwt-btn-primary',handler:submit},
                {"label":"取消",type:'close',cls:'mwt-btn-danger'}
            ]
        });
		dialog.on('open',function(){
			shengchensel.init('birthday-div');
			form.reset();
			form.set(data);
			var title=data.caseid==0 ? '添加命例' : '编辑命例';
			dialog.setTitle(title);
			if (data.caseid!=0) {
				shengchensel.set(data.solar_calendar,data.hour);
			}
		});
	}

	// 提交保存
	function submit()
	{
		var params = form.getData();
		var sc = shengchensel.get();
		for (var k in sc) params[k] = sc[k];
		params.caseid = data.caseid;
		params.gender = get_radio_value('gender-divrdo');
		//print_r(params); return;
		ajax.post('case&action=save',params,function(res){
			if (res.retcode!=0) {
				mwt.notify(res.retmsg,2000,'danger');
			} else {
				dialog.close();
				if (params.caseid==0) require('./grid').query();
				else require('./grid').refresh();
			}
		});
	}

    var o={};
	o.open=function(_data){
		data=_data;
		if (!dialog) init();
		dialog.open();
	};
	return o;
});
