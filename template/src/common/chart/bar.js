/**
 * 饼图
 * 使用: require("common/chart_funnel").show(domid,data);
 * 其中data的数据结构如下:
 * [ 
 *    {k:'运营中',v:123},
 *    {k:'停运', v:23},
 *    ......
 * ]
 **/
define(function(require){
    var o={};
    var chart;

    o.show = function(domid,data,name,title) {
		var dom = document.getElementById(domid);
        chart = echarts.init(dom, 'dark');

		var legend_data = [];
		var series_data = [];
		for (var i=0;i<data.length;++i) {
			var da=data[i];
			legend_data.push(da.k);
			series_data.push(da.v);
		}
		var option = {
			//color: ['#d87a80'],
            title: {
                left: 'center',
                text: title ? title.text : '',
                textStyle: {
                    fontSize: 16,
                    fontWeight: 'normal',
                    color: '#FFEB3B'
                }
            },
            textStyle: {
                fontFamily: "KaiTi,SimSun,'microsoft yahei'",
                fontSize: 14
            },
    		tooltip : {
        		trigger: 'axis',
				axisPointer: { type: 'shadow' },
                formatter: "{b}: {c}%"
    		},
    		grid: {
                top : '14%',
        		left: '5%',
        		right: '5%',
        		bottom: '5%',
        		containLabel: true
    		},
   	 		xAxis : [{
            	type : 'category',
				data : legend_data,
				axisLabel: {
					textStyle: {
                        color: function (value, index) {
                            var yang = '#A2DFFB';
                            var yin = '#FFBBA5';
                            switch (value) {
                                case '比肩': return yang;
                                case '劫财': return yin;
                                case '食神': return yang;
                                case '伤官': return yin;
                                case '正财': return yang;
                                case '偏财': return yin;
                                case '正官': return yang;
                                case '七杀': return yin;
                                case '正印': return yang;
                                case '偏印': return yin;
                            }
                            return '#fff';
                        },
                		fontSize: 14
            		}
       		 	},
			}],
			yAxis : [{
				show : false,
				type : 'value'
			}],
			series : [{
				name:name,
				type:'bar',
				//stack: '总量',
				//areaStyle: {normal: {}},
				data:series_data,
				label : {
					normal: {
                        show: true, 
                        position: 'outside',
                        formatter:"{c}%"
					}
				}/*,
                markPoint: {
                    data : [
                        {type:'max', name:'最大值',itemStyle:{normal:{opacity:0.8}}}
                    ]
                }*/
			}]
		};
        chart.setOption(option);
    };

    return o;
});
