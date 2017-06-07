
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
