define(function(require){
    /* 婚恋 */
    var o={};
    o.show=function(domid,bazicase) {
        var divs = [
            '<div class="wall" id="marriage-graph-'+domid+'" style="height:200px;"></div>',
            '<div id="marriage-summary-'+domid+'" style="margin-top:10px"></div>',
            '<div id="marriage-liunian-'+domid+'" style="margin-top:10px"></div>',
        ];
        jQuery('#'+domid).html(divs.join(''));
        bazicase.show_chart_bazi('marriage-graph-'+domid);          //!< 八字关系图
        bazicase.show_marriage_summary('marriage-summary-'+domid);  //!< 八字婚恋概述
        bazicase.show_marriage_liunian('marriage-liunian-'+domid);  //!< 八字婚恋流年
    };
	return o;
});
