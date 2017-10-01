define(function(require){
    /* 心性 */

    var o={};
    o.show=function(domid,bazicase) {
        var divs = [
            '<div id="summary-'+domid+'"></div>',
//            '<div class="wall" id="shishen-graph-'+domid+'" style="height:200px;"></div>',
            '<div class="wall" id="wordcloud-'+domid+'" style="margin-top:10px;"></div>',
            '<div id="character-'+domid+'" style="margin-bottom:20px;"></div>'
        ];
        jQuery('#'+domid).html(divs.join(''));

        // 命局概述
        bazicase.show_summary('summary-'+domid);
        // 十神力量对比图
        //bazicase.show_chart_shishen('shishen-graph-'+domid);
        // 性格优劣势词云图
        bazicase.show_personality_wordcloud('wordcloud-'+domid);
        // 性格优劣详情
        bazicase.show_personality_detail('character-'+domid);
    };

	return o;
});
