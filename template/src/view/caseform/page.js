define(function(require){
	var cal=require('./calendar');

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
		form.create();
		// 提交
		jQuery('#subbtn').click(function(){
			var data = form.getData();
			data.gender = get_radio_value("gender");
			data.date = cal.getDate();
			data.hour = cal.getHour();
			//print_r(data);
			var msg='提交命例后生辰信息无法修改！<br><br>确定要提交吗？';
			mwt.confirm(msg,function(res){
				if (res) {
					var msgid=mwt.notify('提交数据...',0,'loading');
					ajax.post('case&action=submit',data,function(res){
						mwt.notify_destroy(msgid);
						if (res.retcode!=0) mwt.notify(res.retmsg,2000,'danger');
						else {
							var url=dz.siteurl+'plugin.php?id=bazi:foresee&caseid='+res.data;
							window.location=url;
						}
					});
				}
			});
		});
	}

    var o = {};
	o.execute = function(domid) {
		var code = '<div class="caseform">'+
          '<div id="scbzdiv" style="display:inline-block;width:500px;background:rgba(0,0,0,0.1);border-radius:4px;padding:10px;"></div>'+
          '<div style="display:inline-block;vertical-align:top;margin-left:20px;">'+
		  '<table>'+
			'<tr><th width="70" style="padding-left:20px;">姓名：</th>'+
			  '<td width="200"><input type="text" id="name-divtxt" class="form-control nametxt" placeholder="输入命主姓名"></td>'+
			'</tr><tr>'+
			  '<th width="70" align="right">性别：</th>'+
			  '<td>'+
				'<label class="mwt-radio"><input name="gender" type="radio" value="y" checked><i></i><span>男</span></label>'+
				'<label class="mwt-radio" style="margin-left:40px;"><input name="gender" type="radio" value="x"><i></i><span>女</span></label>'+
			  '</td></tr>'+
		//	'<tr><td id="scbzdiv" colspan="4"></td></tr>'+
			'<tr><td colspan="4">'+
			  '<button id="subbtn" class="mwt-btn-block mwt-btn mwt-btn-primary radius mwt-btn-lg" style="font-size:13px;">'+
				'<i class="fa fa-hand-paper-o"></i>&nbsp;&nbsp;掐 指 一 算</button>'+
			'</td></tr>'+
		  '</table>'+
		  '</div>'+
		'</div>'+
		require('common/copyright').get();
		jQuery('#'+domid).html(code);
		
		cal.init('scbzdiv');

		init();
	};
    return o;
});

