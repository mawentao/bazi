/**
 * 有向图
 **/
define(function(require){
    var o={};
    var chart;

	// 显示有向图
    o.show = function(domid,nodes,links,categories,legendLeft) {
		var dom = document.getElementById(domid);
        chart = echarts.init(dom, 'dark');
        var legend = {
            itemWidth:20, itemHeight:13,
            textStyle : {fontSize:12},
            data: categories.map(function (a) {
                return a.name;
            })
        };
        if (legendLeft) {
            legend['orient'] = 'vertical';
            legend['left'] = 5;
        }
		// 绘图
		var option = {
			tooltip: {},
			legend: [legend],
			textStyle: {
				fontFamily: "KaiTi,SimSun,'microsoft yahei'",
				fontSize: 16
			},
			animationDurationUpdate: 1500,
			animationEasingUpdate: 'quinticInOut',
			series : [{
				type: 'graph',
				color: ['#73a373','#ea7e53','#aa9967','#7289ab','#333','#999'],
				categories: categories,
				layout: 'none',
				symbolSize: 40,
				roam: true,
				label: {
					normal: { show:true }
            	},
				tooltip: {
                	formatter: "{b}"
            	},
				edgeSymbol: ['circle', 'arrow'],
				edgeSymbolSize: [6, 6],
				edgeLabel: {
                	normal: {
                    	textStyle: {fontSize: 14}
                	}
            	},
            	data: nodes, 
				links: links,
				lineStyle: {
                	normal: {
                    	opacity: 0.9,
                    	width: 1,
                    	curveness: 0.2
                	}
            	}
        	}]
		};
        chart.setOption(option);
/*
		// 节点点击事件
		if (clickfun) {
			chart.on('click', function (param) {  
				clickfun(param);
			});
		}
*/
    };

    return o;
});
