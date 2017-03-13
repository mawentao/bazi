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
  <script src="<%plugin_path%>/template/libs/mwtcalendar.js"></script>
  <script>
    var jq=jQuery.noConflict();
    jq(document).ready(function($) {
		require.config({
            baseUrl: "<%plugin_path%>/template/views/src/",
            packages: [
                {name:'mwt', location:'<%plugin_path%>/template/libs/mwt/3.5', main:'mwt.min'}
            ]
        });
		require(["case/page","mwt"],function(mainpage){
            mainpage.execute(); 
        });
    });
  </script>
</head>
<body>
  <div id="grid-div" style="position:absolute;top:40px;left:10px;right:10px;bottom:10px;">命例管理</div>
</body>
</html>
