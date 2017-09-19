define(function(require){
    /* 合婚主页面 (c) 2017 mawentao */
    var domid = 'appdiv';
    var BaziCase = require('bazi/BaziCase');

    function init_center(divid) {
        var code = /*'<a class="logo" href="'+dz.siteurl+'/plugin.php?id=bazi:console">'+
            '<img src="http://localhost:8888/discuz/source/plugin/bazi/template/static/logo2.png">'+
            '<span>八字合婚</span>'+
        '</a>'+*/
        '<div id="marriage-graph-div" class="wall" style="height:180px;"></div>';
        jQuery('#'+divid).html(code);

        require('./merge-graph').init('marriage-graph-div');
    }

    // 初始化
    function init() {
        var sideWidth = 370;
        new mwt.BorderLayout({
            render : domid,
            items : [
                {region:'west',  width:sideWidth, split:false, html:'<div id="frame-west" class="sidefill male"></div>'},
                {region:'east',  width:sideWidth, split:false, html:'<div id="frame-east" class="sidefill female"></div>'},
                {region:'center', id:"frame-center",style:'padding:10px 0;'}
            ]
        }).init();
        var maleBazi = new BaziCase(maleCase);
        var femaleBazi = new BaziCase(femaleCase);
        require('./mingpan').show('frame-west',maleBazi);   //!< 男方命盘
        require('./mingpan').show('frame-east',femaleBazi); //!< 女方命盘
        init_center('frame-center');   //!< 合婚分析区域
    }

    var o={};
	o.execute=function(){
        var taiji = require('../foresee/global/taiji');
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
