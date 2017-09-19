define(function(require){
    /* 预测主页面 (c) 2017 mawentao */
    var domid = 'appdiv';
    var BaziCase = require('bazi/BaziCase');

    // 初始化
    function init() {
        new mwt.BorderLayout({
            render : domid,
            splitWidth: 2,
            splitStyle: 'background:#065679',
            items : [
                {id:'frame-north', region:'north', height:60, style:'background:rgba(0,0,0,0.4);'},
                {id:'frame-west',  region:'west',  width:460, split:true},
                {id:'frame-center',region:'center',html:'center', style:'padding:10px;'}
            ]
        }).init();
        var bazicase = new BaziCase(bazi);
        require('./area_header').init('frame-north',bazicase);
        bazicase.show_mingpan('frame-west');
    }

    var o={};
    o.execute=function() {
        var taiji = require('./global/taiji');
        taiji.show();
        init();
        jQuery('#'+domid).hide();
        setTimeout(function(){
            taiji.hide();
            jQuery('#'+domid).fadeIn('slow');
        },setting.loading_ms);
    };
	return o;
});
