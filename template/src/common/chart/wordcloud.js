define(function(require){
    /* 词云图 */

    /**
     * data数据结构:
     [
          {name:'诚信',value:36},
          {name:'重情重义',value:29},
          {name:'热情',value:41},
          {name:'乐观',value:98},
          {name:'自强不息',value:32}
     ]
     **/

    var o={};
	o.show=function(domid,title,data,color) {
        if (!data || !data.length) {
            data = [{name:'空', value:0}];
        }
        if (!color) color = '#24A6AE';

        var option = {
            title : {
                text: title,
                textStyle: {
                    fontSize: 16,
                    fontWeight: 'normal',
                    color: '#FFEB3B',
                    fontFamily: "KaiTi,SimSun,'microsoft yahei'"
                }
            },
            tooltip: {},
            series: [ {
                type: 'wordCloud',
                gridSize: 2,
                sizeRange: [14, 32],
                rotationRange: [-40, 40],
                shape: 'pentagon',   //!< pentagon, ellipse
                drawOutOfBound: true,
                textStyle: {
                    normal: {
                        color: function () {
                            return color;
                        }
                    }
                },
                data: data
            }]
        };

		var dom = document.getElementById(domid);
        chart = echarts.init(dom, 'dark');
        chart.setOption(option);
	};
	return o;
});
