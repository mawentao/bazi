/* 登录[系统模块], (c) 2016 mawentao */
define(function(require){
	var frame=require('frame');
	var control='login';
	var o={};

	o.conf = {
		controller: 'login',
		path: [
			'/'+control+'/index'
		]
	};

	// 登录action
	o.indexAction=function() {
		if (dz.uid>0) {
			window.location = '#/';
			return;
		}
		var code = '<div class="mwt-dialog" style="display:block;top:50px;">'+
		  '<div id="dg-WdiU4d-modal" class="modaldiv" style="display: block;"></div>'+
          '<div class="dialog-body flipInX" style="top:50px; width:400px; display:inline-block;">'+
            '<div class="dialog-head"><span>用户登录</span></div>'+
            '<div class="content" style="padding:10px 15px 20px;">'+
			  '<div style="width:80%;font-size:13px;padding:0 0 10px 60px;color:red;" id="errmsgdiv"></div>'+
              '<table class="tablay" style="margin:0;font-size:13px;">'+
                '<tr height="45"><td width="100">用户名：</td><td colspan="2"><div id="username-div"></div></td></tr>'+
                '<tr height="45"><td>密码：</td><td colspan="2"><div id="userpass-div"></div></td></tr>'+
                '<tr height="45"><td>验证码：</td>'+
                  '<td width="200"><div id="seccode-div"></div></td>'+
                  '<td><img src="'+dz.seccodeurl+'" id="scodebtn"'+
                       'style="width:120px;height:40px;border-radius:2px;cursor:pointer;"></td>'+
                '</tr>'+
                '<tr height="45"><td></td><td colspan="2">'+
                  '<button id="logbtn" class="mwt-btn mwt-btn-primary radius" style="width:100%;">登 录</button>'+
                '</td></tr>'+
              '</table>'+
			'</div>'+
          '</div>'+
        '</div>';
		frame.showpage(code);
		init_login_form();
	};

	// 检查是否登录
	o.check=function() {
		if (dz.uid==0) {
			window.location = '#/login';
			throw new Error('未登录');
		}
	};

	/////////////////////////////////////////

	var login_form;
	function init_login_form() {
		login_form = new MWT.Form();
		login_form.addField('username',new MWT.TextField({
        	render   : 'username-div',
        	type     : 'text',
        	style    : 'width:91%;padding:5px;border-radius:4px;',
        	value    : '',
        	empty    : false,
        	errmsg   : "请输入用户名",
        	placeholder: '用户名',
        	checkfun : function(v) { return v.length<=50; }
    	}));
		login_form.addField('userpass',new MWT.TextField({
			render   : 'userpass-div',
			type     : 'password',
			style    : 'width:91%;padding:5px;border-radius:4px;',
			value    : '',
			empty    : false,
			errmsg   : "请输入密码",
			placeholder: '密码',
			checkfun : function(v) { return v.length<=50; }
		}));
		login_form.addField('seccode',new MWT.TextField({
        	render   : 'seccode-div',
			type     : 'text',
			style    : 'width:150px;padding:5px;border-radius:4px;',
			value    : '',
			empty    : false,
			errmsg   : "请输入验证码",
			placeholder: '验证码',
			checkfun : function(v) { return v.length>0; }
		}));
		login_form.create();
   	    jQuery('#logbtn').click(logsub);
        jQuery('input').keyup(function (event) {
            if (event.keyCode == "13") {
                document.getElementById("logbtn").click();
                return false;
            }
        });
        // 换一个验证码
        jQuery('#scodebtn').click(function(){
            jQuery(this).attr('src',dz.seccodeurl+'&tm='+time());
        });
	}

	// 登录
	function logsub() {
        jQuery('#errmsgdiv').html('');
		var params = login_form.getData();
		params.username = get_text_value('username-divtxt');
		params.userpass = get_text_value('userpass-divtxt');
		jQuery('#logbtn').unbind('click').html('登录中...');
		//print_r(params);
		ajax.post('uc&action=login',params,function(res){
			jQuery('#logbtn').html('登 录').click(logsub);
			if (res.retcode!=0) {
				jQuery('#errmsgdiv').html(res.retmsg);
			} else {
				window.location.reload();
			}
		});
	}
	return o;
});
