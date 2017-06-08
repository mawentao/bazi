
function login() {
	var code = '<div class="mwt-dialog" style="display:block;top:69px;">'+
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
	jQuery('#login-div').html(code);
}


var ajax = {
	getAjaxUrl: function(api) {
        return dz.ajaxapi+api; 
    },

	ajaxrequest: function(method, url, params, callbackfun, sync) {
        //if(!noanimation) show_loading();
        jQuery.ajax({
            url: url,
            type: method,
            dataType: "json",
            data: params,
            async: !sync, 
            complete: function(res) {
                //if(!noanimation) hide_loading();
            },
            success: function(res) {
                callbackfun(res);
                //console.log(json2str(res));
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                var errmsg = "Error("+XMLHttpRequest.readyState+") : "+textStatus;
				if (textStatus=="abort") {
					console.log("abort ajax: "+method);
				} else {
                	alert(errmsg);
				}
            }
        });
    },

	post: function(url, params, callbackfun, sync) { 
		ajax.ajaxrequest("post", url, params, callbackfun, sync);
	}
};

var qrid=0;


function login()
{
	var code = '<div class="mwt-dialog" style="display:block;top:69px;">'+
		   '<div class="dialog-body flipInX" style="top:50px; width:400px; display:inline-block;">'+
            '<div class="dialog-head"><span>用户登录</span></div>'+
            '<div class="content" style="padding:10px 15px 20px;">'+
              '<div style="width:80%;font-size:13px;padding:0 0 10px 60px;color:red;" id="login-url-qr"></div>'+
			  '<div style="text-align:center;font-size:13px;">（请使用微信扫描二维码登录）</div>'+
            '</div>'+
          '</div>'+
        '</div>';
	jQuery('#login-div').html(code);

	var url = ajax.getAjaxUrl('uc&action=get_login_conf');
	ajax.post(url,{},function(res){
		if (res.retcode!=0) {
			mwt.notify(res.retmsg,1500,'danger');
			return;
		}
		qrid = res.data.qrid;
		var qrurl = res.data.qrurl;
		var qrcode = new QRCode(document.getElementById('login-url-qr'), {
           	width  : 250,
       		height : 250
       	});
    	qrcode.makeCode(qrurl);
		check_wxlogin();
	});
}



function check_wxlogin() 
{
	var ajaxurl = dz.siteurl+"/source/plugin/wxconnect/index.php?version=4&module=wxlogin&action=check";
	var params = {qrid:qrid};
	ajax.post(ajaxurl,params,function(res){
		if (res.data.uid!=0) {
			window.location.reload();
		} else {
    		setTimeout(check_wxlogin,2500);
		}
	});
}


