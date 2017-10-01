define(function(require){ 
    /* BaziCase.js, (c) 2017 mawentao */
var BaziGraph = require("common/BaziGraph");

var BaziCase = function(data)
{
    // 八字命盘
    this.show_mingpan=function(domid,simple)
    {
        require('./mingpan').show(domid,data,simple);
    }

    function get_wuxing_shishen_short(wuxing)
    {
        var wuxingDict = data.dict.wuxing;
        var shishens = wuxingDict[wuxing]['shishens'];
        switch (shishens[0]) {
            case '比肩':
            case '劫财': return '比劫';
            case '食神':
            case '伤官': return '食伤';
            case '正财':
            case '偏财': return '财';
            case '正官':
            case '七杀': return '官杀';
            case '正印':
            case '偏印': return '印';
        };
        return shishens.join('/');
    }

    // 命局概述
    this.show_summary=function(domid) 
    {/*{{{*/
        var rigan  = data.gan[2];   //!< 日元信息
        var yuelin = data.zhi[1];   //!< 月令信息
        var wuxingDict = data.dict.wuxing;

        var yss = [];
        for(var i=0; i<data.xiji.yongshen.wuxing.length; ++i) {
            var wx = data.xiji.yongshen.wuxing[i];
            yss.push(wx+'（'+get_wuxing_shishen_short(wx)+'）');
        }
        var jss = [];
        for(var i=0; i<data.xiji.jishen.wuxing.length; ++i) {
            var wx = data.xiji.jishen.wuxing[i];
            jss.push(wx+'（'+get_wuxing_shishen_short(wx)+'）');
        }

        var list = [
            '<li>'+rigan.z+rigan.wuxing+'日元生于'+yuelin.z+'月，月令为<em>'+wuxingDict[rigan.wuxing]['state']+'</em>，'+
                '日元身<em>'+data.riyuanPower.powerLevel+'</em></li>',
            '<li>喜用<span style="color:#6AD06A">'+yss.join('，')+'</span></li>',
            '<li>忌<span style="color:#E6886B">'+jss.join('，')+'</span></li>'
        ];
        var code = '<h2>【命局概述】</h2><ul class="textul">'+list.join('')+'</ul>';
        jQuery('#'+domid).html(code);
    };/*}}}*/

    // 八字生克关系图
    this.show_chart_bazi=function(domid)
    {/*{{{*/
        var graph = new BaziGraph({render:domid});
        graph.show(data.graph);
    };/*}}}*/

    // 五行力量分布图
    this.show_chart_wuxing=function(domid)
    {/*{{{*/
        var wuxingPower = bazi.dict.wuxing;
        var params = [
            {name:'五行力量',data:[
                {name:'木',value:wuxingPower['木'].power},
                {name:'火',value:wuxingPower['火'].power},
                {name:'土',value:wuxingPower['土'].power},
                {name:'金',value:wuxingPower['金'].power},
                {name:'水',value:wuxingPower['水'].power}
            ]}
        ];
        require('common/chart/pie').show(domid,params,{text:'五行力量分布'});
    };/*}}}*/

    // 十神力量对比图
    this.show_chart_shishen=function(domid)
    {/*{{{*/
        var powerMap = bazi.dict.shishen;
        var params = [
            {k:'比肩', v:powerMap['比肩'].power},
            {k:'劫财', v:powerMap['劫财'].power},
            {k:'食神', v:powerMap['食神'].power},
            {k:'伤官', v:powerMap['伤官'].power},
            {k:'正财', v:powerMap['正财'].power},
            {k:'偏财', v:powerMap['偏财'].power},
            {k:'正官', v:powerMap['正官'].power},
            {k:'七杀', v:powerMap['七杀'].power},
            {k:'正印', v:powerMap['正印'].power},
            {k:'偏印', v:powerMap['偏印'].power}
        ];
        require('common/chart/bar').show(domid,params,'比例',{text:'十神力量分布'});
    };/*}}}*/

    // 神煞矩阵
    this.show_matrix_shensha=function(domid)
    {/*{{{*/
        require('./shensha_matrix').show(domid,data);
    };/*}}}*/

    /////////////////////////////////////////
    // 论婚恋
    /////////////////////////////////////////
    // 婚恋概述
    this.show_marriage_summary=function(domid)
    {/*{{{*/
        require('./marriage_summary').show(domid,data); 
    };/*}}}*/

    // 婚恋流年
    this.show_marriage_liunian=function(domid)
    {/*{{{*/
        require('./marriage_liunian').show(domid,data); 
    };/*}}}*/

    /////////////////////////////////////////
    // 论心性
    /////////////////////////////////////////
    // 性格优劣词云图
    this.show_personality_wordcloud=function(domid)
    {/*{{{*/
        require('./personality_wordcloud').show(domid,data); 
    };/*}}}*/

    // 性格优劣详情
    this.show_personality_detail=function(domid)
    {/*{{{*/
        var map = {'positive':'性格优势','negative':'性格劣势'};
        var codes = [];
        for (var k in map) {
            var lis = [];
            var color = k=='positive' ? '#24A6AE' : '#E6886B';
            if (data.personality[k] && data.personality[k].length) {
                for (var i=0;i<data.personality[k].length;++i) {
                    var pim = data.personality[k][i];
                    var str = '<li style="color:'+color+'">'+pim.word+'：'+pim.desc+'</li>';
                    lis.push(str);
                }
            }
            if (!lis.length) {
                lis.push('<li style="color:#999">&lt;空&gt;</li>');
            }
            var im = map[k];
            var code = '<h2>【'+im+'】</h2><ul class="textul">'+lis.join('')+'</ul>';
            codes.push(code);
        }
        jQuery('#'+domid).html(codes.join(''));
    }/*}}}*/

    // 获取数据
    this.getData = function(){return data;}
};

return BaziCase;
});
