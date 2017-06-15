<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="<%plugin_path%>/template/libs/mwt/3.5/mwt.min.css" type="text/css">
  <link rel="stylesheet" href="<%plugin_path%>/template/static/admin.css" type="text/css">
  <%js_script%>
  <script src="<%plugin_path%>/template/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="<%plugin_path%>/template/libs/requirejs/2.1.9/require.js"></script>
  <script>
    var jq=jQuery.noConflict();
    jq(document).ready(function($) {
		require.config({
            baseUrl: "<%plugin_path%>/template/views/src/",
            packages: [
                {name:'mwt', location:'<%plugin_path%>/template/libs/mwt/3.5', main:'mwt.min'}
            ]
        });
		require(["setting/page","mwt"],function(mainpage){
            mainpage.execute(); 
        });
    });
  </script>
</head>
<body>
  <form method="post" action="admin.php?action=plugins&operation=config&identifier=bazi&pmod=z_setting">
  <!-- 使用提示 -->
  <table class="tb tb2">
    <tr><th colspan="15" class="partition">使用提示</th></tr>
    <tr><td class="tipsblock" s="1">
      <ul id="lis">
        <li>系统地址：<a href="<%siteurl%>/plugin.php?id=bazi" target="_blank"><%siteurl%>/plugin.php?id=bazi</a></li>
      </ul>
    </td></tr>
  </table>
  <!-- 全局设置 -->
  <table class="tb tb2">
    <tr><th colspan="15" class="partition">全局设置</th></tr>
    <tr>
      <td width='80'>屏蔽discuz：</td>
      <td width='300'>
	    <label><input name="disable_discuz" type="radio" value="1"> 是</label>
        &nbsp;&nbsp;
	    <label><input name="disable_discuz" type="radio" value="0"> 否</label>
      </td>
      <td class='tips2'>选'是'所有discuz页面都将跳转到插件页面</td>
    </tr>
	<tr style="display:none;">
	  <td>页面风格：</td>
      <td><select name="page_style" id="page_style">
          <option value="didi">（橙色）滴滴MIS风格</option>
          <option value="aliyun">（蓝色）阿里云风格</option>
      </select></td>
	  <td class='tips2'>设置默认前端页面风格，风格文件位于template/src/frame/目录下</td>
	</tr>
	<tr>
	  <td>版权信息：</td>
      <td><input name="page_copyright" id="page_copyright" class="form-control" style="padding:0 5px;"></td>
	  <td class='tips2'></td>
	</tr>
    <tr>
      <td colspan="3">
		<input type="hidden" id="reset" name="reset" value="0"/>
        <input type="submit" id='subbtn' class='btn' value="保存设置"/>
        &nbsp;&nbsp;
		<input type="submit" class='btn' onclick="jQuery('#reset').val(1);" value="恢复默认设置"/>
      </td>
    </tr>
  </table>
  </form>
</body>
</html>
