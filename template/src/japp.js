/* japp.js, (c) 2016 mawentao */
/* 全局变量 */
var ajax,log;
var conf = {
    // 日志级别 0:关闭;>=1:WARN;>=2:INFO;>=3DEBUG;
    loglevel: 3
};
/* ---------------- store加载消息 ---------------- */
var loadingdivid;
function store_before_load(){loadingdivid=mwt.notify("数据加载中...",10000,'loading');}
function store_after_load(){mwt.notify_destroy(loadingdivid);}

/* JApp定义 */
var JApp=function(baseUrl)
{
    this.init = function() {
		var ecpath = "../libs/echarts/2.0.4";
		require.config({
			baseUrl: baseUrl,
    		packages: [
        		{name:'plugin', location:'plugin', main:'main'},
				{name:'frame', location:'frame', main:'main'},
				{name:'jquery', location:'../libs/jquery/1.11.2', main:'jquery.min'},
				{name:'jquery-ui', location:'../libs/jquery-ui', main:'jquery-ui.min'},
				{name:'qrcode', location:'../libs/qrcode', main:'qrcode'},
				{name:'mwt', location:'../libs/mwt/3.5', main:'mwt.min'},
				{name:'echarts', location:'../libs/echarts/3.1.6', main:'echarts.min'}
    		]
		});
        require(['jappengine','core/log','core/ajax','mwt'], function(jappengine,corelog,coreajax,mwt){
			log = corelog;
			ajax = coreajax;
			jappengine.start();
        });
    };
};

