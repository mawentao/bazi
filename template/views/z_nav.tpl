<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="<%plugin_path%>/template/libs/mwt/3.3/mwt.min.css" type="text/css">
  <style>.floattop {z-index:0;}</style>
  <%js_script%>
  <script src="<%plugin_path%>/template/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="<%plugin_path%>/template/libs/requirejs/2.1.9/require.js"></script>
  <script>
    var jq=jQuery.noConflict();
    jq(document).ready(function($) {
		require.config({
            baseUrl: "<%plugin_path%>/template/views/src/",
            packages: [
                {name:'mwt', location:'<%plugin_path%>/template/libs/mwt/3.3', main:'mwt.min'}
            ]
        });
		require(["nav/page","mwt"],function(mainpage){
            mainpage.execute();
        });
    });
	function checkpost() {
		jQuery('[name="href[]"]').each(function(){
            var str = jQuery(this).val();
			str = str.replace(/[",']/g, '&quot;');
            jQuery(this).val(str);
        });
		return true;
	};
  </script>
</head>
<body>
  <form method="post" action="admin.php?action=plugins&operation=config&identifier=bazi&pmod=z_nav" accept-charset="utf-8"
        onsubmit="return checkpost();">
  <table class="tb tb2">
    <tr><th colspan="15" class="partition">导航设置</th></tr>
    <tr><td colspan='15' class="tipsblock">
	  <ul id="lis">
        <li>如果是插件内页面，可以指定#/controller/action</li>
        <li>前端页面框架会自动从链接地址中提取controller</li>
      </ul>
	</td></tr>
    <tr class='header'>
      <th width='100'>显示顺序</th>
      <th width='80'>图标</th>
      <th width='150'>标题</th>
      <th width='350'>链接</th>
      <th width='100'>新窗口打开</th>
      <th width='60'>可用</th>
      <th></th>
    </tr>
    <tbody id='listbody'></tbody>
    <tr><td colspan='15'>
      <div><a id="addrowbtn" href="javascript:;" onclick="return false;" class="addtr">添加链接</a></div>
    </td></tr>
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
