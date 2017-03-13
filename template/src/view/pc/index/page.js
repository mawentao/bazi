define(function(require){
	var form;
	var shengchensel = require('common/shengchensel');

	function init() {
		form = new MWT.Form();
		form.addField('name', new MWT.TextField({
            render  : 'name-div',
			//style   : 'width:200px;',
            value   : '',
            errmsg  : '',
            empty   : false,
			checkfun: function(v){return v.length<=20;}
        }));
		form.addField('gender', new MWT.RadioField({
            render  : 'gender-div',
            //cls     : 'mwt-radio',
            options : [{text:"男",value:1},{text:"女",value:2}],
            value   : '',
            errmsg  : '请选择性别',
            empty   : false
        }));
		form.create();

		shengchensel.init('birthday-div');


		jQuery('#subbtn').click(function(){
			var data = form.getData();
			var sc = shengchensel.get();
			for (var k in sc) data[k] = sc[k];
			print_r(data);
/*
			mwt.confirm('确定要提交保存吗？',function(res){
				if (res) {
					var data = form.getData();
					var msgid=mwt.notify('保存数据...',0,'loading');
					ajax.post('uc&action=profile_set',data,function(res){
						mwt.notify_destroy(msgid);
						if (res.retcode!=0) mwt.notify(res.retmsg,2000,'danger');
						else mwt.notify('设置成功',1500,'success');
					});
				}
			});
*/
		});
	}

	function submit() {
		var data = form.getData();
		
	};

    var o = {};
	o.execute = function() {
		var code = '<div style="margin:100px auto;width:420px;">'+
		  '<p align="center">命签</p>'+
		  '<table class="mainform">'+
			'<tr><td width="70">姓名：</td><td id="name-div"></td></tr>'+
			'<tr><td>性别：</td><td id="gender-div"></td></tr>'+
			'<tr><td valign="top">生辰：</td><td id="birthday-div">'+
			'</td></tr>'+
			'<tr><td colspan="2">'+
			  '<button id="subbtn" class="mwt-btn-block mwt-btn mwt-btn-primary">解签</button>'+
			'</td></tr>'+
		  '</table></div>';
		require('frame').showpage(code);
		
		init();
	};
    return o;
});
