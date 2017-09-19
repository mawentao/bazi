define(function(require){
    /* 总论 */
    var o={};
    o.show=function(domid,bazicase) {
        var divs = [
            '<div id="summary-'+domid+'"></div>',
            '<h2>【图解八字】</h2>'+
            '<div class="wall">'+
              '<table width="100%">'+
                '<tr>'+
                  '<td width="60%"><div id="chart-bazi-'+domid+'" style="height:250px;position:relative;"></div></td>'+
                  '<td width="40%" style="border-left:solid 1px #065679">'+
                    '<div id="chart-wuxing-'+domid+'" style="height:250px;position:relative;"></div></td>'+
                '</tr>'+
                '<tr>'+
                  '<td colspan="2" style="border-top:solid 1px #065679">'+
                    '<div id="chart-shishen-'+domid+'" style="height:200px;position:relative;"></div></td>'+
                '</tr>'+
              '</table>'+
            '</div>',
            '<h2>【神煞】</h2><div id="matrix-shishen-'+domid+'"></div>'
        ];
        jQuery('#'+domid).html(divs.join(''));

        bazicase.show_summary('summary-'+domid);                //!< 命局概述
        bazicase.show_chart_bazi('chart-bazi-'+domid);          //!< 八字生克关系图
        bazicase.show_chart_wuxing('chart-wuxing-'+domid);      //!< 五行力量分布图
        bazicase.show_chart_shishen('chart-shishen-'+domid);    //!< 十神力量分布图
        bazicase.show_matrix_shensha('matrix-shishen-'+domid);  //!< 神煞矩阵
    };

	return o;
});
