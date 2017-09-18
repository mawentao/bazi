/**
 * 饼图
 * 使用: require("common/chart_funnel").show(domid,data,title);
 * 其中data的数据结构如下:
 * [
 *     {name:'所有海浪用户Tab点击次数',data:[{value:213,name:'1次'},...]},
 *     ...
 * ]
 * title 的数据结构见echartsAPI
 * {text:'饼图', subtext:'纯属虚构'}
 **/
define(function(require){
    var o={};
    var chart;

    o.show = function(domid,data,title) {
		var dom = document.getElementById(domid);
        chart = echarts.init(dom, 'dark');

		var legend_data = [];
		var serie_data = [];
		for (var i=0;i<data.length;++i) {
			var item = data[i];
			for (var k=0;k<item.data.length;++k) {
				var da=item.data[k];
				legend_data.push(da.name);
			}
			serie_data.push({
				type:'pie',
                //color: ['#73a373','#ea7e53','#aa9967','#7289ab','#333','#999'],
                color: ['#24A6AE','#e69d87','#ECDAA1','#7289ab','#333','#999'],
				name: item.name,
                center : ['50%','60%'],
				radius : [0, 60],
                label: {
                    normal:{formatter:"{b}:{d}%"}
                },
				data:data[i].data
			});
		}

		var option = {
            title: {
                text: title ? title.text : '',
                textStyle: {
                    fontSize: 16,
                    fontWeight: 'normal',
                    color: '#FFEB3B'
                }
            },
            textStyle: {
                fontFamily: "KaiTi,SimSun,'microsoft yahei'",
                fontSize: 13
            },
			tooltip: {
				trigger: 'item',
				formatter: "{a} <br/>{b}: {c} ({d}%)"
    		},
    		legend: {
                show: false,
				x:'left', y:'top',
				orient: 'vertical',
                textStyle : {fontSize:12},
        		data: legend_data
    		},
			calculable: true,
			series: serie_data
		};
        chart.setOption(option);
    };

    return o;
});
